<?php

namespace WPNCEasyWP\Providers;

use WPNCEasyWP\Support\AdminMenu;
use WPNCEasyWP\WPBones\Support\ServiceProvider;
use WPNCEasyWP\Providers\AutomaticUpdates\SequoiaIntegrationCache;

class EasyWPServiceProvider extends ServiceProvider
{
  // user_meta key tracking per-user dismissal of the birthday admin notice.
  private $birthdayNoticeId = 'easywp_9th_birthday_2026';

  // Active window for the birthday banner (ClickUp task 86c9kfg2a:
  // May 26 - June 15, 2026 ET).
  private $birthdayStart = '2026-05-26 00:00:00';
  private $birthdayEnd   = '2026-06-15 23:59:59';

  // External link for the "Watch now" CTA — appreciation video provided by
  // Marketing on the ClickUp task (comment 90150226150484).
  private $birthdayWatchUrl = 'https://bit.ly/4uSjJU0';

  public function register()
  {
    $user_id = get_current_user_id();

    // easywp admin menu bar
    add_action("admin_bar_menu", [$this, "adminBarMenu"], 100);

    // check if we have to do some action
    add_action("admin_init", [$this, "processBarActions"]);
    add_action("wp_loaded", [$this, "processBarActions"]);

    // 9th-anniversary banner (driven by the date range above, dismissable per-user)
    if ($this->isBirthdayNoticeActive() && get_user_meta($user_id, $this->birthdayNoticeId, true) !== 'off') {
      add_action('admin_notices', [$this, 'admin_notices_birthday']);
      add_action('wp_ajax_close_birthday_admin_notice', [$this, 'close_birthday_admin_notice']);
    }
  }

  private function isBirthdayNoticeActive()
  {
    $now   = current_time('timestamp');
    $start = strtotime($this->birthdayStart);
    $end   = strtotime($this->birthdayEnd);

    return $now >= $start && $now <= $end;
  }

  public function close_birthday_admin_notice()
  {
    $user_id = get_current_user_id();
    update_user_meta($user_id, $this->birthdayNoticeId, 'off');
    wp_die();
  }

  public function admin_notices_birthday()
  {
    $badge_url = rtrim(WPMU_PLUGIN_URL, "/") . "/wp-nc-easywp/public/images/birthday-badge.png";
    $watch_url = $this->birthdayWatchUrl;
?>
    <div class="notice is-dismissible easywp-birthday-notice">
      <div class="easywp-birthday-notice__inner">
        <img
          class="easywp-birthday-notice__badge"
          src="<?php echo esc_url($badge_url); ?>"
          alt=""
          width="76"
          height="96"
        />
        <div class="easywp-birthday-notice__copy">
          <p class="easywp-birthday-notice__title">EasyWP turns 9!</p>
          <p class="easywp-birthday-notice__body">
            Our customer support team shared their stories on how you inspired<br />
            them in this special appreciation video.
          </p>
        </div>
        <a
          class="easywp-birthday-notice__cta"
          href="<?php echo esc_url($watch_url); ?>"
          target="_blank"
          rel="noopener noreferrer"
        >
          Watch now
        </a>
      </div>
    </div>
    <style>
      .easywp-birthday-notice.notice {
        background: #e5fbfc;
        border: 0;
        border-left: 0;
        border-radius: 16px;
        box-shadow: none;
        padding: 0;
        margin: 16px 20px 16px 2px;
        position: relative;
      }
      .easywp-birthday-notice .easywp-birthday-notice__inner {
        display: flex;
        align-items: center;
        gap: 28px;
        padding: 10px 40px;
      }
      .easywp-birthday-notice__badge {
        width: 76px;
        height: 96px;
        flex: 0 0 76px;
        display: block;
      }
      .easywp-birthday-notice__copy {
        flex: 1 1 auto;
        min-width: 0;
      }
      .easywp-birthday-notice .easywp-birthday-notice__title {
        margin: 0 !important;
        font-family: "Intelo", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif !important;
        font-size: 24px !important;
        font-weight: 700 !important;
        line-height: 1.15 !important;
        color: #2b3a47 !important;
      }
      .easywp-birthday-notice .easywp-birthday-notice__body {
        margin: 0 !important;
        font-family: "Intelo", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif !important;
        font-size: 18px !important;
        font-weight: 400 !important;
        line-height: 1.3 !important;
        color: #52606d !important;
      }
      .easywp-birthday-notice__cta,
      .easywp-birthday-notice a.easywp-birthday-notice__cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        min-width: 170px;
        height: 52px;
        padding: 10px 16px;
        border-radius: 8px;
        background: #108f64;
        color: #ffffff !important;
        font-family: "Intelo", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        font-size: 24px;
        font-weight: 700;
        line-height: 1;
        text-decoration: none !important;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0, 0, 0, 0.08);
        transition: background 0.2s ease;
      }
      .easywp-birthday-notice__cta:hover,
      .easywp-birthday-notice__cta:focus,
      .easywp-birthday-notice a.easywp-birthday-notice__cta:hover,
      .easywp-birthday-notice a.easywp-birthday-notice__cta:focus {
        background: #0d7a55;
        color: #ffffff !important;
        text-decoration: none !important;
      }
      .easywp-birthday-notice .notice-dismiss {
        top: 9px;
        right: 9px;
      }
      @media (max-width: 782px) {
        .easywp-birthday-notice .easywp-birthday-notice__inner {
          flex-direction: column;
          align-items: flex-start;
          gap: 16px;
          padding: 20px;
        }
      }
    </style>
    <script type="text/javascript">
      jQuery(document).on('click', '.notice.is-dismissible.easywp-birthday-notice .notice-dismiss', function() {
        jQuery.post(ajaxurl, {
          action: "close_birthday_admin_notice",
          nonce: typeof easyWP !== 'undefined' ? easyWP.nonce : '',
        });
      });
    </script>
