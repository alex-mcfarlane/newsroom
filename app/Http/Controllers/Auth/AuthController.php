<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Validator;
use App\Newsroom\Validators\AuthValidator;

class AuthController extends Controller
{
    protected $errors = [];
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'register']]);
    }
    
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        $authValidator = new AuthValidator();
        
        if(!$authValidator->isValid($credentials)) {
            return response()->json(['errors' => $authValidator->getErrors()], 400);
        }
        
        try{
            //verify credentials and authenticate user
            if(! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['errors' => ['Invalid login credentials']], 401);
            }
        } catch (JWTException $ex) {
            // something went wrong
            return response()->json(['errors' => ['could_not_create_token']], 500);
        }
        
        return response()->json(compact('token'));
    }
    
    public function register(Request $request)
    {
        $userInfo = $request->only('name', 'email', 'password', 'password_confirmation');
        
        if(!$this->validation($userInfo)) {
            return response()->json($this->errors, 400);
        }
        
        $userInfo['password'] = bcrypt($userInfo['password']);
        if(! $user = User::create($userInfo)) {
            return response()->json('error creating user', 400);
        }
        
        $token = JWTAuth::fromUser($user);
        
        return response()->json(compact('token'));
    }
    
    public function validation($input) {
        $validator = Validator::make($input, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        
        if($validator->fails()) {
            $this->errors = $validator->errors();
            return false;
        }
        
        return true;
    }
}