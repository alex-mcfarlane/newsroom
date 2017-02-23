<?php

namespace App\Newsroom\Articles;

use App\Article;
use App\Newsroom\Images\ImageCreator;
use App\Newsroom\Validators\ArticleValidator;
use App\Newsroom\Exceptions\ArticleException;
use App\Newsroom\Exceptions\ImageException;
use App\Newsroom\Exceptions\CategoryNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;

/**
 * Domain logic for updating an article
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
            
            $optionalAttrs = [
                "headliner" => $attributes['headliner'],
                "categoryId" => $attributes['category_id']
            ];

            $article->edit($attributes['title'], $attributes['body'], $optionalAttrs);

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
            $errors = ["Article has been updated but there were errors changing the image."] + $e->getErrors;
            throw new ArticleException($errors);
        }

        return $article;
    }
}
