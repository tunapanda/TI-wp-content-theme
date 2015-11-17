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

		$wpdb->show_errors();
		$s='';

		$courseTitles=explode("\n",$content);
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

				$s.="[tabby title='$courseTitle'][h5p id='$courseId']";
			}
		}

		$s.='[tabbyending]';

		return do_shortcode($s);
	}

	add_shortcode("course","ti_course");