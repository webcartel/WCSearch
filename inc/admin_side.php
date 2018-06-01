<?php

add_action('admin_menu', function() {
	$page = add_menu_page(
		'WCSearch - Advanced Search',
		'WCSearch', 
		'manage_options',
		'wcsearch',
		'wcsearch_admin',
		WCSEARCH_PLUGIN_DIR_URL.'images/wcsearch-plugin-icon.png',
		100
	);

	add_action( 'admin_print_styles-' . $page, 'wcsearch_admin_css' );
	add_action( 'admin_print_scripts-' . $page, 'wcsearch_admin_js' );
});

// Регистрация скриптов и стилей админки
function wcsearch_admin_css()
{
	wp_enqueue_style( 'wcsearch-admin-font', 'https://fonts.googleapis.com/css?family=Roboto:400,500&amp;subset=cyrillic' );
	wp_enqueue_style( 'wcsearch-admin-css', WCSEARCH_PLUGIN_DIR_URL . 'css/app-admin.css' );
}

function wcsearch_admin_js()
{
	wp_enqueue_script('wcsearch-admin-axios', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js', array(), null, 'in_footer');
	wp_enqueue_script('wcsearch-admin-vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', array(), null, 'in_footer');
	wp_enqueue_script('wcsearch-admin-app', WCSEARCH_PLUGIN_DIR_URL . 'js/app-admin.js', array('wcsearch-admin-vue'), null, 'in_footer');
}

function wcsearch_admin()
{
	echo '
<div id="wcsearch-admin" class="wcsearch-admin">

</div>
';
}


function get_search_results()
{
	global $wpdb;
	$sql = "SELECT * FROM $wpdb->posts WHERE post_status = 'publish'";
	$responce = $wpdb->query($sql, ARRAY_A);
	echo json_encode($responce);
	exit();
}
add_action( 'wp_ajax_get_search_results', 'get_search_results' );