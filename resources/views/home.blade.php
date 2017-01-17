@extends('layouts.app')

@section('content')
<div class="container">
    
    <div id="page-header">
        <div class="row">
            
            <section id="featured" class="col-md-8 col-sm-12">
                @if($featuredArticle)
                <article class="featured-article">
                    <img src="{{$featuredArticle->image->path}}" class="img-responsive" alt="Featured Article alt"/>

                    <div class="article-info">
                        <h4 class="article-tag">{{$featuredArticle->category->title}}</h4>

                        <div class="article-heading">
                            <h2>{{$featuredArticle->title}}</h2>
                            <time>{{date('F d, Y', strtotime($featuredArticle->created_at))}}</time>
                        </div>

                        <p>{{$featuredArticle->body}}</p>
                    </div>

                </article>
                @endif
            </section>
            
            <section id="recent" class="col-md-4 col-sm-12">
                <h3>Newest Articles</h3>
                
                @foreach($newestArticles as $categoryTitle => $category)
                    @if($category['articles']->count() > 0)
                        <article>

                            <div class="article-list-item-info">
                                <h4 class="article-tag">{{$categoryTitle}}</h4>

                                <div class="article-heading">
                                    <h3>{{$category['articles']->title}}</h3>
                                    <time>{{date('F d, Y', strtotime($category['articles']->created_at))}}</time>
                                </div>

                                <p>{{$category['articles']->body}}</p>
                            </div>

                        </article>
                    @endif
                @endforeach
                
            </section>
                 
        </div>
    </div>
        
</div>
@endsection
