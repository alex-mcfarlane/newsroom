<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Newsroom\Exceptions\UserException;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function createAdmin($attributes)
    {
        if(User::all()->count() > 0) {
            throw new UserException(["exists"=>"Admin user already exists"], 400);
        }

        return parent::create($attributes);
    }
    
    public static function getAdmin()
    {
        return User::first();
    }
    
    public static function registrationOpen()
    {
        return is_null(User::getAdmin());
    }
}
