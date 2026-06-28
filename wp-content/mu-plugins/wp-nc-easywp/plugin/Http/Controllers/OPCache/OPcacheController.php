<?php

namespace WPNCEasyWP\Http\Controllers\OpcodeCache;

use WPNCEasyWP\Http\Controllers\Controller;

class OpcodeCacheController extends Controller
{
  public function index()
  {
    return WPNCEasyWP()
      ->view('opcache.index')
      ->withAdminStyle('wpnceasywp-opcache');
  }
}
