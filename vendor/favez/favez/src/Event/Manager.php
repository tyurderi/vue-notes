<?php

namespace Favez\Mvc\Event;

class Manager
{

    protected $listeners = [];

    public function subscribe($name, $callable)
    {
        if (is_callable($callable))
        {
            if (!isset($this->listeners[$name]))
            {
                $this->listeners[$name] = [];
            }

            $this->listeners[$name][] = $callable;
            
            return true;
        }

        return false;
    }

    public function publish($name, $arguments = [])
    {
        if (isset($this->listeners[$name]))
        {
            $result = true;

            foreach ($this->listeners[$name] as $listener)
            {
                if (call_user_func_array($listener, $arguments) === false)
                {
                    $result = false;
                }
            }

            return $result;
        }

        return true;
    }

    public function listeners($name = null)
    {
        if (empty($name))
        {
            return $this->listeners;
        }
        else if(isset($this->listeners[$name]))
        {
            return $this->listeners[$name];
        }
        else
        {
            return false;
        }
    }

}