<?php

namespace WPNCEasyWP\Http\Controllers\Redis;

use WPNCEasyWP\Http\Controllers\Controller;

class RedisController extends Controller
{

  public function index()
  {
    return WPNCEasyWP()
      ->view('redis.index');
  }
}
