<?php

namespace WPNCEasyWP\Providers\Redis;

use WPNCEasyWP\Traits\AdminMenuableTrait;
use WPNCEasyWP\Support\Cache;

class RedisCache extends Cache
{
  use AdminMenuableTrait;

  /**
   * Action used to clear all
   *
   * @var string
   */
  protected $action = 'clear_redis';

  /**
   * Entry point.
   */
  public function __construct()
  {
    //$this->addMenuItem(__('Flush Redis cache', 'wp-nc-easywp'));

    $this->enableForClearAll();

    // FIX: https://app.clickup.com/t/86958e9v4
    add_action('automatic_updates_complete', 'wp_cache_flush');
    add_action('upgrader_process_complete', [$this, 'upgrader_process_complete'], 10, 2);

    // EASYWP-4703
    add_action('automatic_updates_complete', [$this, 'automatic_updates_complete']);
    add_action('delete_option_update_core', 'wp_cache_flush');
    add_action('clear_redis', 'wp_cache_flush');
    add_filter('auto_core_update_send_email', [$this, 'auto_core_update_email']);
    add_action($this->action, 'wp_cache_flush');
  }

  public function auto_core_update_email($email)
  {
    wp_cache_flush();
    return $email;
  }

  public function automatic_updates_complete()
  {
    wp_cache_flush();
  }

  public function upgrader_process_complete($upgrader_object, $options)
  {

    if ('update' !== $options['action']) {
      return;
    }

    $lock = get_option('auto_updater.lock');

    if (!empty($lock) || wp_doing_cron()) {
      return;
    }

    wp_cache_flush();
  }

  /**
   * Method used to clear all.
   *
   * @param bool $silent Set to true to hidden the admin notice. Default false.
   */
  public function doActionMenu($silent = false)
  {
    wp_cache_flush();

    if (!$silent) {
      add_action('admin_notices', function () {
        echo '<div id="message" class="notice notice-success fade is-dismissible">';
        echo '<p>';
        _e('Redis cache emptied!', 'wp-nc-easywp');
        echo '</p>';
        echo '</div>';
      });
    }
  }
}
