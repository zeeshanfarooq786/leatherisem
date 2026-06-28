<?php

namespace WPNCEasyWP\Providers;

use WPNCEasyWP\WPBones\Support\ServiceProvider;

class HeartbeatServiceProvider extends ServiceProvider
{
  const DEFAULT_FREQUENCY = 15;

  public function register()
  {
    $is_disabled = WPNCEasyWP()->config('heartbeat.disabled', false);

    if ($is_disabled) {
      return wp_deregister_script('heartbeat');
    }

    $interval = WPNCEasyWP()->config('heartbeat.interval', self::DEFAULT_FREQUENCY);

    add_filter('heartbeat_send', function ($settings) use ($interval) {
      $settings['heartbeat_interval'] = intval($interval);

      return $settings;
    }, 99);
  }
}
