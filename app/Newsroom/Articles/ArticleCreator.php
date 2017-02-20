<?php
namespace App\Newsroom\Articles;

use App\Article;
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
        
        try{
            $article = Article::fromForm($attributes['title'], $attributes['body'], $attributes['headliner'], 
                                $attributes['category_id']);
            
            $formatter = new ArticleFormatter();
            $article = $formatter->format($article);
        } catch(CategoryNotFoundException $e) {
            $article->delete();
            throw new ArticleException($e->getErrors());
        }
        
        return $article;
    }
}
