<?php

namespace App\Newsroom\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IModelFormatter
{
	public function format(Model $model);
}