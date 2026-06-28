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
    <?php _e('EasyWP Opcache Controller', 'wp-nc-easywp') ?>
  </h1>

  <p>
    <?php _e('From this view you\'ll able to check the Opcache status.', 'wp-nc-easywp') ?>
  </p>

  <h2><?php _e('Opcache status', 'wp-nc-easywp') ?>
  </h2>

  <?php $status = opcache_get_status(false) ?>

  <table class="tb-opcache">
    <tbody>
      <?php foreach ($status as $key => $value) : ?>
        <tr>
          <th><?php echo $key ?>
          </th>
          <td
            class="<?php echo is_array($value) ? 'contains-table' : '' ?>">
            <?php if (is_array($value)) : ?>

              <table class="tb-opcache">
                <tbody>
                  <?php foreach ($value as $skey => $svalue) : ?>
                    <tr>
                      <th><?php echo $skey ?>
                      </th>
                      <td><?php echo $svalue ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>

            <?php elseif (is_bool($value)) : ?>
              <?php echo $value ? 'Yes' : 'No' ?>
            <?php else : ?>
              <?php echo $value ?>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</div>