<?php

	require_once __DIR__."/utils.php";

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
		global $ti_course_items;

		$ti_course_items=array();
		do_shortcode($content);

		$tab=0;
		if (array_key_exists("tab",$_REQUEST) && $_REQUEST["tab"])
			$tab=$_REQUEST["tab"];

		$s="<div class='content-tab-wrapper'>";
		$s.="<ul class='content-tab-list'>";

		$index=0;
		foreach ($ti_course_items as $courseItem) {
			$link=get_page_link($current->ID)."?tab=".$index;
			$sel="";

			if ($index==$tab)
				$sel="class='selected'";

			$s.="<li $sel>";
			$s.="<a href='$link'>{$courseItem[title]}</a>";
			$s.="</li>";

			$index++;
		}

		$s.="</ul>";
		$s.="<div class='content-tab-content'>";
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
			"content"=>"[h5p id='$h5pContent->id']"
		);
	}

	add_shortcode("h5p-course-item","ti_h5p_course_item");

	/**
	 * Init.
	 */
	function ti_init() {
		add_post_type_support('page','excerpt');
	}

	add_action("init","ti_init");
	add_theme_support('menus');
