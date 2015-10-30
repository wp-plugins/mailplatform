<?php

class mailplatform_feed_functions {

	public $pages;
	public $posts;
	public $woocommerce;
	public $feeds = array();

	private function get_id_by_slug($page_slug) {
		$page = get_page_by_path($page_slug);
		if ($page) {
			return $page->ID;
		} else {
			return null;
		}
	}

	public function get_posts( $total = 10, $type = 'post', $cat = null ) {

		$args = array(
			'orderby'        => 'ID',
			'order'          => 'DESC',
			'post_type'      => $type,
			'post_status'    => 'publish',
			'posts_per_page' => $total,
		);

		if ( ! is_null( $cat ) && $type == "post" ) {
			$args['cat'] = $cat;
		}
		if ( ! is_null( $cat ) && $type == "product" ) {
			$args['product_cat'] = $cat;
		}

		$the_query = new WP_Query( $args );

		return $the_query;
	}

	public function __construct() {

		$this->posts       = get_option( 'mailplatform_feed_posts' );
		$this->pages       = get_option( 'mailplatform_feed_pages' );
		$this->woocommerce = get_option( 'mailplatform_feed_woocommerce' );

		if ( $this->posts ) {
			$this->feeds['posts'] = get_site_url() . "/rss-posts";
		} else {
			$id = $this->get_id_by_slug('rss-posts');
			wp_delete_post( $id, true );
		}

		if ( $this->pages ) {
			$this->feeds['pages'] = get_site_url() . "/rss-pages";
		} else {
			$id = $this->get_id_by_slug('rss-pages');
			wp_delete_post( $id, true );
		}

		if ( $this->woocommerce ) {
			$this->feeds['woocommerce'] = get_site_url() . "/rss-woocommerce";
		} else {
			$id = $this->get_id_by_slug('rss-woocommerce');
			wp_delete_post( $id, true );
		}
	}
}