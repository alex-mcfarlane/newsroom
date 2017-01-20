<?php

namespace App\Newsroom\Articles;

use App\Newsroom\EloquentQuerier;
use App\Article;

/**
 * ArticleQuerier Class
 * 
 *
 * @author Alex McFarlane
 */
class ArticleQuerier extends EloquentQuerier{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
        $this->query = Article::query();
    }
    
    protected function getFilters()
    {
        return $this->filters;
    }
    
    protected function getModel()
    {
        return new Article();
    }
    
    protected function getValidFilterableFields()
    {
        return ['title', 'body', 'start_date', 'end_date', 'featured'];
    }

    protected function addToQuery()
    {
        $this->query->with(['category','image']);
    }
}