<?php
  }

  public function processBarActions()
  {
    if (isset($_GET[AdminMenu::ACTION_KEY]) && check_admin_referer(AdminMenu::NONCE)) {
      $action = $_GET[AdminMenu::ACTION_KEY] ?: false;

      if ($action) {
        /**
         * Fires the request action.
         *
         * The dynamic portion of the hook name, $action, refers to the action will be execute.
         */
        do_action(AdminMenu::ACTION_KEY . "_{$action}");
        SequoiaIntegrationCache::init()->flush();
      }
    }
  }

  /**
   * Add the EasyWP Menu in the admin admin bar for administrator user.
   *
   * @param $adminBar
   */
  public function adminBarMenu($adminBar)
  {
    // administrator only
    if (current_user_can("activate_plugins")) {
      $adminMenu = [
        [
          "id" => AdminMenu::PARENT_MENU_ID,
          "title" =>
          '<span class="desktop-clearcache">' .
            __("Clear Cache", "wp-nc-easywp") .
            '</span> <span class="mobile-clearcache">🧽</span>',
          "href" => wp_nonce_url(add_query_arg(AdminMenu::ACTION_KEY, AdminMenu::ACTION_CLEAR_ALL), AdminMenu::NONCE),
          "meta" => [
            "title" => __("Clear Cache", "wp-nc-easywp"),
          ],
        ],
      ];

            //            $adminMenu[] = [
            //                'parent' => AdminMenu::PARENT_MENU_ID,
            //                'id'     => 'wpnceasywp-clear-all-cache',
            //                'title'  => __('Clear Cache', 'wp-nc-easywp'),
            //                'href'   => wp_nonce_url(add_query_arg(AdminMenu::ACTION_KEY, AdminMenu::ACTION_CLEAR_ALL), AdminMenu::NONCE),
            //                'meta'   => [
            //                    'title' => __('Clear Cache', 'wp-nc-easywp'),
            //                ],
            //            ];

      /**
       * Filter the additional admin menu item.
       *
       * The dynamic portion of the hook name, $adminMenu, refers to the list of menu item.
       *
       * @param array $adminMenu
       */
      $adminMenu = apply_filters("wpnceasywp_admin_menu", $adminMenu);

      foreach ($adminMenu as $menu) {
        $adminBar->add_node($menu);
      }
    }
  }
}
