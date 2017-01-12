<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;

class FeaturedArticlesController extends Controller
{
    public function feature($id)
    {
        if(! $article = Article::find($id)) {
            return response()->json('Article not found', 404);
        }
        
        try{
            $article->markAsFeatured();
            return response()->json(null, 204);
        } catch (Exception $ex) {
            return response()->json('Article could not be updated');
        }
    }
}