<?php

namespace App\Newsroom\Articles;

use Illuminate\Database\Eloquent\Builder;
use App\Newsroom\EloquentQuerier;
use App\Article;

/**
 * ArticleQuerier Class
 * 
 *
 * @author Alex McFarlane
 */
class ArticleQuerier extends EloquentQuerier{
    

    protected function getQuery()
    {
        return Article::query();
    }
    
    protected function getModel()
    {
        return new Article();
    }
    
    protected function getValidFilterableFields()
    {
        return ['title', 'body', 'start_date', 'end_date', 'featured'];
    }

    protected function addToQuery(Builder $query)
    {
        return $query->with(['category','image']);
    }
}