@extends('layouts.app')

@section('content')
    <div class="row">
        
        <section class="col-sm-9">
            @if(Auth::check())
                <div class="edit-overlay right">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit_article">Edit Article</button>
                </div>
            @endif
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

    <div id="edit_article" class="modal fade" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3>Edit {{$article->title}}</h3>
                </div>

                <div class="modal-body">
                    <form action="{{url('articles/'.$article->id)}}" method="POST">
                        
                        {{ csrf_field() }}

                        <input type="hidden" name="_method" value="PUT"/>
                        
                        <div class="form-group">
                            <label for="title">Title</label>
                            
                            <input type="text" id="title" name="title" class="form-control" value="{{$article->title}}"></input>
                        </div>
                        
                        <div class="form-group">
                            <label for="body">Body</label>
                            
                            <textarea id="body" name="body" class="form-control">{{$article->body}}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="category_id">Category</label>

                            <select id="category_id" name="category_id" class="form-control">
                                @foreach($categories as $category)

                                    <option value="{{$category->id}}" {{$article->category->id == $category->id ? "selected='selected'" : ''}}>
                                        {{ $category->title }}
                                    </option>

                                @endforeach
                            </select>                               
                        </div>

                        <div class="form-group">
                            <label for="headliner">Headline Article</label>

                            <select id="headliner" name="featured" class="form-control">
                                <option value="0">
                                    No
                                </option>
                                <option value="1" {{$article->featured ? "selected='selected'" : ''}}>
                                    Yes
                                </option>
                            <select>
                        </div>
                        
                        <button type="submit" class="btn btn-success">Create</button>
                        
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection