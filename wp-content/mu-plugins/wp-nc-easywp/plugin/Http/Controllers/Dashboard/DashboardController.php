<?php

namespace WPNCEasyWP\Http\Controllers\Dashboard;

use WPNCEasyWP\Providers\Checker\Plugins;
use WPNCEasyWP\Http\Controllers\Controller;

class DashboardController extends Controller
{

  public function index()
  {
    return WPNCEasyWP()
      ->view('dashboard.index')
      ->withAdminStyle('wpnceasywp-dashboard')
      ->with('plugins', Plugins::boot()->getWillDisabled());
  }
}
