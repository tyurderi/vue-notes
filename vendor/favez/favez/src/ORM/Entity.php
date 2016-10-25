<?php

namespace Favez\Mvc\ORM;

use Favez\Mvc\App;
use Favez\ORM\EntityAbstract;

abstract class Entity extends EntityAbstract
{
    
    public static function repository()
    {
        return App::modules()->models()->repository(static::class);
    }
    
}
