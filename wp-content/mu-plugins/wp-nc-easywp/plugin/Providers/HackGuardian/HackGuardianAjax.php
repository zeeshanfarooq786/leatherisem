<?php

namespace WPNCEasyWP\Providers\HackGuardian;

use WPNCEasyWP\WPBones\Foundation\WordPressAjaxServiceProvider as ServiceProvider;

class HackGuardianAjax extends ServiceProvider
{
  /**
   * List of the ajax actions executed by both logged and not logged users.
   * Here you will used a methods list.
   *
   * @var array
   */
  protected $trusted = [];

  /**
   * List of the ajax actions executed only by logged in users.
   * Here you will used a methods list.
   *
   * @var array
   */
  protected $logged = ["hack_guardian"];

  protected $capability = "administrator";

  /**
   * List of the ajax actions executed only by not logged in user, usually from frontend.
   * Here you will used a methods list.
   *
   * @var array
   */
  protected $notLogged = [];

  protected $nonceKey = "nonce";

  protected $nonceHash = "easywp_nonce";

  public function hack_guardian()
  {
    [$enabled] = $this->useHTTPPost("enabled");

    $response = setHackGuardian($enabled === "true" ? true : false);

    return wp_send_json($response);
  }
}
