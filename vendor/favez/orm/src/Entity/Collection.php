<?php

namespace Favez\ORM\Entity;

use Favez\ORM\EntityInterface;
use Favez\ORM\MetaData;
use Favez\ORM\Utils;

class Collection implements \ArrayAccess, \Iterator
{

    protected $models;

    protected $current;

    protected $count;

    public function __construct($className, $records)
    {
        $models   = [];
        $metaData = MetaData::load($className);
        if($metaData)
        {
            foreach($records as $record)
            {
                /** @var EntityInterface $model */
                $model = new $className();

                self::initializeModel($model, $metaData, $record);

                $models[] = $model;
            }
        }

        $this->models  = $models;
        $this->current = 0;
        $this->count   = count($this->models);
    }

    public static function initializeModel(EntityInterface &$model, $metaData, $record)
    {
        foreach($metaData as $meta)
        {
            $columnName = $meta['name'];
            $methodName = 'set' . Utils::camelize($columnName);

            if(method_exists($model, $methodName))
            {
                $model->{$methodName}($record[$columnName]);
            }
            else if(property_exists($model, $columnName))
            {
                $model->{$columnName} = $record[$columnName];
            }
        }

        $model->initialize();
    }

    public static function create($className, $records)
    {
        return new self($className, $records);
    }

    public function count()
    {
        return $this->count;
    }

    public function delete()
    {
        foreach($this->models as $model)
        {
            $model->delete();
        }

        return true;
    }

    public function first()
    {
        return $this->offsetGet(0);
    }

    public function next()
    {
        ++$this->current;
    }

    public function valid()
    {
        return $this->offsetExists($this->current);
    }

    public function current()
    {
        return $this->offsetGet($this->current);
    }

    public function rewind()
    {
        $this->current = 0;
    }

    public function key()
    {
        return $this->current;
    }

    public function offsetExists($offset)
    {
        return isset($this->models[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ?
            $this->models[$offset] : false;
    }

    public function offsetSet($offset, $value)
    {
        $this->models[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if($this->offsetExists($offset))
        {
            unset($this->models[$offset]);
        }
    }

}