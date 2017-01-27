@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{$category->title}} Articles</h1>

    <section class="listing">
    	<div class="row">

	        @foreach($category->articles as $article)
	        	<div class="col-md-4">
	        		<article class="article-list-item">
	        			<img src="../{{$article->image->path}}" class="img-responsive" alt="Article image alt"/>
	        		
	        			<div class="article-list-item-info">
	            			<h3>{{$article->title}}</h3>
	            		</div>
	            	</article>
	        	</div>
	        @endforeach

    	</div>
    </section>
</div>
@endsection