<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Newsroom\Categories\CategoriesCreator;
use App\Newsroom\Exceptions\CategoryException;
use App\Category;

class CategoriesAPIController extends Controller
{
    public function __construct(CategoriesCreator $categoriesCreator)
    {
        $this->middleware('jwt.auth', ['except' => ['index']]);
    	$this->categoriesCreator = $categoriesCreator;
    }

    public function index()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
    	try{
    		$category = $this->categoriesCreator->make($request->only('title', 'description'));
    	}
    	catch(CategoryException $e) {
    		return response()->json($e->getErrors(), 400);
    	}

    	return response()->json($category);
    }
    
    public function show($id)
    {
        
    }
}
