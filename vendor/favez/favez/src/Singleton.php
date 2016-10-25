<?php

namespace Favez\Mvc;

trait Singleton
{

    protected static $instance = null;

    public static function instance()
    {
        if(self::$instance === null)
        {
            $reflection = new \ReflectionClass(__CLASS__);
            self::$instance = $reflection->newInstanceArgs(func_get_args());
        }

        return self::$instance;
    }

}