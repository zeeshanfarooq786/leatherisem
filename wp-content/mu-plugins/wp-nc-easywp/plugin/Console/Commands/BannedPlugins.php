<?php

namespace WPNCEasyWP\Console\Commands;

use WPNCEasyWP\WPBones\Console\Command;

/**
 * Here we're goinf to extend the WP-Bones Command class.
 * We're using this command to manage the banned plugins.
 * You have to edit
 *
 *      /banned-plugins/banned-plugins.json
 *
 * Then run the command:
 *
 *      php bones banned:plugins
 *
 *
 * A new file will be created will be created in the config folder.
 *
 *      /config/banned-plugins.json
 *
 *
 */
class BannedPlugins extends Command
{
  protected $signature = 'banned:plugins { : Create/Update Banned Plugins list }';

  protected $description = 'Manage the banned lists';

  public function __construct()
  {
    parent::__construct();

    add_action(
      'wpbones_console_deployed',
      function ($bones, $path) {
        $bones->deleteDirectory("{$path}/banned-plugins");
        $bones->deleteDirectory("{$path}/assets");
      },
      10,
      2
    );
  }

  public function handle()
  {
    $path = trailingslashit('banned-plugins');

    $files = glob($path . '*.json', GLOB_MARK);

    $output = [];

    foreach ($files as $file) {
      $this->line("\nLoading... " . $file);

      $bannedPluginsContent = file_get_contents($file);

      $decode  = json_decode($bannedPluginsContent, true);
      $plugins = $decode['plugins'];

      foreach ($plugins[0] as $key => $plugin) {
        $output[$key] = [
          'reason' => $plugin['reason'],
          'description' => $plugin['description'],
        ];

        $this->info($key);
      }
    }

    $configFolder          = WPNCEasyWP()->basePath . '/config';
    $bannedPluginsFilename = WPNCEasyWP()->config('checker.plugins', '');
    file_put_contents("{$configFolder}/{$bannedPluginsFilename}", json_encode($output));

    $this->line("{$configFolder}/{$bannedPluginsFilename} updated successfully");
  }
}
