<?php

namespace App\Newsroom\Validators;

class AuthValidator extends Validator
{
    protected static $rules = [
        "email" => "required",
        "password" => "required"
    ];
}
