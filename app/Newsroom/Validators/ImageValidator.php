<?php

namespace App\Newsroom\Validators;

/**
 * ImageValidator Class
 * Validation rules for creating/updating an image
 * 
 * @author Alex McFarlane
 */

class ImageValidator extends Validator{
    
    protected static $rules = [
        "image" => "required|mimes:jpg,jpeg,png,bmp"
    ];
}
