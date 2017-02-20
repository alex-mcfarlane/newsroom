<?php

namespace App\Newsroom\Articles;

use App\Article;
use App\Newsroom\Exceptions\ArticleException;

/**
 * Description of ArticleFeaturer
 *
 * @author AlexMc
 */
class ArticleHeadliner {
    public function headline($id, $value)
    {
        if(! $article = Article::withSubResources($id)) {
            throw new ArticleException("Article not found", 404);
        }
        
        $article->setHeadliner($value);

        return $article;
    }
}
