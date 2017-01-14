@extends('layouts.app')

@section('content')
<div class="container">
    
    <div id="page-header">
        <div class="row">
            
            <section id="featured" class="col-md-8 col-sm-12">
                @if($featuredArticle)
                <article>
                    <h2>{{$featuredArticle->title}}</h2>
                    <h4>{{$featuredArticle->category->title}}</h4>
                    <p>{{$featuredArticle->body}}</p>
                </article>
                @endif
            </section>
            
            <section id="recent" class="col-md-4 col-sm-12">
                <h3>Newest Articles</h3>
                
                @foreach($newestArticles as $categoryTitle => $category)
                    <article>
                        <h4>{{$category['articles']->title}}</h4>
                        <p>{{$category['articles']->body}}</p>
                        
                        <div class="meta-info">
                            <time>{{date('F d, Y', strtotime($category['articles']->created_at))}}</time>
                            in
                            <a href=''>
                                {{$categoryTitle}}
                            </a>
                        </div>
                        
                        <h5></h5>
                    </article>
                @endforeach
                
            </section>
                 
        </div>
    </div>
        
</div>
@endsection
