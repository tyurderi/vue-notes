<?php

namespace Favez\Mvc\Http;

interface CookiesInterface
{

    public function set($key, $value, $expire = 0, $path = false, $domain = false, $secure = false, $httpOnly = false);

    public function get($key, $default = null);

    public function reset($key);

}