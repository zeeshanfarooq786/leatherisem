<?php

namespace WPNCEasyWP\Traits;


trait ThrottleTrait
{
  protected $throttle = 60;

  protected $throttleTimeout = 60;

  protected $throttleName = 'x_throttle_';

  protected function throttle()
  {
    $ip        = md5($_SERVER['REMOTE_ADDR']);
    $transient = "{$this->throttleName}{$ip}";
    $throttle  = $this->getTransient($transient);

    if (empty($throttle)) {
      $this->setTransient($transient, "1", $this->throttleTimeout);

      return true;
    }

    $throttle++;

    if ($throttle > $this->throttle) {
      return false;
    }

    $this->setTransient($transient, $throttle);

    return true;
  }

  protected function getTransient($name, $default = false)
  {
    $timeout   = "_transient_timeout_{$name}";
    $transient = "_transient_{$name}";

    $value = get_option($timeout);

    if ($value && time() > $value) {
      delete_option($timeout);
      delete_option($transient);

      return false;
    }

    $value = get_option($transient);

    return $value ?: $default;
  }

  protected function setTransient($name, $value, $expired = 0)
  {
    $timeout   = "_transient_timeout_{$name}";
    $transient = "_transient_{$name}";

    if (false === get_option($transient)) {
      add_option($timeout, (time() + $expired), '', 'no');
      add_option($transient, $value, '', 'no');
    } else {
      update_option($transient, $value);
    }
  }
}
