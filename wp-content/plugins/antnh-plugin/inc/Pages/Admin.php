<?php

/**
 * @package AntnhPlugin
 */

namespace Inc\Pages;

use Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;

class Admin extends BaseController
{
  public $settings;
  public $callbacks;
  public $pages = array();

  function register()
  {
    $this->settings = new SettingsApi();
    $this->callbacks = new AdminCallbacks();
    $this->setPages();
    $this->settings
      ->addPages( $this->pages )
      ->register();
  }

  public function setPages() {
    $this->pages = array(
      [
        'page_title' => 'Plugin Tích Điểm',
        'menu_title' => 'Tích Điểm',
        'capability' => 'manage_options',
        'menu_slug' => 'antnh_plugin',
        'callback' => array( $this->callbacks, 'adminDashboard' ),
        'icon_url' => 'dashicons-table-col-after',
        'position' => 110
      ]
    );
  }
}
