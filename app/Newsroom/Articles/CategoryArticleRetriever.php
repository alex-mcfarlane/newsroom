<?php

namespace App\Newsroom\Articles;

use App\Category;
use App\Newsroom\Interfaces\IRetrieverOutput;

/**
 * Abstract CategoryArticleRetriever class
 *
 * @author Alex McFarlane
 */
abstract class CategoryArticleRetriever
{
    protected $query;
    
    public function __construct(Category $category, $limit = 1)
    {
        $this->model = $category;
        $this->limit = $limit;
    }

    protected abstract function retrieval();
    
    public function get(IRetrieverOutput $formatter)
    {
        $articles = $this->startQuery()->retrieval();
        return $formatter->output($this->model, $articles);
    }
    
    public function startQuery()
    {
        $this->query = $this->model->newestArticlesQuery();
        return $this;
    }
}
