<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\FeaturedArticle;


class FeaturedArticlesController extends Controller
{
    protected $articleFeaturer;
    
    public function index()
    {
        return response()->json(FeaturedArticle::all());
    }
    
    public function store(Request $request, $articleId)
    {
        $article = FeaturedArticle::find($articleId);
        $article->setSortOrder($request->input('order_id'));
    }
}