<?php

namespace App\Newsroom\Categories;

/**
 * Description of RecentArticles
 *
 * @author Alex McFarlane
 */
class RecentArticles extends CategoryArticleRetriever
{
    public function get()
    {
        return $this->category->getNewestArticles($this->limit);
    }
}