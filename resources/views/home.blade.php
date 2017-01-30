@extends('layouts.app')

@section('content')
<div class="container">
    
    <div id="page-header">
        <div class="row">
            
            @if(Auth::check())
                @include('home.admin-featured')
            @else
                @include('home.featured')
            @endif
            
            <section id="recent" class="col-md-3 col-sm-12">
                <h2>Newest Articles</h2>
                
                @foreach($newestArticles as $categoryTitle => $category)
                    @if($category['article'])
                        <article class="article-list-item">
                            <img src="{{$category['article']->image->path}}" class="img-responsive" alt="Article image alt"/>
                            
                            <div class="article-list-item-info">
                                <h4 class="article-tag"><a href="{{url('/categories/'.$category->id)}}">{{$categoryTitle}}</a></h4>

                                <div class="article-heading">
                                    <h3>{{$category['article']->title}}</h3>
                                    <time>{{date('F d, Y', strtotime($category['article']->created_at))}}</time>
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
