<?php

namespace WPNCEasyWP\Support;

class JWT
{

  /**
   * Error message
   *
   * @var string
   */
  public $error = false;

  /**
   * JWT token taken from the environment
   *
   * @var string
   */
  public $token = false;

  /**
   * Website id taken from the JWT token in the environment
   *
   * @var string
   */
  public $websiteId = false;

  public function __construct($jwt_token = false)
  {
    $this->token = empty($jwt_token) ? getenv("JWT_TOKEN") : $jwt_token;

    $this->websiteId = $this->getWebsiteId();
  }

  /**
   * Return the object with the JWT token and the JWT info
   *
   * @param string $jwt_token
   * @return object | false
   */
  private function decode($jwt)
  {
    $tks = explode('.', $jwt);

    if (count($tks) != 3) {
      $this->error = "Wrong number of segments";
      return false;
    }

    list($headb64, $bodyb64, $cryptob64) = $tks;

    if (empty($bodyb64)) {
      $this->error = "Empty body";
      return false;
    }

    $decoded = base64_decode($bodyb64);

    if (!$decoded) {
      $this->error = "Invalid body";
      return false;
    }

    $payload = json_decode($decoded);

    return $payload;
  }

  /**
   * Return the payload from the JWT token in the environment
   *
   * @return object | false
   */
  private function getJwtInfo()
  {
    if (empty($this->token)) {
      $this->error = "JWT_TOKEN not set";
      return false;
    }

    return $this->decode($this->token);
  }

  /**
   * Return the website id from the JWT token in the environment. Use `->appId`
   *
   * @return string | false
   */
  private function getWebsiteId()
  {
    $jwt_info = $this->getJwtInfo();

    if (!$jwt_info) {
      return false;
    }

    // check if $jwt_info has the property appId
    if (property_exists($jwt_info, 'appId')) {
      $this->websiteId = $jwt_info->appId;
      return $this->websiteId;
    }

    if (property_exists($jwt_info, 'id')) {
      $this->websiteId = $jwt_info->id;
      return $this->websiteId;
    }

    return false;
  }
}
