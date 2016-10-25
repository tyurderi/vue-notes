<?php

namespace Favez\ORM\Entity;

use Favez\ORM\EntityManager;
use Favez\ORM\Entity\Collection as EntityCollection;

/**
 * Class QueryBuilder
 *
 * @method QueryBuilder groupBy($column)
 * @method QueryBuilder orderBy($column)
 * @method QueryBuilder having($column)
 * @method QueryBuilder limit($limit)
 * @method QueryBuilder offset($offset)
 * @method QueryBuilder select($column)
 * @method QueryBuilder where($conditions, $parameters = [])
 *
 * @method int count()
 */
class QueryBuilder
{

    protected $query        = null;
    
    protected $targetEntity = '';
    
    protected $targetSource = '';

    public function __construct($targetEntity)
    {
        $this->targetEntity = $targetEntity;
        $this->targetSource = EntityManager::instance()->resolve($targetEntity);
        $this->query        = EntityManager::instance()->pdo()->from($this->targetSource);
    }

    public function totalCount()
    {
        return EntityManager::instance()->pdo()->from($this->targetSource)->count();
    }

    public function __call($name, $arguments)
    {
        $methods = ['groupBy', 'orderBy', 'having', 'limit', 'offset', 'select', 'where', 'count'];

        if (in_array($name, $methods))
        {
            return call_user_func_array([$this->query, $name], $arguments);
        }

        return parent::__call($name, $arguments);
    }

    public function fetch()
    {
        return EntityCollection::create($this->targetEntity, $this->query->fetchAll());
    }
    
    public function fetchArrayResult()
    {
        return $this->query->fetchAll();
    }

    /**
     * @deprecated Use EntityManager::newPaginator instead.
     */
    public function toPaginator()
    {
        throw new \BadMethodCallException();
    }

}