<?php
  /**
   * Called on uninstall of plugin
   **/

  if (!defined('WP_UNINSTALL_PLUGIN')) {
      exit;
  }
  delete_option('travel.gayguide.options');
