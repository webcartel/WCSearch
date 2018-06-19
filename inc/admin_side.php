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
	wp_enqueue_style( 'wcsearch-admin-element', 'https://unpkg.com/element-ui/lib/theme-chalk/index.css' );
	wp_enqueue_style( 'wcsearch-admin-css', WCSEARCH_PLUGIN_DIR_URL . 'css/app-admin.css' );
}

function wcsearch_admin_js()
{
	wp_enqueue_script('wcsearch-admin-axios', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js', array(), null, 'in_footer');
	wp_enqueue_script('wcsearch-admin-vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', array(), null, 'in_footer');
	wp_enqueue_script('wcsearch-admin-element', 'https://unpkg.com/element-ui/lib/index.js', array('wcsearch-admin-vue'), null, 'in_footer');
	wp_enqueue_script('wcsearch-admin-app', WCSEARCH_PLUGIN_DIR_URL . 'js/app-admin.js', array('wcsearch-admin-vue'), null, 'in_footer');
}

function wcsearch_admin()
{
	echo '
<div id="wcsearch-admin" class="wcsearch-admin" v-bind:class="{ active: appIsLoad }">
	<el-tabs v-model="activeTabName">

		<el-tab-pane label="Indexation" name="indexation">
			<el-button type="primary" @click="runIndexation()">Run indexation</el-button>
			<el-progress :percentage="80"></el-progress>
			<div class="post-type-list" v-for="postType in postTypesList">
				<div class="post-type">
					<el-switch v-model="postType.toindex" active-color="#8BC34A"></el-switch>
					<span>{{ postType.type }}</span>
				</div>
			</div>
		</el-tab-pane>

		<el-tab-pane label="Config" name="second">Config</el-tab-pane>
	</el-tabs>
</div>
';
}



// function get_search_results()
// {
// 	global $wpdb;
// 	$sql = "SELECT * FROM $wpdb->posts WHERE post_status = 'publish'";
// 	$responce = $wpdb->query($sql, ARRAY_A);
// 	echo json_encode($responce);
// 	exit();
// }
// add_action( 'wp_ajax_get_search_results', 'get_search_results' );



function get_pt()
{
	echo json_encode(get_post_types(array( 'public' => true )));
	exit();
}
add_action( 'wp_ajax_get_pt', 'get_pt' );



function get_all_items_to_index() {
	global $wpdb;
	$sql = "SELECT ID FROM $wpdb->posts WHERE post_status = 'publish'";
	$responce = $wpdb->get_results( $sql, ARRAY_A );
	foreach ($responce as $key => $value) {
		$result_arr[] = intval($value["ID"]);
	}
	echo json_encode($result_arr);

	exit();
}
add_action( 'wp_ajax_get_all_items_to_index', 'get_all_items_to_index' );



function run_index()
{
	include( WCSEARCH_PLUGIN_DIR_PATH . '/vendor/php-text-analysis/autoload.php' );
	include( WCSEARCH_PLUGIN_DIR_PATH . '/vendor/simplehtmldom/simple_html_dom.php' );
	
	global $wpdb;
	$sql = "SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND ID IN (".stripcslashes($_POST['itemstoindex']).");";
	$responce = $wpdb->get_results( $sql, ARRAY_A );

	$wcsearch_index_table = $wpdb->get_blog_prefix() . WCSEARCH_INDEX_TABLE_NAME;

	foreach ( $responce as $item ) {
		$html = str_get_html($item['post_title']);
		$text = $html->plaintext;

		// echo var_dump(stem(tokenize(textClean($text))));
		// echo var_dump(tokenize(textClean($text)));

		$keys = freq_dist(stem(tokenize(textClean($text))));
		foreach ($keys->getKeys() as $word) {
			$wpdb->query("INSERT INTO $wcsearch_index_table (`word`, `post_id`, `weight`) values ('".$word."', '".$item['ID']."', '1');");
		}
	}
	// echo $sql;
	exit();
}
add_action( 'wp_ajax_run_index', 'run_index' );



function textClean($text)
{	
	$mnemonics = array('&laquo;', '&raquo;', '&nbsp;');
	$signs = array('!', '@', '#', '$', '%', '^', '&', '*', '(', '_', ')', '+', '"', '№', ';', '%', ':', '?', '*', '-', '=', '[', ']', '{', '}', '|', '/', '<', '>', ',', '.', '`', '~', '«', '»', '—', '–', '…', '”', '“');
	$englosh_stop_words = array('i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours', 'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers', 'herself', 'it', 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves', 'what', 'which', 'who', 'whom', 'this', 'that', 'these', 'those', 'am', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does', 'did', 'doing', 'a', 'an', 'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down', 'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 'than', 'too', 'very', 's', 't', 'can', 'will', 'just', 'don', 'should', 'now');
	$text = explode(chr(13).chr(10), trim($text));
	$text = implode(' ', $text);
	$text = str_replace( $mnemonics, ' ', $text);
	$text = str_replace( $signs, ' ', $text);
	// $text = str_replace( $englosh_stop_words, ' ', $text);
	$text = str_replace( '’', '', $text);
	// $text = mb_strtoupper($text);
	$text = mb_strtolower($text);
	$text = preg_replace('/\s+/mi', ' ', trim($text));
	// $text = preg_split('/\s+/mi', $text);
	return $text;
}