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
    <nav id="vue-navigation" class="navbar navbar-default navbar-static-top">
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
                <h1>
                    <a class="navbar-brand" href="{{ url('/') }}">
                        NewsRoom
                    </a>
                </h1>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @foreach($categories as $category)
                        <li><a href="{{ url('/categories/'.$category->id) }}">{{$category->title}}</a></li>
                    @endforeach
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right" id="vue-navigation">
                    <!-- Authentication Links -->
                    
                    <li v-if="!isLoggedIn()"><a href="{{ url('/login') }}">Login</a></li>
                    <li v-if="!isLoggedIn()"><a href="{{ url('/register') }}">Register</a></li>
                
                    <li v-if="isLoggedIn()" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="" data-toggle="modal" data-target="#add-article">Add Article</a></li>
                            <li><a href="" data-toggle="modal" data-target="#add-category">Add Category</a></li>
                        </ul>
                    </li>
                    <li v-if="isLoggedIn()" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Hello <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="" v-on:click="logout()"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="vue-app" class="container">
        @yield('content')

        <div id="add-resources">

            <div id="add-article" class="modal fade" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3>Add an Article</h3>
                        </div>

                        <div class="modal-body">
                            <form v-on:submit.prevent="createArticle">
                                
                                <div class="form-group">
                                    <label for="new-feature-title">Title</label>
                                    
                                    <input v-model="article.title" class="form-control"></input>
                                </div>
                                
                                <div class="form-group">
                                    <label for="new-feature-body">Body</label>
                                    
                                    <textarea v-model="article.body" class="form-control"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="new-headline-category">Category</label>

                                    <select v-model="article.category_id" class="form-control">
                                        <option v-for="category in categories" v-bind:value="category.id">
                                            @{{ category.title }}
                                        </option>
                                    </select>                               
                                </div>

                                <div class="form-group">
                                    <label for="new-headline-category">Headliner</label>

                                    <select v-model="article.featured" class="form-control">
                                        <option value="0">
                                            No
                                        </option>
                                        <option value="1">
                                            Yes
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

            <div id="add-category" class="modal fade" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3>Create New Category</h3>
                        </div>

                        <div class="modal-body">
                            <form v-on:submit.prevent="createCategory">
                                
                                <div class="form-group">
                                    <label for="new-feature-title">Title</label>
                                    
                                    <input v-model="category.title" class="form-control"></input>
                                </div>
                                
                                <div class="form-group">
                                    <label for="new-feature-body">Description</label>
                                    
                                    <textarea v-model="category.description" class="form-control"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-success">Create</button>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end of #add-resources -->

    </div> <!-- end of #vue-app -->

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.0.3/vue-resource.min.js"></script>
    <script src="{{ asset('../resources/assets/dist/build.js') }}"></script>
    
    <script>

        
    </script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
