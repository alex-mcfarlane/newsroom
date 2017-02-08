import draggable from 'vuedraggable'

export default {
    components: {
        draggable,
    }
}

new Vue({
    el:"#headliner",
    data: {
        articles: [],
        categories: [],
        article: {},
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
            var self = this;

            this.$http.get('api/articles').then(function(response){
                self.articles = response.body;
            }, function(error){
                console.log(error);
            });
        },
        getCategories: function() {
            self = this;

            this.$http.get('api/categories').then(function(response){
                self.categories = response.body;

                self.article.category_id = self.categories[0].id;
            }, function(error){
                console.log(error);
            });
        },
        getHeadlineArticle: function() {
            var self = this;
            
            this.$http.get('api/articles?featured=1').then(function(response){
                self.headline_article = response.body[0];
                self.headline_article.body = self.headline_article.body.substring(0, 150) + " ...";

                self.headline_article_id = self.headline_article.id;
            }, function(error){
                console.log(error);
            });
        },
        changeHeadlineArticle: function(id) {
            var self = this;
            
            this.$http.put('api/articles/'+id+'/headline').then(function(response){
                self.headline_article = response.body;
                self.headline_article.body = self.headline_article.body.substring(0, 150) + " ...";
            }, function(error){
                console.log(error);
            });
        },
        createHeadlineArticle: function() {
            var self = this;
            this.article.featured = true;

            this.$http.post('api/articles', self.article).then(function(response){
                self.headline_article = response.body;
                self.headline_article.body = self.headline_article.body.substring(0, 150) + " ...";
                
                //upload image for article
                this.$http.post('api/articles/'+self.headline_article.id+'/images', self.fileFormData).then(function(response){
                    self.headline_article.image = response.body;

                    //close modal and clear entry
                    $('#add_headline').modal('toggle');
                    self.article = {};

                }, function(error){
                    console.log(error);
                })
                
            }, function(error){
                console.log(error);
            });
        },
        onFileChange: function(e) {
            this.fileFormData.append('image', e.target.files[0]);
        }
    }
});

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
            var self = this;

            this.$http.get('api/articles/featured').then(function(response){
                self.featured_articles = response.body;
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