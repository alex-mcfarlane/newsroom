<?php
namespace App\Newsroom\Articles;

use App\Article;
use App\Category;
use App\Newsroom\Validators\ArticleValidator;
use App\Newsroom\Exceptions\ArticleException;

/**
 * ArticleCreator Class
 * Validation and Business logic for creating an article
 * @author Alex McFarlane
 */
class ArticleCreator {
    
    protected $validator;
    
    public function __construct(ArticleValidator $validator)
    {
        $this->validator = $validator;
    }
    
    public function make($attributes)
    {
        //create the article
        if(! $this->validator->isValid($attributes)) {
            throw new ArticleException($this->validator->getErrors());
        }
        
        $article = Article::create([
            "title" => $attributes["title"],
            "body" => $attributes["body"],
        ]);
        
        //add the category
        if(isset($attributes["category_id"])) {
            //associate article with category
            if(! $category = Category::find($attributes["category_id"])) {
                throw new ArticleException(["Unable to find a category with the id specified"]);
            }
            $article->category()->associate($category);
        }
        
        return $article;
    }
}
