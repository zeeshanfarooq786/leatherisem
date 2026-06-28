<?php

namespace WPNCEasyWP\Providers\AutomaticUpdates;

use WPNCEasyWP\Providers\AutomaticUpdates\AutomaticUpdatesOptionsNamesTrait;
use WPNCEasyWP\Providers\AutomaticUpdates\SequoiaIntegrationCache;
use WPNCEasyWP\Traits\LogTrait;

class AutomaticUpdatesPending
{
  use AutomaticUpdatesOptionsNamesTrait;
  use LogTrait;

  /**
   * Flag to force the update on boot
   *
   * @var bool
   */
  private $forceUpdate = false;

  public static function init()
  {
    return new self();
  }

  public function __construct()
  {

    $this->logging = false;

    // avoid running the code if we're in an AJAX request
    if (defined('DOING_AJAX') && true === DOING_AJAX) {
      return;
    }

    add_filter('pre_set_site_transient_update_plugins', [$this, 'updatePluginsPending']);
    add_filter('pre_set_site_transient_update_themes', [$this, 'updateThemesPending']);
    add_filter('pre_set_site_transient_update_core', [$this, 'updateCorePending']);

    // check if the option for the plugins updates is set
    $options = get_option(self::$OPTION_PLUGIN_UPDATE_PENDING, -1);
    // if the option is not set
    if ($this->forceUpdate || -1 === $options) {
      wp_update_plugins();
    }

    // check if the option for the themes updates is set
    $options = get_option(self::$OPTION_THEME_UPDATE_PENDING, -1);
    // if the option is not set
    if ($this->forceUpdate || -1 === $options) {
      wp_update_themes();
    }

    // check if the option for the core updates is set
    $options = get_option(self::$OPTION_CORE_UPDATE_PENDING, -1);
    // if the option is not set
    if ($this->forceUpdate || -1 === $options) {
      wp_version_check([], true);
    }

    // check if the option for the translations updates is set
    $options = get_option(self::$OPTION_TRANSLATIONS_UPDATE_PENDING, -1);
    // if the option is not set
    if ($this->forceUpdate || -1 === $options) {
      $this->updateTranslationsPending();
    }
  }

  /**
   * Get the core that have updates available
   *
   * @return array
   */
  public function updateCorePending($update_core)
  {
    global $wp_version;

    $lists = [];

    $this->log('✅ -----> Update Core Pending', $update_core->updates);

    if (!empty($update_core->updates)) {
      foreach ($update_core->updates as $core_data) {
        // REMEMBER: the $core_data->response can be 'upgrade' or 'autoupdate'
        if ($core_data->response !== 'upgrade') {
          continue;
        }

        $lists[] = [
          'current' => $core_data->current,
          'version' => $core_data->version,
          'new_bundled' => $core_data->new_bundled,
          'locale' => $core_data->locale,
          'installed' => $wp_version,
        ];
      }
    }

    wp_cache_delete(self::$OPTION_CORE_UPDATE_PENDING, 'options');
    update_option(self::$OPTION_CORE_UPDATE_PENDING, json_encode($lists), false);

    $this->updateTranslationsPending();

    SequoiaIntegrationCache::init()->flush();

    // remember to return the $update_core as it's a filter
    return $update_core;
  }

  /**
   * Get the plugins that have updates available
   *
   * @return array
   */
  public function updatePluginsPending($update_plugins)
  {
    $lists = [];

    $this->log('✅ -----> Update Plugins Pending');

    if (!empty($update_plugins->response)) {
      foreach ($update_plugins->response as $plugin_slug => $plugin_data) {
        $copy_plugin_data = clone $plugin_data;

        unset($copy_plugin_data->id);
        unset($copy_plugin_data->icons);
        unset($copy_plugin_data->banners);
        unset($copy_plugin_data->banners_rtl);
        unset($copy_plugin_data->tested);
        unset($copy_plugin_data->package);
        unset($copy_plugin_data->requires_php);
        unset($copy_plugin_data->requires_plugins);
        unset($copy_plugin_data->requires);

        $lists[] = $copy_plugin_data;
      }
    }

    $this->log('Update Plugins Pending Lists', $lists);

    wp_cache_delete(self::$OPTION_PLUGIN_UPDATE_PENDING, 'options');
    update_option(self::$OPTION_PLUGIN_UPDATE_PENDING, json_encode($lists), false);

    $this->updateTranslationsPending();

    // remember to return the $update_plugins as it's a filter
    return $update_plugins;
  }

  /**
   * Get the themes that have updates available
   *
   * @return array
   */
  public function updateThemesPending($update_themes)
  {
    $lists = [];

    $this->log('✅ -----> Update Themes Pending');

    if (!empty($update_themes->response)) {
      foreach ($update_themes->response as $theme_slug => $theme_data) {
        $copy_theme_data = $theme_data;

        unset($copy_theme_data['package']);
        unset($copy_theme_data['requires']);
        unset($copy_theme_data['requires_php']);

        $lists[] = $copy_theme_data;
      }
    }

    wp_cache_delete(self::$OPTION_THEME_UPDATE_PENDING, 'options');
    update_option(self::$OPTION_THEME_UPDATE_PENDING, json_encode($lists), false);

    $this->updateTranslationsPending();

    // remember to return the $update_themes as it's a filter
    return $update_themes;
  }

  /**
   * Get the translations that have updates available
   *
   * @return self;
   */
  public function updateTranslationsPending()
  {
    $lists = [];

    $this->log('✅ -----> Update Translations Pending');

    $translation_updates = wp_get_translation_updates();

    if (!empty($translation_updates)) {
      foreach ($translation_updates as $translation) {
        $copy_translation = clone $translation;

        unset($copy_translation->package);
        unset($copy_translation->autoupdate);

        $lists[] = $copy_translation;
      }
    }

    wp_cache_delete(self::$OPTION_TRANSLATIONS_UPDATE_PENDING, 'options');
    update_option(self::$OPTION_TRANSLATIONS_UPDATE_PENDING, json_encode($lists), false);

    return $this;
  }

  /**
   * Return the plugins that have updates available
   *
   * @return array
   */
  public function plugins()
  {
    $pending = get_option(self::$OPTION_PLUGIN_UPDATE_PENDING, '[]');

    $values = json_decode($pending, true);

    return $values;
  }

  /**
   * Return the themes that have updates available
   *
   * @return array
   */
  public function themes()
  {
    $pending = get_option(self::$OPTION_THEME_UPDATE_PENDING, '[]');

    $values = json_decode($pending, true);

    return $values;
  }

  /**
   * Return the core that have updates available
   *
   * @return array
   */
  public function core()
  {
    $pending = get_option(self::$OPTION_CORE_UPDATE_PENDING, '[]');

    $values = json_decode($pending, true);

    return $values;
  }

  /**
   * Return the translations that have updates available
   *
   * @return array
   */
  public function translations()
  {
    $pending = get_option(self::$OPTION_TRANSLATIONS_UPDATE_PENDING, '[]');

    $values = json_decode($pending, true);

    return $values;
  }
}
