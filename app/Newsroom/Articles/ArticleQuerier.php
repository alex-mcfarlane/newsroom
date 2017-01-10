<?php

namespace App\Newsroom\Articles;

/**
 * ArticleQuerier Class
 * 
 *
 * @author Alex McFarlane
 */
class ArticleQuerier {
    protected $validFilterableFields = [];
    
    public function addFilters($filters)
    {
        $articles = new Article();
        
        foreach($filters as $field => $value)
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
