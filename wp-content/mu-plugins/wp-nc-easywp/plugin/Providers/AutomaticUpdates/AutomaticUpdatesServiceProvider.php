<?php

namespace WPNCEasyWP\Providers\AutomaticUpdates;

use WPNCEasyWP\WPBones\Support\ServiceProvider;
use WPNCEasyWP\Providers\AutomaticUpdates\EasyWPInternalModel;
use WPNCEasyWP\Providers\AutomaticUpdates\AutomaticUpdatesPending;
use WPNCEasyWP\Providers\AutomaticUpdates\AutomaticUpdatesCompleted;
use WPNCEasyWP\Support\DashboardNotifications;

class AutomaticUpdatesServiceProvider extends ServiceProvider
{
  /**
   * Used to send the notification if any update failed
   */
  private $failed = false;

  /**
   * Used to send the notification if any update was successful
   */
  private $success = false;

  /**
   * Used to count the failed updates
   */
  private $count = 0;

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    // init pending also for AJAX requests
    // because we need to force the update on the delete action
    AutomaticUpdatesPending::init();

    // avoid running the code if we're in an AJAX request
    if (defined('DOING_AJAX') && true === DOING_AJAX) {
      return;
    }

    // run the migration and create the table if it doesn't exist
    EasyWPInternalModel::createTableIfDoesntExist();

    // update the wp prefix
    EasyWPInternalModel::updateWPPrefix();

    // setup the automatic updates in accordance with the strategy
    $this->setUpAutomaticUpdatesStrategy();

    // after update core, plugins or themes
    add_action('automatic_updates_complete', [$this, 'automatic_updates_complete']);

    // avoid running the code if we're not in a CRON request
    if ((defined('DOING_CRON') && true === DOING_CRON)) {
      // used to check if HackGuardian is enabled before automatic updates
      add_action('pre_auto_update', [$this, 'pre_auto_update']);
      return;
    }

    // Check if the user has closed the notice
    if (!get_user_meta(get_current_user_id(), 'easywp_automatic_update_notice_closed', true)) {
      // display an admin notice for automatic updates
      // add_action('admin_notices', [$this, 'admin_notices']);
    }

    // -- Reminders --

    // generic update for core, plugins or themes
    //add_action('upgrader_process_complete', [$this, 'upgrader_process_complete'], 10, 2);

    //add_action('upgrader_pre_install', [$this, 'before_plugin_update'], 10, 2);
    //add_filter('allow_major_auto_core_updates', '__return_true');
    //add_filter('upgrader_post_install', [$this, 'upgrader_post_install'], 10, 3);


    // we're using notice level for logging
    //add_action('automatic_updates_debug', [$this, 'automatic_updates_debug']);
    //add_action('automatic_updates_failed', [$this, 'automatic_updates_failed']);
    //add_action('automatic_updates_success', [$this, 'automatic_updates_success']);
    //add_action('wp_maybe_auto_update', [$this, 'wp_maybe_auto_update']);

    // alias 'upgrader_process_complete'
    //add_action('wp_update_plugins', [$this, 'wp_update_plugins']);

    // alias 'upgrader_process_complete'
    //add_action('wp_update_themes', [$this, 'wp_update_themes']);

