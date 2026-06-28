<?php
if (!defined("ABSPATH")) {
  exit();
}

/*
|--------------------------------------------------------------------------
| Plugin Menus routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the menu routes for a plugin.
| In this context, the route are the menu link.
|
*/

return [
  //    'wpnceasywp_slug_menu' => [
  //        "page_title" => "EasyWP",
  //        "menu_title" => "EasyWP",
  //        'capability' => 'activate_plugins',
  //        'icon'       => 'easywp-logo.svg',
  //        'items'      => [
  //            [
  //                "page_title" => "Main View",
  //                "menu_title" => "Welcome",
  //                'capability' => 'activate_plugins',
  //                'route'      => [
  //                    'get' => 'Dashboard\DashboardController@index',
  //                ],
  //            ],
  //            [
  //                "page_title" => "Varnish",
  //                "menu_title" => "Varnish",
  //                'capability' => 'activate_plugins',
  //                'route'      => [
  //                    'get'  => 'Varnish\VarnishController@index',
  //                    'put'  => 'Varnish\VarnishController@update',
  //                    'post' => 'Varnish\VarnishController@debug',
  //                ],
  //            ],
  //            [
  //                "page_title" => "Opcache",
  //                "menu_title" => "Opcache",
  //                'capability' => 'activate_plugins',
  //                'route'      => [
  //                    'get'  => 'OpcodeCache\OpcodeCacheController@index',
  //                ],
  //            ],
  //            [
  //                "page_title" => "Redis",
  //                "menu_title" => "Redis",
  //                'capability' => 'activate_plugins',
  //                'route'      => [
  //                    'get'  => 'Redis\RedisController@index',
  //                ],
  //            ],
  //        ],
  //    ],
];
