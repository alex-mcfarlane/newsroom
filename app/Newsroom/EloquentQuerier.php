<?php

namespace App\Newsroom;

use App\Newsroom\Interfaces\IQuerier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
/**
 * Abstract Querier Class
 * 
 *
 * @author Alex McFarlane
 */
abstract class EloquentQuerier implements IQuerier{
    private $filters;

    abstract protected function getModel();
    abstract protected function getValidFilterableFields();
    abstract protected function getQuery();
    abstract protected function addToQuery(Builder $query);
    
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function search()
    {   
        $query = $this->applyFilters($this->getModel(), $this->getValidFilterableFields(), $this->getQuery());
        $this->addToQuery($query);

        return $query->get();
    }
    
    protected function applyFilters(Model $model, array $validFields, Builder $query)
    {
        foreach($this->filters as $field => $value)
        {
            if(!in_array($field, $validFields)) {
                continue;
            }
            
            $method = 'filterBy'.camel_case($field);

            if(method_exists($model, 'scope'.$method)) {
                $query->$method($value);
            }
            else{
                $query->where($field, $value);
            }
        }

        return $query;
    }
}
