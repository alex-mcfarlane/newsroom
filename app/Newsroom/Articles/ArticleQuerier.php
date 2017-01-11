<?php

namespace App\Newsroom\Articles;

use App\Article;

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
        $article = new Article;
        $query = Article::query();
        
        foreach($this->filters as $field => $value)
        {
            if(!in_array($field, $this->validFilterableFields)) {
                continue;
            }
            
            $method = 'filterBy'.camel_case($field);
            
            if(method_exists($article, 'scope'.$method)) {
                $query->$method($value);
            }
            else{
                $query->where($field, $value);
            }
        }
        
        return $query;
    }

}
