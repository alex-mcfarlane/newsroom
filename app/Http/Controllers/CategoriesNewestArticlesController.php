<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Category;

class CategoriesNewestArticlesController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $newestArticlesPerCategory = [];
        
        foreach($categories as $category)
        {
            $article = $category->getNewestArticle();
            
            if($article)
            {
                $newestArticlesPerCategory[$category->title] = $article;
            }
        }

    	return response()->json($newestArticlesPerCategory);
    }
}
