<?php
namespace App\Newsroom\Articles;

use App\Article;
use App\Category;

/**
 * ArticleCreator Class
 * Validation and Business logic for creating an article
 * @author Alex McFarlane
 */
class ArticleCreator {
    public function make($fillableAttr, $categoryId)
    {
        //find the category
        $category = Category::find($categoryId);
        //create the article
        $article = Article::create($fillableAttr);
        //associate article with category
        $article->category()->associate($category);
        
        return $article;
    }
}
