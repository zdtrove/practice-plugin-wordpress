<?php

/**
 * @package AntnhPlugin
 */

/**
 * Plugin Name: Antnh Plugin
 * Plugin URI: new-pj.dev.com
 * Description: This is my first plugin
 * Version: 1.0.0
 * Author: Antnh
 * Author URI: new-pj.dev.com
 * License: GPLv2 or later
 * Text Domain: antnh-plugin
 */

defined( 'ABSPATH' ) or die( 'Hey, you can\t access this file, you silly human!' );

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
  require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

function activate_antnh_plugin() {
  Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_antnh_plugin' );

function deactivate_antnh_plugin() {
  Inc\Base\Deactivate::deactivate();
}
register_activation_hook( __FILE__, 'deactivate_antnh_plugin' );

if ( class_exists( 'Inc\\Init' ) ) {
  Inc\Init::register_services();
}