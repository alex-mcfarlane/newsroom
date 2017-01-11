<?php

namespace App\Newsroom\Categories;

use App\Category;

/**
 * Abstract CategoryArticleRetriever class
 *
 * @author Alex McFarlane
 */
abstract class CategoryArticleRetriever
{
    public function __construct(Category $category, $limit = 1)
    {
        $this->category = $category;
        $this->limit = $limit;
    }
    
    protected abstract function get();
}
