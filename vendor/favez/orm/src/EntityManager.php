<?php

namespace Favez\ORM;

use Favez\ORM\Entity\Paginator;
use Favez\ORM\Entity\QueryBuilder;
use Favez\ORM\Entity\Repository;
use Favez\ORM\Exception\EntityNotFoundException;

class EntityManager
{

    protected static $instance = null;

    protected $pdo   = null;
    
    protected $cache = null;

    public static function instance(\PDO $pdo = null, \Stash\Pool $cache = null)
    {
        if (self::$instance === null)
        {
            self::$instance = new self(new \FluentPDO($pdo), $cache);
        }

        return self::$instance;
    }

    private function __construct(\FluentPDO $pdo, \Stash\Pool $cache = null)
    {
        $this->pdo   = $pdo;
        $this->cache = $cache;
    }
    
    public function pdo()
    {
        return $this->pdo;
    }
    
    public function cache()
    {
        return $this->cache;
    }

    /**
     * @param string|EntityInterface $className
     * @return Repository
     * @throws EntityNotFoundException
     */
    public function repository($className = '')
    {
        if(empty($className))
        {
            $className = get_called_class();
        }

        if($this->isValid($className))
        {
            $tableName = $className::getSource();

            return Repository::create($className, $tableName);
        }

        throw new EntityNotFoundException();
    }

    public function newBuilder($targetEntity)
    {
        return new QueryBuilder($targetEntity);
    }

    /**
     * @deprecated Use EntityManager::newBuilder instead
     *
     * @param $targetEntity
     * @return QueryBuilder
     */
    public function queryBuilder($targetEntity)
    {
        return $this->newBuilder($targetEntity);
    }

    public function newPaginator($options)
    {
        return new Paginator($options);
    }

    /**
     * @param string|EntityInterface $className
     * @return null
     */
    public function resolve($className)
    {
        if ($this->isValid($className))
        {
            return $className::getSource();
        }

        return null;
    }

    public function isValid($className)
    {
        return $className instanceof EntityInterface
            || class_exists($className) && in_array('Favez\\ORM\\EntityInterface', class_implements($className));
    }

    public function insert($className, $data)
    {
        if ($table = $this->resolve($className))
        {
            return $this->pdo->insertInto($table, $data);
        }

        throw new EntityNotFoundException();
    }

    public function update($className, $data, $conditions = array())
    {
        if ($table = $this->resolve($className))
        {
            return $this->pdo->update($table, $data)->where($conditions);
        }

        throw new EntityNotFoundException();
    }

    public function delete($className, $conditions = array())
    {
        if ($table = $this->resolve($className))
        {
            return $this->pdo->delete($table)->where($conditions);
        }

        throw new EntityNotFoundException();
    }

}