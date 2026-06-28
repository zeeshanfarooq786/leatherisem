<!--
 |
 | In $plugin you'll find an instance of Plugin class.
 | If you'd like can pass variable to this view, for example:
 |
 | return PluginClassName()->view( 'dashboard.index', [ 'var' => 'value' ] );
 |
-->

<div class="wpnceasywp wrap">
  <h1>
    <?php _e('EasyWP Varnish Controller', 'wp-nc-easywp') ?>
  </h1>

  <p>
    <?php _e('From this view you\'ll able to check the varnish status.', 'wp-nc-easywp') ?>
  </p>

  <h2>
    <?php _e('Information', 'wp-nc-easywp') ?>
  </h2>

  <p>
    <label for="">
      <?php _e('Schema', 'wp-nc-easywp') ?>
      <input type="text" readonly
        value="<?php echo $plugin->options['varnish.schema'] ?>" />

    </label>
  </p>

  <p>
    <label for="">
      <?php _e('IP', 'wp-nc-easywp') ?>
      <input type="text" readonly
        value="<?php echo $plugin->options['varnish.ip'] ?>" />
    </label>
  </p>

  <?php if (!empty($plugin->options['varnish.last_purge'])) : ?>

  <h2><?php _e('Last purge', 'wp-nc-easywp') ?>
  </h2>

  <p>
    <?php echo human_time_diff($plugin->options['varnish.last_purge']) ?>
    <?php _e('ago', 'wp-nc-easywp') ?>
  </p>

  <h2><?php _e('Last purged urls', 'wp-nc-easywp') ?>
  </h2>

  <form method="post">
    <input type="hidden" name="_method" value="PUT" />
    <select multiple size="15" disabled readonly="readonly">
      <?php foreach ($plugin->options['varnish.last_purged_urls'] as $url) : ?>
      <option><?php echo $url ?>
      </option>
      <?php endforeach; ?>
    </select>

    <p><?php _e('Of course, you can', 'wp-nc-easywp') ?>
      <button class="button button-primary"><?php _e('Purge Again', 'wp-nc-easywp') ?></button>
    </p>
  </form>

  <?php endif; ?>


  <h2><?php _e('Debug', 'wp-nc-easywp') ?>
  </h2>

  <p><?php _e('Here you can start a debug of Varnish', 'wp-nc-easywp') ?>
  </p>

  <form action="" method="post">
    <p>
      <button class="button button-primary" name="debug" value="start">
        Debug
      </button>
    </p>
  </form>

  <?php if (isset($debug)) : ?>
  <?php _e('Debug Results', 'wp-nc-easywp') ?>
  <?php endif; ?>

</div>