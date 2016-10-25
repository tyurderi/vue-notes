<?php

namespace Favez\Mvc\DI;

class Item
{

    protected $name;

    protected $callable;

    protected $shared;

    public function __construct($name, $callable, $shared)
    {
        $this->name     = $name;
        $this->callable = $callable;
        $this->shared   = $shared;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \Closure
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @return boolean
     */
    public function isShared()
    {
        return $this->shared;
    }

}