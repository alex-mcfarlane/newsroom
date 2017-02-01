<?php

use App\Article;
use App\Newsroom\Validators\ArticleValidator;
use App\Newsroom\Exceptions\ArticleException;
use App\Newsroom\Exceptions\CategoryNotFoundException;

namespace App\Newsroom\Articles;

/**
 * Domain logic for updating an article
 *
 * @author Alex McFarlane
 */
class ArticleUpdater {
    protected $validator;
    
    public function __construct(ArticleValidator $validator)
    {
        $this->validator = $validator;
    }
    
    public function update($attributes)
    {
        //validate the input
        if(! $this->validator->isValid($attributes)) {
            throw new ArticleException($this->validator->getErrors());
        }
        
        if(!$article = Article::find($id))
        {
            throw new ArticleException(["Article not found"]);
        }
        
        $article->edit([$attributes['title'], $attributes['body']], $attributes['featured']);
        
        try{
            if($attributes['category_id']) {
                $this->setCategory($attributes['category_id']);
            }
        } catch(CategoryNotFoundException $e) {
        
        }
    }
}
