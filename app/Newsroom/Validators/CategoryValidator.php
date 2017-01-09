<?php

namespace App\Newsroom\Validators;

class CategoryValidator extends Validator
{
	protected static $rules = [
		'title' => 'required',
		'description' => 'required'
	];
}