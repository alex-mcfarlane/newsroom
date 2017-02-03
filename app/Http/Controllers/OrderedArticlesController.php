<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;

class OrderedArticlesController extends Controller
{
    public function index()
    {
        return response()->json(Article::orderedArticles());
    }
}
