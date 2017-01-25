<?php

namespace App\Newsroom\Categories;

use App\Newsroom\Interfaces\IModelFormatter;

/**
 * Description of RecentArticles
 *
 * @author Alex McFarlane
 */
class CategoryRecentArticles extends CategoryRecentArticlesRetriever
{
    public function retrieval(IModelFormatter $formatter)
    {
        $articles = $this->query->newestArticles($this->limit);

        foreach($articles as $article) {
        	$article = $formatter->format($article);
        }

        return $articles;
    }
}