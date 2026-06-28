<?php

namespace WPNCEasyWP\Providers\HackGuardian;

use WPNCEasyWP\WPBones\Support\ServiceProvider;

class HackGuardianServiceProvider extends ServiceProvider
{
  protected $hackGuardianEnabled = false;

  protected $description = [
    "enabled" => "<p><strong>Disable HackGuardian</strong> to install themes, plugins, and update WordPress.</p>",
    "disabled" =>
    "<p>Now you can install plugins, themes, and update WordPress. Don’t forget to re-enable to keep your website secure.</p>",
  ];

  public function register()
  {
    if (!wpbones_flags()->flags('hackguardian.enabled')) {
      return;
    }

    $this->hackGuardianEnabled = useHackGuardian();

    // fix admin bar in frontend when the user is logged in
    add_action("wp_enqueue_scripts", [$this, "wp_enqueue_scripts"]);

    // easywp admin menu bar
    add_action("admin_bar_menu", [$this, "adminBarMenu"], 100);

    // add the admin styles
    add_action("admin_enqueue_scripts", [$this, "enqueueAdminStyles"]);
    add_action("admin_enqueue_scripts", [$this, "enqueueAdminScripts"]);

    // adds the admin toast
    add_action("admin_footer", [$this, "display_admin_toast"]);

    if ($this->hackGuardianEnabled) {
      add_action("admin_init", [$this, "disable_plugin_install"], 10);

      /**
       * We're going to comment this out because we don't want to disable the automatic updates
       * as we will roll out a new feature for automatic updates.
       *
      add_filter("auto_update_core", "__return_false", 99);
      add_filter("auto_update_plugin", "__return_false", 99);
      add_filter("auto_update_theme", "__return_false", 99);
      add_filter("auto_update_translation", "__return_false", 99);
       */
    }
  }

  public function wp_enqueue_scripts()
  {
    global $show_admin_bar;

    if ($show_admin_bar) {
      $this->enqueueAdminStyles();
      $this->enqueueAdminScripts();
    }
  }

  public function disable_plugin_install()
  {
    //
  }

  public function display_admin_toast()
  {
    $current_screen = get_current_screen();

    if (
      !$this->hackGuardianEnabled ||
      !in_array($current_screen->id, [
        "plugins",
        "plugin-install",
        "plugin-editor",
        "themes",
        "theme-install",
        "theme-editor",
        "update-core",
        "import",
      ])
    ) {
      return;
    }
?>
    <div id="hack-guardian-toast" class="hack-guardian-<?php echo $this->hackGuardianEnabled
                                                          ? "enabled"
                                                          : "disabled"; ?>">
      <div class="title">
        <span>
          <img draggable="false" height="24px" alt="Drag me" src="<?php echo WP_CONTENT_URL .
                                                                    "/mu-plugins/wp-nc-easywp/public/images/grip-vertical.svg"; ?>" />
          <h3>HackGuardian Status</h3>
        </span>
        <button id="hack-guardian-accordion-button" aria-disabled="false" aria-describedby="dashboard_hack_guardian_down">
          <span class="screen-reader-text">Move down</span>
          <span class="drop" aria-hidden="true"></span>
        </button>
      </div>

      <div class="content">

        <?php echo $this->description[$this->hackGuardianEnabled ? "enabled" : "disabled"]; ?>

        <div class="status">
          <div class="info">
            <p>
              <?php echo $this->hackGuardianEnabled ? "Enabled" : "Disabled"; ?>
            </p>
          </div>

          <div class="action">
            <input class="easywp size-md"
              type="checkbox"
              id="hack-guardian-toast-toggle" <?php echo $this->hackGuardianEnabled ? 'checked="checked"' : ""; ?> />
            <label for="hack-guardian-toast-toggle"></label>
          </div>
        </div>
      </div>
    </div>
<?php
  }

  public function enqueueAdminScripts()
  {
    wp_enqueue_script(
      "easywp-admin",
      WP_CONTENT_URL . "/mu-plugins/wp-nc-easywp/public/js/easywp-admin.js",
      ["jquery"],
      $this->plugin->Version,
      true
    );

    wp_localize_script("easywp-admin", "easyWP", [
      "ajaxurl" => admin_url("admin-ajax.php"),
      "hackGuardian" => $this->hackGuardianEnabled ? "enabled" : "disabled",
      "nonce" => wp_create_nonce("easywp_nonce"),
      "description" => $this->description,
    ]);
  }

  /**
   * Enqueue the admin styles.
   */
  public function enqueueAdminStyles()
  {
    wp_enqueue_style(
      "easywp-admin",
      WP_CONTENT_URL . "/mu-plugins/wp-nc-easywp/public/css/easywp-admin.css",
      [],
      $this->plugin->Version,
      "all"
    );
  }

  public function adminBarMenu($adminBar)
  {
    if (current_user_can("activate_plugins")) {
      $adminMenu = [
        [
          "id" => "hack-guardian",
          "title" =>
          '<span class="hackguardian-title">HackGuardian</span> <span class="ab-icon hackguardian-tooltip-trigger"></span>
          <input class="easywp" type="checkbox" id="hack-guardian-checkbox" name="hack-guardian-checkbox" value="1" ' .
            ($this->hackGuardianEnabled ? 'checked="checked"' : "") .
            '/>
          <label for="hack-guardian-checkbox"/>
          ',
          "href" => "#",
          "meta" => [
            "title" => __("HackGuardian", "wp-nc-easywp"),
          ],
        ],
      ];

      foreach ($adminMenu as $menu) {
        $adminBar->add_node($menu);
      }
    }
  }
}
