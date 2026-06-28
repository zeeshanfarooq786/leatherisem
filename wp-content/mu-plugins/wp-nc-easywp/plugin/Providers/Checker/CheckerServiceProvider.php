<?php

namespace WPNCEasyWP\Providers\Checker;

use WPNCEasyWP\Providers\Checker\Plugins;
use WPNCEasyWP\WPBones\Support\ServiceProvider;

class CheckerServiceProvider extends ServiceProvider
{

  public function register()
  {
    if (is_admin()) {
      Plugins::boot();
    }

    //        add_filter('wpnceasywp_admin_menu',
    //            function ($adminMenu, $parentId) {
    //                $adminMenu[] = [
    //                    'parent' => $parentId,
    //                    'id'     => 'wpnceasywp-check-banned-plugins',
    //                    'title'  => __('Check for incompatible Plugin', 'wp-nc-easywp'),
    //                    'href'   => wp_nonce_url(add_query_arg('vhp_flush_do', 'all'), 'vhp-flush-do'),
    //                    'meta'   => [
    //                        'title' => __('Check for incompatible Plugin', 'wp-nc-easywp'),
    //                    ],
    //                ];
    //
    //                return $adminMenu;
    //
    //            }, 100, 2);
  }
}
