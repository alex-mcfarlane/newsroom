@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{$category->title}} Articles</h1>

    <section class="listing">
    	<div class="row">

	        @foreach($category->articles as $article)
	        	<div class="col-md-4">
	        		<article class="article-list-item">

	        			<div class="article-image">
	        				<img src="../{{$article->image->path}}" class="img-responsive" alt="Article image alt"/>
	        			</div>

	        			<div class="article-list-item-info">
	        				<h4 class="article-tag">
	        					<a href="{{url('/categories/'.$category->id)}}">
	        						{{$category->title}}
	        					</a>
	        				</h4>

	        				<div class="article-heading">
	            				<h3>{{$article->title}}</h3>
	            				<time>{{date('F d, Y', strtotime($article->created_at))}}</time>
	            			</div>
	            			
	            			<p>{{substr($article->body, 0, 150)." ..."}}</p>
	            		</div>

	            	</article>
	        	</div>
	        @endforeach

    	</div>
    </section>
</div>
@endsection