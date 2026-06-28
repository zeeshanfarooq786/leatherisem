<?php

/**
 * Plugin Name: EasyWP Plugin
 * Plugin URI: https://www.namecheap.com/support/knowledgebase/article.aspx/10015/2279/easywp-plugins-cache-plugin-seo-plugin-and-blocked-plugins/
 * Description: Integrates with EasyWP to guarantee website performance with caching, monitoring and other services.
 * Version: 2.1.11
 * Author: Namecheap, Inc.
 * Author URI: http://namecheap.com
 * Text Domain: wpnceasywp
 * Domain Path: localization
 *
 */

if (!defined("ABSPATH")) {
  exit();
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require_once __DIR__ . "/bootstrap/autoload.php";
