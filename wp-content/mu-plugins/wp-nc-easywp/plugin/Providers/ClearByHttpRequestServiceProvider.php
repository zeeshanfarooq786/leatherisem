<?php

namespace WPNCEasyWP\Providers;

use WPNCEasyWP\WPBones\Support\ServiceProvider;
use WPNCEasyWP\Providers\AutomaticUpdates\SequoiaIntegrationCache;

class ClearByHttpRequestServiceProvider extends ServiceProvider
{
  public function register()
  {
    if (isset($_SERVER['HTTP_X_EWP_CLEAR'])) {

      add_action('shutdown', function () {
        do_action('clear_redis');
        do_action('clear_varnish');
        do_action('clear_opcache');
        SequoiaIntegrationCache::init()->flush();
      });
    }
  }
}
