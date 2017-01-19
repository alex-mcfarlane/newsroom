<?php

namespace App\Newsroom\Articles;

use App\Newsroom\Querier;
use App\Article;

/**
 * ArticleQuerier Class
 * 
 *
 * @author Alex McFarlane
 */
class ArticleQuerier extends Querier{

    protected $validFilterableFields = ['title', 'body', 'start_date', 'end_date', 'featured'];
    protected $filters;
    protected $model;
    
    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->model = new Article();
        $this->query = Article::query();
    }
    
    protected function getFilters()
    {
        return $this->filters;
    }
    
    protected function getModel()
    {
        return $this->model;
    }
    
    protected function getValidFilterableFields()
    {
        return $this->validFilterableFields;
    }

    protected function addToQuery()
    {
        $this->query->with(['category','image']);
    }
}