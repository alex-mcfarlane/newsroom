<?php

namespace App\Newsroom;

use App\Newsroom\Interfaces\IQuerier;
use App\Newsroom\Interfaces\IModelFormatter;
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

    public function search(IModelFormatter $formatter = null)
    {   
        $query = $this->applyFilters($this->getModel(), $this->getValidFilterableFields(), $this->getQuery());
        
        $collection = $this->addToQuery($query)->get();

        if($formatter) {
            $collection = $this->format($collection, $formatter);
        }

        return $collection;
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

    private function format($collection, IModelFormatter $formatter)
    {
        foreach($collection as $model) {
            $model = $formatter->format($model);
        }

        return $collection;
    }
}
