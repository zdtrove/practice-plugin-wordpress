<?php
global $wpdb;

setup_db();
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
      film_season VARCHAR(255) NULL,
      discount INT NULL,
      category_id INT NULL,
      category_name VARCHAR(255) NULL,
      create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY(id))';
    dbDelta($sql);
  }
}

add_action('woocommerce_product_options_general_product_data', 'add_film_field');
function add_film_field()
{
  global $post;
  global $wpdb;
  $tableFilms = $wpdb->prefix . 'films';

  $value = get_post_meta($post->ID, '_film_selected', true);
  if (empty($value)) $value = '';

  $films = $wpdb->get_results('SELECT * FROM ' . $tableFilms . ' ORDER BY id ASC', ARRAY_A);

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

add_action('woocommerce_process_product_meta', 'save_film_field');
function save_film_field($post_id)
{
  $woocommerce_select = $_POST['_film_selected'];
  if (!empty($woocommerce_select)) {
    update_post_meta($post_id, '_film_selected', esc_attr($woocommerce_select));
  } else {
    update_post_meta($post_id, '_film_selected',  '');
  }
}

add_menu_page('Danh sách phim', 'Danh sách phim', 'manage_options', 'danh-sach-phim',  'pageTemplate', '', 81);
function pageTemplate()
{
  require_once(dirname(__FILE__) . '/templates/admin.php');
}

add_action('admin_enqueue_scripts', 'themeslug_enqueue_style');
function themeslug_enqueue_style()
{
  wp_enqueue_style('admin_css', get_template_directory_uri()  . '/style.css');
  wp_enqueue_script('admin_js', get_template_directory_uri()  . '/script.js');
}

add_action('admin_menu', 'remove_notice');
function remove_notice()
{
  remove_action('admin_notices', 'update_nag', 3);
}

add_action('admin_enqueue_scripts', 'load_media_files');
function load_media_files()
{
  wp_enqueue_media();
}

add_filter('upload_size_limit', 'filter_site_upload_size_limit', 20);
function filter_site_upload_size_limit()
{
  return 1024 * 512000;
}

add_filter('manage_edit-product_columns', 'change_product_column', 15);
function change_product_column($columns)
{
  $columns['_film_selected'] = __('Film');
  unset($columns['product_tag']);
  unset($columns['sku']);
  unset($columns['is_in_stock']);
  unset($columns['thumb']);
  unset($columns['featured']);

  $newColumns = [];
  $newColumns['cb'] = $columns['cb'];
  $newColumns['name'] = $columns['name'];
  $newColumns['_film_selected'] = $columns['_film_selected'];
  $newColumns['price'] = $columns['price'];
  $newColumns['product_cat'] = $columns['product_cat'];
  $newColumns['date'] = $columns['date'];

  return $newColumns;
}

add_action('manage_product_posts_custom_column', 'add_column_product_list', 10, 2);
function add_column_product_list($column, $postid)
{
  if ($column == '_film_selected') {
    global $wpdb;
    $tableFilms = $wpdb->prefix . 'films';
    $films = $wpdb->get_results('SELECT * FROM ' . $tableFilms . ' ORDER BY id ASC', ARRAY_A);
    $idFilm = get_post_meta($postid, '_film_selected', true);
    foreach ($films as $film) {
      if ($film['id'] == $idFilm) {
        echo $film['film_name'];
        if (!empty($film['film_season'])) {
          echo ' - Phần ' . $film['film_season'];
        }
      }
    }
  }
}

add_filter('woocommerce_products_admin_list_table_filters', 'remove_product_filter', 10, 1);
function remove_product_filter($filters)
{
  if (isset($filters['product_type'])) {
    unset($filters['product_type']);
  }

  if (isset($filters['stock_status'])) {
    unset($filters['stock_status']);
  }

  return $filters;
}

add_action('admin_menu', 'remove_taxonomy_menu_pages', 999);
function remove_taxonomy_menu_pages()
{
  remove_submenu_page('edit.php?post_type=product', 'product_attributes');
  remove_submenu_page('edit.php?post_type=product', 'product-reviews');
  remove_submenu_page('edit.php?post_type=product', 'edit-tags.php?taxonomy=product_tag&amp;post_type=product');
}

add_action('init', 'rename_product');
function rename_product()
{
  global $wp_post_types;
  if (class_exists('WooCommerce')) {
    $wp_post_types['product']->labels->name = 'Tập phim';
    $wp_post_types['product']->labels->add_new = 'Thêm tập phim';
    $wp_post_types['product']->labels->all_items = 'Tất cả tập phim';
    $wp_post_types['product']->labels->menu_name = 'Tập phim';
  }
}

add_filter('post_row_actions', 'remove_product_list_action', 15, 2);
function remove_product_list_action($actions, $post)
{
  if ('product' === $post->post_type) {
    unset($actions['inline hide-if-no-js']);
    unset($actions['trash']);
    unset($actions['view']);
    unset($actions['duplicate']);
    unset($actions['edit']);
  }

  return $actions;
}

function cs_get_filter_options()
{
  global $wpdb;
  $tableFilms = $wpdb->prefix . 'films';
  $films = $wpdb->get_results('SELECT * FROM ' . $tableFilms . ' ORDER BY id DESC', ARRAY_A);

  $options = [];
  $options = [...$options, [
    'name' => 'Filter by film name',
    'value' => '',
    'selected' => (!isset($_GET['_film_selected']) || empty($_GET['_film_selected'])) ? 'selected' : '',
  ]];

  foreach ($films as $film) {
    $addOption = [
      'name' => $film['film_name'],
      'value' => $film['id'],
      'selected' => (isset($_GET['_film_selected']) && $_GET['_film_selected'] == $film['id']) ? 'selected="selected"' : '',
    ];

    if (!empty($film['film_season'])) {
      $addOption['name'] .= ' - Phần ' . $film['film_season'];
    }

    $options = [...$options, $addOption];
  }

  $output = '';
  foreach ($options as $option) {
    $output .= '<option value="' . $option['value'] . '" ' . $option['selected'] . '>' . $option['name'] . '</option>';
  }

  return $output;
}

add_filter('woocommerce_product_filters', 'custom_filter_film');
function custom_filter_film($output)
{
  $output .= '<select class="film-filter dropdown_product_cat" name="_film_selected">' . cs_get_filter_options() . '</select>';

  return $output;
}

add_action('pre_get_posts', 'cs_products_filter_query');
function cs_products_filter_query($query)
{
  if (is_admin()) {
    if (isset($_GET['_film_selected']) && !empty($_GET['_film_selected'])) {
      $meta_query = (array)$query->get('meta_query');
      $meta_query[] = [
        'key'     => '_film_selected',
        'value'   => wc_clean(wp_unslash($_GET['_film_selected'])),
        'compare' => '=',
      ];

      $query->set('meta_query', $meta_query);
    }
  }
}

add_filter('bulk_actions-edit-product', 'remove_from_bulk_actions');
function remove_from_bulk_actions($actions)
{
  unset($actions['edit']);

  return $actions;
}

function post_remove() { 
  remove_menu_page('edit.php');
  remove_menu_page('index.php');
  remove_menu_page('upload.php');
  remove_menu_page('edit.php?post_type=page');
  remove_menu_page('edit-comments.php');
  remove_menu_page('tools.php');
  remove_menu_page('themes.php');
  remove_menu_page('plugins.php');
  remove_menu_page('options-general.php');
}

add_action('admin_menu', 'post_remove');

add_filter( 'woocommerce_admin_disabled', '__return_true' );

function remove_tab($tabs){
  unset($tabs['linked_product']);
  unset($tabs['inventory']);
  unset($tabs['shipping']);
  unset($tabs['attribute']);
  unset($tabs['advanced']);
  unset($tabs['variations']);
  return($tabs);
}
add_filter('woocommerce_product_data_tabs', 'remove_tab', 10, 1);

add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );

