<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\FeaturedArticle;
use App\Newsroom\Articles\ArticleFormatter;

class FeaturedArticlesController extends Controller
{
    protected $articleFeaturer;
    
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index']]);
    }
    
    public function index()
    {
        return response()->json(FeaturedArticle::all('*', new ArticleFormatter));
    }
    
    public function store(Request $request, $articleId)
    {
        $article = FeaturedArticle::find($articleId);
        $article->setSortOrder($request->input('order_id'));
    }
}