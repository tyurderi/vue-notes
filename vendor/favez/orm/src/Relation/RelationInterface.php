<?php

namespace Favez\ORM\Relation;

use Favez\ORM\EntityInterface;
use Favez\ORM\Entity\Collection as EntityCollection;

interface RelationInterface
{

    /**
     * RelationInterface constructor.
     *
     * @param EntityInterface $model
     * @param string          $modelKey
     * @param string          $referenceClass
     * @param string          $referenceKey
     */
    public function __construct(EntityInterface $model, $modelKey, $referenceClass, $referenceKey);

    /**
     * Checks if this relation is related to $referenceClass
     *
     * @param string $referenceClass
     *
     * @return boolean
     */
    public function has($referenceClass);

    /**
     * Fetches the related models.
     *
     * @return EntityCollection|EntityInterface
     */
    public function fetch();

    /**
     * Sets an alias for this relation.
     *
     * As example the relation can be accessed by "ModelInterface::getRelated('name')" instead of writing the
     * full qualified class name.
     *
     * @param string $name
     *
     * @return RelationInterface
     */
    public function setName($name);
    
}