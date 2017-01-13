<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Newsroom\Users\UserCreator;
use App\Newsroom\Exceptions\UserException;

class UsersController extends Controller
{
    protected $userCreator;
    
    public function __construct(UserCreator $userCreator)
    {
        $this->userCreator = $userCreator;
    }
    
    public function create()
    {
        return view('users.create');
    }
    
    public function store(Request $request)
    {
        try{
            $token = $this->userCreator->register($request->only('name', 'email', 'password', 'password_confirmation'));
            return response()->json(compact('token'));
        } catch(UserException $ex) {
            return redirect()->back()->withErrors($ex->getErrors())->withInput();
        }
    }
}
