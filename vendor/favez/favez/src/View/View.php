<?php

namespace Favez\Mvc\View;

use Favez\Mvc\DI\Injectable;
use Slim\Container;

class View extends Injectable
{
    
    const DEFAULT_THEME = 'default';
    
    /**
     * @var \Twig_Environment
     */
    protected $engine;
    
    /**
     * @var \Twig_Loader_Filesystem
     */
    protected $loader;

    /**
     * @var array
     */
    protected $context;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $theme;
    
    public function __construct()
    {
        parent::__construct();

        if (!$this->dispatcher()->dispatched())
        {
            trigger_error('The view component was created before a controller was dispatched so the view component 
            probably doesn\'t work as expected.', E_USER_NOTICE);
        }
        
        $this->config  = new Container($this->app()->config('view'));
        $this->theme   = $this->config->has('theme') ? $this->config->get('theme') : self::DEFAULT_THEME;
        $this->context = [];

        $this->loader = new \Twig_Loader_Filesystem();
        $this->engine   = new \Twig_Environment($this->loader, [
            'autoescape'  => false,
            'auto_reload' => true,
            'cache'       => $this->dir() . $this->config->get('cache_path')
        ]);

        $this->updatePaths();
        
        $this->events()->publish('core.view.init', [$this]);
        
        $this->controllerName = $this->dispatcher()->controllerName();
        $this->actionName     = $this->dispatcher()->actionName();
    }
    
    public function config()
    {
        return $this->config;
    }

    public function engine()
    {
        return $this->engine;
    }
    
    public function loader()
    {
        return $this->loader;
    }
    
    public function updatePaths($overwrite = true)
    {
        if ($overwrite)
        {
            $this->loader->setPaths([]);
        }
        
        $this->loader->addPath($this->dir() . $this->getThemePath(), 'default');
        $this->loader->addPath($this->dir() . $this->getThemePath($this->theme));
        $this->loader->addPath($this->dir() . $this->getThemePath($this->theme), 'parent');
    }
    
    public function exists($name)
    {
        return $this->loader->exists($this->getName($name));
    }
    
    public function render($name, $context = [])
    {
        $name = $this->getName($name);
        
        $this->events()->publish('core.view.render', [ $this, $name ]);

        return $this->engine()->render($name, array_merge($this->context, $context));
    }
    
    public function __set($name, $value)
    {
        $this->assign($name, $value);
    }
    
    public function __get($name)
    {
        return $this->getAssign($name);
    }
    
    public function assign($name, $value = [])
    {
        if(is_array($name))
        {
            $this->context = array_merge($this->context, $name);
        }
        else
        {
            $this->context[$name] = $value;
        }
        
        return $this;
    }
    
    public function getAssign($name, $default = null)
    {
        if (isset($this->context[$name]))
        {
            return $this->context[$name];
        }
        
        return $default;
    }
    
    public function clearAssign($name = null)
    {
        if($name === null)
        {
            $this->context = [];
        }
        else
        {
            if(isset($this->context[$name]))
            {
                unset($this->context[$name]);
            }
        }
        
        return $this;
    }
    
    public function themePath()
    {
        return $this->getThemePath($this->theme);
    }
    
    protected function getThemePath($themeName = self::DEFAULT_THEME)
    {
        return $this->config()->get('theme_path') . $this->dispatcher()->moduleName() . '/' . $themeName . '/';
    }

    protected function getName($name)
    {
        if (strrpos($name, '.twig') !== strlen($name)-5)
        {
            $name .= '.twig';
        }
        
        return $name;
    }
    
}