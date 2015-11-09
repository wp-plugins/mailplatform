<?php
/*
Plugin Name: MailPlatform
Version:     1.4.8-1
License:     GPLv2 or later
Author:      MailPlatform
Author URI:  http://mailplatform.dk/
Text Domain: mailplatform
Description: Use MailPlatform to send your newsletters. This module gives you a quick and easy way to ensure that your new recipients are transferred to MailPlatform.
*/

//===> CONSTANTS AND REQUIRED <===\\

define( 'mailplatform__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'mailplatform__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'mailplatform__PLUGIN_PATH', str_replace( " ", "%20", mailplatform__PLUGIN_URL ) );

require_once( mailplatform__PLUGIN_DIR . '_inc/functions.php' );
require_once( mailplatform__PLUGIN_DIR . '_inc/feed-functions.php' );
require_once( mailplatform__PLUGIN_DIR . 'mailplatform.widget.php' );

register_activation_hook( __FILE__, 'jal_install' );
register_deactivation_hook( __FILE__, 'jal_uninstall' );

//===> OPTIONS <===\\

/**
 * Register Options
 */
function mailplatform_register_my_setting() {

	$options = array(
		'username'      => 'mailplatform_api_username',
		'token'         => 'mailplatform_api_token',
		'xml_path'      => 'mailplatform_api_xml_path',
		'posts'         => 'mailplatform_feed_posts',
		'pages'         => 'mailplatform_feed_pages',
		'woocommerce'   => 'mailplatform_feed_woocommerce',
		'listid'        => 'mailplatform_listid',
		'custom_fields' => 'mailplatform_custom_fields',
	);

	register_setting( 'mailplatform-db-api-options', $options['username'] );
	register_setting( 'mailplatform-db-api-options', $options['token'] );
	register_setting( 'mailplatform-db-api-options', $options['xml_path'] );
	register_setting( 'mailplatform-db-feed-options', $options['posts'] );
	register_setting( 'mailplatform-db-feed-options', $options['pages'] );
	register_setting( 'mailplatform-db-feed-options', $options['woocommerce'] );
	register_setting( 'mailplatform-db-options', $options['listid'] );
	register_setting( 'mailplatform-db-options', $options['custom_fields'] );

	wp_enqueue_style( 'mailplatform-plugin-css', mailplatform__PLUGIN_PATH . 'content/mailplatform.plugin.css', '1.0', true );
	wp_enqueue_script( 'mailplatform-plugin-js', mailplatform__PLUGIN_PATH . 'content/mailplatform.plugin.js', array(
		'jquery-ui-core',
		'jquery-ui-widget',
		'jquery-ui-mouse',
		'jquery-ui-sortable'
	), '1.0', true );
}

//===> MENU <===\\

/**
 * Admin Menu
 */
function mailplatform_menu() {

	$capability = "manage_options";
	$menu_slug  = "mailplatform";
	// Add top menu item
	add_menu_page( 'MailPlatform Options', __( 'MailPlatform', 'mailplatform' ), $capability, $menu_slug, 'mailplatform_views' );

	// Submenu Items
	// Form Handler
	add_submenu_page( $menu_slug, 'MailPlatform Lister', __( 'List Overview', 'mailplatform' ), $capability, "{$menu_slug}-list-options", 'mailplatform_views' );
	remove_submenu_page( $menu_slug, $menu_slug );
	add_submenu_page( $menu_slug, 'MailPlatform Options', __( 'API Settings', 'mailplatform' ), $capability, "{$menu_slug}&type=settings", 'mailplatform_views' );
	add_submenu_page( $menu_slug, 'MailPlatform Options', __( 'XML Feed Settings', 'mailplatform' ), $capability, "{$menu_slug}&type=feeds", 'mailplatform_views' );
}

function mailplatform_manage_options() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
}

/**
 * Views for admin
 */
function mailplatform_views() {

	mailplatform_manage_options();
	require_once( mailplatform__PLUGIN_DIR . 'mailplatform.views.php' );
}

/**
 * Shortcode widget handler
 *
 * @param $atts
 */
function mailplatform_shortcode( $atts ) {

	$a = shortcode_atts( array(
		'listid' => null
	), $atts );

	ob_start();
	mailplatform_WidgetAndShortcode( $a['listid'], 'shortcode' );
	$output_string = ob_get_contents();
	ob_end_clean();

	return $output_string;
}

function mailplatform_feed_tpl( $tpl ) {

	$func = new mailplatform_feed_functions();
	if ( is_page( 'rss-pages' ) && $func->pages ) {
		$tpl = mailplatform__PLUGIN_DIR . 'feeds/pages-feed.php';
	}
	if ( is_page( 'rss-posts' ) && $func->posts ) {
		$tpl = mailplatform__PLUGIN_DIR . 'feeds/posts-feed.php';
	}
	if ( is_page( 'rss-woocommerce' ) && $func->woocommerce && class_exists( 'WooCommerce' ) ) {
		$tpl = mailplatform__PLUGIN_DIR . 'feeds/woocommerce-feed.php';
	}

	return $tpl;
}

function mailplatform_check_feed_pages() {

	$func = new mailplatform_feed_functions();
	if ( $func->pages ) {
		if ( get_page_by_title( 'rss-pages' ) == null ) {
			mailplatform_create_feed_pages( 'rss-pages' );
		}
	}
	if ( $func->posts ) {
		if ( get_page_by_title( 'rss-posts' ) == null ) {
			mailplatform_create_feed_pages( 'rss-posts' );
		}
	}
	if ( $func->woocommerce && class_exists( 'WooCommerce' ) ) {
		if ( get_page_by_title( 'rss-woocommerce' ) == null ) {
			mailplatform_create_feed_pages( 'rss-woocommerce' );
		}
	}
}

function mailplatform_create_feed_pages( $pageName ) {

	$createPage = array(
		'post_title'   => $pageName,
		'post_content' => '',
		'post_status'  => 'publish',
		'post_author'  => 1,
		'post_type'    => 'page',
		'post_name'    => $pageName
	);

	// Insert the post into the database
	wp_insert_post( $createPage );
}

//===> FILTERS <===\\
add_filter( 'page_template', 'mailplatform_feed_tpl' );

//===> ACTIONS <===\\
add_action( 'admin_init', 'mailplatform_register_my_setting' );
add_action( 'admin_menu', 'mailplatform_menu' );
add_action( 'widgets_init', 'mailplatform_wpb_load_widget' );
add_shortcode( 'mailplatform', 'mailplatform_shortcode' );
add_action( 'init', 'mailplatform_check_feed_pages' );