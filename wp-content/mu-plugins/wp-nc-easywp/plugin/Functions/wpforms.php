<?php
if (!defined("ABSPATH")) {
    exit();
}

/*
|--------------------------------------------------------------------------
| WPForms filter
|--------------------------------------------------------------------------
|
| Here we're going to add our own filter to the wpforms plugins.
|
|
*/

// Filter for affiliate link of wpforms plugin
add_filter('wpforms_upgrade_link', function ($url) {
  $sasID = 2303424;
  return 'http://www.shareasale.com/r.cfm?B=837827&U=' . $sasID . '&M=64312&urllink=' . rawurlencode($url);
});
