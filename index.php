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

define( 'WCSEARCH_INDEX_TABLE_NAME', 'wcsearch_index' );

function wcsearch_activate() {
	global $wpdb;

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$wcsearch_index_table = $wpdb->get_blog_prefix() . WCSEARCH_INDEX_TABLE_NAME;

	$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=MyISAM";

	$sql .= "CREATE TABLE {$wcsearch_index_table} (
		`word` VARCHAR(32) NOT NULL,
		`post_id` BIGINT(20) NOT NULL,
		`weight` INT(10) NOT NULL,
		PRIMARY KEY (`word`, `post_id`)
		-- UNIQUE (`chat_id`)
	) {$charset_collate};";

	dbDelta($sql);
}
register_activation_hook( __FILE__, 'wcsearch_activate' );


function wcsearch_deactivate() {
	global $wpdb;
	$wcsearch_index_table = $wpdb->get_blog_prefix() . WCSEARCH_INDEX_TABLE_NAME;
	$sql = "DROP TABLE IF EXISTS {$wcsearch_index_table};";
	$wpdb->query($sql);
}
register_deactivation_hook(  __FILE__, 'wcsearch_deactivate' );




include('inc/user_side.php');
include('inc/admin_side.php');