<?php
if (!defined("ABSPATH")) {
    exit();
}

/*
|--------------------------------------------------------------------------
| Custom page routes
|--------------------------------------------------------------------------
|
| Here is where you can register all page routes for your custom view.
| Then you will use $plugin->getPageUrl( 'custom_page' ) to get the URL.
|
*/

return [
  'easywp' => [
    'title'      => 'EasyWP Plugin Debug',
    'capability' => 'administrator',
    'route'      => [
      'get' => 'Dashboard\DashboardController@index'
    ]
  ]
];
