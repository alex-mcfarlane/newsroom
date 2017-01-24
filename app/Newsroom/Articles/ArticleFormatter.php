<?php

namespace App\Newsroom\Articles;

use App\Newsroom\Interfaces\IModelFormatter;
use Illuminate\Database\Eloquent\Model;

class ArticleFormatter implements IModelFormatter
{
	public function format(Model $article)
	{
		$article->setImage();

		return $article;
	}
}