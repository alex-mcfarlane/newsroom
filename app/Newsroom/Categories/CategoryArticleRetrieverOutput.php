<?php

namespace App\Newsroom\Categories;

use App\Newsroom\Interfaces\IRetrieverOutput;
use Illuminate\Database\Eloquent\Model;

class CategoryArticleRetrieverOutput implements IRetrieverOutput
{
	public function output(Model $category, $article)
	{
        $category["article"] = $article;

        return $category;
	}
}