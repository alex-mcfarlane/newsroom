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
        
        try{
            $formatter = new ArticleFormatter();

            if($attributes['category_id']) {
                $article = Article::createCategorizedArticle($attributes['title'], $attributes['body'], $attributes['featured'], $attributes['category_id']);
            }
            else{
                $article = Article::fromForm($attributes['title'], $attributes['body'], $attributes['featured']);
            }

            $article = $formatter->format($article);
        } catch(CategoryNotFoundException $e) {
            throw new ArticleException($e->getErrors());
        }
        
        return $article;
    }
}
