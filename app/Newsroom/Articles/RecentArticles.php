<?php

namespace App\Newsroom\Articles;

/**
 * Description of RecentArticles
 *
 * @author Alex McFarlane
 */
class RecentArticles extends CategoryArticleRetriever
{
    public function retrieval()
    {
        return $this->query->newestArticles($this->limit);
    }
}