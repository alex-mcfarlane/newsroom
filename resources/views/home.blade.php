@extends('layouts.app')

@section('content')
<div class="container">
    
    <div id="page-header">
        <div class="row">
            
            @if(Auth::check())
                <section id="featured" class="col-md-9 col-sm-12">
                    <article id="admin-featured-article" class="featured-article">
                        <img v-bind:src="imagePath" class="img-responsive" alt="Featured Article alt"/>

                        <div class="article-info">
                            <h4 class="article-tag">@{{category.title}}</h4>

                            <div class="article-heading">
                                <h2>@{{title}}</h2>
                                <time>@{{createdAt}}</time>
                            </div>

                            <p>@{{body}}</p>
                        </div>

                    </article>
                </section>
            @else
                <section id="featured" class="col-md-9 col-sm-12">
                    @if($featuredArticle)
                    <article class="featured-article">
                        <img src="{{$featuredArticle->image->path}}" class="img-responsive" alt="Featured Article alt"/>

                        <div class="article-info">
                            @if(Auth::check())
                                <button type="button" class="btn btn-default btn-lg">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New Feature Article
                                </button>

                                <button type="button" class="btn btn-default btn-lg">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>Change Feature Article
                                </button>
                            @endif
                            <h4 class="article-tag">{{$featuredArticle->category->title}}</h4>

                            <div class="article-heading">
                                <h2>{{$featuredArticle->title}}</h2>
                                <time>{{date('F d, Y', strtotime($featuredArticle->created_at))}}</time>
                            </div>

                            <p>{{substr($featuredArticle->body, 0, 150)." ..."}}</p>
                        </div>

                    </article>
                    @endif
                </section>
            @endif
            
            <section id="recent" class="col-md-3 col-sm-12">
                <h2>Newest Articles</h2>
                
                @foreach($newestArticles as $categoryTitle => $category)
                    @if($category['articles']->count() > 0)
                        <article class="article-list-item">
                            <img src="{{$category['articles']->getImagePath()}}" class="img-responsive" alt="Article image alt"/>
                            
                            <div class="article-list-item-info">
                                <h4 class="article-tag">{{$categoryTitle}}</h4>

                                <div class="article-heading">
                                    <h3>{{$category['articles']->title}}</h3>
                                    <time>{{date('F d, Y', strtotime($category['articles']->created_at))}}</time>
                                </div>

                            </div>

                        </article>
                    @endif
                @endforeach
                
            </section>
                 
        </div>
    </div>
        
</div>
@endsection
