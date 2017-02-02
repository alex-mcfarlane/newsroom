<?php

namespace App\Newsroom\Articles;

use App\Article;
use App\Newsroom\Validators\ArticleValidator;
use App\Newsroom\Exceptions\ArticleException;
use App\Newsroom\Exceptions\CategoryNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    
    public function update($id, array $attributes)
    {
        //validate the input
        if(! $this->validator->isValid($attributes)) {
            throw new ArticleException($this->validator->getErrors());
        }
        
        try{
            $article = Article::findOrFail($id);

            $article->edit($attributes['title'], $attributes['body'], $attributes['featured'], 
                            $attributes['category_id']);
        } catch(ModelNotFoundException $e) {
            throw new ArticleException([$e->getMessage()]);
        } catch(CategoryNotFoundException $e) {
            throw new ArticleException($e->getErrors());
        }

        return $article;
    }
}
