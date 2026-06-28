<?php

namespace WPNCEasyWP\Providers;

use WPNCEasyWP\WPBones\Support\ServiceProvider;

class MonarxServiceProvider extends ServiceProvider
{
  protected $notifications = [];

  public function register()
  {
    if (!wpbones_flags()->flags('monarx.enabled')) {
      return;
    }

    if (is_admin()) {
      // Get potential notification
      $this->notifications = useMonarx();

      // if we have some notifications, display them
      if (is_array($this->notifications) && !empty($this->notifications)) {
        add_action('admin_notices', [$this, 'admin_notices_monarx']);
      }
    }
  }

  public function noticeMonarx($notification)
  {
?>
    <div class="notice notice-warning easywp-alert">
      <div class="easywp-alert-title">
        <div class="easywp-alert-icon"></div>
        <?php echo isset($notification['title']) ? $notification['title'] : 'Warning'; ?>
      </div>
      <div class="easywp-alert-content">
        <?php echo isset($notification['text']) ? $notification['text'] : '(missing text)'; ?>
      </div>
    </div>
<?php
  }

  public function admin_notices_monarx()
  {
    if (is_array($this->notifications) && !empty($this->notifications)) {
      foreach ($this->notifications as $notification) {
        $this->noticeMonarx($notification);
      }
    }
  }
}
