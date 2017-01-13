<?php

namespace App\Newsroom\Validators;

class UserValidator extends Validator
{
	protected static $rules = [
		'name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
		'password' => 'required|min:6|confirmed'
	];
}