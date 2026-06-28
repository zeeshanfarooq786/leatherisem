<?php

namespace WPNCEasyWP\Providers\AutomaticUpdates;

trait AutomaticUpdatesOptionsNamesTrait
{
  static $OPTION_UPDATE_SCHEDULE = "easywp_auto_update_strategy";

  static $OPTION_THEME_UPDATE_SCHEDULED = "auto_update_themes";
  static $OPTION_PLUGIN_UPDATE_SCHEDULED = "auto_update_plugins";
  static $OPTION_TRANSLATIONS_UPDATE_SCHEDULED = "auto_update_translations";

  static $OPTION_CORE_UPDATE_PENDING = "easywp_auto_update_core_pending";
  static $OPTION_THEME_UPDATE_PENDING = "easywp_auto_update_themes_pending";
  static $OPTION_PLUGIN_UPDATE_PENDING = "easywp_auto_update_plugins_pending";
  static $OPTION_TRANSLATIONS_UPDATE_PENDING = "easywp_auto_update_translations_pending";

  static $OPTION_CORE_UPDATE_COMPLETED = "easywp_auto_update_core_completed";
  static $OPTION_THEME_UPDATE_COMPLETED = "easywp_auto_update_themes_completed";
  static $OPTION_PLUGIN_UPDATE_COMPLETED = "easywp_auto_update_plugins_completed";
  static $OPTION_TRANSLATIONS_UPDATE_COMPLETED = "easywp_auto_update_translations_completed";
}
