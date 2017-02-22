<?php

namespace App\Newsroom\Categories;

/**
 * Description of RecentArticle
 *
 * @author Alex McFarlane
 */
class RecentArticle extends CategoryArticlesRetriever
{    
    public function retrieval()
    {
        $article = $this->query->newestArticle();
        
        return $this->formatter->format($article);
    }
}