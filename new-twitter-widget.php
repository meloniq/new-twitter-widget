<?php
/*
	Plugin Name: New Twitter Widget
	Plugin URI: http://blog.meloniq.net/
	Description: WordPress plugin that adds customizable Twitter widget.
	Author: MELONIQ.NET
	Version: 1.0
	Author URI: http://blog.meloniq.net
*/


/**
 * Avoid calling file directly
 */
if ( ! function_exists( 'add_action' ) )
	die( 'Whoops! You shouldn\'t be doing that.' );


/**
 * Plugin version and textdomain constants
 */
define( 'NTW_VERSION', '1.0' );
define( 'NTW_TD', 'new-twitter-widget' );


/**
 * Load Text-Domain
 */
load_plugin_textdomain( NTW_TD, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/**
 * Load front-end scripts
 */
function ntw_enqueue_scripts() {

}
add_action( 'wp_enqueue_scripts', 'ntw_enqueue_scripts' );


/**
 * Load front-end styles
 */
function ntw_enqueue_styles() {
	wp_enqueue_style( 'ntw_style', plugins_url( 'style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'ntw_enqueue_styles' );


/**
 * Initialize WP App Store Installer
 */
function ntw_wpappstore_init() {

	if ( class_exists( 'WP_App_Store_Installer' ) )
		return;

	require_once( 'includes/wp-app-store.php' );
	$wp_app_store_installer = new WP_App_Store_Installer( 3788 );
}
add_action( 'admin_init', 'ntw_wpappstore_init', 9 );


