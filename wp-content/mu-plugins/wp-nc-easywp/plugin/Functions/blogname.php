<?php
if (!defined("ABSPATH")) {
  exit();
}

/*
|--------------------------------------------------------------------------
| Blog name utility
|--------------------------------------------------------------------------
|
| Here we're going to add our own filter to the blogname.
|
| 1. we will catch when the blog name is changed. And we will call a webhook
|    we will use the "update_option_blogname" action hook.
|
| 2. we may also check the blogname before it is stored in the database. Not used.
|
*/

/**
 * Fires after the value of a specific option has been successfully updated.
 *
 * The dynamic portion of the hook name, `$option`, refers to the option name.
 *
 * @since 2.0.1
 * @since 4.4.0 The `$option` parameter was added.
 *
 * @param mixed  $old_value The old option value.
 * @param mixed  $value     The new option value.
 * @param string $option    Option name.
 */
add_action(
  'update_option_blogname',
  function ($old_value, $value, $option) {

    if (defined('WP_CLI') && WP_CLI) {
      // WP-CLI is in use, skip the getenv() checks
      return;
    }

    // we will skip to call the webhook if the blogname is "My Site"
    // usually this is the default value and happens when the site is first installed
    if ($old_value == 'My Site') {
      return;
    }

    $jwt_token = easywpJWT()->token;

    if (!$jwt_token) {
      error_log("easywp-plugin (update_option_blogname): JWT_TOKEN not set");
      return;
    }

    $website_webhook_url = getenv("WEBSITE_WEBHOOK_URL");

    if (!$website_webhook_url) {
      error_log("easywp-plugin: WEBSITE_WEBHOOK_URL not set");
      return;
    }

    $data        = ["oldTitle" => $old_value, "newTitle" => $value];
    $data_string = json_encode($data);

    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL            => "{$website_webhook_url}/v1/title",
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
      CURLOPT_POSTFIELDS     => $data_string,
    ]);

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
  },
  10,
  3
);

// We may also filter the blogname before it is stored in the database

/**
 * Filters a specific option before its value is (maybe) serialized and updated.
 *
 * The dynamic portion of the hook name, `$option`, refers to the option name.
 *
 * @since 2.6.0
 * @since 4.4.0 The `$option` parameter was added.
 *
 * @param mixed  $value     The new, unserialize option value.
 * @param mixed  $old_value The old option value.
 * @param string $option    Option name.
 */
add_filter(
  'pre_update_option_blogname',
  function ($value, $old_value, $option) {
    return $value;
  },
  10,
  3
);
