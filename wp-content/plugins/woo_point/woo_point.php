<?php
/**
* Plugin Name: woo_point
* Plugin URI: https://www.yourwebsiteurl.com/
* Description: This is the very first plugin I ever created.
* Version: 1.0
* Author: WOO_POINT
* Author URI: http://yourwebsiteurl.com/
**/

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if(is_admin())
    {
        new Paulund_Wp_List_Table();
    }

    
class Paulund_Wp_List_Table
{
    /**
     * Constructor will create the menu item
     */
    public $plugin_path;
    public $plugin_url;

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 1 ) ).'woo_point';
        $this->plugin_url = plugin_dir_url( dirname( __FILE__ ) ).'woo_point';
        add_action( 'admin_menu', array($this, 'themeslug_enqueue_style') );
        add_action( 'admin_menu', array($this, 'add_menu_example_list_table_page' ));
        add_action('admin_enqueue_scripts', array($this, 'load_media_files'));
    }
    function themeslug_enqueue_style() {
        wp_enqueue_style( 'add_point_style', $this->plugin_url . '/assets/styles/styles.css' );
        wp_enqueue_script( 'add_point_script', $this->plugin_url . '/assets/scripts/scripts.js' );
    }

    function load_media_files() {
        wp_enqueue_media();
    }
    
    /**
     * Menu item will allow us to load the page to display the table
     */
    public function add_menu_example_list_table_page()
    {
        $this->theme_options_panel();
    }
    public function theme_options_panel(){
        add_menu_page('Tích Điểm', 'Tích Điểm', 'manage_options', 'tich-diem',  array($this, 'wps_theme_func'));

      }
       
    function wps_theme_func(){
        return require_once( "$this->plugin_path/templates/admin.php" );

      }
    public function wps_theme_func_settings(){
              echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
              <h2>Settings</h2></div>';
      }
      public  function wps_theme_func_tich_diem(){
              echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
              <h2>FAQ</h2></div>';
      }
    /**
     * Display the list table page
     *
     * @return Void
     */
    public function list_table_page()
    {
        ?>
        <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>Push Log</h2>
                <form method="get">
                <p class="search-box">
                    <label class="screen-reader-text" for="search"><?php echo (isset($_GET['s']))?$_GET['s']:""; ?>:</label>
                    <input type="hidden" name="page" value="example-list-table.php">
                    <input type="search" id="<?php echo esc_attr( 'search' ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
                        <?php submit_button( "Save Changes", '', '', false, array( 'id' => 'search-submit' ) ); ?>
                </p>
                </form>
                <?php $exampleListTable->display(); ?>
            </div>
        <?php
    }
}

class Example_List_Table extends WP_List_Table
{
   
    // public function curlApiGet($url="",$perPage,$page,$order,$string)
    // {
     
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt( $ch, CURLOPT_HTTPHEADER,  array( "Origin: ".get_option('siteurl')));

    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch,CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, "page=$page&perPage=$perPage&order=$order&s=$string");
    //     $response = curl_exec($ch);
        
        
    //     curl_close ($ch);

    //     // do anything you want with your response
    //     return json_decode($response);
    // }
    // public function prepare_items()
    // {
    //     $columns = $this->get_columns();
        
    //     $hidden = $this->get_hidden_columns();
    //     $sortable = $this->get_sortable_columns();

    //     $data = $this->table_data();
    // 	$url = get_option( 'url_notification', '' ).'/get_chart_queue';
    //     $perPage = 10;
    //     $currentPage = $this->get_pagenum();
    //     $order = (isset($_GET['order']))?$_GET['order']:"DESC";
    //     $string = (isset($_GET['s']))?$_GET['s']:"";
    //     $results = $this->curlApiGet($url,$perPage,$currentPage,$order,$string);
      
    //     if(!$results){
    
    //     die;
    //     }
    //     $data = $results->data;
    //     foreach($data as $key => $val){
    //         $data[$key] = (array) $val;
            
    //         $data[$key]['percent_clicked'] = $val->percent_clicked ." (".round($val->percent_clicked *100 / ($val->percent_success * $val->total / 100),1)."%)";
    //         $data[$key]['created_at'] = date("Y/m/d H:i", strtotime($val->created_at) + 60*60*9);
    //         $data[$key]['percent_success'] = $val->percent_success * $val->total / 100 . " (".round($val->percent_success,1)."%)";
    //         $data[$key]['percent_error'] = $val->percent_error * $val->total / 100 . " (".round($val->percent_error,1)."%)";
    //     }
     

    //     usort( $data, array( &$this, 'sort_data' ) );
        
    //     $totalItems = (isset($results->page))?$results->page->total:count($data);

    //     $this->set_pagination_args( array(
    //         'total_items' => $totalItems,
    //         'per_page'    => $perPage
    //     ) );
      
    //     $this->_column_headers = array($columns, $hidden, $sortable);
    //     $this->items = $data;
    // }

    // /**
    //  * Override the parent columns method. Defines the columns to use in your listing table
    //  *
    //  * @return Array
    //  */
    // public function get_columns()
    // {
    //     $columns = array(
    //         'title'          => 'Post title',
    //         'created_at'       => '送信日',
    //         'total' => '総送信数',
    //         'percent_success'        => '送信成功',
    //         'percent_error'    => '送信失敗',
    //         'percent_clicked' => 'Tapされた数'
    //     );

    //     return $columns;
    // }

    // /**
    //  * Define which columns are hidden
    //  *
    //  * @return Array
    //  */
    // public function get_hidden_columns()
    // {
    //     return array();
    // }

    // /**
    //  * Define the sortable columns
    //  *
    //  * @return Array
    //  */
    // public function get_sortable_columns()
    // {
    //     return array('created_at' => array('created_at', false));
    // }

    // /**
    //  * Get the table data
    //  *
    //  * @return Array
    //  */
    // // private function table_data()
    // // {

    // //     $data = array();

    // //     $data[] = array(
    // //                 'id'          => 1,
    // //                 'title'       => 'The Shawshank Redemption',
    // //                 'description' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
    // //                 'year'        => '1994',
    // //                 'director'    => 'Frank Darabont',
    // //                 'rating'      => '9.3'
    // //                 );

        

    // //     return $data;
    // // }

    // /**
    //  * Define what data to show on each column of the table
    //  *
    //  * @param  Array $item        Data
    //  * @param  String $column_name - Current column name
    //  *
    //  * @return Mixed
    //  */
    // public function column_default( $item, $column_name )
    // {
  
    //     switch( $column_name ) {
    //         case 'id':
    //         case 'title':
    //         case 'created_at':
    //         case 'total':
    //         case 'percent_success':
    //         case 'percent_error':
    //         case 'percent_clicked':
    //             return $item[ $column_name ];

    //         default:
    //             return print_r( $item, true ) ;
    //     }
    // }

    // /**
    //  * Allows you to sort the data by the variables set in the $_GET
    //  *
    //  * @return Mixed
    //  */
    // private function sort_data( $a, $b )
    // {
    //     // Set defaults
    //     $orderby = 'title';
    //     $order = 'asc';

    //     // If orderby is set, use this as the sort column
    //     if(!empty($_GET['orderby']))
    //     {
    //         $orderby = $_GET['orderby'];
    //     }

    //     // If order is set use this as the order
    //     if(!empty($_GET['order']))
    //     {
    //         $order = $_GET['order'];
    //     }


    //     $result = strcmp( $a[$orderby], $b[$orderby] );

    //     if($order === 'asc')
    //     {
    //         return $result;
    //     }

    //     return -$result;
    // }
}



