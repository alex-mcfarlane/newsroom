<?php
namespace App\Newsroom\Factories;

use App\Category;
use App\Newsroom\Categories\RecentArticle;
use App\Newsroom\Categories\RecentArticles;

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
                return new RecentArticle($category);
                break;
            case $limit > 1:
                return new RecentArticles($category, $limit);
        }
    }
}
