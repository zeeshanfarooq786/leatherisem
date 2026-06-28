<?php

namespace WPNCEasyWP\Providers;

use WP_Error;
use WPNCEasyWP\Traits\ThrottleTrait;
use WPNCEasyWP\WPBones\Support\ServiceProvider;

class WordPressVersionServiceProvider extends ServiceProvider
{
  use ThrottleTrait;

  protected $request;

  public function register()
  {
    $this->throttleName    = 'x_version_';
    $this->throttleTimeout = 3600;
    $this->request         = $_REQUEST['ewp-version'] ?? null;

    add_action('wp_loaded', [$this, 'wp_loaded']);
    add_action('wp_loaded', [$this, 'storeWordPressVersion']);
    add_action('_core_updated_successfully', [$this, 'storeWordPressVersion']);
  }

  /**
   * Check if the request is valid
   */
  public function wp_loaded()
  {
    if ($this->request) {
      if (!is_wp_error($this->grant())) {
        return $this->getWordPressVersion();
      }
      die(json_encode(['error' => 'error', 'throttle' => $this->throttle(), 'request' => $this->request]));
    }
  }

  /**
   * Store the current WordPress version into an option
   */
  public function storeWordPressVersion()
  {
    $option_key = 'easywp_wp_version';

    try {
      // check if wordpress maintenance mode is enabled
      if (defined('WP_INSTALLING') && WP_INSTALLING || wp_is_maintenance_mode()) {
        return;
      }

      // get the current version from the option
      $current_version = get_option($option_key);

      // if no error, store the current version
      update_option($option_key, $GLOBALS['wp_version']);
    } catch (\Exception $e) {
      // catch any potential error and waiting for the next cycle
    }
  }

  /**
   * Grant the request
   */
  protected function grant()
  {
    $args = [
      'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
      ],
      'method' => 'GET',
    ];

    $url = rtrim(get_site_url(), '/');

    $redirect = "{$this->request}&site_url={$url}";

    add_filter('https_ssl_verify', '__return_false');

    $response = wp_remote_request($redirect, $args);

    add_filter('https_ssl_verify', '__return_true');

    $code = wp_remote_retrieve_response_code($response);

    if (200 !== $code) {
      $error = new WP_Error('ewp_bad_status');
      $error->add_data($code);

      return $error;
    }

    return (array) json_decode(wp_remote_retrieve_body($response), true);
  }

  /**
   * Get the current WordPress version
   */
  protected function getWordPressVersion()
  {
    die(json_encode(['version' => $GLOBALS['wp_version']]));
  }
}
