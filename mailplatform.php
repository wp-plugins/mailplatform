<?php
/*
Plugin Name: MailPlatform
Version:     1.4
License:     GPLv2 or later
Author:      MailPlatform
Author URI:  http://mailplatform.dk/
Text Domain: mailplatform
Description: Use MailPlatform to send your newsletters. This module gives you a quick and easy way to ensure that your new recipients are transferred to MailPlatform.
*/

//===> CONSTANTS AND REQUIRED <===\\

define( 'mailplatform__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'mailplatform__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'mailplatform__PLUGIN_PATH', str_replace(" ", "%20", mailplatform__PLUGIN_URL) );

require_once(mailplatform__PLUGIN_DIR . '_inc/functions.php'); 
require_once(mailplatform__PLUGIN_DIR . 'mailplatform.widget.php'); 

register_activation_hook( __FILE__, 'jal_install' );
register_deactivation_hook( __FILE__, 'jal_uninstall' );


//===> OPTIONS <===\\

/**
 * Register Options
 */
function mailplatform_register_my_setting() {
	register_setting( 'mailplatform-db-options', 'mailplatform_username' );
	register_setting( 'mailplatform-db-options', 'mailplatform_token' );
	register_setting( 'mailplatform-db-options', 'mailplatform_xml_path' );

	register_setting( 'mailplatform-db-customfields', 'mailplatform_listid' );
	register_setting( 'mailplatform-db-customfields', 'mailplatform_custom_fields' );

	wp_enqueue_style( 'mailplatform-plugin-css', mailplatform__PLUGIN_PATH . 'content/mailplatform.plugin.css', '1.0', true);
	wp_enqueue_script('mailplatform-plugin-js', mailplatform__PLUGIN_PATH . 'content/mailplatform.plugin.js', array(
		'jquery-ui-core',
		'jquery-ui-widget',
		'jquery-ui-mouse',
		'jquery-ui-sortable'
	), '1.0', true);
}

//===> MENU <===\\

/**
 * Admin Menu
 */
function mailplatform_menu() {
	$capability = "manage_options";
	$menu_slug = "mailplatform";
	// Add top menu item
	add_menu_page('MailPlatform Options',  __( 'MailPlatform', 'mailplatform' ), $capability, $menu_slug, 'mailplatform_views');

	// Submenu Items
	// Form Handler
	add_submenu_page( $menu_slug, 'MailPlatform Lister', __( 'List Overview', 'mailplatform' ), $capability, "{$menu_slug}-list-options", 'mailplatform_views');
	remove_submenu_page( $menu_slug, $menu_slug );
	add_submenu_page( $menu_slug, 'MailPlatform Options', __( 'API Settings', 'mailplatform' ), $capability, "{$menu_slug}&type=settings", 'mailplatform_views');
}

function mailplatform_manage_options(){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
}

/**
 * Views for admin
 */
function mailplatform_views() {
	mailplatform_manage_options();
	require_once(mailplatform__PLUGIN_DIR . 'mailplatform.views.php');
}

/**
 * Shortcode widget handler
 * @param $atts
 */
function mailplatform_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'listid' => null
    ), $atts );

	ob_start();
	mailplatform_WidgetAndShortcode($a['listid'], 'shortcode');
	$output_string = ob_get_contents();
	ob_end_clean();

	return $output_string;
}

//===> ACTIONS <===\\
add_action( 'admin_init', 'mailplatform_register_my_setting' );
add_action( 'admin_menu', 'mailplatform_menu' );
add_action( 'widgets_init', 'mailplatform_wpb_load_widget' );
add_shortcode( 'mailplatform', 'mailplatform_shortcode' );