<div class="course listing">
	<div class="header">
		<div class="title">
			<?php echo $page->post_title; ?>
		</div>
	</div>

	<div class="description">
		<?php echo $page->post_excerpt; ?>
	</div>

	<a href="<?php echo get_page_link($page->ID); ?>">Follow swagpath</a>
</div>