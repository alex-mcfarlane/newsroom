@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="row">
        
        <section class="col-sm-9">
            <div class="article article-item">
                <h4 class="article-tag">
                    <a href="{{url('/categories/'.$article->category->id)}}">
                        {{$article->category->title}}
                    </a>
                </h4>

                <div class="article-heading">
                    <h2>{{$article->title}}</h2>
                    <time>{{date('F d, Y', strtotime($article->created_at))}}</time>
                </div>

                <div class="article-image">
                    <img src="../{{$article->image->path}}" class="img-responsive" alt="Image alt">
                </div>

                <p>{{$article->body}}</p>
            </div>
        </section>
        
        <section id="recent" class="col-md-3 col-sm-12">

            <h2>Newest Articles</h2>

            @foreach($newestArticles as $categoryTitle => $category)
                @if($category['article'])
                    <article class="article article-list-item">

                        <div class="article-list-item-info">
                            <h4 class="article-tag"><a href="{{url('/categories/'.$category->id)}}">{{$categoryTitle}}</a></h4>

                            <div class="article-heading">
                                <h3><a href="{{url('/articles/'.$category['article']->id)}}">{{$category['article']->title}}</a></h3>
                                <time>{{date('F d, Y', strtotime($category['article']->created_at))}}</time>
                            </div>

                        </div>

                    </article>
                @endif
            @endforeach

        </section>
        
    </div>
</div>
@endsection