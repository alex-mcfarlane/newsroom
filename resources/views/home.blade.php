@extends('layouts.app')

@section('content')
    
    <div id="page-header">
            
        @if(Auth::check())
            @include('home.admin-featured')
        @else
            @include('home.featured')
        @endif
            
        <section id="featured-articles">
            <h2>Featured Articles</h2>
            <div class="row">
                @if(Auth::check())
                    <draggable :list="featured_articles" @change="onChange">
                        <div v-for="featured_article in featured_articles" class="col-sm-4">
                            <article class="article article-list-item" 
                                v-bind:data-article-id="featured_article.id"
                                v-bind:data-order-id="featured_article.order">

                                <div class="article-image">
                                    <img :src="featured_article.image.path" class="img-responsive" alt="Article image alt"/>
                                </div>

                                <div class="article-list-item-info">
                                    <h4 v-if="featured_article.category" class="article-tag">
                                        <a v-bind:href="'categories/'+featured_article.category.id">
                                            @{{featured_article.category.title}}
                                        </a>
                                    </h4>

                                    <div class="article-heading">
                                        <h3><a v-bind:href="'articles/'+featured_article.id">@{{featured_article.title}}</a></h3>
                                        <time>@{{featured_article.created_at}}</time>
                                    </div>
                                </div>

                            </article>
                        </div>
                    </draggable>
                @else
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
                @endif

            </div>
            
        </section>
        
    </div>
@endsection