add_filter( 'product_type_selector', 'remove_product_types' );

function remove_product_types($types){
  unset($types['grouped']);
  unset($types['external']);
  unset($types['variable']);
  return $types;
}

function CM_woocommerce_account_menu_items_callback($items) {
  unset( $items['downloads'] );
  return $items;
}
add_filter('woocommerce_account_menu_items', 'CM_woocommerce_account_menu_items_callback', 10, 1);

add_filter( 'login_url', 'customLogin', 10, 2 );
function customLogin( $login_url) {
  return str_replace('wp-login.php', 'dangnhap', $login_url);
}

function my_login_logo() { ?>
  <style type="text/css">
    .wp-login-lost-password, #backtoblog {
      display: none;
    }
    #login h1 a, .login h1 a {
      background-image: url('https://movie.ntvco.com/wp-content/uploads/2024/03/logogimy2.png');
      background-size: 145px;
      width: 145px;
    }
    #login {
      width: 500px !important;
      padding: 8% 0 0 !important;
    }
    #loginform {
      padding: 30px 60px;
      border-radius: 10px;
      box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
    }
    #loginform input {
      border-radius: 20px;
      border: 1px solid #ddd;
      outline: none;
      padding: 5px 10px;
      padding-bottom: 6px;
      font-size: 16px;
    }
    #loginform #wp-submit {
      padding: 0px 15px;
      border-radius: 3px;
      font-size: 14px;
    }
    #loginform .wp-hide-pw {
      color: #fe6900;
      outline: none;
      border: unset;
    }
    #loginform .wp-hide-pw:focus {
      border-color: unset;
      box-shadow: unset;
    }
    #loginform label {
      font-weight: bold;
    }
    #loginform #wp-submit {
      background-color: #fe6900;
      border-color: #fe6900;
      font-weight: bold;
    }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function custom_login_redirect() {
  return home_url() . '/wp-admin/admin.php?page=danh-sach-phim/';
}
  
add_filter('login_redirect', 'custom_login_redirect');

/* Rename WooCommerce menu */
add_action( 'admin_menu', 'rename_woocoomerce', 999 );
function rename_woocoomerce()
{
  global $menu;
  $woo = rename_woocommerce( 'WooCommerce', $menu );
  if( !$woo )
    return;
    $menu[$woo][0] = 'Orders';
  }
  function rename_woocommerce($needle, $haystack) {
    foreach($haystack as $key => $value) {
    $current_key = $key;
    if (
      $needle === $value
      OR (
        is_array( $value )
        && rename_woocommerce( $needle, $value ) !== false
      )
    ) {
      return $current_key;
    }
  }
  return false;
}

function plt_hide_woocommerce_menus() {
	remove_menu_page('wc-admin&path=/wc-pay-welcome-page');
	remove_submenu_page('woocommerce', 'wc-admin');
	remove_submenu_page('woocommerce', 'wc-admin&path=/customers');
	remove_submenu_page('woocommerce', 'wc-reports');
	remove_submenu_page('woocommerce', 'wc-admin&path=/extensions');
	remove_submenu_page('woocommerce', 'wc-addons');
	remove_submenu_page('woocommerce-marketing', 'admin.php?page=wc-admin&path=/marketing');
  remove_submenu_page('woocommerce', 'wc-status');
}

add_action('admin_menu', 'plt_hide_woocommerce_menus', 100);