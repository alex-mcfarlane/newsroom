<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Newsroom\Users\UserCreator;
use App\Newsroom\Exceptions\UserException;
use App\Http\Controllers\Controller;
use JWTAuth;
/**
 * API endpoints for users resource
 *
 * @author Alex McFarlane
 */
class UsersAPIController extends Controller{
    protected $userCreator;
    
    public function __construct(UserCreator $userCreator)
    {
        $this->userCreator = $userCreator;
    }
    
    public function store(Request $request)
    {
        try{
            $user = $this->userCreator->register($request->only('name', 'email', 'password', 'password_confirmation'));
            $token = JWTAuth::fromUser($user);
            
            return response()->json(compact('token'));
        } catch(UserException $ex) {
            return response()->json($ex->getErrors(), $ex->getHttpStatusCode());
        }
    }
}
