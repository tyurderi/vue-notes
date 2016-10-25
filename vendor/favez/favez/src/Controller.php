<?php

namespace Favez\Mvc;

abstract class Controller extends DI\Injectable
{

    protected $moduleName     = '';

    protected $controllerName = '';

    public function __construct()
    {
        parent::__construct();

        $this->moduleName     = $this->dispatcher()->moduleName();
        $this->controllerName = $this->dispatcher()->controllerName();
    }

    public function forward($action = 'index', $controller = null, $module = null, $params = [], $update = false)
    {
        $module     = $module ? $module : $this->moduleName;
        $controller = $controller ? $controller : $this->controllerName;

        return $this->dispatcher()->forward($module, $controller, $action, $params, $update);
    }

}