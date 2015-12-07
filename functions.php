<?php

	require_once __DIR__."/utils.php";
	require_once __DIR__."/xapi.php";

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
		wp_register_style("ti",get_template_directory_uri()."/style.css");
		wp_enqueue_style("ti");
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

		$out="";

		foreach ($pages as $page) {
			if ($page->ID!=$parentId) {
				$out.=render_tpl(__DIR__."/tpl/tracklisting.php",array(
					"page"=>$page
				));
			}
		}

		return $out;
	}

	add_shortcode("track-listing","ti_track_listing");

	/**
	 * Handle the track-listing short code.
	 */
	function ti_course_listing() {
		$parentId=get_the_ID();

		$pages=get_pages(array(
			"parent"=>$parentId
		));

		$out="";

		foreach ($pages as $page) {
			if ($page->ID!=$parentId) {
				$out.=render_tpl(__DIR__."/tpl/courselisting.php",array(
					"page"=>$page
				));
			}
		}

		return $out;
	}

	add_shortcode("course-listing","ti_course_listing");

	/**
	 * Handle the course shortcode.
	 */
	function ti_course($args, $content) {
		$post=get_post();
		//print_r($post);
		//print_r(get_post_meta($post->ID));

		global $ti_course_items;

		$ti_course_items=array();
		do_shortcode($content);

		$tab=0;
		if (array_key_exists("tab",$_REQUEST) && $_REQUEST["tab"])
			$tab=$_REQUEST["tab"];

		$s="<div class='content-tab-wrapper'>";
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
	 * The h5p-course-item short code.
	 */
	function ti_h5p_course_item($args) {
		global $ti_course_items;

		if (array_key_exists("id", $args))
			$h5pContent=getH5pContentBy("id",$args["id"]);

		else if (array_key_exists("title", $args))
			$h5pContent=getH5pContentBy("title",$args["title"]);

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
