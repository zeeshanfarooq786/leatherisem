<?php

namespace WPNCEasyWP\Providers\Varnish;

use WPNCEasyWP\Providers\Varnish\VarnishCache;
use WPNCEasyWP\WPBones\Support\ServiceProvider;

class VarnishServiceProvider extends ServiceProvider
{

  public function register()
  {
    VarnishCache::boot();
  }
}
