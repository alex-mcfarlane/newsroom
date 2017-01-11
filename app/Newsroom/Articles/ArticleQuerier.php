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

    protected $validFilterableFields = ['title', 'body', 'start_date', 'end_date'];

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->model = new Article();
        $this->query = Article::query();
    }
}