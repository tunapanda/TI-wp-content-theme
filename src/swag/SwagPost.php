<?php

/**
 * Per post related swag operations.
 */
class SwagPost {

	/**
	 * Construct.
	 */
	public function __construct($post) {
		$this->post=$post;
	}

	/**
	 * Get required swag.
	 */
	public function getRequiredSwag() {
		return get_post_meta($this->post->ID,"requires");
	}

	/**
	 * Get required swag.
	 */
	public function getProvidedSwag() {
		return get_post_meta($this->post->ID,"provides");
	}

	/**
	 * Get post.
	 */
	public function getPost() {
		return $this->post;
	}

	/**
	 * Get the ids for the swagpaths that provides the swag.
	 */
	public function getPostsProvidingSwag($swags) {
		$posts=array();
		$postIds=array();

		foreach ($swags as $swag) {
			$q=new WP_Query(array(
				"post_type"=>"any",
				"meta_key"=>"provides",
				"meta_value"=>$swag
			));

			foreach ($q->get_posts() as $post) {
				if (!in_array($post->ID,$postIds)) {
					$posts[]=$post;
					$postIds[]=$post->ID;
				}
			}
		}

		return $posts;
	}
}