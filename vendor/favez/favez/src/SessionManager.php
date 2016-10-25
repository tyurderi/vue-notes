<?php

namespace Favez\Mvc;

class SessionManager
{
    
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
    }
    
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    public function get($key, $default = null)
    {
        if (isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
        
        return $default;
    }
    
    public function reset($key)
    {
        if (isset($_SESSION[$key]))
        {
            unset($_SESSION[$key]);
        }
    }

    public function destroy()
    {
        return session_destroy();
    }
    
}