<?php

namespace App\Newsroom;

use App\Newsroom\Interfaces\IQuerier;

/**
 * Abstract Querier Class
 * 
 *
 * @author Alex McFarlane
 */
abstract class Querier implements IQuerier{

    protected $validFilterableFields = [];
    protected $filters = [];
    protected $model;
    protected $query;
    
    public function search()
    {        
        foreach($this->filters as $field => $value)
        {
            if(!in_array($field, $this->validFilterableFields)) {
                continue;
            }
            
            $method = 'filterBy'.camel_case($field);
            
            if(method_exists($this->model, 'scope'.$method)) {
                $this->query->$method($value);
            }
            else{
                $this->query->where($field, $value);
            }
        }
        
        return $this->query;
    }

}
