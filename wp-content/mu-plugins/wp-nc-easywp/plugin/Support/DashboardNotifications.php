<?php

namespace WPNCEasyWP\Support;

/**
 * Class to manage dashboard notifications
 *
 * @since 1.5.0
 */
class DashboardNotifications
{

  /**
   * Public sequoia url
   * @var string
   */
  private $_sequoia_url;

  /**
   * Notification api
   * @var string
   */
  private $_api;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->_sequoia_url = getenv("WEBSITE_WEBHOOK_URL");
    $this->_api = "/v1/sequoia-app-webhook/api/v1alpha2/notifications/easywp/";
  }

  /**
   * Get the singleton instance of the class.
   *
   * @return object
   */
  public static function instance()
  {
    static $instance = null;
    if (null === $instance) {
      $instance = new static();
    }
    return $instance;
  }

  /**
   * Create a notification.
   *
   * @param array $args Notification data:
   *  @type string $category
   *  @type string $title
   *  @type string $text
   *  @type string $start_display_at
   *  @type string $end_date
   * @return void
   */
  public function create($args)
  {
    if (empty($this->_sequoia_url)) {
      error_log("easywp-plugin: DashboardNotifications WEBSITE_WEBHOOK_URL not set");
      return;
    }

    $default = [
      "category" => "WordPress Updates",
      "title" => "Website auto-updates completed successfully",
      "text" => "",
      "start_display_at" => date("Y-m-d\TH:i:s\Z"),
      // end_date = start_display_at + 7 days
      "end_date" => date("Y-m-d\TH:i:s\Z", strtotime("+7 days"))
    ];

    $args = wp_parse_args($args, $default);

    $website_id = easywpJWT()->websiteId;
    $token = easywpJWT()->token;

    $args = json_encode($args);
    $response = wp_remote_post($this->_sequoia_url . $this->_api . $website_id, [
      'method' => 'POST',
      'headers' => [
        'Accept'        => '*/*',
        'Authorization' => $token,
        'Content-Type'  => 'application/json'
      ],
      "body" => $args
    ]);

    // Check for errors
    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      echo "Something went wrong: $error_message";
      error_log(print_r($error_message, true));
    } else {
      // Comment out the below line to Process the response
      // $response_body = wp_remote_retrieve_body($response);
    }
  }

  /**
   * Create a notification for auto update completed successfully.
   *
   * @return void
   */
  public function automaticUpdateSuccessfully()
  {
    $this->create([
      'category' => "WordPress Updates",
      "title" => "WordPress auto-updates completed successfully"
    ]);
  }

  /**
   * Create a notification for auto update failed.
   *
   * @return void
   */
  public function automaticUpdateFailed($count = 1)
  {
    $this->create([
      'category' => "WordPress Updates",
      "title" => "There $count failed auto update(s)"
    ]);
  }
}
