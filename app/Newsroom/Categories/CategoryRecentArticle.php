<?php

namespace App\Newsroom\Categories;

use App\Newsroom\Interfaces\IModelFormatter;
use Illuminate\Support\Collection;

/**
 * Description of RecentArticle
 *
 * @author Alex McFarlane
 */
class CategoryRecentArticle extends CategoryRecentArticlesRetriever
{    
    public function retrieval(IModelFormatter $formatter)
    {
        $article = $this->query->newestArticle();
        
        return $formatter->format($article);
    }
}