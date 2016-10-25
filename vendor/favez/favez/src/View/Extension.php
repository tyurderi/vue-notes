<?php

namespace Favez\Mvc\View;

use Favez\Mvc\App;

class Extension extends \Twig_Extension
{
    
    /**
     * @var \Favez\Mvc\App
     */
    private $app;

    /**
     * @var \Favez\Mvc\View\View
     */
    private $view;
    
    public function __construct(\Favez\Mvc\App $app, \Favez\Mvc\View\View $view)
    {
        $this->app  = $app;
        $this->view = $view;
    }
    
    public function getName()
    {
        return 'View Extension';
    }
    
    public function getFunctions()
    {
        $functions = ['url', 'resource_url'];
        $result    = [];
        
        foreach($functions as $functionName)
        {
            $result[] = new \Twig_SimpleFunction($functionName, [$this, $functionName . 'Function']);
        }
        
        return $result;
    }
    
    public function urlFunction($path = '')
    {
        return App::instance()->url($path);
    }
    
    public function resource_urlFunction($path)
    {
        return $this->urlFunction($this->view->themePath() . '_resources/' . $path);
    }
    
}