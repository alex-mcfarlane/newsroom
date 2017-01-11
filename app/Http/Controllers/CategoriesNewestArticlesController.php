<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Category;
use App\Newsroom\Factories\ArticleRetrieverFactory;

class CategoriesNewestArticlesController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        
        foreach($categories as $category)
        {
            $retriever = ArticleRetrieverFactory::create($category, $request->input('limit'));
            $result = $retriever->get();
            
            if($result)
            {
                $newestArticlesPerCategory[$category->title] = $result;
            }
        }

    	return response()->json($newestArticlesPerCategory);
    }
}