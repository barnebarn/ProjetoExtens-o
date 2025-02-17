<?php

namespace Core;

class Config
{
    private static $config;

    public static function get($key)
    {
        if (!self::$config) {
            self::$config = require __DIR__ . "/../../config/config.php";
        }

        return self::$config[$key] ?? null;
    }
}
