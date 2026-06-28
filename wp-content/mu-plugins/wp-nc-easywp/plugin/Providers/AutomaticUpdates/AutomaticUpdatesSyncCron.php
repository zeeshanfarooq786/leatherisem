<?php

namespace WPNCEasyWP\Providers\AutomaticUpdates;

use WPNCEasyWP\WPBones\Foundation\WordPressScheduleServiceProvider as ServiceProvider;
use WPNCEasyWP\Providers\AutomaticUpdates\AutomaticUpdatesCompleted;

class AutomaticUpdatesSyncCron extends ServiceProvider
{
  // Hook name - used in the WordPress schedule event
  protected $hook = 'wpnc_easywp_autoupdates';

  // Recurrence - used in the WordPress schedule event
  protected $recurrence = 'hourly';

  public function boot()
  {
    // You may override this method to set the properties
    // $this->hook = 'schedule_example_event';
    $this->recurrence = wpbones_flags()->flags('autoupdates.sync.recurrence', 'hourly');
  }

  /**
   * Run the scheduled event.
   *
   */
  public function run()
  {
    $enabled = wpbones_flags()->flags('autoupdates.sync.enabled', false);

    if (! $enabled) {
      return;
    }
    AutomaticUpdatesCompleted::init()->syncPlugins()->syncThemes();
  }
}