function pluginprefix_setup_db(){
    // Function change serialized
    set_time_limit(-1);
    global $wpdb;
    try{
        if(!function_exists('dbDelta')) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        }
        $ptbd_table_name = $wpdb->prefix . 'woo_rank';
        if ($wpdb->get_var("SHOW TABLES LIKE '". $ptbd_table_name ."'"  ) != $ptbd_table_name ) {

            $sql  = 'CREATE TABLE '.$ptbd_table_name.'(
            id INT AUTO_INCREMENT,
            imageurl VARCHAR(255)  NULL,
            name VARCHAR(255) NOT NULL ,
            minimum_spending INT NOT NULL,
            price_sale_off INT NOT NULL,
            is_limit INT DEFAULT 0,
            price_sale_off_max INT NOT NULL,
                    PRIMARY KEY(id))';
            dbDelta($sql);
        }

        $ptbd_table_name = $wpdb->prefix . 'woo_setting';
        if ($wpdb->get_var("SHOW TABLES LIKE '". $ptbd_table_name ."'"  ) != $ptbd_table_name ) {

            $sql  = 'CREATE TABLE '.$ptbd_table_name.'(
            id INT AUTO_INCREMENT,
            amount_spent INT NOT NULL,
            points_converted_to_money INT NOT NULL,
                    PRIMARY KEY(id))';
            dbDelta($sql);
        }

        $ptbd_table_name = $wpdb->prefix . 'woo_history_user_point';
        if ($wpdb->get_var("SHOW TABLES LIKE '". $ptbd_table_name ."'"  ) != $ptbd_table_name ) {

            $sql  = 'CREATE TABLE '.$ptbd_table_name.'(
            id INT AUTO_INCREMENT,
            user_id INT NOT NULL,
            total_order INT NOT NULL,
            order_id INT NULL,
            point INT NOT NULL,
            minimum_spending INT  NULL,
            price_sale_off INT  NULL,
            price_sale_off_max INT  NULL,
            status INT DEFAULT 1, 
            create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP  ,

                    PRIMARY KEY(id))';
                    //status =1 (them) =2  (tru)
            dbDelta($sql);
        }
    } catch (\Exception $ex) {
    }
}

/**
 * Activate the plugin.
 */
function pluginprefix_activate() { 
    // Trigger our function that registers the custom post type plugin.
    pluginprefix_setup_db(); 
    
    // Clear the permalinks after the post type has been registered.
    flush_rewrite_rules(); 
}

// /**
//  * DeActivate the plugin.
//  */
// function pluginprefix_deactivate() { 
//     // Trigger our function that registers the custom post type plugin.
//     pluginprefix_unsetup_db(); 
//     // Clear the permalinks after the post type has been registered.
//     flush_rewrite_rules(); 
// }
register_activation_hook( __FILE__, 'pluginprefix_activate' );
// register_deactivation_hook(
// 	__FILE__,
// 	'pluginprefix_deactivate'
// );
