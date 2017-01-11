<?php

namespace App\Newsroom\Categories;

/**
 * Description of RecentArticle
 *
 * @author Alex McFarlane
 */
class RecentArticle extends CategoryArticleRetriever
{    
    public function retrieval()
    {    
        return $this->output($this->query->newestArticle());
    }
}