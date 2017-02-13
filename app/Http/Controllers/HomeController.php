<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\FeaturedArticle;
use App\Category;
use App\Newsroom\Articles\ArticleFormatter;

class HomeController extends Controller
{   
    public function index()
    {
        $headlineArticle = Article::headliner();        
        $featuredArticles = FeaturedArticle::all('*', new ArticleFormatter);

        return view('home', compact('headlineArticle', 'featuredArticles'));
    }
}