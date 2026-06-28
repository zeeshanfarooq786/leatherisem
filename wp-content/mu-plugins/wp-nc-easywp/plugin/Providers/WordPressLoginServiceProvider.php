<?php

namespace WPNCEasyWP\Providers;

use WP_Error;
use WPNCEasyWP\Traits\ThrottleTrait;
use WPNCEasyWP\WPBones\Support\ServiceProvider;

class WordPressLoginServiceProvider extends ServiceProvider
{
  use ThrottleTrait;

  protected $admin_path = "";

  protected $decode = [];
  protected $query = '';
  protected $url = '';

  public function register()
  {
    $this->throttleName = "x_autologin_";
    $this->throttleTimeout = 30;

    if (isset($_REQUEST["ewp-action"])) {
      $action = $_REQUEST["ewp-action"];
      $this->decode = parse_url($action);

      $this->admin_path = 'index.php';

      if (isset($this->decode["query"])) {
        parse_str($this->decode["query"], $this->query);
        if (!empty($this->query["admin_path"])) {
          $this->admin_path = $this->query["admin_path"];
          // remove the array with key "admin_path"
          unset($this->query["admin_path"]);
        }
      }

      if ($this->isAction()) {
        if (!is_wp_error($this->grant())) {
          do_action("clear_varnish");
          do_action("clear_redis");
          do_action("clear_opcache");

          $this->login();
        }
      }
    }
  }

  protected function isAction()
  {
    // domains allowed
    $domains = [
      "dashboard.easywp.com",
      "dashboard.easywp.website",
      "dashboard.easywp.cloud",
      "easywp.cs",
      "dashboard.namecheapcloud.host",
      "dashboard.namecheapcloud.net",
      "dashboard.namecheapcloud.wtf",
      "cs-panel.namecheapcloud.host",
      "cs-panel.namecheapcloud.net",
      "sequoia.namecheapcloud.host",
      "sequoia.namecheapcloud.net",
      "sequoia.namecheapcloud.wtf",
      "spaceship.com",
      "www.spaceship.com",
    ];

    $host = $this->decode["host"];

    if (!in_array($host, $domains)) {
      return false;
    }

    $websiteId = easywpJWT()->websiteId;

    if (empty($websiteId)) {
      return false;
    }

    // Spaceship
    if (in_array($host, ["spaceship.com", "www.spaceship.com"])) {
      if (isset($this->query['websiteId']) && isset($this->query['token'])) {

        $websiteIdURL = $this->query['websiteId'];
        $websiteIdToken = easywpJWT($this->query['token'])->websiteId;

        if ($websiteId != $websiteIdURL || $websiteId != $websiteIdToken) {
          return false;
        }
      } else {
        return false;
      }
    }
    // Dashboard or CS Panel
    else {
      preg_match('/websites\/(\d+)\//', $this->decode["path"], $id_matches);
      $id = $id_matches[1];

      $token = basename($this->decode["path"]);
      $websiteIdToken = easywpJWT($token)->websiteId;

      if (empty($id) || $websiteId != $id || $websiteId != $websiteIdToken) {
        return false;
      }
    }

    $this->url = $this->decode["scheme"] . "://" .
      $host .
      $this->decode["path"] .
      (empty($this->query) ? '' : '?' . $this->decode["query"]);

    return true;
  }

  protected function grant()
  {
    $args = [
      "headers" => [
        "Accept" => "application/json",
        "Content-Type" => "application/json",
      ],
      "method" => "GET",
    ];

    add_filter("https_ssl_verify", "__return_false");

    $response = wp_remote_request($this->url, $args);

    add_filter("https_ssl_verify", "__return_true");

    $code = wp_remote_retrieve_response_code($response);

    if (200 !== $code) {
      $error = new WP_Error("ewp_bad_status");
      $error->add_data($code);

      return $error;
    }

    return (array) json_decode(wp_remote_retrieve_body($response), true);
  }

  protected function login()
  {
    $redirect = esc_url_raw(self_admin_url());

    if (current_user_can("administrator")) {
      wp_safe_redirect($redirect . $this->admin_path);

      exit();
    }

    $user = $this->getUserId();

    if ($user) {
      wp_set_auth_cookie($user->ID);

      wp_safe_redirect($redirect . $this->admin_path);

      exit();
    }
  }

  /**
   * Get the first administrator user.
   *
   * @return object|bool
   */
  protected function getUserId()
  {
    // Get the oldest administrator as a fallback.
    $user = get_users([
      "number" => 1,
      "role" => "administrator",
      "orderby" => "registered",
      "order" => "ASC",
    ]);

    return !empty($user[0]->ID) ? $user[0] : false;
  }
}
