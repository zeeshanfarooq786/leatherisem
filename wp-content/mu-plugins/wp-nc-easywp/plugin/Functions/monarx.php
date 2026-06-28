<?php
if (!defined("ABSPATH")) {
  exit();
}

/*
|--------------------------------------------------------------------------
| Monarx
|--------------------------------------------------------------------------
|
|
*/

function useMonarx($bypass_transient = false)
{
  if (defined('WP_CLI') && WP_CLI) {
    // WP-CLI is in use, skip the getenv() checks
    return json_decode("[]", true);
  }

  // Check if the transient exists
  $transient_key = 'monarx_api_response';
  $cached_response = get_transient($transient_key);

  if ($cached_response !== false && !$bypass_transient) {
    $decoded_response = json_decode($cached_response, true);
    return $decoded_response;
  }

  $jwt_token = easywpJWT()->token;

  if (!$jwt_token) {
    error_log("easywp-plugin: JWT_TOKEN not set");
    return json_decode("[]", true);
  }

  $website_webhook_url = getenv("WEBSITE_WEBHOOK_URL");

  if (!$website_webhook_url) {
    error_log("easywp-plugin: WEBSITE_WEBHOOK_URL not set");
    return json_decode("[]", true);
  }

  // get the appId from the JWT token
  $appId = easywpJWT()->websiteId;

  if (!$appId) {
    error_log("easywp-plugin: APP_ID not set");
    return json_decode("[]", true);
  }

  // YAML config
  $api_route = wpbones_flags()->flags('monarx.route', '/v1/sequoia-app-webhook/api/v1alpha1/notifications');
  $minutes = intval(wpbones_flags()->flags('monarx.throttle', 5));
  $timeout = intval(wpbones_flags()->flags('monarx.timeout', 0));

  $url = "{$website_webhook_url}{$api_route}/{$appId}";

  $curl = curl_init();

  curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => $timeout,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => ["Content-Type: application/json", "authorization: " . $jwt_token],
  ]);

  $response = curl_exec($curl);

  curl_close($curl);

  if ($response === false) {
    error_log("easywp-plugin: CURL error: " . curl_error($curl));
    return json_decode("[]", true);
  }

  // decode the response and return an array instead of an object
  $decoded_response = json_decode($response, true);

  // Cache the response for 5 minutes
  set_transient($transient_key, $response, $minutes * MINUTE_IN_SECONDS);

  // decode the response and return an arra instead of an object
  return $decoded_response;
}
