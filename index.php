<?php

/*
Plugin Name: WCSearch
Description: Advanced Search Plugin
Plugin URI: http://#
Author: Web Cartel
Author URI: http://web-cartel.ru
Version: 1.0
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WCSEARCH_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WCSEARCH_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );



function wcsearch_activate() {

}
register_activation_hook( __FILE__, 'wcsearch_activate' );


function wcsearch_deactivate() {

}
register_deactivation_hook(  __FILE__, 'wcsearch_deactivate' );




include('inc/user_side.php');
include('inc/admin_side.php');