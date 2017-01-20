<?php

namespace App\Newsroom\Images;

use App\Article;
use App\Image;
use App\Newsroom\Validators\ImageValidator;
use App\Newsroom\Exceptions\ImageException;

class ImageCreator
{
	protected $validator;

	public function __construct(ImageValidator $validator)
	{
		$this->validator = $validator;
	}

	public function make($articleId, $file)
	{
		if(!$article = Article::find($articleId)) {
			throw new ImageException(['Article not found'], 404);
		}

		if(!$this->validator->isValid(['image' => $file])) {
			throw new ImageException($this->validator->getErrors());
		}
	    
	    $image = Image::fromRequest($file);

	    $article->addImage($image);

	    return $image;
	}
}