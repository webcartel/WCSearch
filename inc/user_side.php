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
	
	<div class="search-form-block">
		<input type="text" name="query" placeholder="Search" autocomplete="off" autofocus v-model="query" @keyup="getResults()">
		<button class="search-btn">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
			    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
			    <path d="M0 0h24v24H0z" fill="none"/>
			</svg>
		</button>
	</div>
	<div class="search-results-block" v-if="results != null">
		<div class="search-item" v-for="result in results" @click="goTo(result.url)">
			{{ result.word }}
		</div>
	</div>
</div>
';
}



function get_search_results()
{
	if ( $_POST['query'] != '' ) {
		global $wpdb;
		$wcsearch_index_table = $wpdb->get_blog_prefix() . WCSEARCH_INDEX_TABLE_NAME;
		$sql = "SELECT * FROM $wcsearch_index_table WHERE word LIKE '%".$_POST['query']."%' ORDER BY `weight` DESC";
		$responce = $wpdb->get_results( $sql, ARRAY_A );
		foreach ($responce as $item) {
			$results[] = array('word' => $item['word'], 'url' => get_permalink($item['post_id']), 'post_id' => $item['post_id']);
		}
		echo json_encode($results);
		exit();
	}
}
add_action( 'wp_ajax_get_search_results', 'get_search_results' );
add_action( 'wp_ajax_nopriv_get_search_results', 'get_search_results' );