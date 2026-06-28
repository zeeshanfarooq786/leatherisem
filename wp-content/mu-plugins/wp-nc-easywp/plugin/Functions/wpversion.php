<?php
if (!defined("ABSPATH")) {
  exit();
}

/*
|--------------------------------------------------------------------------
| WordPress version utility
|--------------------------------------------------------------------------
|
| Here we're going to add our own action when the WordPress version is updated.
|
|
*/

/**
 * Fires after WordPress core has been successfully updated.
 *
 * @since 3.3.0
 *
 * @param string $wp_version The current WordPress version.
 */
add_action(
  '_core_updated_successfully',
  function (string $wp_version) {

    if (defined('WP_CLI') && WP_CLI) {
      // WP-CLI is in use, skip the getenv() checks
      return;
    }

    $curl = curl_init();

    $data = ["wpVersion" => "$wp_version"];

    $data_string = json_encode($data);

    $jwt_token = easywpJWT()->token;

    if (!$jwt_token) {
      error_log("easywp-plugin: JWT_TOKEN not set");
      return;
    }

    $website_webhook_url = getenv("WEBSITE_WEBHOOK_URL");

    if (!$website_webhook_url) {
      error_log("easywp-plugin: WEBSITE_WEBHOOK_URL not set");
      return;
    }

    curl_setopt_array($curl, array(
      CURLOPT_URL            => "{$website_webhook_url}/v1/wpversion",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING       => '',
      CURLOPT_MAXREDIRS      => 10,
      CURLOPT_TIMEOUT        => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST  => 'PUT',
      CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'authorization: ' . $jwt_token,
      ],
      CURLOPT_POSTFIELDS     => $data_string
    ));

    curl_exec($curl);
    curl_close($curl);
  },
  10
);
