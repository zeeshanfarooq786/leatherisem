<?php

namespace WPNCEasyWP\Console;

use WPNCEasyWP\WPBones\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // extends bones console with "banned:plugins" command
        'WPNCEasyWP\Console\Commands\BannedPlugins',
    ];
}
