<?php

namespace App\Newsroom\Articles;

use App\Newsroom\Interfaces\IModelFormatter;

/**
 * Description of RecentArticle
 *
 * @author Alex McFarlane
 */
class RecentArticle extends CategoryArticleRetriever
{    
    public function retrieval(IModelFormatter $formatter)
    {    
        $article = $this->query->newestArticle();

        return $formatter->format($article);
    }
}