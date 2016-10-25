<?php

namespace Favez\ORM\Entity;

use Favez\ORM\EntityInterface;
use Favez\ORM\Entity\Collection as EntityCollection;
use Favez\ORM\EntityManager;

class Repository
{

    protected $className;

    protected $tableName;

    public static function create($className, $tableName)
    {
        $repository = new self();
        $repository->className = $className;
        $repository->tableName = $tableName;

        return $repository;
    }

    /**
     * @return EntityInterface
     */
    public function findFirst()
    {
        $query = $this->getQuery();

        return EntityCollection::create($this->className, [$query->fetch()])->first();
    }

    /**
     * @param int $primaryKey
     *
     * @return EntityInterface
     */
    public function findByID($primaryKey)
    {
        return $this->findOneBy([
            'id' => $primaryKey
        ]);
    }

    /**
     * @return EntityCollection
     */
    public function findAll()
    {
        return $this->findBy([]);
    }

    /**
     * @param array $criteria
     *
     * @return EntityCollection
     */
    public function findBy(array $criteria)
    {
        return EntityCollection::create(
            $this->className,
            $this->getQuery()->where($criteria)->fetchAll()
        );
    }

    /**
     * @param array $criteria
     *
     * @return EntityInterface
     */
    public function findOneBy(array $criteria)
    {
        return $this->findBy($criteria)->first();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->getQuery()->count();
    }

    /**
     * @return \SelectQuery
     */
    public function getQuery()
    {
        return EntityManager::instance()->pdo()->from($this->tableName);
    }

}