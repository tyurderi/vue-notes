<?php

namespace Favez\ORM\Relation;

use Favez\ORM\EntityInterface;
use Favez\ORM\Entity\Collection as EntityCollection;

abstract class RelationAbstract implements RelationInterface
{

    /**
     * @var EntityInterface
     */
    protected $model;

    /**
     * @var string
     */
    protected $modelKey;

    /**
     * @var string
     */
    protected $referenceClass;

    /**
     * @var string
     */
    protected $referenceKey;

    /**
     * @var EntityCollection|EntityInterface
     */
    protected $referenceModel;

    /**
     * @var string
     */
    protected $aliasName;

    public function __construct(EntityInterface $model, $modelKey, $referenceClass, $referenceKey)
    {
        $this->model          = $model;
        $this->modelKey       = $modelKey;
        $this->referenceClass = $referenceClass;
        $this->referenceKey   = $referenceKey;
        $this->aliasName      = '';
        $this->referenceModel = null;
    }

    public function setName($name)
    {
        $this->aliasName = $name;
    }

    public function has($referenceClass)
    {
        return $this->referenceClass == $referenceClass || $this->aliasName == $referenceClass;
    }

}