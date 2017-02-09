<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Template;
use App\TemplateInstance;

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


    public function templates(){
        $this->hasMany(Template::class);
    }

    public function templateInstances(){
        $this->hasMany(TemplateInstance::class);
    }

}
