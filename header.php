<!DOCTYPE html>
<html>
	<head>
		<?php wp_head(); ?>
		<script>
			THEME_URI='<?php echo get_template_directory_uri(); ?>';
		</script>
		<meta name="viewport" content="width=device-width">
	</head>
	<body <?php body_class(); ?>>
		<div id="header">
			<a href="<?php echo site_url(); ?>">
				<img id="header-logo" 
					src="<?php echo get_template_directory_uri(); ?>/img/cropped-tunapandalogo.png"/>
			</a>
			<h1>Knowledgebase</h1>
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
