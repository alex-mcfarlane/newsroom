<?php

namespace App\Newsroom\Categories;

/**
 * Description of AllRecentArticles
 *
 * @author AlexMc
 */
class AllRecentArticles extends CategoryArticlesRetriever
{    
    public function retrieval()
    {
        $articles = $this->query->get();

        foreach($articles as $article) {
        	$article = $this->formatter->format($article);
        }

        return $articles;
    }
}
