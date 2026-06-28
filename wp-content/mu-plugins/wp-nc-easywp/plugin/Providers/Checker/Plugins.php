<?php

namespace WPNCEasyWP\Providers\Checker;

use WP_Error;

/**
 * This class is used to check if the plugins installed are compatible with the current WordPress version.
 * We're using a banned list of plugins that are known to be incompatible with the current WordPress version.
 * If the plugin is found in the banned list, we'll return an error.
 */
class Plugins
{
  /**
   * Singleton.
   *
   * @var $this
   */
  private static $instance;
  /**
   * List of plugins will be disabled.
   *
   * @var array
   */
  public $willDisabled = [];
  /**
   * Re-check every 24 hours.
   *
   * @var int
   */
  protected $checkEvery = 24;
  /**
   * List of plugins disabled.
   *
   * @var array
   */
  protected $disabled = [];
  /**
   * List of all plugins.
   *
   * @var array
   */
  protected $plugins;
  /**
   * Plugin's key list.
   *
   * @var array
   */
  protected $pluginsKeys;
  /**
   * List of not allowed plugins.
   *
   * @var array
   */
  protected $notAllowed;
  /**
   * List of not allowed plugins slug.
   * Used for `plugin_install_action_links` filter.
   *
   * @var array
   */
  protected $notAllowedSlug;
  /**
   * Not allowed plugin's keys.
   *
   * @var array
   */
  protected $notAllowedKeys;
  /**
   * Remove th activate link in the plugins page in admin area.
   *
   * @var bool
   */
  protected $removeActivateLink;

  public function __construct()
  {
    $this->removeActivateLink = WPNCEasyWP()->config('checker.removeActivateLink', true);

    $this->checkNotAllowed();

    $this->initFilters();
  }

  /**
   * Check the now allowed plugins, prepare the admin notice and deactive when not allowed.
   *
   */
  protected function checkNotAllowed()
  {
    foreach ($this->getNotAllowed() as $file => $info) {

      // a not allowed plugins is installed
      if (in_array($file, $this->getPluginsKeys())) {

        // remove the "activate" link for this plugin
        if ($this->removeActivateLink) {
          add_filter(
            "plugin_action_links_{$file}",
            function ($actions, $plugin_file, $plugin_data, $context) {
              unset($actions['activate']);

              return $actions;
            },
            10,
            4
          );

          add_action(
            "after_plugin_row_{$file}",
            function ($file, $plugin_data) {
              echo '<tr class="plugin-update-tr">' .
                '<td colspan="4" class="plugin-update colspanchange">' .
                '<div class="update-message notice inline notice-error notice-alt">' .
                '<p>';
              echo $this->getReasonByKey($file);
              echo "</p></div></td></tr>";
            },
            10,
            2
          );
        }

        // collect data
        $data = [
          'info' => $info,
          'data' => $this->getPlugins()[$file],
        ];

        // add this plugin to list of will disabled
        $this->setWillDisabled($file, $data);
      }
    }

    if (!empty($this->getWillDisabled())) {
      $adminNotice = function () {
        add_action('admin_notices', function () {
          echo '<div id="message" class="notice notice-success fade is-dismissible">';
          printf('<h4>%s</h4>', _n('The following plugin can\'t be activated:', 'The following plugins can\'t be activated:', count($this->getWillDisabled()), 'wp-nc-easywp'));
          echo '<ul>';
          foreach ($this->getWillDisabled() as $key => $value) {
            $reason      = $value['info']['reason'];
            $description = $value['info']['description'];
            $info        = ($reason != $description) ? ("{$reason} {$description}") : $reason;

            echo '<li>';
            printf(' - %s: %s', $value['data']['Name'], $info);
            echo '</li>';
          }
          echo '</ul></div>';
        });
      };

      // we're going to display some admin notices in the plugins page in admin area, because we've removed the
      // "activate" link action
      add_action('admin_head-plugins.php', $adminNotice);
      add_action('admin_head-plugin-install.php', $adminNotice);
    }

    if (!empty($this->getDisabled())) {

      // we're going to display an admin notices to warn the customer that some plugin was disabled.
      add_action('admin_notices', function () {
        echo '<div id="message" class="notice notice-success fade is-dismissible">';
        printf('<h4>%s</h4>', _n('The following plugin was disabled', 'The following plugins were disabled', count($this->getDisabled()), 'wp-nc-easywp'));
        echo '<ul>';
        foreach ($this->getDisabled() as $key => $value) {
          echo '<li>';
          printf('%s: %s', $value['data']['Name'], $value['info']['reason']);
          echo '</li>';
        }
        echo '</ul></div>';
      });
    }
  }

