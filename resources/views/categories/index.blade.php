@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{$category->title}} Articles</h1>

    <section class="listing">
        @foreach($category->articles as $article)
            <h2>{{$article->title}}</h2>
        @endforeach
    </section>
</div>
@endsection