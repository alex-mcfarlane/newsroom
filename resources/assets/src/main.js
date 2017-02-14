import draggable from 'vuedraggable'

export default {
    components: {
        draggable,
    }
}

new Vue({
    el:"#vue-app",
    data: {
        articles: [],
        featured_articles: [],
        categories: [],
        article: {},
        category: {},
        headline_article: {
            image:
            {
                path: ''
            }
        },
        headline_article_id: 1,
        new_feature_article_id: 1,
        add_headliner: false,
        edit_headliner: false,
        fileFormData: new FormData()
    },
    components: {
        draggable
    },
    created: function () {
        this.getArticles();
        this.getFeaturedArticles();
        this.getCategories();
        this.getHeadlineArticle();
    },
    methods: {
        getArticles: function() {
            this.$http.get('api/articles').then(function(response){
                this.articles = response.body;
            }, function(error){
                console.log(error);
            });
        },
        getFeaturedArticles: function() {
            this.$http.get('api/articles/featured').then(function(response){
                this.featured_articles = response.body;
            }, function(error){
                console.log(error);
            })
        },
        getCategories: function() {
            this.$http.get('api/categories').then(function(response){
                this.categories = response.body;

                this.article.category_id = this.categories[0].id;
            }, function(error){
                console.log(error);
            });
        },
        getHeadlineArticle: function() {
            this.$http.get('api/articles?featured=1').then(function(response){
                this.headline_article = response.body[0];
                this.headline_article.body = this.headline_article.body.substring(0, 150) + " ...";

                this.headline_article_id = this.headline_article.id;
            }, function(error){
                console.log(error);
            });
        },
        changeHeadlineArticle: function(id) {
            this.$http.put('api/articles/'+id+'/headline').then(function(response){
                this.headline_article = response.body;
                this.headline_article.body = this.headline_article.body.substring(0, 150) + " ...";
            }, function(error){
                console.log(error);
            });
        },
        createArticle: function() {
            this.$http.post('api/articles', this.article).then(function(response){
                var article = response.body;
                
                //upload image for article
                this.$http.post('api/articles/'+article.id+'/images', this.fileFormData).then(function(response){
                    article.image = response.body;

                    //add new article to list of articles
                    this.articles.push(article);
                    //close modal and clear entry
                    $('#add-article').modal('toggle');
                    this.article = {};

                }, function(error){
                    console.log(error);
                })
                
            }, function(error){
                console.log(error);
            });
        },
        createHeadlineArticle: function() {
            this.article.featured = true;

            this.$http.post('api/articles', this.article).then(function(response){
                this.headline_article = response.body;
                this.headline_article.body = this.headline_article.body.substring(0, 150) + " ...";
                
                //upload image for article
                this.$http.post('api/articles/'+this.headline_article.id+'/images', this.fileFormData).then(function(response){
                    this.headline_article.image = response.body;

                    //close modal and clear entry
                    $('#add_headliner').modal('toggle');
                    this.article = {};

                }, function(error){
                    console.log(error);
                })
                
            }, function(error){
                console.log(error);
            });
        },
        featureArticle: function(id) {
            var orderId = this.featured_articles.length + 1;
            this.$http.post('api/articles/'+id+'/featured', {"order_id": orderId}).then(function(response){
                // TODO:: push article on to featured_articles
            }, function(error){
                console.log(error);
            })
        },
        createCategory: function() {
            this.$http.post('api/categories', this.category).then(function(response){
                //add to list of categories
                this.categories.push(response.body);
                $('#add-category').modal('toggle');
            }, function(error){
                console.log(error);
            });
        },
        onChange: function(object) {
            if(object.hasOwnProperty("moved")) {
                this.$http.post('api/articles/'+object.moved.element.id+'/featured', {
                    "order_id": object.moved.newIndex + 1
                }).then(function(response){

                }, function(error){
                    console.log(error);
                });
            }
        },
        onFileChange: function(e) {
            this.fileFormData.append('image', e.target.files[0]);
        }
    }
});