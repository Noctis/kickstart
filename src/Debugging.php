<?php

declare(strict_types=1);

namespace Noctis\KickStart;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;

final class Debugging
{
    private static ?Whoops $debugger = null;

    private function __construct()
    {
    }

    public static function on(): void
    {
        self::boot();

        ini_set('display_errors', 'On');
        ini_set('display_startup_errors', 'On');
        ini_set('error_reporting', -1);
        ini_set('log_errors', 'On');

        /** @psalm-suppress PossiblyNullReference */
        self::$debugger->register();
    }

    public static function off(): void
    {
        self::boot();

        ini_set('display_errors', 'Off');
        ini_set('display_startup_errors', 'Off');
        ini_set('error_reporting', E_ALL);
        ini_set('error_reporting', 'On');

        /** @psalm-suppress PossiblyNullReference */
        self::$debugger->unregister();
    }

    private static function boot(): void
    {
        if (self::$debugger === null) {
            self::$debugger = new Whoops();
            self::$debugger->pushHandler(
                new PrettyPageHandler()
            );
        }
    }
}
