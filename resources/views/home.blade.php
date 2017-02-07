@extends('layouts.app')

@section('content')
<div class="container">
    
    <div id="page-header">
            
            @if(Auth::check())
                @include('home.admin-featured')
            @else
                @include('home.featured')
            @endif
            
        <section id="featured-articles">
            <h2>Featured Articles</h2>
            
            <div class="row">
                @foreach($featuredArticles as $article)
                    <div class="col-sm-4">
                        <article class="article article-list-item">
                            
                            <div class="article-image">
                                <img src="{{$article->image->path}}" class="img-responsive" alt="Article image alt"/>
                            </div>

                            <div class="article-list-item-info">
                                @if($article->category)
                                    <h4 class="article-tag">
                                        <a href="{{url('/categories/'.$article->id)}}">
                                            {{$article->category->title}}
                                        </a>
                                    </h4>
                                @endif

                                <div class="article-heading">
                                    <h3><a href="{{url('/articles/'.$article->id)}}">{{$article->title}}</a></h3>
                                    <time>{{date('F d, Y', strtotime($article->created_at))}}</time>
                                </div>
                            </div>

                        </article>
                    </div>
                @endforeach
            </div>
            
        </section>
        
    </div>
        
</div>
@endsection
