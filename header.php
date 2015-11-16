<!DOCTYPE html>
<html>
	<head>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php
			wp_nav_menu(array(
				'menu_class'     => 'nav-menu',
				'theme_location' => 'navigation',					
			));
		?>
