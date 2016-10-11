<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h1><?php //the_title( '<h1 class="entry-title">', '</h1>' ); ?></h1>
	
		<?php
		//var_dump( $post);
//			if (!is_front_page()){
			/*if (count($post->ancestors)>0) {
				echo '<div id="breadcrumbs">';
				echo '<ul id="breadcrumb">';
				for ($i = count($post->ancestors)-1; $i >= 0; $i--) {
					echo '<li>';
					if (($home->ID) != ($post->ancestors[$i])) {
						echo '<a  href="';
						echo get_permalink($post->ancestors[$i]); 
						echo '">';
						echo get_the_title($post->ancestors[$i]);
					}
					echo '</li>';
				}
				echo '<li>';
				echo '<a href="">';
				echo  get_the_title(get_the_ID());
				echo "</a>";	
				echo '</li>';

				echo '</ul>';
				echo '</div>';
			}*/
		?>
	

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
