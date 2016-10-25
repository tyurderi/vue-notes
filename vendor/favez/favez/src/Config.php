<?php

namespace Favez\Mvc;

trait Config
{

    protected function config($key, $default = null, $config = [])
    {
        $keys = explode('.', $key);

        foreach ($keys as $key)
        {
            if (isset($config[$key]))
            {
                $config = $config[$key];
            }
            else
            {
                $config = $default;
                break;
            }
        }

        return $config;
    }
    
}