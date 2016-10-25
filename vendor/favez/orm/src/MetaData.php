<?php

namespace Favez\ORM;

use Favez\ORM\Exception\EntityNotFoundException;
use Favez\ORM\Exception\MetaDataNotLoadException;

class MetaData
{

    public static function load($className)
    {
        $em = EntityManager::instance();
        
        if ($table = $em->resolve($className))
        {
            if ($em->cache() instanceof \Stash\Pool)
            {
                $item = $em->cache()->getItem('model_metadata_' . $table);
                
                if ($item->isMiss())
                {
                    $item->lock();
                    $item->set(self::getMetaData($table));
                    
                    $em->cache()->save($item);
                }
                
                return $item->get();
            }
            else
            {
                return self::getMetaData($table);
            }
        }

        throw new EntityNotFoundException();
    }

    private static function getMetaData($tableName)
    {
        $query = 'SELECT * FROM ' . $tableName . ' LIMIT 0';
        $query = str_replace('?', $tableName, $query);
        $stmt  = EntityManager::instance()->pdo()->getPdo()->prepare($query);

        if($stmt->execute())
        {
            $columns = [];

            for($i = 0; $i < $stmt->columnCount(); $i++)
            {
                $columns[] = $stmt->getColumnMeta($i);
            }

            return $columns;
        }

        throw new MetaDataNotLoadException();
    }

}