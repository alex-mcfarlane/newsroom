import draggable from 'vuedraggable'

export default {
    components: {
        draggable,
    }
}

var auth = {
    data: {
        user: {
            "username": "",
            "email": ""
        },
    },
    methods: {
        login: function() {
            this.$http.post('api/auth', this.user).then(function(response){
                this.saveToken(response.body.token);

                var index = window.location.href.lastIndexOf('/login');
                var homeUrl = window.location.href.substring(0, index);
                window.location.href = homeUrl;
            }, function(error){
                console.log(error);
            });
        },
        isLoggedIn: function() {
            var token = this.getToken();
            
            if(token) {
                var payload = JSON.parse(window.atob(token.split('.')[1]));

                if(payload.exp > Date.now() / 1000) {
                    return true;
                }
                else{
                    return false;
                }
                
            }
            else{ return false; }
        },
        logout: function() {
            localStorage.removeItem('newsroom-token');
            window.location.href = window.location.href;
        },
        saveToken: function(token) {
            localStorage.setItem('newsroom-token', token);
        },
        getToken: function() {
            return localStorage.getItem('newsroom-token');
        },
    }
}

new Vue({
    el:"#vue-app",
    mixins: [auth],
    data: {
        articles: [],
        featured_articles: [],
        lookup: [],
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

                // Create a lookup dictionary of featured articles
                // This will allow for easier searching in the future
                for(var i = 0; i < this.articles.length; i++) {
                    var article = this.articles[i];

                    this.lookup[article.id] = article;
                }
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
        getUnfeaturedArticles: function() {
            var unFeatured = [];
            var self = this;

            var unfeatured = this.articles;

            this.featured_articles.filter(function(article){
                var index = unfeatured.indexOf(self.lookup[article.id]);
                if(index >= 0) {
                    unfeatured.splice(index, 1);
                }
            });
            
            if(unfeatured[0]) {
                //this.new_feature_article_id = unfeatured[0].id;
            }

            return unfeatured;
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
            this.$http.get('api/articles?headliner=1').then(function(response){
                this.headline_article = response.body[0];
                this.headline_article.body = this.headline_article.body.substring(0, 150) + " ...";

                this.headline_article_id = this.headline_article.id;
            }, function(error){
                console.log(error);
            });
        },
        changeHeadlineArticle: function(id) {
            this.$http.put('api/articles/'+id+'/headline', {}, {
                headers: {
                    "Authorization": "Bearer "+ this.getToken()
                }
            }).then(function(response){
                this.headline_article = response.body;
                this.headline_article.body = this.headline_article.body.substring(0, 150) + " ...";
            }, function(error){
                console.log(error);
            });
        },
        createArticle: function() {
            var token = this.getToken();

            this.$http.post('api/articles', this.article, {
                headers: {
                    "Authorization": "Bearer "+ token
                }
            }).then(function(response){
                var article = response.body;
                
                //upload image for article
                this.$http.post('api/articles/'+article.id+'/images', this.fileFormData, {
                    headers: {
                        "Authorization": "Bearer "+ token
                    }
                }).then(function(response){
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
            var token = this.getToken();
            this.article.headliner = true;

            this.$http.post('api/articles', this.article, {
                headers: {
                    "Authorization": "Bearer "+ token
                }
            }).then(function(response){
                this.headline_article = response.body;
                this.headline_article.body = this.headline_article.body.substring(0, 150) + " ...";
                
                //upload image for article
                this.$http.post('api/articles/'+this.headline_article.id+'/images', this.fileFormData, {
                    headers: {
                        "Authorization": "Bearer "+ token
                    }
                }).then(function(response){
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
            this.$http.post('api/articles/'+id+'/featured', {"order_id": orderId}, {
                headers: {
                    "Authorization": "Bearer "+ this.getToken()
                }
            }).then(function(response){
                var article = this.lookup[id];

                //push article on to featured_articles
                this.featured_articles.push(article);
            }, function(error){
                console.log(error);
            })
        },
        createCategory: function() {
            this.$http.post('api/categories', this.category, {
                headers: {
                    "Authorization": "Bearer "+ this.getToken()
                }
            }).then(function(response){
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
                }, {
                    headers: {
                        "Authorization": "Bearer "+ this.getToken()
                    }
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

new Vue({
    el: "#vue-navigation",
    mixins: [auth]
});