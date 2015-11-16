<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h1><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></h1>

	<?php the_content(); ?>
	<?php
		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );
	?>

</article><!-- #post-## -->
