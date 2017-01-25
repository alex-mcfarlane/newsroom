<?php

namespace App\Newsroom\Categories;

use App\Newsroom\Interfaces\IRetrieverOutput;
use Illuminate\Database\Eloquent\Model;

class CategoryArticleRetrieverOutput implements IRetrieverOutput
{
	public function output(Model $category, $articles)
	{
		$output = $category->toArray();
        $output["articles"] = [];
        $output["articles"] = $articles;

        return $output;
	}
}