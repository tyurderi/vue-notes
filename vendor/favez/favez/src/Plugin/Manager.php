<?php

namespace Favez\Mvc\Plugin;

use Favez\Mvc\App;

class Manager
{

    /**
     * @var App
     */
    protected $app = null;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function initialize(AbstractPlugin $plugin)
    {
        $plugin->setContainer($this->app->di());
        $plugin->initialize();
    }

}