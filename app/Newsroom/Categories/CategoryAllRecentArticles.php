<?php

namespace App\Newsroom\Categories;

use App\Newsroom\Interfaces\IModelFormatter;

/**
 * Description of CategoryAllRecentArticles
 *
 * @author AlexMc
 */
class CategoryAllRecentArticles extends CategoryRecentArticlesRetriever{
    
    public function retrieval(IModelFormatter $formatter)
    {
        $articles = $this->query->get();

        foreach($articles as $article) {
        	$article = $formatter->format($article);
        }

        return $articles;
    }
}
