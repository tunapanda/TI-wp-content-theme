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
		global $wpdb;

		$current=get_post();

		$tab=0;
		if (array_key_exists("tab",$_REQUEST))
			$tab=$_REQUEST["tab"];

		$wpdb->show_errors();
		$s="<div class='content-tab-wrapper'>";
		$s.="<ul class='content-tab-list'>";

		$courseTitles=explode("\n",$content);
		$tabContent="";
		$index=0;

		foreach ($courseTitles as $courseTitle) {
			$courseTitle=trim(strip_tags($courseTitle));
			if ($courseTitle) {
				$q=$wpdb->prepare(
					"SELECT id ".
					"FROM   {$wpdb->prefix}h5p_contents ".
					"WHERE  title=%s",
					$courseTitle
				);

				$courseId=$wpdb->get_var($q);
				$sel="";

				if ($index==$tab) {
					$tabContent=
						"<div class='content-tab-content'>".
						"[h5p id='$courseId']".
						"</div>";

					$sel="class='selected'";
				}

				$link=get_page_link($current->ID)."?tab=".$index;

				$c=$courseTitle;
				if (strlen($c)>20)
					$c=substr($c,0,20)."...";

				$s.="<li $sel>";
				$s.="<a href='$link'>$c</a>";
				$s.="</li>";

				$index++;
			}
		}

		$s.="</ul>";
		$s.=$tabContent;
		$s.='</div>';

		return do_shortcode($s);
	}

	add_shortcode("course","ti_course");

	/**
	 * Init.
	 */
	function ti_init() {
		add_post_type_support('page','excerpt');
	}

	add_action("init","ti_init");
	add_theme_support('menus');
