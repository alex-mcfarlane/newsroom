<?php
namespace App\Newsroom\Articles;

use App\Article;
use App\Category;
use App\Newsroom\Validators\ArticleValidator;
use App\Newsroom\Exceptions\ArticleException;
use App\Newsroom\Exceptions\CategoryNotFoundException;

/**
 * ArticleCreator Class
 * Validation and Business logic for creating an article
 * 
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
        
        $article = Article::fromForm($attributes['title'], $attributes['body'], 
                    $attributes['sub_title'], $attributes['headliner']);

        if(isset($attributes['category_id'])) {
            try{
                $category = Category::find($attributes['category_id']);

                $article->associateCategory($category);
            } catch(CategoryNotFoundException $e) {
                $article->delete();
                throw new ArticleException($e->getErrors());
            }
        }
        
        $formatter = new ArticleFormatter();
        
        return $formatter->format($article);
    }
}
