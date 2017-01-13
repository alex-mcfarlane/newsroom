<?php

namespace App\Newsroom\Articles;

use App\Category;

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
    
    public function get()
    {
        return $this->startQuery()->retrieval();
    }
    
    public function startQuery()
    {
        $this->query = $this->model->newestArticlesQuery();
        return $this;
    }
    
    protected function output($collection)
    {
        $output = $this->model->toArray();
        $output["articles"] = [];
        $output["articles"][] = $collection;
        
        return $output;
    }
    
    protected abstract function retrieval();
}
