<?php

namespace WPNCEasyWP\Providers\Varnish;

class VarnishDebug
{

  protected $url;

  protected $response;
  protected $headers;

  public function __construct()
  {
    $this->url = home_url();

    $this->response = $this->remoteGet($this->url);

    $this->headers = wp_remote_retrieve_headers($this->response);
  }

  protected function remoteGet($url)
  {
    // Make sure it's not a stupid URL
    $url = esc_url($url);

    $args = [
      'headers' => [
        'timeout' => 30,
        'redirection' => 10,
      ],
    ];

    $response = wp_remote_get($url, $args);

    return $response;
  }

  protected function getRemoteIp()
  {
    $headers = $this->headers;

    if (isset($headers['X-Forwarded-For']) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
      $remote_ip = $headers['X-Forwarded-For'];
    } elseif (
      isset($headers['HTTP_X_FORWARDED_FOR']) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
    ) {
      $remote_ip = $headers['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($headers['Server']) && strpos($headers['Server'], 'cloudflare') !== false) {
      $remote_ip = 'cloudflare';
    } else {
      $remote_ip = false;
    }

    return $remote_ip;
  }
}
