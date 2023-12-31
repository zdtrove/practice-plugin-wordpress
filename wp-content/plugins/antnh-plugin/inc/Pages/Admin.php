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
  public $subPages = array();

  function register()
  {
    $this->settings = new SettingsApi();
    $this->callbacks = new AdminCallbacks();
    $this->setPages();
    $this->setSubPages();
    $this->setSettings();
    $this->setSections();
    $this->setFields();
    $this->settings
      ->addPages( $this->pages )
      ->withSubPage( 'Dashboard' )
      ->addSubPages( $this->subPages )
      ->register();
  }

  public function setPages() {
    $this->pages = array(
      [
        'page_title' => 'Antnh Plugin 001',
        'menu_title' => 'Antnh001',
        'capability' => 'manage_options',
        'menu_slug' => 'antnh_plugin',
        'callback' => array( $this->callbacks, 'adminDashboard' ),
        'icon_url' => 'dashicons-store',
        'position' => 110
      ]
    );
  }

  public function setSubPages() {
    $this->subPages = array(
      array(
        'parent_slug' => 'antnh_plugin',
        'page_title' => 'Custom Post Types',
        'menu_title' => 'CPT',
        'capability' => 'manage_options',
        'menu_slug' => 'antnh_cpt',
        'callback' => function() { echo '<h1>CPT Manager</h1>'; },
      ),
      array(
        'parent_slug' => 'antnh_plugin',
        'page_title' => 'Custom Taxonomies',
        'menu_title' => 'Taxonomies',
        'capability' => 'manage_options',
        'menu_slug' => 'antnh_taxonomies',
        'callback' => function() { echo '<h1>Taxonomies Manager</h1>'; },
      ),
      array(
        'parent_slug' => 'antnh_plugin',
        'page_title' => 'Custom Widgets',
        'menu_title' => 'Widgets',
        'capability' => 'manage_options',
        'menu_slug' => 'antnh_widgets',
        'callback' => function() { echo '<h1>Widgets Manager</h1>'; },
      ),
    );
  }

  public function setSettings() {
    $args = array(
      array(
        'option_group' => 'antnh_options_group',
        'option_name' => 'text_example',
        'callback' => array( $this->callbacks, 'antnhOptionsGroup' )
      ),
      array(
        'option_group' => 'antnh_options_group',
        'option_name' => 'first_name'
      )
    );

    $this->settings->setSettings( $args );
  }

  public function setSections() {
    $args = array(
      array(
        'id' => 'antnh_admin_index',
        'title' => 'Settings',
        'callback' => array( $this->callbacks, 'antnhAdminSection' ),
        'page' => 'antnh_plugin'
      )
    );

    $this->settings->setSections( $args );
  }

  public function setFields() {
    $args = array(
      array(
        'id' => 'text_example',
        'title' => 'Text Example',
        'callback' => array( $this->callbacks, 'antnhTextExample' ),
        'page' => 'antnh_plugin',
        'section' => 'antnh_admin_index',
        'args' => array(
          'label_for' => 'text_example',
          'class' => 'example-class'
        )
        ),
        array(
          'id' => 'first_name',
          'title' => 'First Name',
          'callback' => array( $this->callbacks, 'antnhFirstName' ),
          'page' => 'antnh_plugin',
          'section' => 'antnh_admin_index',
          'args' => array(
            'label_for' => 'first_name',
            'class' => 'example-class'
          )
        )
    );

    $this->settings->setFields( $args );
  }
}
