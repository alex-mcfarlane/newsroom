<?php

namespace App\Newsroom\Categories;

use App\Newsroom\Validators\CategoryValidator;
use App\Newsroom\Exceptions\CategoryException;
use App\Category;

class CategoriesCreator
{
	public function __construct(CategoryValidator $validator)
	{
		$this->validator = $validator;
	}

	public function make($attributes)
	{
		if(!$this->validator->isValid($attributes)) {
			throw new CategoryException($this->validator->getErrors());
		}

		try{
			$category = Category::create($attributes);
		} catch(\Illuminate\Database\QueryException $e) {
			if($e->errorInfo[1] == 1062) {
				throw new CategoryException(['A category already exists with the'. 
					' name supplied.']);
			}
			else{
				throw new CategoryException(['A database error has occured.']);
			}
		}

		return $category;
	}
}