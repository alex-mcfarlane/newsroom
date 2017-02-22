<?php
namespace App\Newsroom\Categories;

use App\Category;
use App\Newsroom\Categories\CategoryArticlesRetrieverOutput;
use App\Newsroom\Categories\CategoryArticleRetrieverOutput;

/**
 * ArticleRetrieverFactory
 * Creates a concrete implementation of the abstract CategoryArticleRetriever
 * @author Alex McFarlane
 */
class CategoryArticleRetrieverFactory {
    public static function create(Category $category, $limit)
    {
        switch(true){
            case $limit == 1:
                return new RecentArticle($category, $limit, new ArticleRetrieverOutput);
                break;
            case $limit > 1:
                return new RecentArticles($category, $limit, new ArticlesRetrieverOutput);
            case $limit == null:
                return new AllRecentArticles($category, $limit, new ArticlesRetrieverOutput);
        }
    }
}
