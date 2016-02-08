<?php

	require_once __DIR__."/utils.php";
	require_once __DIR__."/xapi.php";
	require_once __DIR__."/src/utils/ShortcodeUtil.php";
	require_once __DIR__."/src/swag/SwagUser.php";
	require_once __DIR__."/src/swag/SwagPost.php";

	/**
	 * Compute the url that H5P uses to save xAPI statements.
	 * Looks somethins like:
	 * http://localhost/wordpress/wp-admin/admin-ajax.php?action=h5p_embed&id=5
	 */
	function getH5pObjectUrlById($h5pId) {
		return get_site_url()."/wp-admin/admin-ajax.php?action=h5p_embed&id=".$h5pId;
	}

	/**
	 * Check if the H5P item has been completed by the current user.
	 */
	function isH5pCompleted($h5pId) {
		$activityUrl=getH5pObjectUrlById($h5pId);

		if (!get_option("ti_xapi_endpoint_url"))
			return FALSE;

		$xapi=new Xapi(
			get_option("ti_xapi_endpoint_url"),
			get_option("ti_xapi_username"),
			get_option("ti_xapi_password")
		);

		$user=wp_get_current_user();

		if (!$user || !$user->user_email)
			return;

		$params=array();
		$params["agentEmail"]=$user->user_email;
		$params["activity"]=$activityUrl;
		$params["verb"]="http://adlnet.gov/expapi/verbs/completed";

		$statements=$xapi->getStatements($params);

		return sizeof($statements)>0;
	}

	/**
	 * Scripts and styles.
	 */
	function ti_enqueue_scripts() {
		wp_register_style("ti",get_template_directory_uri()."/style.css?v=5"); //?v=x added to refresh browser cache when stylesheet is updated. 
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
		global $ti_course_items;

		$post=get_post();
		$swagPost=new SwagPost($post);
		$swagUser=new SwagUser(wp_get_current_user());
		$ti_course_items=array();
		do_shortcode($content);

		$tab=0;
		if (array_key_exists("tab",$_REQUEST) && $_REQUEST["tab"])
			$tab=$_REQUEST["tab"];

		$s="";

		if (!$swagUser->isSwagCompleted($swagPost->getRequiredSwag())) {
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

			$s.="<div class='course-info'>";
			$s.="In order to get the most out of this swagpath, it is recommended that you ";
			$s.="first collect these swag: ";
			$s.=join(", ",$uncollectedFormatted);
			$s.=". You can collect them by following these swagpaths: ";
			$s.=join(", ",$swagpathsFormatted);
			$s.=".</div>";
		}

		$s.="<div class='content-tab-wrapper'>";
		$s.="<ul class='content-tab-list'>";

		$index=0;
		$title="";
		foreach ($ti_course_items as $courseItem) {
			$link=get_page_link($current->ID)."?tab=".$index;
			$sel="";

			if ($index==$tab) {
				$sel="class='selected'";
				$title=$courseItem[title];
			}

			$s.="<li $sel>";
			$s.="<a href='$link'>";

			if ($courseItem["completed"])
				$s.="<img class='coursepresentation' src='".get_template_directory_uri()."/img/completed-logo.png'/>";

			else
				$s.="<img class='coursepresentation' src='".get_template_directory_uri()."/img/coursepresentation-logo.png'/>";

			$s.="</a>";
			$s.="</li>";

			$index++;
		}

		$s.="</ul>";
		$s.="<div class='content-tab-content'>";
		$s.="<h1>$title</h1>";
		$s.=do_shortcode($ti_course_items[$tab]["content"]);
		$s.="</div>";
		$s.='</div>';

		return $s;
	}

	add_shortcode("course","ti_course");

	/**
	 * Get h5p content from the database.
	 */
	function getH5pContentBy($by, $value) {
		global $wpdb;

		$q=$wpdb->prepare(
			"SELECT * ".
			"FROM   {$wpdb->prefix}h5p_contents ".
			"WHERE  $by=%s",
			$value
		);

		return $wpdb->get_row($q);
	}

	/**
	 * Get H5P content by shortcode args.
	 */
	function getH5pContentByShortcodeArgs($args) {
		$h5pContent=NULL;

		if (array_key_exists("id", $args))
			$h5pContent=getH5pContentBy("id",$args["id"]);

		else if (array_key_exists("title", $args))
			$h5pContent=getH5pContentBy("title",$args["title"]);

		else if (array_key_exists("slug", $args))
			$h5pContent=getH5pContentBy("slug",$args["slug"]);

		return $h5pContent;
	}

	/**
	 * The h5p-course-item short code.
	 */
	function ti_h5p_course_item($args) {
		global $ti_course_items;

		$h5pContent=getH5pContentByShortcodeArgs($args);

		if (!$h5pContent) {
			$ti_course_items[]=array(
				"title"=>"Not found",
				"content"=>"H5P Content not found<br><pre>".print_r($args,TRUE)."</pre>"
			);

			return;
		}

		$ti_course_items[]=array(
			"title"=>$h5pContent->title,
			"content"=>"[h5p id='$h5pContent->id']",
			"completed"=>isH5pCompleted($h5pContent->id)
		);
	}

	add_shortcode("h5p-course-item","ti_h5p_course_item");

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

		//error_log("saved complete statement post id: ".$postId);

		$shortcodes=ShortcodeUtil::extractShortcodes($post->post_content);
		foreach ($shortcodes as $attrs) {
			if ($attrs["_"]=="h5p-course-item") {
				$h5pContent=getH5pContentByShortcodeArgs($attrs);
				if (!$h5pContent)
					error_log("H5P not found... ".print_r($attrs,TRUE));

				if (!isH5pCompleted($h5pContent->id)) {
					//error_log("not yet complete, returning: ".$h5pContent->id);
					return;
				}
			}
		}

		$provides=get_post_meta($post->ID,"provides");

		$user=wp_get_current_user();
		if (!$user || !$user->user_email)
			return;

		$xapi=new Xapi(
			get_option("ti_xapi_endpoint_url"),
			get_option("ti_xapi_username"),
			get_option("ti_xapi_password")
		);

		foreach ($provides as $provide) {
			$statement=array(
				"actor"=>array(
					"mbox"=>"mailto:".$user->user_email,
					"name"=>$user->display_name
				),

				"object"=>array(
					"objectType"=>"Activity",
					"id"=>"http://swag.tunapanda.org/".$provide,
					"definition"=>array(
						"name"=>array(
							"en-US"=>$provide
						)
					)
				),

				"verb"=>array(
					"id"=>"http://adlnet.gov/expapi/verbs/completed"
				),

				"context"=>array(
					"contextActivities"=>array(
						"category"=>array(
							array(
								"objectType"=>"Activity",
								"id"=>"http://swag.tunapanda.org/"
							)
						)
					)
				),
			);

			$xapi->putStatement($statement);

			//error_log("got swag: ".$provide);
		}
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
