<?php 

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

function ti_redirect_wppb_login() {
	return do_shortcode("[wppb-login]");
}

add_shortcode("redirect-wppb-login","ti_redirect_wppb_login");