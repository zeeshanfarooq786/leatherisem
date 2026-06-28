<?php

if (!defined("ABSPATH")) {
  exit();
}

/*
|--------------------------------------------------------------------------
| WordPress Readonly - HackGuardian
|--------------------------------------------------------------------------
|
|
*/

/**
 * Set HackGuardian
 * Execute the API call to set the website in readonly mode
 *
 * @param bool $enabled
 * @return mixed
 */
function setHackGuardian(bool $enabled)
{
  if (defined('WP_CLI') && WP_CLI) {
    // WP-CLI is in use, skip the getenv() checks
    return;
  }

  $jwt_token = easywpJWT()->token;

  if (!$jwt_token) {
    error_log("easywp-plugin: JWT_TOKEN not set");
    return;
  }

  $data = ["oldReadonly" => !$enabled, "newReadonly" => $enabled];
  $data_string = json_encode($data);
  $website_webhook_url = getenv("WEBSITE_WEBHOOK_URL");

  if (!$website_webhook_url) {
    error_log("easywp-plugin: WEBSITE_WEBHOOK_URL not set");
    return;
  }

  // YAML config
  $api_route = wpbones_flags()->flags('hackguardian.route', '/v1/readonly');
  $timeout = intval(wpbones_flags()->flags('hackguardian.timeout', 0));

  $curl = curl_init();

  curl_setopt_array($curl, [
    CURLOPT_URL => "{$website_webhook_url}{$api_route}",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => $timeout,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_HTTPHEADER => ["Content-Type: application/json", "authorization: " . $jwt_token],
    CURLOPT_POSTFIELDS => $data_string,
  ]);

  $response = curl_exec($curl);

  curl_close($curl);

  return $response;
}

/**
 * Check if the plugins directory is writable
 *
 * @deprecated since version 2.0+
 *
 * @return bool
 */
function is_plugins_directory_writable()
{
  $directory = '/tmp';
  $test_file = $directory . '/easywp-plugin-readonly-check.tmp';

  try {
    // Try to create a temporary file
    if (@file_put_contents($test_file, 'test') !== false) {
      // Remove the temporary file
      @unlink($test_file);
      return true;
    }
  } catch (Exception $e) {
    return false;
  }

  return false;
}

/**
 * Read the file `/run/podinfo/labels` and check the line `read-only="true"`
 * exists
 *
 * @since 2.0
 *
 * @return string
 */
function get_readonly_status()
{
  $file = '/run/podinfo/labels';

  if (!file_exists(realpath($file))) {
    clearstatcache(true);
  }

  $content = file_get_contents($file);

  if ($content === false) {
    return false;
  }

  $lines = explode("\n", $content);

  foreach ($lines as $line) {
    if (strpos($line, 'read-only="true"') !== false) {
      return true;
    }
  }

  return false;
}

/**
 * Return true if HackGuardian is enabled
 *
 * @return bool
 */
function useHackGuardian()
{
  $hack_guardian_enabled = get_readonly_status();

  return $hack_guardian_enabled;
}
