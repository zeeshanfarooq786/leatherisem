<?php

namespace WPNCEasyWP\Providers\OpcodeCache;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use WPNCEasyWP\Traits\AdminMenuableTrait;
use WPNCEasyWP\Support\Cache;

class OpcodeCache extends Cache
{
  use AdminMenuableTrait;

  /**
   * Action used to clear all
   *
   * @var string
   */
  protected $action = 'clear_opcache';

  /**
   * List of actions where will be clear the opcache.
   *
   * @var array
   */
  protected $actions = [
    'activate_plugin',
    'deactivate_plugin',
    'switch_theme',
    'upgrader_process_complete',
    'automatic_updates_complete',
    'delete_option_update_core',
    'clear_opcache',
  ];

  /**
   * Entry point.
   */
  public function __construct()
  {
    foreach ($this->actions as $action) {
      add_action($action, [$this, 'flushOpcache']);
    }

    add_filter(
      'auto_core_update_email',
      function ($email) {
        $this->flushOpcache();

        return $email;
      }
    );

    //$this->addMenuItem(__('Clear Opcache', 'wp-nc-easywp'));

    $this->enableForClearAll();
  }

  public function flushOpcache()
  {
    if (function_exists('opcache_reset')) {
      opcache_reset();
    }
  }

  protected function flushOpcacheFiles()
  {
    try {
      if (class_exists('RecursiveDirectoryIterator')) {
        $fileCache = ini_get('opcache.file_cache');

        if ($fileCache && is_writable($fileCache)) {
          $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fileCache, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
          foreach ($files as $fileinfo) {
            if ($fileinfo->isDir()) {
              @rmdir($fileinfo->getRealPath());
            } else {
              @unlink($fileinfo->getRealPath());
            }
          }
        }
      }
    } catch (Exception $e) {
      logger()->error('flushOpcacheFiles', [$e]);
    }
  }

  protected function flushOpcachePreload()
  {
    try {
      if (function_exists('opcache_compile_file') && class_exists('RecursiveDirectoryIterator')) {
        $di = new RecursiveDirectoryIterator(ABSPATH, RecursiveDirectoryIterator::SKIP_DOTS);
        $it = new RecursiveIteratorIterator($di);

        foreach ($it as $file) {
          if (pathinfo($file, PATHINFO_EXTENSION) == "php") {
            @opcache_compile_file($file);
          }
        }
      }
    } catch (Exception $e) {
      logger()->error('flushOpcachePreload', [$e]);
    }
  }


  /**
   * Method used to clear all.
   *
   * @param bool $silent Set to true to hidden the admin notice. Default false.
   */
  public function doActionMenu($silent = false)
  {
    $this->flushOpcache();

    if (!$silent) {
      add_action('admin_notices', function () {
        echo '<div id="message" class="notice notice-success fade is-dismissible">';
        echo '<p>';
        _e('Opcache emptied!', 'wp-nc-easywp');
        echo '</p>';
        echo '</div>';
      });
    }
  }
}
