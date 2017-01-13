<?php
namespace App\Newsroom\Users;

use App\Newsroom\Validators\UserValidator;
use App\Newsroom\Exceptions\UserException;
use App\User;
use JWTAuth;

/**
 * Description of UserCreator
 *
 * @author Alex McFarlane
 */
class UserCreator {
    protected $validator;
    
    public function __construct(UserValidator $userValidator)
    {
        $this->validator = $userValidator;
    }
    public function register($input)
    {
        if(!$this->validator->isValid($input)) {
            throw new UserException($this->validator->getErrors(), 400);
        }
        
        //encrypt pswd
        $input['password'] = bcrypt($input['password']);
        
        $user = User::create($input);
        
        return JWTAuth::fromUser($user); //return token
    }
}
