<?php

namespace WPNCEasyWP\Providers\AutomaticUpdates;

use WPNCEasyWP\Providers\AutomaticUpdates\AutomaticUpdatesOptionsNamesTrait;
use WPNCEasyWP\Providers\AutomaticUpdates\SequoiaIntegrationCache;

class AutomaticUpdatesCompleted
{
  use AutomaticUpdatesOptionsNamesTrait;

  public static function init()
  {
    return new self();
  }

  public function __construct()
  {
    // ...
  }

  /**
   * Update the core when the update is completed
   *
   * @param array $data Core update data
   * @return self
   */
  public function updateCore($data)
  {
    update_option(self::$OPTION_CORE_UPDATE_COMPLETED, json_encode($data));

    SequoiaIntegrationCache::init()->flush();

    return $this;
  }

  /**
   * Update the plugin when the update is completed
   *
   * @param array $data Plugins data
   * @param bool $merge Merge the data with the existing data
   * @return self
   */
  public function updatePlugins($data, $merge = true)
  {
    $completed = $this->plugins();

    if (empty($completed)) {
      update_option(self::$OPTION_PLUGIN_UPDATE_COMPLETED, json_encode($data));

      return $this;
    }

    $update = $merge ? [...$completed, ...$data] : $data;

    update_option(self::$OPTION_PLUGIN_UPDATE_COMPLETED, json_encode($update));

    SequoiaIntegrationCache::init()->flush();

    return $this;
  }

  /**
   * Update the themes when the update is completed
   *
   * @param array $data Themes data
   * @param bool $merge Merge the data with the existing data
   * @return self
   */
  public function updateThemes($data, $merge = true)
  {
    $completed = $this->themes();

    if (empty($completed)) {
      update_option(self::$OPTION_THEME_UPDATE_COMPLETED, json_encode($data));

      return $this;
    }

    $update = $merge ? [...$completed, ...$data] : $data;

    update_option(self::$OPTION_THEME_UPDATE_COMPLETED, json_encode($update));

    SequoiaIntegrationCache::init()->flush();

    return $this;
  }

  /**
   * Update the translations when the update is completed
   *
   * @param array $data Translations data
   * @param bool $merge Merge the data with the existing data
   * @return self
   */
  public function updateTranslations($data, $merge = true)
  {
    $completed = $this->translations();

    if (empty($completed)) {
      update_option(self::$OPTION_TRANSLATIONS_UPDATE_COMPLETED, json_encode($data));

      return $this;
    }

    $update = $merge ? [...$completed, ...$data] : $data;

    update_option(self::$OPTION_TRANSLATIONS_UPDATE_COMPLETED, json_encode($update));

    SequoiaIntegrationCache::init()->flush();

    return $this;
  }

  /**
   * Get the plugins completed updated
   *
   * @return array
   */
  public function plugins()
  {
    $completed = get_option(self::$OPTION_PLUGIN_UPDATE_COMPLETED, '[]');

    $values = json_decode($completed, true);

    return $values;
  }

  /**
   * Get the themes completed updated
   *
   * @return array
   */
  public function themes()
  {
    $completed = get_option(self::$OPTION_THEME_UPDATE_COMPLETED, '[]');

    $values = json_decode($completed, true);

    return $values;
  }

  /**
   * Get the translations completed updated
   *
   * @return array
   */
  public function translations()
  {
    $completed = get_option(self::$OPTION_TRANSLATIONS_UPDATE_COMPLETED, '[]');

    $values = json_decode($completed, true);

    return $values;
  }

  /**
   * Get the core completed updated
   *
   * @return array
   */
  public function core()
  {
    $completed = get_option(self::$OPTION_CORE_UPDATE_COMPLETED, '[]');

    $values = json_decode($completed, true);

    return $values;
  }

  /**
   * Sync the updated plugins with the plugins in the WordPress installation
   *
   * @return self;
   */
  public function syncPlugins()
  {
    $values = $this->plugins();

    $plugins = get_plugins();
    $data = [];

    foreach ($plugins as $slug => $plugin) {
      if (isset($values[$slug])) {
        $data[$slug] = $values[$slug];
      }
    }

    // Do not merge the previus data
    $this->updatePlugins($data, false);

    SequoiaIntegrationCache::init()->flush();

    return $this;
  }

  /**
   * Sync the updated themes with the themes in the WordPress installation
   *
   * @return self;
   */
  public function syncThemes()
  {
    $values = $this->themes();

    $themes = wp_get_themes();
    $data = [];

    foreach ($themes as $slug => $theme) {
      if (isset($values[$slug])) {
        $data[$slug] = $values[$slug];
      }
    }

    // Do not merge the previus data
    $this->updateThemes($data, false);

    SequoiaIntegrationCache::init()->flush();

    return $this;
  }
}
