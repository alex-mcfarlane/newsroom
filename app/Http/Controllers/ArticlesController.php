<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Newsroom\Articles\ArticleCreator;
use App\Newsroom\Exceptions\ArticleException;

class ArticlesController extends Controller
{
    public function __construct(ArticleCreator $articleCreator)
    {
        $this->articleCreator = $articleCreator;
    }
    
    public function store(Request $request)
    {        
        try{
            $article = $this->articleCreator->make($request->only('title', 'body', 'category_id'));
        }
        catch(ArticleException $e) {
            return response()->json($e->getErrors());
        }
        
        return response()->json($article);
    }
}
