<?php

namespace Favez\Mvc\DI;

use Favez\Mvc\App;

/**
 * Class Injectable
 *
 * @method string                        dir()
 * @method \Stash\Pool                   cache()
 * @method \Favez\Mvc\Database\Database  db()
 * @method \Favez\Mvc\View\View          view(array $configuration = [])
 * @method \Favez\ORM\EntityManager      models()
 * @method \Favez\Mvc\Http\Response\Json json()
 * @method \Favez\Mvc\Event\Manager      events()
 * @method \Favez\Mvc\Plugin\Manager     plugins()
 * @method \Favez\Mvc\Dispatcher         dispatcher()
 * @method \Favez\Mvc\SessionManager     session()
 * @method \Favez\Mvc\Http\Cookies       cookies()
 *
 * @method \Favez\Mvc\App                app()
 * @method \Slim\Http\Request            request()
 * @method \Slim\Http\Response           response()
 */
abstract class Injectable
{

    /**
     * @var Container
     */
    private $container = null;

    public function __construct()
    {
        $this->initializeContainer();
    }

    public function setContainer($container)
    {
        $this->container = $container;

        return $this;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function __call($name, $arguments)
    {
        return $this->container->get($name, $arguments);
    }

    protected function initializeContainer()
    {
        $this->container = App::di();
    }

}