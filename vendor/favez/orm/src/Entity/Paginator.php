<?php

namespace Favez\ORM\Entity;

use Favez\ORM\Exception\InvalidModeException;

class Paginator
{

    /**
     * The records will be fetched as raw array.
     */
    const MODE_ARRAY  = 1;

    /**
     * The records will be fetched as objects in an Entity\Collection (handled as an array)
     */
    const MODE_OBJECT = 2;
    
    protected $options;
    
    public function __construct($options)
    {
        $this->options = $options;
    }
    
    public function paginate()
    {
        $limit = $this->option('limit', 10);
        
        if (($data = $this->option('data')) && is_array($data))
        {
            $total  = count($data);
            $pages  = ceil($total / $limit);
            $page   = min($pages, max(1, $this->option('page', 1)));
            $offset = ($page - 1) * $limit;
            
            $data   = array_slice($data, $offset, $limit);
        }
        else if(($builder = $this->option('builder')) && $builder instanceof QueryBuilder)
        {
            $total  = $builder->count();
            $pages  = ceil($total / $limit);
            $page   = min($pages, max(1, $this->option('page', 1)));
            $offset = ($page - 1) * $limit;
            
            $builder->offset($offset);
            $builder->limit($limit);
            
            switch ($this->option('mode', static::MODE_ARRAY))
            {
                case (self::MODE_ARRAY): {
                    $data = $builder->fetchArrayResult();
                } break;
                case (self::MODE_OBJECT): {
                    $data = $builder->fetch();
                } break;
                default: {
                    throw new InvalidModeException();
                }
            }
        }
        else
        {
            throw new \InvalidArgumentException();
        }
        
        return [
            'first' => 1,
            'last'  => $total,
            'next'  => $page < $total ? $page + 1 : null,
            'prev'  => $page > 1 ? $page - 1 : null,
            'data'  => $data,
            'total' => $total,
            'page'  => $page,
            'pages' => $pages
        ];
    }
    
    protected function option($key, $default = null)
    {
        if (isset($this->options[$key]))
        {
            return $this->options[$key];
        }
        
        return $default;
    }

}