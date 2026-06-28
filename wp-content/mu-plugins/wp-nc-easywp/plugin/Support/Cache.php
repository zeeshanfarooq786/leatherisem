<?php

namespace WPNCEasyWP\Support;

abstract class Cache
{
  public static function boot()
  {

    static $instances = [];

    $calledClass = get_called_class();

    if (!isset($instances[$calledClass])) {
      $instances[$calledClass] = new static();
    }

    return $instances[$calledClass];
  }
}
