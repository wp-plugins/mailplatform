<?php

class mailplatform_feed_functions {

	public $pages;
	public $posts;
	public $woocommerce;
	public $feeds = array();

	public function get_posts( $total = 10, $type = 'post', $cat = null ) {

		$args = array(
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'post_type'      => $type,
			'post_status'    => 'publish',
			'posts_per_page' => $total,
		);

		if(!is_null($cat) && $type == "post") $args['cat'] = $cat;
		if(!is_null($cat) && $type == "product") $args['product_cat'] = $cat;

		$the_query = new WP_Query( $args );

		return $the_query;
	}

	public function __construct() {

		$this->posts       = get_option( 'mailplatform_posts' );
		$this->pages       = get_option( 'mailplatform_pages' );
		$this->woocommerce = get_option( 'mailplatform_woocommerce' );

		if ( $this->posts ) {
			$this->feeds['posts'] = get_site_url() . "/rss-posts";
		}

		if ( $this->pages ) {
			$this->feeds['pages'] = get_site_url() . "/rss-pages";
		}

		if ( $this->woocommerce ) {
			$this->feeds['woocommerce'] = get_site_url() . "/rss-woocommerce";
		}
	}
}