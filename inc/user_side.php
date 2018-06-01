<?php

// Регистрация скриптов и стилей
function wcsearch_user_css_js($value='')
{
	wp_enqueue_script('wcsearch-user-axios', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js', array(), null, 'in_footer');
	wp_enqueue_script('wcsearch-user-vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', array(), null, 'in_footer');
	wp_enqueue_script('wcsearch-user-app', WCSEARCH_PLUGIN_DIR_URL . 'js/app-user.js', array('wcsearch-user-vue'), null, 'in_footer');
	wp_enqueue_style( 'wcsearch-user-css', WCSEARCH_PLUGIN_DIR_URL . 'css/app-user.css' );
	wp_enqueue_style( 'wcsearch-user-font', 'https://fonts.googleapis.com/css?family=Roboto:400,500&amp;subset=cyrillic' );
	// wp_enqueue_style( 'wcsearchusercss', WCSEARCH_PLUGIN_DIR_URL . 'css/main.css' );
	// wp_enqueue_script('wcsearchuserjs', WCSEARCH_PLUGIN_DIR_URL . 'js/main.js', array('jquery'), null, 'in_footer');

	wp_localize_script( 'wcsearch-user-app', 'wcsearch_ajax', array('url' => admin_url('admin-ajax.php')));
}
add_action( 'wp_enqueue_scripts', 'wcsearch_user_css_js' );



// Button shortcode
function wcsearchBtnSet( $atts ){
	 return '<div class="wcsearch-btn" id="wcsearch-btn">wcsearch-btn</div>';
}
add_shortcode('wcsearch-btn', 'wcsearchBtnSet');


// Search window html
add_action('init', 'wcsearch_user_init');
function wcsearch_user_init() {
	add_action('wp_footer', 'wcsearch_html', 0);
}
function wcsearch_html() {
	echo '
<div id="wcsearch-user">
	<div class="search-window-cloce" id="search-window-cloce"></div>

</div>
';
}