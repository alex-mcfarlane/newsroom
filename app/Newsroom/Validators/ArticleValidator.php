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
        "body" => "required",
        "category_id" => "numeric",
        "headliner" => "boolean"
    ];
}
