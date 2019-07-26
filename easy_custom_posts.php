<?php

/*
	Plugin Name: Easy Custom Posts
	Description: Plugin para criação de custom posts
	Author: Andre Mendonsa
	Version: 0.6
*/

add_action('admin_menu', 'sc_custom_post_add_menu');
add_action('admin_init', 'sc_custom_post_enqueue');
add_action('wp_ajax_delete_post_type', 'delete_post_type');
add_action('wp_ajax_nopriv_delete_post_type', 'delete_post_type');
register_activation_hook( __FILE__, 'sc_custom_post_install' );
require_once "display_custom_posts.php";

global $options_db_version;
$options_db_version = '1.0';

/*Treat injection in the post*/
function sc_custom_post_install() {
	global $wpdb;
	global $options_db_version;

	$table_name = $wpdb->prefix .'custom_posts';	

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id INT(11) NOT NULL AUTO_INCREMENT,
		name tinytext NULL,
		singular_name tinytext NULL,
		dashicon tinytext NULL,
		post_type tinytext NULL,
		supports tinytext NULL,
		thumbnail_dimensions tinytext NULL,
		related_post tinytext NULL,
		has_category INT(11) NOT NULL DEFAULT 0,
		has_tag INT(11) NOT NULL DEFAULT 0,
		create_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY id (id)

	) $charset_collate;";	

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );
	add_option( 'options_db_version', $options_db_version );
}

function sc_custom_post_add_menu() {
	add_menu_page(
		'Easy Custom Posts',
		'Easy Custom Posts',
		'manage_options',
		'sc_custom_posts',
		'sc_custom_post_page_layout',
		'dashicons-admin-network'
	);

	/* Add Post */
	add_submenu_page( 
    	null,
		'Adicionar Post Type',
		'Adicionar Post Type',
		'manage_options',
		'add_post',
		'custom_post_add_page'
    );

    /* Edit Post */
    add_submenu_page( 
    	null,
		'Editar Post Type',
		'Editar Post Type',
		'manage_options',
		'edit_post',
		'custom_post_edit_page'
    );

	/* Import Custom Post */
	add_submenu_page(
		null,
		'Import Posts',
		'Import Posts',
		'manage_options',
		'import_posts',
		'custom_post_import'
	);
}

function sc_custom_post_page_layout() {
	require_once(plugin_dir_path(__FILE__) . 'templates/view_posts.php');
}

function custom_post_add_page(){
	if (!empty($_POST) && isset($_POST['add_post'])){
		global $wpdb;

		foreach ($_POST['supports'] as $key => $value) {
			$supports[] =  $value;
		}
		
		$table = $wpdb->prefix."custom_posts";
		$data = array(
			'name'           => sanitize_text_field($_POST['name']),
			'singular_name'  => sanitize_text_field($_POST['singular_name']),
			'dashicon' 		 => sanitize_text_field($_POST['dashicon']),
			'post_type'   	 => sanitize_text_field($_POST['post_type']),
			'supports'   	 => !empty($supports)?implode(",", $supports):'',
			'thumbnail_dimensions'   => isset($_POST['thumbnail_dimensions'])?sanitize_text_field($_POST['thumbnail_dimensions']):'',
			'related_post'   => isset($_POST['related_post'])?sanitize_text_field($_POST['related_post']):'',
			'has_category'   => isset($_POST['category'])?sanitize_text_field($_POST['category']):'',
			'has_tag'   => isset($_POST['tag'])?sanitize_text_field($_POST['tag']):''
		);

		$insert = $wpdb->insert($table, $data);

		if ( $insert == true ){
			redirect_posts_list();
		}
	}

	require_once(plugin_dir_path(__FILE__) . 'templates/add_post_type.php');
}

function custom_post_edit_page(){
	if (!empty($_POST) && isset($_POST['edit_post'])){
		global $wpdb;

		foreach ($_POST['supports'] as $key => $value) {
			$supports[] =  $value;
		}

		$table = $wpdb->prefix."custom_posts";
		$data = array(
			'name'           => sanitize_text_field($_POST['name']),
			'singular_name'  => sanitize_text_field($_POST['singular_name']),
			'dashicon' 		 => sanitize_text_field($_POST['dashicon']),
			'post_type'   	 => sanitize_text_field($_POST['post_type']),
			'supports'   	 => !empty($supports)?implode(",", $supports):'',
			'thumbnail_dimensions'   => isset($_POST['thumbnail_dimensions'])?sanitize_text_field($_POST['thumbnail_dimensions']):'',
			'related_post'   => isset($_POST['related_post'])?sanitize_text_field($_POST['related_post']):'',
			'has_category'   => isset($_POST['category'])?sanitize_text_field($_POST['category']):'0',
			'has_tag'   => isset($_POST['tag'])?sanitize_text_field($_POST['tag']):'0'
		);

		$update = $wpdb->update($table, $data, array( 'id' => $_POST['post_id'] ));

		if ( $update == true ){
			redirecet_post_edit();
		}
	}

	require_once(plugin_dir_path(__FILE__) . 'templates/edit_post_type.php');		
}

function custom_post_import(){
	$str = file_get_contents(plugin_dir_path(__FILE__) . 'custom_posts_json.json');
	$custom_posts = json_decode($str, true);

	global $wpdb;
	$table = $wpdb->prefix."custom_posts";

	foreach ($custom_posts['posts'] as $post) {
		$data = array(
			'name'                 => $post['name'],
			'singular_name'        => $post['singular_name'],
			'dashicon' 		       => $post['dashicon'],
			'post_type'   	       => $post['post_type'],
			'supports'   	       => $post['supports'],
			'thumbnail_dimensions' => $post['thumbnail_dimensions'],
			'related_post'         => $post['related_post'],
			'has_category'         => $post['has_category'],
			'has_tag'              => $post['has_tag']
		);

		$insert = $wpdb->insert($table, $data);
    }


	if ( $insert == true ){
		redirect_posts_list();
	}
}

function delete_post_type(){
	$id = $_POST['id'];

	global $wpdb;
	$table = $wpdb->prefix."custom_posts";

	$delete = $wpdb->delete(
		$table,
		array(
			'id' => $id
		)
	);

	if($delete) {
	    $response['class'] = "success";
	    $response['message'] = "Custom post excluido com sucesso!";
	} else {
		$response['class'] = "error";
		$response['message'] = "Não foi possível excluir. Tente novamente mais tarde.";
	}

	echo json_encode($response);
	die();
}

function sc_get_custom_posts(){
	global $wpdb;
	$table = $wpdb->prefix."custom_posts";
	$post_type = $wpdb->get_results("SELECT * FROM ".$table." ORDER BY name");

	return $post_type;
}

function has_posts(){
	global $wpdb;
	$table = $wpdb->prefix."custom_posts";
	$post_type = $wpdb->get_results("SELECT * FROM ".$table." ORDER BY name");

	if ($post_type){
	    return true;
    } else {
	    return false;
    }
}

function redirect_posts_list(){?>
	<script type="text/javascript">
        window.location = '<?=admin_url();?>/admin.php?page=sc_custom_posts';
	</script>
<?php }

function redirecet_post_edit(){ ?>
	<script type="text/javascript">
        location.reload();
	</script>
<?php }

function sc_custom_post_enqueue() {
	// CSS
	wp_enqueue_style( 'sc_custom_post_font_awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'sc_custom_post_css', plugin_dir_url( __FILE__ ) . 'css/estilo.css', array(), null );
	wp_enqueue_style( 'sc_custom_post_modal_css', plugin_dir_url( __FILE__ ) . 'css/jquery.loadingModal.min.css', array(), null );

	//JS
    wp_enqueue_script('sc_custom_post_modal_js', plugin_dir_url( __FILE__ ) . 'js/jquery.loadingModal.min.js', array(), null);
}

