<?php

namespace WPNCEasyWP\Http\Controllers\Varnish;

if (!defined('ABSPATH')) {
  exit;
}

use WPNCEasyWP\Http\Controllers\Controller;
use WPNCEasyWP\Providers\Varnish\VarnishDebug;

class VarnishController extends Controller
{

  public function index()
  {
    return WPNCEasyWP()
      ->view('varnish.index');
  }

  public function update()
  {
    do_action('wpnceasywp_varnish_purge_again');

    return WPNCEasyWP()
      ->view('varnish.index');
  }

  public function debug()
  {
    return WPNCEasyWP()
      ->view('varnish.index')
      ->with(['debug' => new VarnishDebug()]);
  }
}
