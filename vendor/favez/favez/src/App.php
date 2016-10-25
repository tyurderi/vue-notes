<?php

namespace Favez\Mvc;

use Favez\Mvc\Exception\InvalidPluginException;
use Favez\Mvc\Exception\InvalidTargetException;

/**
 * Class App
 *
 * @package Favez\Mvc
 *
 * @method static App instance(array $configuration = [])
 */
class App extends \Slim\App
{
    use Singleton;
    use Config { config as getConfig; }
    
    private static $di;

    /**
     * @return Modules
     */
    public static function modules()
    {
        return Modules::instance();
    }
    
    /**
     * @return DI\Container
     */
    public static function di()
    {
        if (self::$di === null)
        {
            self::$di = new DI\Container();
        }
        
        return self::$di;
    }

    public function __construct($container = null)
    {
        parent::__construct($container);

        $this->loadDI();
        $this->loadPlugins();
        
        self::modules()->events()->publish('core.app.init');
    }

    public function config($key, $default = null)
    {
        return $this->getConfig($key, $default, $this->getContainer()->get('config'));
    }

    public function run($silent = false)
    {
        self::modules()->events()->publish('core.app.run');

        return parent::run($silent);
    }

    public function map(array $methods, $pattern, $target)
    {
        if (is_string($target))
        {
            parent::map($methods, $pattern, function() use ($target) {
                return self::modules()->dispatcher()->dispatch($target, func_get_args());
            });
        }
        else if(is_callable($target))
        {
            parent::map($methods, $pattern, $target);
        }
        else
        {
            throw new InvalidTargetException();
        }
    }

    public function isDebug()
    {
        return $this->config('debug') === true;
    }

    public function url($path)
    {
        return $this->modules()->request()->getUri()->getBaseUrl() . '/' . $path;
    }

    protected function loadDI()
    {
        self::di()->registerShared([
            'dir'        => function() {
                return $this->config('app.path');
            },
            'cache'      => function() {
                return new \Stash\Pool(new \Stash\Driver\FileSystem([
                    'path'            => $this->config('app.path') . $this->config('app.cache_path'),
                    'filePermissions' => 0777,
                    'dirPermissions'  => 0777
                ]));
            },
            'db'         => function() {
                return new Database\Database($this);
            },
            'view'       => function() {
                $view = new View\View();

                if ($this->isDebug())
                {
                    $view->engine()->enableDebug();
                    $view->engine()->addExtension(new \Twig_Extension_Debug());
                }

                $view->engine()->addExtension(new View\Extension($this, $view));

                return $view;
            },
            'models'     => function() {
                return \Favez\ORM\EntityManager::instance(
                    self::modules()->db()->PDO()
                );
            },
            'json'       => function() {
                return new Http\Response\Json();
            },
            'events'     => function() {
                return new Event\Manager();
            },
            'plugins'    => function() {
                return new Plugin\Manager($this);
            },
            'dispatcher' => function() {
                return new Dispatcher();
            },
            'session'    => function() {
                return new SessionManager();
            },
            'cookies'    => function() {
                return new Http\Cookies();
            }
        ]);

        self::di()->register([
            'app'     => function() {
                return $this;
            },
            'request' => function() {
                return $this->getContainer()->get('request');
            },
            'response' => function() {
                return $this->getContainer()->get('response');
            }
        ]);
    }

    private function loadPlugins()
    {
        $pluginManager = self::modules()->plugins();
        $plugins       = $this->config('plugin_instances', []);

        foreach ($plugins as $plugin)
        {
            if (!($plugin instanceof \Favez\Mvc\Plugin\AbstractPlugin))
            {
                throw new InvalidPluginException();
            }

            $pluginManager->initialize($plugin);
        }
    }

}