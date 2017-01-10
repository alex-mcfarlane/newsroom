<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Newsroom\Articles\ArticleCreator;
use App\Newsroom\Exceptions\ArticleException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticlesController extends Controller
{
    public function __construct(ArticleCreator $articleCreator)
    {
        $this->articleCreator = $articleCreator;
    }
    
    public function index(Request $request)
    {
        $articles = Article::query();

        if($request->has('start_date')) {
            $articles->filterAfterDate($request->input('start_date'));
        }
        if($request->has('end_date')) {
            $articles->filterBeforeDate($request->input('end_date'));
        }

        return response()->json($articles->get());
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
    
    public function show($id)
    {
        try{
            return response()->json(Article::withSubResources($id));
        } catch(ModelNotFoundException $e) {
            return response()->json($e->getMessage());
        }
    }
}
