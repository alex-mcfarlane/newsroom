<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Newsroom\Articles\ArticleCreator;

class ArticlesController extends Controller
{
    public function __construct(ArticleCreator $articleCreator)
    {
        $this->articleCreator = $articleCreator;
    }
    
    public function store(Request $request)
    {
        $fillableAttributes = $request->only('title', 'body');
        $categoryId = $request->input('category_id');
        
        $article = $this->articleCreator->make($fillableAttributes, $categoryId);
        
        return response()->json($article);
    }
}
