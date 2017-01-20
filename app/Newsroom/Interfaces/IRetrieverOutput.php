<?php
namespace App\Newsroom\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IRetrieverOutput
{
	public function output(Model $model, $subResourceCollection);
}