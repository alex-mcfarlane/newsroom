<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Newsroom\Articles\ArticleRetrieverService;
use App\Category;

class CategoriesController extends Controller
{
    public function __construct(ArticleRetrieverService $articleRetrieverService)
    {
        $this->articleRetrieverService = $articleRetrieverService;
    }
    
    public function show($id)
    {   
        $category = $this->articleRetrieverService->retrieveArticlesForCategory(Category::find($id));
        
        return view('categories.index', compact('category'));
    }
}
