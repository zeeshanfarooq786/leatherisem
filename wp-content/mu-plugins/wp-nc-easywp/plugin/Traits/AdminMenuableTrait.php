<?php

namespace WPNCEasyWP\Traits;

if (!defined('ABSPATH')) {
  exit;
}

use WPNCEasyWP\Support\AdminMenu;

trait AdminMenuableTrait
{

  protected $title;

  protected function addMenuItem($title)
  {
    $this->title = $title;

    add_filter('wpnceasywp_admin_menu', [$this, 'adminBarMenuItem'], 10, 2);

    // single clear all
    add_action(AdminMenu::ACTION_KEY . "_{$this->action}", [$this, 'doActionMenu']);
  }

  protected function enableForClearAll()
  {
    // massive clear
    add_action(AdminMenu::ACTION_KEY . "_" . AdminMenu::ACTION_CLEAR_ALL, [$this, 'doActionMenuForClearAll']);
  }

  /**
   * Add a menu item in admin menu bar.
   *
   * @param array $adminMenu
   * @return array
   */
  public function adminBarMenuItem(array $adminMenu): array
  {
    $adminMenu[] = [
      'parent' => AdminMenu::PARENT_MENU_ID,
      'id' => $this->action,
      'title' => $this->title,
      'href' => wp_nonce_url(add_query_arg(AdminMenu::ACTION_KEY, $this->action), AdminMenu::NONCE),
      'meta' => [
        'title' => $this->title,
      ],
    ];

    return $adminMenu;
  }

  public function doActionMenuForClearAll()
  {
    $this->doActionMenu(true);

    if (!isset($GLOBALS['oneshot'])) {
      add_action('admin_notices', function () {
        echo '<div id="message" class="notice notice-success fade is-dismissible">';
        echo '<p>';
        _e('Cache cleared', 'wp-nc-easywp');
        echo '</p>';
        echo '</div>';
      });
      $GLOBALS['oneshot'] = true;
    }
  }

  // we are using a different action in order to display a different admin notice

  public function doActionMenu()
  {
    // you have to override this method
  }
}
