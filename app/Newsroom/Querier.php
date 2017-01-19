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
    
    protected $query;
    
    abstract protected function getFilters();
    abstract protected function getModel();
    abstract protected function getValidFilterableFields();
    abstract protected function addToQuery();
    
    public function search()
    {
        $filters = $this->getFilters();
        $model = $this->getModel();
        $validFields = $this->getValidFilterableFields();
        
        $this->applyFilters($filters, $model, $validFields);
        $this->addToQuery();

        return $this->query->get();
    }
    
    protected function applyFilters($filters, $model, $validFields)
    {
        foreach($filters as $field => $value)
        {
            if(!in_array($field, $validFields)) {
                continue;
            }
            
            $method = 'filterBy'.camel_case($field);

            if(method_exists($model, 'scope'.$method)) {
                $this->query->$method($value);
            }
            else{
                $this->query->where($field, $value);
            }
        }
    }
}
