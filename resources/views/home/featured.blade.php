<section id="featured">
    @if($headlineArticle)
    <article class="featured-article">
        
        <div class="article-image">
            <img src="{{$headlineArticle->image->path}}" class="img-responsive" alt="Featured Article alt"/>
        </div>

        <div class="article-info">
            @if($headlineArticle->category)
                <h4 class="article-tag">
                    <a href="{{url('/articles/'.$headlineArticle->id)}}">
                        {{$headlineArticle->category->title}}
                    </a>
                </h4>
            @endif

            <div class="article-heading">
                <h2>{{$headlineArticle->title}}</h2>
                <time>{{date('F d, Y', strtotime($headlineArticle->created_at))}}</time>
            </div>

            <p>{{substr($headlineArticle->body, 0, 150)." ..."}}</p>
        </div>

    </article>
    @endif
</section>