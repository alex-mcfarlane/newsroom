<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;

class ArticlesController extends Controller
{
    public function show($id)
    {
        $article = Article::withSubResources($id);
        
        return view('articles.view', compact('article'));
    }
}
