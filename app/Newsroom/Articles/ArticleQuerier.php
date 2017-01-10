<?php

namespace App\Newsroom\Articles;

/**
 * ArticleQuerier Class
 * 
 *
 * @author Alex McFarlane
 */
class ArticleQuerier {
    protected $filters = [];
    protected $validFilterableFields = ['start_date', 'end_date'];

    public function __construct($filters)
    {
        $this->filters = $filters;
    }
    
    public function search()
    {
        $articles = new Article();
        
        foreach($this->filters as $field => $value)
        {
            if(!in_array($field, $this->validFilterableFields)) {
                return false;
            }
            
            $method = 'filterBy'.camel_case($field);
            if(method_exists($articles, $method)) {
                $articles->$method($value);
            }
            else{
                $articles->where($field, $value);
            }
        }
        
        return $articles;
    }
}
