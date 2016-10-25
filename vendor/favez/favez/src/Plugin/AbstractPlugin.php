<?php

namespace Favez\Mvc\Plugin;

abstract class AbstractPlugin extends \Favez\Mvc\DI\Injectable
{

    final public function __construct() { }

    abstract function initialize();

}