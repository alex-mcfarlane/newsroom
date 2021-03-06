<?php

namespace App\Newsroom\Categories;

use App\Category;
use App\Newsroom\Interfaces\IRetrieverOutput;
use App\Newsroom\Articles\ArticleFormatter;

/**
 * Abstract CategoryArticleRetriever class
 *
 * @author Alex McFarlane
 */
abstract class CategoryArticlesRetriever
{
    protected $model;
    protected $limit;
    protected $query;
    protected $formatter;
    private $presenter;

    public function __construct(Category $category, IRetrieverOutput $presenter, $limit = 0)
    {
        $this->model = $category;
        $this->presenter = $presenter;
        $this->limit = $limit;
        $this->formatter = new ArticleFormatter;
    }

    protected abstract function retrieval();
    
    public function get()
    {
        $articles = $this->startQuery()->retrieval();
        
        return $this->presenter->output($this->model, $articles);
    }
    
    public function startQuery()
    {
        $this->query = $this->model->newestArticlesQuery();
        return $this;
    }
}
