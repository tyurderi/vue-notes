<?php

namespace Notes\Models;

use Favez\Mvc\ORM\Entity;

class Note extends Entity
{

    public $id;

    public $title;

    public $text;

    public $changed;

    public $created;

    public $archived;

    public static function getSource()
    {
        return 'notes';
    }

}