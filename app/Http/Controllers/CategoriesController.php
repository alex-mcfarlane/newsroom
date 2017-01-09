<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Newsroom\Categories\CategoriesCreator;
use App\Newsroom\Exceptions\CategoryException;

class CategoriesController extends Controller
{
    public function __construct(CategoriesCreator $categoriesCreator)
    {
    	$this->categoriesCreator = $categoriesCreator;
    }

    public function store(Request $request)
    {
    	try{
    		$category = $this->categoriesCreator->make($request->only('title', 'description'));
    	}
    	catch(CategoryException $e) {
    		return response()->json($e->getErrors());
    	}

    	return response()->json($category);
    }
}
