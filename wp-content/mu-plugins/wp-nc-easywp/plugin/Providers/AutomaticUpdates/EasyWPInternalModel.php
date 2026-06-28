<?php

namespace WPNCEasyWP\Providers\AutomaticUpdates;

use WPNCEasyWP\WPBones\Database\Model;
use WPNCEasyWP\Providers\AutomaticUpdates\AutomaticUpdatesOptionsNamesTrait;

/**
 * EasywpInternal model.
 *
 * This class is used to interact with the easywp_internal table.
 *
 */
class EasyWPInternalModel extends Model
{
  use AutomaticUpdatesOptionsNamesTrait;

  protected $table = 'easywp_internal';
  protected $usePrefix = false;

  // wp prefix cache key
  protected static $cache_wp_prefix = 'easywp_wp_prefix';

  // wp prefix cache group
  protected static $cache_group = 'easywp';

  /**
   * Create the easywp_internal table
   *
   * @return void
   */
  public static function createTableIfDoesntExist()
  {
    global $wpdb;

    // check if the table 'easywp_internal' exits
    $tableName = self::getTableName('easywp_internal', false);
    $tableExists = $wpdb->get_var("SHOW TABLES LIKE '{$tableName}'") === $tableName;

    if ($tableExists) {
      return;
    }

    // create the table
    $sql = "CREATE TABLE {$tableName} (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      name varchar(191) NOT NULL DEFAULT '',
      value longtext NOT NULL,
      PRIMARY KEY (id),
      UNIQUE KEY unique_name (name)
    ) {$wpdb->get_charset_collate()};";

    $wpdb->query($sql);

    wp_cache_set(self::$cache_wp_prefix, false, self::$cache_group);
  }

  /**
   * Update/create the wp prefix in the easywp_internal table
   *
   * @return int
   */
  public static function updateWPPrefix()
  {
    global $wpdb;

    $wp_prefix = wp_cache_get(self::$cache_wp_prefix, self::$cache_group);

    if ($wp_prefix !== $wpdb->prefix) {
      wp_cache_set(self::$cache_wp_prefix, $wpdb->prefix, self::$cache_group);
      self::updateOrInsert('wpprefix', $wpdb->prefix);
    }

    return $wp_prefix;
  }

  /**
   * Update/create a row in the easywp_internal table
   *
   * @param string $name Row name
   * @param string $value Row value
   * @return int
   */
  public static function updateOrInsert($name, $value)
  {
    global $wpdb;

    $tableName = self::getTableName('easywp_internal', false);

    $sql = "INSERT INTO {$tableName} (name,value) VALUES ('{$name}','{$value}') ON DUPLICATE KEY UPDATE value='{$value}'";

    return $wpdb->query($sql);
  }
}
