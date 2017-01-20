<?php

namespace App\Newsroom;

use App\Newsroom\Interfaces\IQuerier;
use Illuminate\Database\Eloquent\Model;
/**
 * Abstract Querier Class
 * 
 *
 * @author Alex McFarlane
 */
abstract class EloquentQuerier implements IQuerier{
    
    protected $query;

    abstract protected function getFilters();
    abstract protected function getModel();
    abstract protected function getValidFilterableFields();
    abstract protected function addToQuery();
    
    public function search()
    {   
        $this->applyFilters($this->getFilters(), $this->getModel(), $this->getValidFilterableFields());
        $this->addToQuery();

        return $this->query->get();
    }
    
    protected function applyFilters(array $filters, Model $model, array $validFields)
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
