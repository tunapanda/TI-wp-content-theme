<<<<<<< HEAD
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
		$template->set("swagUser",$swagUser);
		$template->set("swagPost",$swagPost);

// Creating lesson plan functionality

		$template->set("showLessonPlan",FALSE);
		if (array_key_exists("lessonplan",$args)) {
		$template->set("lessonPlan",get_home_url().'/wp-content/uploads'.$args["lessonplan"]);
		$template->set("showLessonPlan",TRUE);}

			if ($swagPost->getProvidedSwag() && $swagUser->isSwagCompleted($swagPost->getProvidedSwag())) {
			$template->set("lessonplanAvailable",TRUE);
		}
		else {
			$template->set("lessonplanAvailable",FALSE);
		}

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
=======
<?php 
>>>>>>> refactor

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
	 * Scripts and styles.
	 */
	function ti_enqueue_scripts() {
		wp_register_style("ti",get_template_directory_uri()."/style.css?v=7"); //?v=x added to refresh browser cache when stylesheet is updated. 
		wp_enqueue_style("ti");
	}
	add_action('wp_enqueue_scripts','ti_enqueue_scripts');
	
	register_nav_menu("navigation","Main menu for the site");