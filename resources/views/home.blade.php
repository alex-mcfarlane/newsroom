@extends('layouts.app')

@section('content')
<div class="container">
    
    <div id="page-header">
        <div class="row">
            
            <section id="featured" class="col-md-8 col-sm-12">
                <article>
                    <h2>{{$featuredArticle->title}}</h2>
                    <h4>{{$featuredArticle->category->title}}</h4>
                    <p>{{$featuredArticle->body}}</p>
                </article>
            </section>
            
            <section id="recent" class="col-md-4 col-sm-12">
                <h3>Newest in...</h3>
                
                @foreach($newestArticles as $categoryTitle => $category)
                    <h4>{{$categoryTitle}}</h4>
                    
                    <article>
                        <h3>{{$category['articles']->title}}</h3>
                        <p>{{$category['articles']->body}}</p>
                    </article>
                @endforeach
                
            </section>
                 
        </div>
    </div>
        
</div>
@endsection
