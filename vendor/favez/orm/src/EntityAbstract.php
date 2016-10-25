<?php

namespace Favez\ORM;

use Favez\ORM\Relation\RelationInterface;
use Favez\ORM\Entity\Collection as EntityCollection;
use Favez\ORM\Exception\PropertyNotFoundException;

abstract class EntityAbstract implements EntityInterface
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var RelationInterface[]
     */
    private $_relations;
    
    /**
     * @var array
     */
    private static $_columns;
    
    /**
     * @param array $data
     */
    final public function __construct($data = [])
    {
        $this->set($data);
    }

    public function initialize()
    {

    }
    
    public static function repository()
    {
        return EntityManager::instance()->repository(static::class);
    }

    /**
     * {@inheritdoc}
     */
    final public function belongsTo($localKey, $referenceClass, $referenceKey = 'id')
    {
        $relation           = new Relation\ManyToOne($this, $localKey, $referenceClass, $referenceKey);
        $this->_relations[] = $relation;

        return $relation;
    }

    /**
     * {@inheritdoc}
     */
    final public function hasMany($referenceClass, $referenceKey, $localKey = 'id')
    {
        $relation           = new Relation\OneToMany($this, $localKey, $referenceClass, $referenceKey);
        $this->_relations[] = $relation;

        return $relation;
    }

    /**
     * {@inheritdoc}
     */
    final public function hasOne($referenceClass, $referenceKey, $localKey = 'id')
    {
        $relation           = new Relation\OneToOne($this, $localKey, $referenceClass, $referenceKey);
        $this->_relations[] = $relation;

        return $relation;
    }

    /**
     * {@inheritdoc}
     */
    final public function getRelated($referenceClass)
    {
        foreach ($this->_relations as $relation)
        {
            if ($relation->has($referenceClass))
            {
                return $relation->fetch();
            }
        }

        return trigger_error(sprintf('Trying to access unknown relation: %s.', $referenceClass));
    }

    final public function __get($referenceClass)
    {
        return $this->getRelated($referenceClass);
    }

    /**
     * {@inheritdoc}
     */
    final public function save()
    {
        $values = $this->get(null, ['id']);
        $query  = null;

        if(empty($this->id))
        {
            $this->id = EntityManager::instance()->pdo()->insertInto(
                $this->getSource(),
                $values
            )->execute();

            return $this->id != 0;
        }
        else
        {
            return EntityManager::instance()->pdo()->update(
                $this->getSource(),
                $values,
                $this->id
            )->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    final public function delete()
    {
        return EntityManager::instance()->pdo()->delete($this->getSource(), $this->id)->execute();
    }

    final public function refresh()
    {
        $metaData = $this->getMeta();
        $data     = EntityManager::instance()->pdo()->from($this->getSource(), $this->id)->fetch();

        EntityCollection::initializeModel($this, $metaData, $data);
    }
    
    /**
     * {@inheritdoc}
     */
    final public function set($param, $value = null)
    {
        if (is_array($param))
        {
            foreach ($param as $key => $value)
            {
                $this->set($key, $value);
            }
        }
        else if (property_exists($this, $param))
        {
            $this->{$param} = $value;
        }
        else
        {
            throw new PropertyNotFoundException();
        }
        
        return $this;
    }
    
    /**
     * @param null|string @param
     * @return array|mixed
     * @throws PropertyNotFoundException
     */
    final public function get($param = null, $filter = [])
    {
        if ($param === null)
        {
            $metaData = $this->getMeta();
            $values   = [];
            
            foreach($metaData as $meta)
            {
                if (!in_array($meta['name'], $filter))
                {
                    $values[$meta['name']] = $this->{$meta['name']};
                }
            }
            
            return $values;
        }
        else if(property_exists($this, $param))
        {
            return $this->{$param};
        }
        
        throw new PropertyNotFoundException();
    }
    
    /**
     * Loads metadata for the model and caches it.
     *
     * @return array
     */
    private function getMeta()
    {
        if (!isset(self::$_columns[static::class]))
        {
            self::$_columns[static::class] = MetaData::load($this);
        }
        
        return self::$_columns[static::class];
    }

}