  /**
   * Return the list of plugins not allowed.
   *
   * @return array
   */
  public function getNotAllowed(): array
  {

    if (!$this->notAllowed) {
      $configFolder          = WPNCEasyWP()->basePath . '/config';
      $bannedPluginsFilename = WPNCEasyWP()->config('checker.plugins', '');
      $bannedPluginsContent  = file_get_contents("{$configFolder}/{$bannedPluginsFilename}");

      $this->notAllowed = json_decode($bannedPluginsContent, true);
    }

    if (is_null($this->notAllowed) || !is_array($this->notAllowed)) {
      $this->notAllowed = [];
    }

    return $this->notAllowed;
  }

  protected function getPluginsKeys()
  {
    if (!$this->pluginsKeys) {
      $this->pluginsKeys = array_keys($this->getPlugins());
    }

    return $this->pluginsKeys;
  }

  protected function getPlugins()
  {
    if (!$this->plugins) {
      $this->plugins = get_plugins();
    }

    return $this->plugins;
  }

  protected function getReasonByKey(string $key)
  {
    return $this->getNotAllowed()[$key]['reason'];
  }

  /**
   * Return the list of plugins which will be disable.
   *
   * @return array
   */
  public function getWillDisabled(): array
  {
    return $this->willDisabled;
  }

  protected function setWillDisabled($file, $value)
  {
    $this->willDisabled[$file] = $value;

    // if for any reason the plugin is activated, then we'll disable it
    if (is_plugin_active($file)) {
      $this->setDisabled($file, $value);
      deactivate_plugins($file, true);
    }

    return $this;
  }

  protected function getDisabled()
  {
    return $this->disabled;
  }

  protected function setDisabled($file, $value)
  {
    $this->disabled[$file] = $value;

    return $this;
  }

  protected function initFilters()
  {
    add_filter(
      'pre_update_option',
      function ($value, $option, $old_value) {
        if ($option === 'active_plugins' && isset($value[0]) && in_array($value[0], $this->getNotAllowedKeys())) {
          return $old_value;
        }

        return $value;
      },
      10,
      3
    );

    // will remove install from plugins list
    add_filter(
      'plugin_install_action_links',
      function ($action_links, $plugin) {
        if (in_array($plugin['slug'], $this->getNotAllowedSlug())) {
          //unset($action_links[ 0 ]);
          $action_links[0] = $this->warningBySlug($plugin['slug']);
        }

        return $action_links;
      },
      10,
      2
    );

    // will remove Install Now from plugin detail
    add_filter(
      'plugins_api_result',
      function ($res, $action, $args) {
        if (isset($res->slug) && in_array($res->slug, $this->getNotAllowedSlug())) {
          $res->slug = '';
        }

        return $res;
      },
      10,
      3
    );

    add_filter(
      'update_plugin_complete_actions',
      function ($update_actions, $plugin) {
        return $update_actions;
      },
      10,
      2
    );

    add_filter(
      'install_plugin_complete_actions',
      function ($install_actions, $api, $plugin_file) {
        if (in_array($plugin_file, $this->getNotAllowedKeys())) {
          $install_actions['activate_plugin'] = __('The plugin can\'t be installed', 'wp-nc-easywp') . '<details>' . $this->getReasonByKey($plugin_file) . '</details>';
        }

        return $install_actions;
      },
      10,
      3
    );

    add_filter(
      'upgrader_source_selection',
      function ($source) {
        $pluginSlug = basename($source);

        if (in_array($pluginSlug, $this->getNotAllowedSlug())) {
          $source = new WP_Error(999, __('The plugin can\'t be installed', 'wp-nc-easywp') . '<details>' . $this->getReasonBySlug($pluginSlug) . '</details>');
        }

        return $source;
      },
      10,
      4
    );
  }

  protected function warningBySlug($slug)
  {
    $details = $this->getReasonBySlug($slug);
    $div = '<div style="position: absolute;right: -16px;top: -16px;width:200px;box-shadow:4px 4px 4px 0 rgba(0,0,0,0.1);padding:4px;border-radius:6px;border:2px solid #ffae96;text-align:left;background-color:#fff5bb">';
    $warning = __('The plugin can\'t be installed', 'wp-nc-easywp');
    return "{$div}{$warning}<details>{$details}</details></div>";
  }

  protected function getNotAllowedKeys()
  {
    if (!$this->notAllowedKeys) {
      $this->notAllowedKeys = array_keys($this->getNotAllowed());
    }

    return $this->notAllowedKeys;
  }

  protected function getNotAllowedSlug()
  {
    if (!$this->notAllowedSlug) {
      $this->notAllowedSlug = array_map(
        function ($value) {
          $parts = explode('/', $value);

          return $parts[0];
        },
        $this->getNotAllowedKeys()
      );
    }

    return $this->notAllowedSlug;
  }

  protected function getReasonBySlug($slug)
  {
    foreach ($this->getNotAllowed() as $key => $value) {
      if (dirname($key) === $slug) {
        return $value['reason'];
      }
    }
  }

  public static function boot()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self;
    }

    return self::$instance;
  }
}
