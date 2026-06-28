<?php
if (!defined("ABSPATH")) {
    exit();
}
/*
|--------------------------------------------------------------------------
| Heartbeat options
|--------------------------------------------------------------------------
|
| Here you'll find the setting to handle the heartbeat in WordPress.
|
| The 'interval' option is the time in seconds between two heartbeat
| available intervals: 5, 15, 30, 60, and 120.
|
*/

return [
  'disabled' => false,
  'interval' => 60,
];
