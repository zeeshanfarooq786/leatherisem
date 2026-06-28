<?php

namespace WPNCEasyWP\Traits;

if (! defined('ABSPATH')) {
  exit;
}

trait LogTrait
{

  /**
   * Flag to enable or disable the logging
   *
   * @var bool
   */
  protected $logging = true;

  /**
   * Log the message
   *
   * @param string $message The message to log
   * @param mixed $values Optional values to log in json format
   *
   * @return self
   */
  public function log($message, $values = '')
  {
    if ($this->logging) {

      // get the class name which using this trait
      $class = get_class($this);

      // encoding the values via json if exists
      $values = json_encode($values);

      // build the log with CLASSNAME: MESSAGE: VALUES
      $message = $class . ': ' . $message . ":\n" . $values;

      error_log($message);
    }

    return $this;
  }

  /**
   * Log the message
   *
   * @param string $message The message to log
   * @param mixed $values Optional values to log in json format
   *
   * @return self
   */
  public function debug($message, $values = '')
  {
    if ($this->logging) {

      // get the class name which using this trait
      $class = get_class($this);

      // encoding the values via json if exists
      if (!empty($values)) {
        // get the print_r output
        $values = print_r($values, true);
      }

      // build the log with CLASSNAME: MESSAGE: VALUES
      $message = $class . ': ' . $message . ":\n" . $values;

      error_log($message);
    }

    return $this;
  }
}
