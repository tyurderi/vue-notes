<?php

namespace Favez\Mvc\DI;

class Container
{

    /**
     * @var Item[]
     */
    protected $items = [];

    /**
     * @var mixed[]
     */
    protected $values = [];

    public function register($name, $callable = null, $shared = false)
    {
        if (is_array($name))
        {
            foreach($name as $key => $value)
            {
                $this->register($key, $value, $shared);
            }

            return true;
        }
        else if (!isset($this->items[$name]) && is_callable($callable))
        {
            $this->items[$name] = new Item($name, $callable, $shared);

            return true;
        }

        return false;
    }

    public function registerShared($name, $callable = null)
    {
        return $this->register($name, $callable, true);
    }

    public function get($name, $arguments = [])
    {
        if (isset($this->items[$name]))
        {
            $item = $this->items[$name];

            if ($item->isShared())
            {
                if (!isset($this->values[$name]))
                {
                    $this->values[$name] = call_user_func_array($item->getCallable(), $arguments);
                }
                
                return $this->values[$name];
            }
            else
            {
                return call_user_func_array($item->getCallable(), $arguments);
            }
        }

        return false;
    }

}