<?php

	/**
	 * Scripts and styles.
	 */
	function ti_enqueue_scripts() {
		wp_register_style("ti",get_template_directory_uri()."/style.css");
		wp_enqueue_style("ti");
	}

	add_action('wp_enqueue_scripts','ti_enqueue_scripts');
