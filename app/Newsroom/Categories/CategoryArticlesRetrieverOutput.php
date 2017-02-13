<?php

namespace App\Newsroom\Categories;

use App\Newsroom\Interfaces\IRetrieverOutput;
use Illuminate\Database\Eloquent\Model;

class CategoryArticlesRetrieverOutput implements IRetrieverOutput
{
	public function output(Model $category, $articles)
	{
        $category["articles"] = $articles;

        return $category;
	}
}