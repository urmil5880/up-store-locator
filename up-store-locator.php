<?php
/*
Plugin Name: WP Store Locator
Plugin URL:  
Text Domain: up-store-locator
Domain Path: /languages/
Description: A simple store locator plugin and custo post type with shortcode. Also work with Gutenberg shortcode block.
Version: 4.1.1
Author: Urmilwp
Author URI: https://profiles.wordpress.org/urmilwp
Contributors: Urmilwp
*/

if( !defined( 'WPSL_VERSION' ) ) {
	define( 'WPSL_VERSION', '4.1.1' ); // Version of plugin
}
if( !defined( 'WPSL_DIR' ) ) {
	define( 'WPSL_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'WPSL_POST_TYPE' ) ) {
	define( 'WPSL_POST_TYPE', 'store-locator' ); // Plugin post type
}
if( !defined( 'WPSL_CAT' ) ) {
	define( 'WPSL_CAT', 'store-category' ); // Plugin Category
}

register_activation_hook( __FILE__, 'install_storefree_version' );
function install_storefree_version() {
	if( is_plugin_active('up-store-locator/up-store-locator.php') ){
		 add_action('update_option_active_plugins', 'deactivate_storefree_version');
	}
}

function deactivate_storefree_version() {
   deactivate_plugins('up-store-locator/up-store-locator.php',true);
}

function up_store_load_textdomain() {
	load_plugin_textdomain( 'up-store-locator', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
add_action('plugins_loaded', 'up_store_load_textdomain');

function wpstorestyle_css_script() {
	wp_enqueue_style( 'cssstore',  plugin_dir_url( __FILE__ ). 'assets/css/stylestore.css', array(), WPSL_VERSION );
	wp_enqueue_script( 'up-store-public', plugin_dir_url( __FILE__ ) . 'assets/js/up-store-public.js', array( 'jquery' ), WPSL_VERSION);
}
add_action( 'wp_enqueue_scripts', 'wpstorestyle_css_script' );

function upstore_display_tags( $query ) {
	if( is_tag() && $query->is_main_query() ) {       
	   $post_types = array( 'post', WPSL_POST_TYPE );
		$query->set( 'post_type', $post_types );
	}
}
add_filter( 'pre_get_posts', 'upstore_display_tags' );

// Posttype
require_once( WPSL_DIR . '/includes/up-store-locator-post-type.php' );

// Shortcode
require_once( WPSL_DIR . '/includes/up-store-locator-shortcode.php' );

// Custom Field
require_once( WPSL_DIR . '/includes/up-custom-field.php' );
