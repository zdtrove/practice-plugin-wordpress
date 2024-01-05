<?php

/**
 * @package AntnhPlugin
 */

namespace Inc\Base;

class Enqueue extends BaseController
{
  public function register() {
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
  }

  function enqueue() {
    // enqueue all our scripts
    wp_enqueue_style( 'antnhpluginstyles', $this->plugin_url . 'assets/styles/styles.css' );
    wp_enqueue_script( 'antnhpluginscript', $this->plugin_url . 'assets/scripts/scripts.js' );
  }
}