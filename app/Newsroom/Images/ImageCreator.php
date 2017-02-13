<?php

namespace App\Newsroom\Images;

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

	public function make($file)
	{
		if(!$this->validator->isValid(['image' => $file])) {
			throw new ImageException($this->validator->getErrors());
		}
	    
	    $image = Image::fromRequest($file, new LocalFileStore);

	    return $image;
	}
}