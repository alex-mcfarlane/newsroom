<?php

namespace App\Newsroom\Articles;

/**
 * Description of RecentArticle
 *
 * @author Alex McFarlane
 */
class RecentArticle extends CategoryArticleRetriever
{    
    public function retrieval()
    {    
        return $this->query->newestArticle();
    }
}