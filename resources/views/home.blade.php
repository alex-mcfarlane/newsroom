@extends('layouts.app')

@section('content')
<div class="container">
    
    <div id="page-header">
        <div class="row">
            
            @if(Auth::check())
                <section id="featured" class="col-md-9 col-sm-12">

                    <div class="edit-overlay">
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#add_feature">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New Feature Article
                        </button>

                        <button v-on:click="edit_feature = true" type="button" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>Change Feature Article
                        </button>
                        
                        <div v-if="edit_feature">
                            <select v-model="feature_article_id">
                                <option v-for="article in articles" v-bind:value="article.id">
                                    @{{ article.title }}
                                </option>
                            </select>

                            <button v-on:click="changeFeatureArticle(feature_article_id)" class="btn btn-default btn-sm">Change Feature Article</button>
                        </div>
                    </div>

                    <article class="featured-article">

                        <img v-bind:src="feature_article.image.path" class="img-responsive" alt="Featured Article alt"/>

                        <div class="article-info">
                            <h4 v-if="feature_article.category" class="article-tag">@{{feature_article.category.title}}</h4>

                            <div class="article-heading">
                                <h2>@{{feature_article.title}}</h2>
                                <time>@{{feature_article.created_at}}</time>
                            </div>

                            <p>@{{feature_article.body}}</p>
                        </div>

                    </article>
                    
                    <div id="add_feature" class="modal fade" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h3>Create New Feature Article</h3>
                                </div>

                                <div class="modal-body">
                                    <form v-on:submit.prevent="createFeatureArticle">
                                        
                                        <div class="form-group">
                                            <label for="new-feature-title" class="control-label">Title</label>
                                            
                                            <input v-model="article.title" id="new-feature-title"></input>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="new-feature-body" class="control-label">Body</label>
                                            
                                            <textarea v-model="article.body" id="new-feature-body"></textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-success">Create</button>
                                        
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>
            
            @else
                <section id="featured" class="col-md-9 col-sm-12">
                    @if($featuredArticle)
                    <article class="featured-article">
                        <img src="{{$featuredArticle->image->path}}" class="img-responsive" alt="Featured Article alt"/>

                        <div class="article-info">
                            @if($featuredArticle->category)
                                <h4 class="article-tag">{{$featuredArticle->category->title}}</h4>
                            @endif

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
                    @if($category['article'])
                        <article class="article-list-item">
                            <img src="{{$category['article']->image->path}}" class="img-responsive" alt="Article image alt"/>
                            
                            <div class="article-list-item-info">
                                <h4 class="article-tag"><a href="{{url('/categories/'.$category->id)}}">{{$categoryTitle}}</a></h4>

                                <div class="article-heading">
                                    <h3>{{$category['articles'][0]->title}}</h3>
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
