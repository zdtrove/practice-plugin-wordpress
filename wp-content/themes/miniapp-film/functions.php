<?php
global $wpdb;

function setup_db()
{
  set_time_limit(-1);
  if (!function_exists('dbDelta')) {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  }

  $tableName = $wpdb->prefix . 'films';
  if ($wpdb->get_var("SHOW TABLES LIKE '" . $tableName . "'") != $tableName) {
    dbDelta("SET GLOBAL TIME_ZONE = '+07:00';");
    $sql = 'CREATE TABLE ' . $tableName . '(
      id BIGINT AUTO_INCREMENT,
      film_name VARCHAR(255) NULL,
      film_poster VARCHAR(255) NULL,
      category_id INT NULL,
      category_name VARCHAR(255) NULL,
      create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY(id))';
    dbDelta($sql);
  }
}

setup_db();

function add_film_field() {
  global $post;
  global $wpdb;
  $tableFilms = $wpdb->prefix . 'films';

  $value = get_post_meta( $post->ID, '_film_selected', true );
  if( empty( $value ) ) $value = '';

  $films = $wpdb->get_results( 'SELECT * FROM ' . $tableFilms . ' ORDER BY id ASC', ARRAY_A );

  $options[''] = 'Chọn phim';
  foreach ($films as $film) {
    $options[$film['id']] = $film['film_name'];
  }

  echo '<div class="options_group">';
  woocommerce_wp_select(
    array(
      'id'      => '_film_selected',
      'label'   => 'Chọn tên phim',
      'options' =>  $options,
      'value'   => $value,
    )
  );
  echo '</div>';
}

add_action( 'woocommerce_product_options_general_product_data', 'add_film_field' );

function save_film_field($post_id){
  $woocommerce_select = $_POST['_film_selected'];
  if (!empty( $woocommerce_select)) {
    update_post_meta( $post_id, '_film_selected', esc_attr( $woocommerce_select ) );
  } else {
    update_post_meta( $post_id, '_film_selected',  '' );
  }
}

add_action( 'woocommerce_process_product_meta', 'save_film_field' );

add_menu_page('Danh sách phim', 'Danh sách phim', 'manage_options', 'danh-sach-phim',  'pageTemplate', '', 81);

function pageTemplate() {
  require_once(dirname(__FILE__) . '/templates/admin.php');
}

function themeslug_enqueue_style() {
  wp_enqueue_style('admin_css', get_template_directory_uri()  . '/style.css');
  wp_enqueue_script('admin_js', get_template_directory_uri()  . '/script.js');
}

add_action('admin_enqueue_scripts', 'themeslug_enqueue_style');

function remove_notice() {
  remove_action('admin_notices', 'update_nag', 3);
}
  
add_action('admin_menu','remove_notice');

function load_media_files() {
  wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'load_media_files');

function filter_site_upload_size_limit() {
  return 1024 * 512000;
}

add_filter( 'upload_size_limit', 'filter_site_upload_size_limit', 20 );
