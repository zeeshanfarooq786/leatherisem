<!--
 |
 | In $plugin you'll find an instance of Plugin class.
 | If you'd like can pass variable to this view, for example:
 |
 | return PluginClassName()->view( 'dashboard.index', [ 'var' => 'value' ] );
 |
-->

<div class="wpnceasywp wrap">
  <h1><?php echo $plugin->Name; ?> ver.<?php echo $plugin->Version; ?></h1>
  <h2>
    PHP ver.<?php echo phpversion(); ?>
  </h2>
  <hr />

  <h2>WP Update Plugins</h2>
  <hr />

  <?php
  wp_update_plugins();
  $update_plugins = get_site_transient('update_plugins');
  if (!empty($update_plugins->response)) {
    foreach ($update_plugins->response as $plugin_slug => $plugin_data) {
      echo 'Plugin: ' . $plugin_slug . ' - Available version: ' . $plugin_data->new_version . '<br>';
    }
  } else {
    echo 'No updates available for the plugins.';
  }
  ?>

  <h2>Database easywp_internal</h2>
  <hr />

  <?php

  use WPNCEasyWP\Providers\AutomaticUpdates\EasyWPInternalModel;
  use WPNCEasyWP\Providers\AutomaticUpdates\AutomaticUpdatesCompleted;
  use WPNCEasyWP\Providers\AutomaticUpdates\AutomaticUpdatesPending;

  $history = EasyWPInternalModel::all();

  if (empty($history->count())) {
    echo "<p>No history found.</p>";
  } else {
    echo "<ul>";
    foreach ($history as $item) {
      echo "<li><strong>{$item->name}</strong>: <code>{$item->value}</code></li>";
    }
    echo "</ul>";
  }

  $completed = new AutomaticUpdatesCompleted();
  $pending = new AutomaticUpdatesPending();

  ?>

  <h2>Automatic updates - Completed</h2>
  <hr />

  <details>
    <summary>Plugins</summary>
    <pre><?php echo json_encode($completed->plugins(), JSON_PRETTY_PRINT); ?></pre>
  </details>

  <details>
    <summary>Themes</summary>
    <pre><?php echo json_encode($completed->themes(), JSON_PRETTY_PRINT); ?></pre>
  </details>

  <details>
    <summary>Core</summary>
    <pre><?php echo json_encode($completed->core(), JSON_PRETTY_PRINT); ?></pre>
  </details>

  <h2>Automatic updates - Pending</h2>
  <hr />

  <details>
    <summary>Plugins</summary>
    <pre><?php echo json_encode($pending->plugins(), JSON_PRETTY_PRINT); ?></pre>
  </details>

  <details>
    <summary>Themes</summary>
    <pre><?php echo json_encode($pending->themes(), JSON_PRETTY_PRINT); ?></pre>
  </details>

  <details>
    <summary>Core</summary>
    <pre><?php echo json_encode($pending->core(), JSON_PRETTY_PRINT); ?></pre>
  </details>

  <details>
    <summary>Tanslations</summary>
    <pre><?php echo json_encode($pending->translations(), JSON_PRETTY_PRINT); ?></pre>
  </details>

  <h2>YAML config</h2>
  <hr />

  <details>
    <summary>Click to see the YAML config</summary>
    <p>YAML flags version: <?php echo wpbones_flags()->flags('version') ?></p>
    <p>YAML flags HackGuardian:</p>
    <pre><?php echo json_encode(wpbones_flags()->flags('hackguardian'), JSON_PRETTY_PRINT); ?></pre>
    <p>YAML flags Monarx:</p>
    <pre><?php echo json_encode(wpbones_flags()->flags('monarx'), JSON_PRETTY_PRINT); ?></pre>
  </details>

  <?php if (!empty($plugins)): ?>

    <h2><?php _e("Plugins...", "wp-nc-easywp"); ?></h2>

    <h4><?php _e("Warning", "wp-nc-easywp"); ?></h4>

    <p>
      <?php echo _n(
        "The following plugin will be disabled.",
        "The following plugins will be disabled.",
        count($plugins),
        "wp-nc-easywp"
      );  ?>
    </p>

    <ul>

      <?php foreach ($plugins as $file => $value): ?>
        <li>
          <?php printf(
            __("%s will be disabled because: %s", "wp-nc-easywp"),
            $value["data"]["Name"],
            $value["info"]["description"]
          ); ?>
        </li>
      <?php endforeach; ?>

    </ul>

  <?php endif; ?>

  <h2>Paths</h2>
  <hr />

  <details>
    <summary>Click to see the paths</summary>
    <p>__FILE__: <code><?php echo __FILE__; ?></code></p>
    <p>__DIR__: <code><?php echo __DIR__; ?></code></p>
    <p>ABSPATH: <code><?php echo ABSPATH; ?></code></p>
    <p>WP_CONTENT_DIR: <code><?php echo WP_CONTENT_DIR; ?></code></p>
    <p>WP_CONTENT_URL: <code><?php echo WP_CONTENT_URL; ?></code></p>
    <p>WP_PLUGIN_DIR: <code><?php echo WP_PLUGIN_DIR; ?></code></p>
    <p>WP_PLUGIN_URL: <code><?php echo WP_PLUGIN_URL; ?></code></p>
    <p>WPMU_PLUGIN_DIR: <code><?php echo WPMU_PLUGIN_DIR; ?></code></p>
    <p>WPMU_PLUGIN_URL: <code><?php echo WPMU_PLUGIN_URL; ?></code></p>
    <p>plugin->basePath: <code><?php echo $plugin->basePath; ?></code></p>
    <p>plugin->baseUri: <code><?php echo $plugin->baseUri; ?></code></p>
    <p>plugin->js: <code><?php echo $plugin->js; ?></code></p>
    <p>plugin->css: <code><?php echo $plugin->css; ?></code></p>
  </details>

  <h2>Environment variables</h2>
  <hr />

  <h3>By getenv()</h3>
  <hr />

  <details>
    <summary>Click to see the environment variables</summary>
    <?php
    $envVars = getenv();
    foreach ($envVars as $key => $value) {
      echo "<p>$key: <code>$value</code></p>";
    }
    ?>
  </details>

  <h3>By $_ENV</h3>
  <hr />

  <details>
    <summary>Click to see the environment variables</summary>
    <?php
    $envVars = $_ENV;
    foreach ($envVars as $key => $value) {
      echo "<p>$key: <code>$value</code></p>";
    }
    ?>
  </details>

  <h3>By $_SERVER</h3>
  <hr />

  <details>
    <summary>Click to see the environment variables</summary>
    <?php
    $envVars = $_SERVER;
    $envVars = array_filter($envVars, function ($key) {
      return strpos($key, 'HTTP_') === false;
    }, ARRAY_FILTER_USE_KEY);

    foreach ($envVars as $key => $value) {
      echo "<p>$key: <code>$value</code></p>";
    }
    ?>
  </details>

  <h3>Hack Guardian</h3>
  <hr />

  <?php
  $jwt_token = easywpJWT()->token;
  $appId = easywpJWT()->websiteId;
  ?>

  <p>JWT_TOKEN: <code><?php echo $jwt_token; ?></code></p>
  <p>WEBSITE_WEBHOOK_URL: <code><?php echo getenv("WEBSITE_WEBHOOK_URL"); ?></code></p>
  <p>EASYWP_READONLY: <code><?php echo getenv("EASYWP_READONLY"); ?></code></p>
  <p>APP_ID: <code><?php echo $appId; ?></code></p>

  <?php $notifictions = useMonarx(); ?>
  <h3>Monarx notifications</h3>
  <hr />
  <pre><?php echo json_encode($notifictions, JSON_PRETTY_PRINT); ?></pre>

  <?php $notifictions = useMonarx(true); ?>
  <h3>Monarx notifications - no cached</h3>
  <hr />
  <pre><?php echo json_encode($notifictions, JSON_PRETTY_PRINT); ?></pre>

  <h2>Kubernetes</h2>
  <hr />

  <?php $info = \WPNCEasyWP\Providers\Varnish\VarnishCache::info(); ?>

  <p>HOSTNAME: <code><?php echo $info["HOSTNAME"]; ?></code></p>
  <p>Service: <code><?php echo $info["svc"]; ?></code></p>
  <p>IPs: <code><?php echo $info["ips"]; ?></code></p>

</div>
