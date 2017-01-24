<?php
namespace App\Newsroom\Articles;

use App\Category;
use App\Newsroom\Articles\RecentArticle;
use App\Newsroom\Articles\RecentArticles;
use App\Newsroom\Articles\ArticleFormatter;

/**
 * ArticleRetrieverFactory
 * Creates a concrete implementation of the abstract CategoryArticleRetriever
 * @author Alex McFarlane
 */
class ArticleRetrieverFactory {
    public static function create(Category $category, $limit)
    {
        switch(true){
            case $limit <= 1 || $limit == null:
                return new RecentArticle($category, new ArticleFormatter);
                break;
            case $limit > 1:
                return new RecentArticles($category, new ArticleFormatter, $limit);
        }
    }
}
