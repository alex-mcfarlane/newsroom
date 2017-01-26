<?php

namespace App\Newsroom\Categories;

use App\Category;
use App\Newsroom\Interfaces\IRetrieverOutput;
use App\Newsroom\Interfaces\IModelFormatter;

/**
 * Abstract CategoryArticleRetriever class
 *
 * @author Alex McFarlane
 */
abstract class CategoryRecentArticlesRetriever
{
    protected $model;
    protected $limit;
    protected $query;
    private $formatter;

    public function __construct(Category $category, IModelFormatter $formatter, $limit)
    {
        $this->model = $category;
        $this->formatter = $formatter;
        $this->limit = $limit;
    }

    protected abstract function retrieval(IModelFormatter $formatter);
    
    public function get(IRetrieverOutput $presenter)
    {
        $articles = $this->startQuery()->retrieval($this->formatter);
        return $presenter->output($this->model, $articles);
    }
    
    public function startQuery()
    {
        $this->query = $this->model->newestArticlesQuery();
        return $this;
    }
}
