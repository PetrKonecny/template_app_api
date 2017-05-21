<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Template;
use App\TemplateInstance;

//model for user that is authenticable
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'token_id', 'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    //relation to its templates
    public function templates(){
        $this->hasMany(Template::class);
    }

    //relation to its template instances
    public function templateInstances(){
        $this->hasMany(TemplateInstance::class);
    }

    //whther user is admin or not
    public function isAdmin(){
        return $this->admin;
    }

}
