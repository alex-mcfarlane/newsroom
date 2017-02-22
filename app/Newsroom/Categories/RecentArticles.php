<?php

namespace App\Newsroom\Categories;

/**
 * Description of RecentArticles
 *
 * @author Alex McFarlane
 */
class RecentArticles extends CategoryArticlesRetriever
{
    public function retrieval()
    {
        $articles = $this->query->newestArticles($this->limit);

        foreach($articles as $article) {
        	$article = $this->formatter->format($article);
        }

        return $articles;
    }
}