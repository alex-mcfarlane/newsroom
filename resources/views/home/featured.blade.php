<section id="featured" class="col-md-12">
    @if($featuredArticle)
    <article class="featured-article">
        
        <div class="article-image">
            <img src="{{$featuredArticle->image->path}}" class="img-responsive" alt="Featured Article alt"/>
        </div>

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