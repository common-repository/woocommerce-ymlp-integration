<?php
/*
Plugin Name: WooCommerce - YMLP integration
Plugin URI: http://dannyvankooten.com/wordpress-plugins/wc-ymlp/
Description: Adds a newsletter sign-up checkbox to your WooCommerce checkout form
Version: 1.0
Author: Danny van Kooten
Author URI: http://dannyvankooten.com

YMLP for WooCommerce
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define('WC_YMLP_VERSION', "1.0");
define("WC_YMLP_PLUGIN_DIR", plugin_dir_path(__FILE__)); 
define("WC_YMLP_PLUGIN_URL", plugins_url( '/' , __FILE__ ));

require_once WC_YMLP_PLUGIN_DIR . 'includes/functions.php';
require_once WC_YMLP_PLUGIN_DIR . 'includes/class-plugin.php';
new WC_YMLP_Plugin();

if(!is_admin()) {

	// PUBLIC SECTION
	require_once WC_YMLP_PLUGIN_DIR . 'includes/class-public.php';
	new WC_YMLP_Public();

} elseif(!defined("DOING_AJAX") || !DOING_AJAX) {
	// ADMIN SECTION

	require_once WC_YMLP_PLUGIN_DIR . 'includes/class-admin.php';
	new WC_YMLP_Admin();
	
}
