<?php

namespace App\Newsroom\Exceptions;

/**
 * CategoryNotFoundException class
 * Exception that is thrown when a category cannot be identified retreived from the data store 
 * 
 * @author Alex McFarlane
 */
class CategoryNotFoundException extends BaseException{
    public function __construct()
    {
        parent::__construct("Category not found", ["Unable to retreive category"], 404);
    }
}
