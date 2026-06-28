<?php

namespace WPNCEasyWP\Providers\AutomaticUpdates;

use WPNCEasyWP\WPBones\Foundation\WordPressAjaxServiceProvider as ServiceProvider;

class AutomaticUpdatesAjax extends ServiceProvider
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
  protected $logged = ["easywp_automatic_update_notice_closed"];

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

  public function easywp_automatic_update_notice_closed()
  {
    update_user_meta(get_current_user_id(), 'easywp_automatic_update_notice_closed', true);
    wp_send_json_success();
  }
}
