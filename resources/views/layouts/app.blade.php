<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:100,300,400,600,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('../resources/assets/sass/app.css') }}">

    <style>
        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Newsroom
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">Articles</a></li>
                    <li><a href="{{ url('/home') }}">Archives</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.0.3/vue-resource.min.js"></script>
    
    <script>
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
        })
    </script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
