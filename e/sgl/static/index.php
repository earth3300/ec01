<?php

class Hello
{
    private static $greeting = 'Hello';
    private static $initialized = false;

    private static function initialize()
    {
        if (self::$initialized)
            return;

        self::$greeting .= ' There!';
        self::$initialized = true;
    }

    public static function greet()
    {
        self::initialize();
        echo self::$greeting;
    }
}

Hello::greet(); // Hello There!
