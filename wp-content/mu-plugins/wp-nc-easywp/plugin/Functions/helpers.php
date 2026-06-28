<?php
if (!defined("ABSPATH")) {
  exit();
}

use WPNCEasyWP\Support\JWT;
use WPNCEasyWP\Support\DashboardNotifications;

/**
 * Global functions
 * Return the object with the JWT token and the JWT info
 *
 * @param string $jwt_token
 * @return WPNCEasyWP\Support\JWT
 */
function easywpJWT($jwt_token = false)
{
  return new JWT($jwt_token);
}

/**
 * Global functions
 * Return the object with the Dashboard Notifications
 *
 * @return WPNCEasyWP\Support\DashboardNotifications
 */
function easywpDashboardNotifications()
{
  return new DashboardNotifications();
}
