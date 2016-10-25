<?php

namespace Favez\ORM;

use Favez\ORM\Entity\Collection as EntityCollection;
use Favez\ORM\Exception\PropertyNotFoundException;
use Favez\ORM\Entity\Repository;

interface EntityInterface
{

    /**
     * Gets called then the model was initialized.
     * 
     * @return mixed
     */
    public function initialize();

    /**
     * Retrieves the table of the model.
     *
     * @return string
     */
    public static function getSource();

    /**
     * Creates or updates the model entity to database.
     *
     * @return boolean
     */
    public function save();

    /**
     * Deletes the model entity from database.
     * 
     * @return boolean
     */
    public function delete();

    /**
     * Refreshes the model data.
     *
     * @return boolean
     */
    public function refresh();

    /**
     * Defines a many to one relation.
     * Means that this model belongs/is associated to another model.
     *
     * $localKey       = 'model_id';
     * $referenceClass = 'Model';
     * $referenceKey   = 'id';
     *
     * @param string $localKey
     * @param string $referenceClass
     * @param string $referenceKey
     */
    public function belongsTo($localKey, $referenceClass, $referenceKey = 'id');

    /**
     * Defines a one to many relation.
     * Means that this model belongs/is associated to multiple another model.
     * Its pretty the opposite of the many to one relation definition above.
     *
     * $localKey       = 'id';
     * $referenceClass = 'AnotherModel';
     * $referenceKey   = 'model_id';
     *
     * @param        $referenceClass
     * @param        $referenceKey
     * @param string $localKey
     */
    public function hasMany($referenceClass, $referenceKey, $localKey = 'id');

    /**
     * Defines a one to one relation.
     * Means that his model belongs/is associated to another model.
     * Its like the one to many relation, but will later return only one model instance.
     *
     * $localKey       = 'id';
     * $referenceClass = 'AnotherModel';
     * $referenceKey   = 'model_id';
     *
     * @param string $referenceClass
     * @param string $referenceKey
     * @param string $localKey
     */
    public function hasOne($referenceClass, $referenceKey, $localKey = 'id');

    /**
     * Fetches the related model instances.
     *
     * @param string $referenceClass
     * @return EntityCollection|EntityInterface
     */
    public function getRelated($referenceClass);

    /**
     * Alias for ModelInterface::getRelated
     * 
     * @param string $referenceClass
     * @return EntityCollection|EntityInterface
     */
    public function __get($referenceClass);

    /**
     * Fill the model data by an associative array or key and value.
     *
     * @param string|array $param
     * @param mixed        $value
     *
     * @throws PropertyNotFoundException
     *
     * @return EntityAbstract
     */
    public function set($param, $value = null);

    /**
     * Gets data from model as an assocate array of value by key.
     *
     * @param null|string $param
     * @param array       $filter
     *
      @throws PropertyNotFoundException
     *
     * @return array|mixed
     */
    public function get($param = null, $filter = []);
    
    /**
     * @return Repository
     */
    public static function repository();

}