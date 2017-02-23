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
            $optionalAttrs = [
                "headliner" => $attributes['headliner'],
                "categoryId" => $attributes['category_id']
            ];

            $article = Article::fromForm($attributes['title'], $attributes['body'], $optionalAttrs);
        } catch(CategoryNotFoundException $e) {
            //$article->delete();
            throw new ArticleException($e->getErrors());
        }
        
        $formatter = new ArticleFormatter();
        
        return $formatter->format($article);
    }
}
