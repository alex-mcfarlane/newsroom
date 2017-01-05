<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;

class ArticlesController extends Controller
{
    public function __construct(ArticleCreator $articleCreator)
    {
        $this->articleCreator = $articleCreator;
    }
    
    public function store(Request $request)
    {
        $this->articleCreator->make($request->only('title', 'body', 'category_id'));
    }
}
