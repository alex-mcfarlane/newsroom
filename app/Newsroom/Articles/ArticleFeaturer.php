<?php

namespace App\Newsroom\Articles;

use App\Article;
use App\Newsroom\Exceptions\ArticleException;

/**
 * Description of ArticleFeaturer
 *
 * @author AlexMc
 */
class ArticleFeaturer {
    public function feature($id, $value)
    {
        if(! $article = Article::find($id)) {
            throw new ArticleException("Article not found", 404);
        }
        
        return $article->setFeatured($value);
    }
}
