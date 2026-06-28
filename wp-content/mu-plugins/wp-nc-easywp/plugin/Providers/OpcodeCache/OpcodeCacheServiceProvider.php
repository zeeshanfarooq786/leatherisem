<?php

namespace WPNCEasyWP\Providers\OpcodeCache;

use WPNCEasyWP\Providers\OpcodeCache\OpcodeCache;
use WPNCEasyWP\WPBones\Support\ServiceProvider;

class OpcodeCacheServiceProvider extends ServiceProvider
{

  public function register()
  {
    OpcodeCache::boot();
  }
}
