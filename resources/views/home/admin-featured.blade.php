<section id="headliner">

    <div class="edit-overlay">
        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#add_headliner">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New Headline Article
        </button>

        <button v-on:click="edit_headliner = true" type="button" class="btn btn-default btn-sm">
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>Change Headline Article
        </button>
        
        <div v-if="edit_headliner">
            <select v-model="headline_article_id">
                <option v-for="article in articles" v-bind:value="article.id">
                    @{{ article.title }}
                </option>
            </select>

            <button v-on:click="changeHeadlineArticle(headline_article_id)" class="btn btn-default btn-sm">Change Headline Article</button>
        </div>
    </div>

    <article v-if="headline_article" class="featured-article">

        <div class="article-image">
            <img :src="headline_article.image.path" class="img-responsive" alt="headliner Article alt"/>
        </div>

        <div class="article-info">
            <h4 v-if="headline_article.category" class="article-tag">
                <a v-bind:href="'categories/'+headline_article.category.id">
                    @{{headline_article.category.title}}
                </a>
            </h4>

            <div class="article-heading">
                <h2>
                    <a v-bind:href="'articles/'+headline_article.id">
                        @{{headline_article.title}}
                    </a>
                </h2>
                <time>@{{headline_article.created_at}}</time>
            </div>

            <p>@{{headline_article.body}}</p>
        </div>

    </article>

    <div id="add_headliner" class="modal fade" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3>Create New Headline Article</h3>
                </div>

                <div class="modal-body">
                    <form v-on:submit.prevent="createHeadlineArticle">
                        
                        <div class="form-group">
                            <label for="new-feature-title">Title</label>
                            
                            <input v-model="article.title" id="new-headline-title" class="form-control"></input>
                        </div>
                        
                        <div class="form-group">
                            <label for="new-feature-body">Body</label>
                            
                            <textarea v-model="article.body" id="new-headline-body" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="new-headline-category">Category</label>

                            <select v-model="article.category_id" id="new-headline-category" class="form-control">
                                <option v-for="category in categories" v-bind:value="category.id">
                                    @{{ category.title }}
                                </option>
                            </select>                               
                        </div>

                        <div class="form-group">
                            <label for="new-headline-image">Image</label>

                            <input v-on:change="onFileChange" type="file"></input>
                        </div>
                        
                        <button type="submit" class="btn btn-success">Create</button>
                        
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>