    // we're using info level for logging
    // add_filter('automatic_updater_disabled', [$this, 'automatic_updater_disabled'], 10, 1);
    // add_filter('allow_dev_auto_core_updates', [$this, 'allow_dev_auto_core_updates'], 10, 1);
    // add_filter('allow_minor_auto_core_updates', [$this, 'allow_minor_auto_core_updates'], 10, 2);
    // add_filter('allow_major_auto_core_updates', [$this, 'allow_major_auto_core_updates'], 10, 2);
    // add_filter('auto_update_core', [$this, 'auto_update_core'], 10, 2);
    // add_filter('auto_update_plugin', [$this, 'auto_update_plugin'], 10, 2);
    // add_filter('auto_update_theme', [$this, 'auto_update_theme'], 10, 2);
    // add_filter('auto_update_translation', [$this, 'auto_update_translation'], 10, 2);
    // add_filter('automatic_updates_is_vcs_checkout', [$this, 'automatic_updates_is_vcs_checkout'], 10, 2);
  }

  /**
   * Set up automatic updates based on the strategy defined in the options.
   *
   * This method retrieves the update strategy from the 'easywp_auto_update_strategy' option,
   * decodes it, and sets up automatic updates for core, plugins, themes, and translations
   * based on the strategy.
   *
   * @return void
   */
  public function setUpAutomaticUpdatesStrategy()
  {
    // the `easywp_auto_update_strategy` option must have the autoload off
    wp_cache_delete('easywp_auto_update_strategy', 'options');

    // could return something like
    // {"core":"major","plugins":"true","themes":"false","translations":"true"}
    $option = get_option('easywp_auto_update_strategy', false);

    if (empty($option)) {
      return;
    }

    $strategy = json_decode($option, true);

    $core = $strategy['core'];
    $plugins = $strategy['plugins'];
    $themes = $strategy['themes'];
    $translations = $strategy['translations'];

    if (wpbones_is_true($core)) {
      if (in_array($core, ['minor', 'major'])) {
        add_filter("allow_{$core}_auto_core_updates", '__return_true');
      } else {
        add_filter('auto_update_core', '__return_true');
      }
    }

    if (wpbones_is_true($plugins)) {
      add_filter('auto_update_plugin', '__return_true');
    }

    if (wpbones_is_true($themes)) {
      add_filter('auto_update_theme', '__return_true');
    }

    if (wpbones_is_true($translations)) {
      add_filter('auto_update_translation', '__return_true');
    }
  }

  /**
   * Check if HackGuardian is enabled before automatic updates
   *
   * @param bool|WP_Error $response   Installation response.
   * @param array         $hook_extra Extra arguments passed to hooked filters.
   */
  public function pre_auto_update()
  {
    // get the current status of HackGuardian
    $enabled = useHackGuardian();

    if ($enabled) {
      $status = setHackGuardian(false);

      sleep(1);

      // check for 5 times if the HackGuardian is disabled
      for ($i = 1; $i <= 5; $i++) {
        $enabled = useHackGuardian();
        if (!$enabled) {
          break;
        }
        sleep(1);
      }

      // hotfix: https://app.clickup.com/t/86c4mj1tz
      add_action('automatic_updates_complete', [$this, 'easywp_reenable_hack_guardian']);
      if ($enabled) {
        error_log("easywp-plugin: Proceeding with the update despite HackGuardian is still detected as enabled");
      }
    }
  }

  /**
   * Enable HackGuardian
   */
  public function easywp_reenable_hack_guardian()
  {
    setHackGuardian(true);
  }

  /**
   * Automatic updates.
   * We will update the history foe plugins or themes
   */
  public function automatic_updates_complete($update_results)
  {
    global $wp_version;

    $this->resetStatusNotification();

    // Core
    if (is_array($update_results) && isset($update_results['core'])) {
      $item = $update_results['core'][0]->item;

      $auto_core_update_failed = get_option('auto_core_update_failed', false);

      AutomaticUpdatesCompleted::init()->updateCore([
        'name' => 'WordPress Core',
        'date' => gmdate('Y-m-d H:i:s'),
        'from_version' => $wp_version,
        'current_version' => $item->version,
        'result' => !$auto_core_update_failed,
      ]);

      $this->success = !$auto_core_update_failed;
      $this->failed = $auto_core_update_failed;
      $this->count = $auto_core_update_failed ? 1 : 0;
    }

    // Themes
    if (is_array($update_results) && isset($update_results['theme'])) {

      $active_theme = wp_get_theme();
      $active_theme_slug = $active_theme->get_template();

      $values = [];

      foreach ($update_results['theme'] as $theme) {
        $item = $theme->item;
        $result = $theme->result === true;

        $name = $theme->name;
        $slug = $item->theme;
        $current_version = $item->current_version;
        $new_version = $item->new_version;

        $values[$slug] = [
          'name' => $name,
          'date' => gmdate('Y-m-d H:i:s'),
          'from_version' => $current_version,
          'current_version' => $new_version,
          'active' => $slug === $active_theme_slug,
          'result' => $result,
        ];

        // check any failed update
        // check any successful update
        $this->setStatusNotification($result);
      }

      AutomaticUpdatesCompleted::init()
        ->updateThemes($values)
        ->syncThemes();
    }

    // Plugins
    if (is_array($update_results) && isset($update_results['plugin'])) {
      $values = [];

      foreach ($update_results['plugin'] as $plugin) {
        $item = $plugin->item;
        $result = $plugin->result === true;

        $name = $plugin->name;

        $icon = '';

        if (isset($item->icons) && is_array($item->icons)) {
          if (isset($item->icons['2x'])) {
            $icon = $item->icons['2x'];
          } elseif (isset($item->icons['1x'])) {
            $icon = $item->icons['1x'];
          }
        }

        $slug = $item->plugin;
        $current_version = $item->current_version;
        $new_version = $item->new_version;

        $values[$slug] = [
          'name' => $name,
          'icon' => $icon,
          'date' => gmdate('Y-m-d H:i:s'),
          'from_version' => $current_version,
          'current_version' => $new_version,
          'active' => is_plugin_active($slug),
          'result' => $result,
        ];

        // check any failed update
        // check any successful update
        $this->setStatusNotification($result);
      }

      AutomaticUpdatesCompleted::init()
        ->updatePlugins($values)
        ->syncPlugins();
    }

    // Translations
    if (is_array($update_results) && isset($update_results['translation'])) {
      $values = [];

      foreach ($update_results['translation'] as $translation) {
        $item = $translation->item;
        $name = $translation->name;

        $type = $item->type;

        $slug = $item->slug;
        $language = $item->language;
        $version = $item->version;
        $updated = $item->updated;

        $values[$type][] = [
          'name' => $name,
          'language' => $language,
          'updated' => $updated,
          'date' => date('Y-m-d H:i:s'),
          'version' => $version,
        ];

        $this->success = true;
      }

      AutomaticUpdatesCompleted::init()->updateTranslations($values);
    }

    // Send Notification if any successful update
    $this->sendNotifications();
  }

  /**
   * Reset the status of the notification
   */
  private function resetStatusNotification()
  {
    $this->failed = false;
    $this->success = false;
    $this->count = 0;
  }

  /**
   * Set the status of the notification
   *
   * @param bool $result
   */
  private function setStatusNotification($result)
  {
    if ($result === true) {
      $this->success = true;
    } else {
      $this->failed = true;
      $this->count++;
    }
  }

  /**
   * Send Notifications if any successful or failed update
   */
  private function sendNotifications()
  {
    // Send Notification if any successful update
    if ($this->success) {
      DashboardNotifications::instance()->automaticUpdateSuccessfully();
    }

    // Send Notification if any failed update
    if ($this->failed) {
      DashboardNotifications::instance()->automaticUpdateFailed($this->count);
    }

    $this->resetStatusNotification();
  }

  /**
   * Display an admin notice for automatic updates.
   */
  public function admin_notices()
  {

    $current_screen = get_current_screen();

    if (!in_array($current_screen->id, ['dashboard', 'update-core'])) {
      return;
    }

    $title = "New WordPress Automatic Updates from EasyWP";
    $content = "Outdated WordPress core files, themes, and plugins increase the risk of cyber threats to your website. <a href='https://www.youtube.com/watch?v=Ut_mwQdb0BA' target='_blank' style='color:white;outline:none'>WordPress Automatic Updates</a> regularly updates WordPress without manual intervention.";

?>
    <div class="notice notice-info is-dismissible" id="easywp_automatic_update_notice_closed">
      <img src="<?php echo rtrim(WPMU_PLUGIN_URL, "/") ?>/wp-nc-easywp/public/images/automatic-updates-notice.png" alt=" Automatic Updates" />
      <div>
        <h2>
          <?php echo $title; ?>
        </h2>
        <p>
          <?php echo $content; ?>
        </p>
      </div>
    </div>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $('#easywp_automatic_update_notice_closed').on('click', '.notice-dismiss', function() {
          $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
              action: 'easywp_automatic_update_notice_closed',
              nonce: '<?php echo wp_create_nonce("easywp_nonce") ?>'
            }
          });
        });
      });
    </script>
<?php
  }
}
