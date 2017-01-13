<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Newsroom\Articles\ArticleFeaturer;

class FeaturedArticlesController extends Controller
{
    protected $articleFeaturer;
    
    public function __construct(ArticleFeaturer $articleFeaturer)
    {
        $this->articleFeaturer = $articleFeaturer;
    }
    
    public function feature($id)
    {   
        try{
            $this->articleFeaturer->feature($id, true);
            return response()->json(null, 204);
        } catch (\App\Newsroom\Exceptions\ArticleException $ex) {
            return response()->json($ex->getErrors(), $ex->getHttpStatusCode());
        }
    }
    
    public function unfeature($id)
    {
        try{
            $this->articleFeaturer->feature($id, false);
            return response()->json(null, 204);
        } catch (\App\Newsroom\Exceptions\ArticleException $ex) {
            return response()->json($ex->getErrors(), $ex->getHttpStatusCode());
        }
    }
}