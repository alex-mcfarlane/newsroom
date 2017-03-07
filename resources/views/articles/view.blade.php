@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/styles/default.min.css">
@endsection

@section('content')
    <div class="row">
        
        <section class="col-sm-9">
            <div v-if="isLoggedIn()">
                <div class="edit-overlay right">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit-article">Edit Article</button>
                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-article">Delete Article</button>
                </div>
            </div>
            <article class="article article-item">
                @if($article->category)
                    <h4 class="article-tag">
                        <a href="{{url('/categories/'.$article->category->id)}}">
                            {{$article->category->title}}
                        </a>
                    </h4>
                @endif

                <section class="article-heading">
                    <h2>{{$article->title}}</h2>
                    <time>{{date('F d, Y', strtotime($article->created_at))}}</time>
                </section>

                <section class="article-image">
                    <img src="../{{$article->image->path}}" class="img-responsive" alt="Image alt">
                </section>

                <section class="article-body">
                    @if(!empty($article->sub_title))
                        <h3 class="sub-title">{{$article->sub_title}}</h3>
                    @endif
                    <p>{!!html_entity_decode($article->body)!!}</p>
                </section>
                
            </article>
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

    <div id="edit-article" class="modal fade" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3>Edit {{$article->title}}</h3>
                </div>

                <div class="modal-body">
                    <form action="{{url('articles/'.$article->id)}}" method="POST" enctype="multipart/form-data">
                        
                        {{ csrf_field() }}

                        <input type="hidden" name="_method" value="PUT"/>

                        <div class="form-group">
                            <label for="title">Title</label>
                            
                            <input type="text" id="title" name="title" class="form-control" value="{{$article->title}}"></input>
                        </div>

                        <div class="form-group">
                            <label for="sub-title">Sub Title</label>
                            
                            <input type="text" id="sub_title" name="sub_title" class="form-control" value="{{$article->sub_title}}"></input>
                        </div>
                        
                        <div class="form-group">
                            <label for="body">Body</label>
                            
                            <textarea id="body" name="body" class="form-control">{{$article->body}}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="category_id">Category</label>

                            <select id="category_id" name="category_id" class="form-control">
                                @foreach($categories as $category)

                                    <option value="{{$category->id}}" {{$article->category == $category ? "selected='selected'" : ''}}>
                                        {{ $category->title }}
                                    </option>

                                @endforeach
                            </select>                               
                        </div>

                        <div class="form-group">
                            <label for="image">Image (leave blank to keep original)</label>

                            <input type="file" id="image" name="image"></input>
                        </div>

                        <div class="form-group">
                            <label for="headliner">Headline Article</label>

                            <select id="headliner" name="headliner" class="form-control">
                                <option value="0">
                                    No
                                </option>
                                <option value="1" {{$article->headliner ? "selected='selected'" : ''}}>
                                    Yes
                                </option>
                            <select>
                        </div>
                        
                        <button type="submit" class="btn btn-success">Save</button>
                        
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div id="delete-article" class="modal fade" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3>Delete {{$article->title}}</h3>
                </div>

                <div class="modal-body">
                    <form method="POST" action="{{url('articles/'.$article->id)}}">
                        {{ csrf_field() }}

                        <input type="hidden" name="_method" value="DELETE"/>

                        <p>Are you sure you want to delete this article?</p>

                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form> 
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/highlight.min.js"></script>
    
    <script>hljs.initHighlightingOnLoad();</script>
@endsection