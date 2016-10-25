<?php

namespace Favez\Mvc\Http;

class Cookies implements CookiesInterface
{

    public function set($key, $value, $expire = 0, $path = false, $domain = false, $secure = false, $httpOnly = false)
    {
        return setcookie($key, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    public function get($key, $default = null)
    {
        if (isset($_COOKIE[$key]))
        {
            return $_COOKIE[$key];
        }

        return $default;
    }

    public function reset($key)
    {
        $this->set($key, '', 0);
    }

}