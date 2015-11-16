<!DOCTYPE html>
<html>
	<head>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="header">
			<img id="header-logo" 
				src="<?php echo get_template_directory_uri(); ?>/img/cropped-tunapandalogo.png"/>
			<h1>Knowledgebase</h1>
			<?php
				wp_nav_menu(array(
					'menu_class'     => 'nav-menu',
					'theme_location' => 'navigation',					
				));
			?>
		</div>
		<div id="content">
