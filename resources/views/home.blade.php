@extends('layouts.app')

@section('content')
    
    <div id="page-header">
            
        <div v-if="isLoggedIn()">
            @include('home.admin-headline')
        </div>
        <div v-else>
            @include('home.headline')
        </div>
            
        <section id="featured-articles">
            <h2>Featured Articles</h2>
            <div class="row">
                <div v-if="isLoggedIn()">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-4">
                                <select v-model="new_feature_article_id" class="form-control">
                                    <option v-for="article in getUnfeaturedArticles()" v-bind:value="article.id">@{{article.title}}</option>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <button v-on:click="featureArticle(new_feature_article_id)" class="btn btn-sm btn-primary">Feature an Article</button>
                            </div>
                        </div>
                    </div>
                
                    <draggable :list="featured_articles" @change="onChange">

                        <div v-for="(featured_article, index) in featured_articles">
                            <div v-if="index % 3 == 0" class="clearfix"></div>

                            <div class="col-sm-4">
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
                        </div>

                    </draggable>
                </div>

                <div v-else>
                    @foreach($featuredArticles as $index => $article)
                        @if($index % 3 ==0)
                            <div class="clearfix"></div>
                        @endif

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

            </div>
            
        </section>
        
    </div>
@endsection
