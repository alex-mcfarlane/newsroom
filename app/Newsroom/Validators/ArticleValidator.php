<?php

namespace App\Newsroom\Validators;

/**
 * ArticleValidator Class
 * Validation rules for creating/updating an article
 * 
 * @author Alex McFarlane
 */

class ArticleValidator extends Validator{
    
    protected static $rules = [
        "title" => "required",
        "sub_title" => "max:300",
        "body" => "required|max:10000",
        "category_id" => "numeric",
        "headliner" => "boolean"
    ];
}
