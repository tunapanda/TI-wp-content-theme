<!DOCTYPE html>
<html>
	<head>
		<?php wp_head(); ?>
		<script>
			THEME_URI='<?php echo get_template_directory_uri(); ?>';
		</script>
		<meta name="viewport" content="width=device-width">
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/tunapanda.ico" />
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-touch-icon-57x57-precomposed.png" />
    	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-touch-icon-72x72-precomposed.png" />
    	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/img/icons/apple-touch-icon-114x114-precomposed.png" />
    	<meta name="application-name" content="Tunapanda Institute"/>
    	<meta name="msapplication-TileColor" content="#ffffff"/> 
    	<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/img/icons/ms-icon-144x144.png"/>
	</head>
	<body <?php body_class(); ?>>
		<div id="header">
			<a href="<?php echo site_url(); ?>">
				<img id="header-logo" 
					src="<?php echo get_template_directory_uri(); ?>/img/cropped-tunapandalogo.png"
					alt="Tunapanda Logo" />
			</a>
			<h1>Learning</h1>
			<div class="menu">
				<?php
					wp_nav_menu(array(
						'menu_class'     => 'nav-menu',
						'theme_location' => 'navigation',
					));
				?>
			</div>
		</div>
		<div id="content">

