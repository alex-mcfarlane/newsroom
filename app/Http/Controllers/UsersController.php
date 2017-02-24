<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Newsroom\Users\UserCreator;
use App\Newsroom\Exceptions\UserException;
use Auth;

class UsersController extends Controller
{
    protected $userCreator;
    
    public function __construct(UserCreator $userCreator)
    {
        $this->userCreator = $userCreator;
    }
    
    public function create()
    {
        return view('auth.register');
    }
    
    public function store(Request $request)
    {
        try{
            $user = $this->userCreator->register($request->only('name', 'email', 'password', 'password_confirmation'));
            
            Auth::login($user);

            return redirect()->action('HomeController@index');
        } catch(UserException $ex) {
            return redirect()->back()->withErrors($ex->getErrors())->withInput();
        }
    }
}
