<?php

namespace App\Newsroom\Articles;

use App\Article;
use App\Category;
use App\Newsroom\Images\ImageCreator;
use App\Newsroom\Validators\ArticleValidator;
use App\Newsroom\Exceptions\ArticleException;
use App\Newsroom\Exceptions\ImageException;
use App\Newsroom\Exceptions\CategoryNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;

/**
 * Application logic for updating an article
 *
 * @author Alex McFarlane
 */
class ArticleUpdater {
    protected $validator;
    protected $imageCreator;

    public function __construct(ArticleValidator $validator, ImageCreator $imageCreator)
    {
        $this->validator = $validator;
        $this->imageCreator =$imageCreator;
    }
    
    public function update($id, array $attributes, UploadedFile $file = null)
    {
        //validate the input
        if(! $this->validator->isValid($attributes)) {
            throw new ArticleException($this->validator->getErrors());
        }
        
        try{
            $article = Article::findOrFail($id);
            $article->edit($attributes['title'], $attributes['body'], 
                        $attributes['sub_title'], $attributes['headliner']);

            //client can specify whether to remove or associate category
            if(empty($attributes['category_id'])) {
                if($article->hasCategory()) {
                    $article->clearCategory();
                }
            }
            else{
                $category = Category::find($attributes['category_id']);
                $article->associateCategory($category);
            }

            //change article image if client passed one in
            if($file) {
                $image = $this->imageCreator->make($file);

                $article->changeImage($image);
            }
        } catch(ModelNotFoundException $e) {
            throw new ArticleException([$e->getMessage()]);
        } catch(CategoryNotFoundException $e) {
            throw new ArticleException($e->getErrors());
        } catch(ImageException $e) {
            $errors = ["Article has been updated but there were errors changing the image."];// TODO: array union with this + $e->getErrors();
            throw new ArticleException($errors);
        }

        return $article;
    }
}
