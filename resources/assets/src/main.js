import draggable from 'vuedraggable'

export default {
    components: {
        draggable,
    }
}


new Vue({
    el:"#featured-articles",
    data:{                
        featured_articles: []
    },
    components: {
        draggable
    },
    created: function() {
        this.getFeaturedArticles();
    },
    methods: {
        getFeaturedArticles: function() {
            this.$http.get('api/articles/featured').then(function(response){
                this.featured_articles = response.body;
            }, function(error){
                console.log(error);
            })
        },
        categoryHref: function(id) {
            return 'categories/'+id;
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
        }
    }
});

new Vue({
    el:"#vue-app",
    data: {
        articles: [],
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
        add_headliner: false,
        edit_headliner: false,
        fileFormData: new FormData()
    },
    created: function () {
        this.getArticles();
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
                    $('#add_headline').modal('toggle');
                    this.article = {};

                }, function(error){
                    console.log(error);
                })
                
            }, function(error){
                console.log(error);
            });
        },
        createCategory: function() {
            this.$http.post('api/categories', this.category).then(function(response){
                $('#add-category').modal('toggle');
            }, function(error){
                console.log(error);
            });
        },
        onFileChange: function(e) {
            this.fileFormData.append('image', e.target.files[0]);
        }
    }
});