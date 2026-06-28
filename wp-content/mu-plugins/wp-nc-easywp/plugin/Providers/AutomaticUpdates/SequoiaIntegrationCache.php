<?php

namespace WPNCEasyWP\Providers\AutomaticUpdates;

class SequoiaIntegrationCache
{

  public static function init()
  {
    return new self();
  }

  /**
   * Flush/Delete Sequoia API cache
   *
   */
  public function flush()
  {
    $jwt_token = getenv("JWT_TOKEN");

    if (!$jwt_token) {
      error_log("easywp-plugin: JWT_TOKEN not set");
      return;
    }

    // TODO: currently it's hardcoded, but it should be set in the environment variable
    $website_webhook_url = wpbones_flags()->flags('autoupdates.cache.url', 'http://sequoia-integration-public.sequoia-integration.svc.cluster.local:8080/api');

    if (!$website_webhook_url) {
      error_log("easywp-plugin: WEBSITE_WEBHOOK_URL not set");
      return;
    }

    $api_route = wpbones_flags()->flags('autoupdates.cache.route', '/v1alpha1/cache');
    $timeout = intval(wpbones_flags()->flags('autoupdates.cache.timeout', 0));

    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => "{$website_webhook_url}{$api_route}",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => $timeout,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "DELETE",
      CURLOPT_HTTPHEADER => ["Content-Type: application/json", "authorization: " . $jwt_token],
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
  }
}
