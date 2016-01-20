<div class="course listing <?php if (!$prepared) echo "unprepared"; ?>">
    <div class="listing-info">
    	<div class="header">
    		<div class="title">
    			<?php echo $page->post_title; ?>
    		</div>
    	</div>
    
    	<div class="description">
    		<?php echo $page->post_excerpt; ?>
    	</div>
        
        <div class="footer">
            <a href="<?php echo get_page_link($page->ID); ?>">Follow Swagpath</a>
        </div>
    </div>
</div>