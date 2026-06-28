<?php
if (!defined("ABSPATH")) {
    exit();
}

/*
|--------------------------------------------------------------------------
| Checker options
|--------------------------------------------------------------------------
|
| Here you'll find the setting for the main checker feature.
|
*/

return [

    // list of "bad" banned plugins
    // to create this list, run "$ php bones banned:plugins --file banned-plugins.json" from console
    'plugins'            => 'banned-plugins.json',

    // remove the "activate" link in the plugins page in admin area.
    'removeActivateLink' => true,

];
