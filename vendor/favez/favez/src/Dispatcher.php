<?php

namespace Favez\Mvc;

use Favez\Mvc\DI\Injectable;

class Dispatcher extends Injectable
{

    protected $moduleName     = '';

    protected $controllerName = '';

    protected $actionName     = '';

    protected $dispatched     = false;

    public function forward($module, $controller, $action = 'index', $params = [], $update)
    {
        if ($update && $this->moduleName !== $module)
        {
            $this->moduleName = $module;
            
            $this->view()->updatePaths();
        }
        
        return $this->dispatch($module . ':' . $controller . ':' . $action, [
            $this->request(),
            $this->response(),
            $params
        ]);
    }

    public function dispatch($target, $params)
    {
        list($controllerClass, $method) = $this->parseTarget($target, $params[2]);

        $this->dispatched = true;
        
        $this->events()->publish('controller.resolve.' . $this->moduleName . '.' . $this->controllerName);

        if (class_exists($controllerClass) && method_exists($controllerClass, $method))
        {
            $this->events()->publish('controller.pre_dispatch.' . $this->moduleName);
            $this->events()->publish('controller.pre_dispatch.' . $this->moduleName . '.' . $this->controllerName);

            /** @var Controller $controller */
            $controller = new $controllerClass();
            $result     = null;

            if ($this->events()->publish(
                'controller.on_dispatch.' . $this->moduleName . '.' . $this->controllerName . '.' . $this->actionName
            ))
            {
                $result = call_user_func_array(
                    [$controller, $method],
                    array_splice($params, 2)
                );
            }

            $this->events()->publish('controller.post_dispatch.' . $this->moduleName . '.' . $this->controllerName);
            $this->events()->publish('controller.post_dispatch.' . $this->moduleName);

            return $result;
        }

        return $this->app()->notFoundHandler($this->request(), $this->response());
    }

    public function dispatched()
    {
        return $this->dispatched;
    }

    public function moduleName()
    {
        return $this->moduleName;
    }

    public function controllerName()
    {
        return $this->controllerName;
    }

    public function actionName()
    {
        return $this->actionName;
    }

    protected function parseTarget($target, $params)
    {
        list($module, $controller, $action) = explode(':', $this->applyParams($target, $params));

        $this->moduleName     = $module;
        $this->controllerName = $controller;
        $this->actionName     = $action;

        $controllerConfig = $this->app()->config('modules.' . $module . '.controller');
        $controllerClass  = sprintf('%s%s%s',
            $controllerConfig['namespace'],
            $controller,
            $controllerConfig['class_suffix']
        );

        $methodName = sprintf('%s%s', $action, $controllerConfig['method_suffix']);

        return [$controllerClass, $methodName];
    }

    protected function applyParams($target, $params)
    {
        $params = array_merge([
            'module'     => 'frontend',
            'controller' => 'index',
            'action'     => 'index'
        ], $params);

        $params['controller'] = ucfirst($params['controller']);

        foreach ($params as $key => $value)
        {
            $target = str_replace('{' . $key . '}', $value, $target);
        }

        return $target;
    }

}