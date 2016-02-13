<?php

	require_once __DIR__."/utils.php";
	require_once __DIR__."/src/utils/Xapi.php";
	require_once __DIR__."/src/utils/ShortcodeUtil.php";
	require_once __DIR__."/src/swag/SwagUser.php";
	require_once __DIR__."/src/swag/SwagPost.php";
	require_once __DIR__."/src/utils/Template.php";

	/**
	 * Scripts and styles.
	 */
	function ti_enqueue_scripts() {
		wp_register_style("ti",get_template_directory_uri()."/style.css?v=7"); //?v=x added to refresh browser cache when stylesheet is updated. 
		wp_enqueue_style("ti");

		wp_register_script("d3",get_template_directory_uri()."/d3.v3.min.js");
		wp_register_script("ti-main",get_template_directory_uri()."/main.js");

		wp_enqueue_script("jquery");
		wp_enqueue_script("d3");
		wp_enqueue_script("ti-main");
	}

	add_action('wp_enqueue_scripts','ti_enqueue_scripts');

	/**
	 * Handle the track-listing short code.
	 */
	function ti_track_listing() {
		$parentId=get_the_ID();

		$pages=get_pages(array(
			"parent"=>$parentId
		));

		$out = '<div class="masonry-loop">';

		foreach ($pages as $page) {
			if ($page->ID!=$parentId) {
    			$page->swagpaths = count(get_pages(array('child_of'=>$page->ID)));
				$out.=render_tpl(__DIR__."/tpl/tracklisting.php",array(
					"page"=>$page
				));
			}
		}
        
        $out .= '</div>';
        
		return $out;
	}

	add_shortcode("track-listing","ti_track_listing");

	/**
	 * Handle the track-listing short code.
	 */
	function ti_course_listing() {
		$swagUser=new SwagUser(wp_get_current_user());
		$parentId=get_the_ID();

		$q=new WP_Query(array(
			"post_type"=>"any",
			"post_parent"=>$parentId,
			"posts_per_page"=>-1
		));

		$pages=$q->get_posts();

		$out = '<div class="masonry-loop">';

		$unpreparedCount=0;
        
		foreach ($pages as $page) {
			if ($page->ID!=$parentId) {
				$swagPost=new SwagPost($page);
				$prepared=$swagUser->isSwagCompleted($swagPost->getRequiredSwag());
				$completed=$swagUser->isSwagCompleted($swagPost->getProvidedSwag());

				if (!$swagPost->getProvidedSwag())
					$completed=FALSE;

				if (!$prepared)
					$unpreparedCount++;

				$out.=render_tpl(__DIR__."/tpl/courselisting.php",array(
					"page"=>$page,
					"prepared"=>$prepared,
					"completed"=>$completed
				));
			}
		}

		if ($unpreparedCount) {
			$out.=render_tpl(__DIR__."/tpl/afterlisting.php",array(
				"unprepared"=>$unpreparedCount
			));
		}

		$out .= '</div>';

		return $out;
	}

	add_shortcode("course-listing","ti_course_listing");

	/**
	 * Handle the course shortcode.
	 */
	function ti_course($args, $content) {
		$swagPost=SwagPost::getCurrent();
		$swagUser=SwagUser::getCurrent();

		$template=new Template(__DIR__."/tpl/course.php");
		$template->set("swagPost",$swagPost);

		$template->set("showHintInfo",FALSE);
		if (!$swagUser->isSwagCompleted($swagPost->getRequiredSwag())) {
			$template->set("showHintInfo",TRUE);

			$uncollected=$swagUser->getUncollectedSwag($swagPost->getRequiredSwag());
			$uncollectedFormatted=array();

			foreach ($uncollected as $swag)
				$uncollectedFormatted[]="<b>$swag</b>";

			$swagpaths=SwagPost::getPostsProvidingSwag($uncollected);
			$swagpathsFormatted=array();

			foreach ($swagpaths as $swagpath)
				$swagpathsFormatted[]=
					"<a href='".get_post_permalink($swagpath->ID)."'>".
					$swagpath->post_title.
					"</a>";

			$template->set("uncollectedSwag",join(", ",$uncollectedFormatted));
			$template->set("uncollectedSwagpaths",join(", ",$swagpathsFormatted));
		}

		return $template->render();
	}

	add_shortcode("course","ti_course");

	/**
	 * Init.
	 */
	function ti_init() {
		add_post_type_support('page','excerpt');
		add_post_type_support('page','custom-fields');
	}

	add_action("init","ti_init");
	add_theme_support('menus');

	/**
	 * Create the admin menu.
	 */
	function ti_admin_menu() {
		add_options_page(
			'Tunapanda Learning',
			'Tunapanda Learning',
			'manage_options',
			'ti_settings',
			'ti_create_settings_page'
		);
	}

	/**
	 * Admin init.
	 */
	function ti_admin_init() {
		register_setting("ti","ti_xapi_endpoint_url");
		register_setting("ti","ti_xapi_username");
		register_setting("ti","ti_xapi_password");
	}

	/**
	 * Create settings page.
	 */
	function ti_create_settings_page() {
		require __DIR__."/settings.php";
	}

	add_action('admin_menu','ti_admin_menu');
	add_action('admin_init','ti_admin_init');

	register_nav_menu("navigation","Main menu for the site");

	/**
	 * Render swagmap.
	 */
	function ti_swagmap() {
		return "<div id='swagmapcontainer'>
		<div id='swag_description_container'>A swagmap is gamified display of performance. The green hollow nodes indicate the swagpath is not completed or attempted while non-hollow green nodes indicate the swagpaths is completed and questions answered.
		</div>
		</div>";

	}
	add_shortcode("swagmap","ti_swagmap");

	/**
	 * Act on completed xapi statements.
	 * Save xapi statement for swag if applicable.
	 */
	function ti_xapi_post_save($statement) {
		if ($statement["verb"]["id"]!="http://adlnet.gov/expapi/verbs/completed")
			return;

		$postPermalink=$statement["context"]["contextActivities"]["grouping"][0]["id"];
		$postId=url_to_postid($postPermalink);
		$post=get_post($postId);

		if (!$post)
			return;

		$swagPost=new SwagPost($post);
		if ($swagPost->isAllSwagPostItemsCompleted())
			$swagPost->saveProvidedSwag();
	}

	add_action("h5p-xapi-post-save","ti_xapi_post_save");

	function ti_my_swag() {
		$swagUser=new SwagUser(wp_get_current_user());
		$completedSwag=$swagUser->getCompletedSwag();

		$baseuri=get_template_directory_uri();

		$out="";

		foreach ($completedSwag as $swag) {
			$out.="<div class='swag-badge-container'>\n";
			$out.="<img class='swag-badge-image' src='$baseuri/img/badge.png'>\n";
			$out.="<div class='swag-badge-label'>$swag</div>\n";
			$out.="</div>\n";
		}

		return $out;
	}

	add_shortcode("my-swag","ti_my_swag");
