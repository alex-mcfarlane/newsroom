<?php
namespace App\Newsroom\Categories;

use App\Category;
use App\Newsroom\Articles\ArticleFormatter;

/**
 * ArticleRetrieverFactory
 * Creates a concrete implementation of the abstract CategoryArticleRetriever
 * @author Alex McFarlane
 */
class CategoryArticleRetrieverFactory {
    public static function create(Category $category, $limit)
    {
        switch(true){
            case $limit <= 1 || $limit == null:
                return new CategoryRecentArticle($category, new ArticleFormatter);
                break;
            case $limit > 1:
                return new CategoryRecentArticles($category, new ArticleFormatter, $limit);
        }
    }
}
