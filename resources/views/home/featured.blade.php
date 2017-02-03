<section id="featured" class="col-md-9 col-sm-12">
    @if($featuredArticle)
    <article class="featured-article">
        <img src="{{$featuredArticle->image->path}}" class="img-responsive" alt="Featured Article alt"/>

        <div class="article-info">
            @if($featuredArticle->category)
                <h4 class="article-tag">
                    <a href="{{url('/articles/'.$featuredArticle->id)}}">
                        {{$featuredArticle->category->title}}
                    </a>
                </h4>
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