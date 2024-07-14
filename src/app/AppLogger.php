<?php

namespace StreamData\app;

use Monolog\Handler\StreamHandler;
use Monolog\{Logger, Level};

class AppLogger
{

    static function get(string $context): Logger
    {
        $logger = new Logger($context);
        $logger->pushHandler(new StreamHandler('logs/combined.log', Level::Debug));
        return $logger;
    }
}
