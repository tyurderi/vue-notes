<?php

namespace Favez\ORM\Relation;

use Favez\ORM\EntityManager;

class OneToMany extends RelationAbstract implements RelationInterface
{

    public function fetch()
    {
        if (!isset($this->referenceModel))
        {
            $repository = EntityManager::instance()->repository($this->referenceClass);
            $result     = $repository->findBy([
                $this->referenceKey => $this->model->{$this->modelKey}
            ]);

            $this->referenceModel = $result;
        }

        return $this->referenceModel;
    }

}