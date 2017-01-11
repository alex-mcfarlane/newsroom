<?php

namespace App\Newsroom\Categories;

/**
 * Description of RecentArticles
 *
 * @author Alex McFarlane
 */
class RecentArticles extends CategoryArticleRetriever
{
    public function retrieval()
    {
        return $this->output($this->query->newestArticles($this->limit));
    }
}