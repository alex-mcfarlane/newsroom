@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">

                    <div v-if="errors.length > 0" class="alert alert-danger">
                        <ul>
                            <li v-for="error in errors">
                                @{{ error }}
                            </li>
                        </ul>
                    </div>

                    <form class="form-horizontal" role="form" method="POST" v-on:submit.prevent="login">

                        <div class="form-group"
                             v-bind:class="{'has-error': form_errors.email}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" v-model="user.email">

                                    <span v-if="form_errors.email" class="help-block">
                                        <strong>@{{ form_errors.email[0] }}</strong>
                                    </span>
                            </div>
                        </div>

                        <div class="form-group"
                             v-bind:class="{'has-error': form_errors.password}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" v-model="user.password">

                                <span v-if="form_errors.password" class="help-block">
                                    <strong>@{{form_errors.password[0]}}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Login
                                </button>

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
