<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Category;

class NewestArticlesController extends Controller
{
    public function index(Request $request)
    {
    	return response()->json(Article::newestForEachCategory(Category::all()));
    }
}
