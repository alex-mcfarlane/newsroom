<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Newsroom\Articles\ArticleCreator;
use App\Newsroom\Articles\ArticleQuerier;
use App\Newsroom\Articles\ArticleFormatter;
use App\Newsroom\Images\ImageCreator;
use App\Newsroom\Exceptions\ArticleException;
use App\Newsroom\Exceptions\ImageException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticlesController extends Controller
{
    protected $articleCreator;
    protected $imageCreator;

    public function __construct(ArticleCreator $articleCreator, ImageCreator $imageCreator)
    {
        //$this->middleware('jwt.auth', ['except' => ['index']]);
        $this->articleCreator = $articleCreator;
        $this->imageCreator = $imageCreator;
    }
    
    public function index(Request $request)
    {
        
            $articleQuerier = new ArticleQuerier($request->all());
            $articles = $articleQuerier->search(new ArticleFormatter);

        return response()->json($articles);
    }

    public function store(Request $request)
    {        
        try{
            $article = $this->articleCreator->make($request->only('title', 'body', 'featured', 'category_id'));
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

    public function addImage(Request $request, $articleId)
    {
        error_log(print_R($request->file('image'), true));
        try{
            $image = $this->imageCreator->make($articleId, $request->file('image'));
            return response()->json($image);
        } catch(ImageException $e) {
            return response()->json($e->geterrors());
        }
    }
}
