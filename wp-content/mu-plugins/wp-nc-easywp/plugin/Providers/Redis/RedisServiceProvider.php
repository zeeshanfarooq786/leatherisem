<?php

namespace WPNCEasyWP\Providers\Redis;

use WPNCEasyWP\Providers\Redis\RedisCache;
use WPNCEasyWP\WPBones\Support\ServiceProvider;

class RedisServiceProvider extends ServiceProvider
{
  /**
   * Default filename for object cache dropin plugin.
   *
   * @var string
   */
  protected $objectCacheDropinFilename = 'object-cache.php';

  protected function validateObjectCacheDropin()
  {

    if (!$this->objectCacheDropinExists()) {
      return false;
    }

    $dropin = $this->getDropinData();
    $plugin = $this->getDropinIncludedData();

    if (strcmp($dropin['PluginURI'], $plugin['PluginURI']) !== 0) {
      return false;
    }

    return true;
  }

  // ---

  public function register()
  {
    RedisCache::boot();

    $this->enableObjectCache();
  }

  protected function enableObjectCache()
  {
    if (!$this->isDropinUpdated()) {
      $result = copy($this->getDropinIncludedPath(), $this->getDropinPath());
    }
  }

  protected function isDropinUpdated()
  {
    if (!$this->objectCacheDropinExists()) {
      return false;
    }

    $dropin = $this->getDropinData();
    $plugin = $this->getDropinIncludedData();

    if (version_compare($dropin['Version'], $plugin['Version'], '<')) {
      return false;
    }

    return true;
  }

  /**
   * Return TRUE the object cache dropin plugin exists in the plugin folder.
   *
   * @return bool
   */
  protected function objectCacheDropinExists()
  {
    return file_exists($this->getDropinPath());
  }

  protected function getDropinPath()
  {
    return WP_CONTENT_DIR . '/' . $this->objectCacheDropinFilename;
  }

  protected function getDropinData()
  {
    if ($this->objectCacheDropinExists()) {
      return get_plugin_data($this->getDropinPath());
    }

    return false;
  }

  protected function getDropinIncludedData()
  {
    return get_plugin_data($this->getDropinIncludedPath());
  }

  protected function getDropinIncludedPath()
  {
    return $this->plugin->basePath . '/plugin/Providers/Redis/includes/' . $this->objectCacheDropinFilename;
  }
}
