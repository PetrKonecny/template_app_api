<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Template;
use App\TemplateInstance;
use App\Album;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(Template::class);
    }

    //relation to its template instances
    public function templateInstances(){
        return $this->hasMany(TemplateInstance::class);
    }

    public function albums(){
        return $this->hasMany(Album::class);
    }

    //whther user is admin or not
    public function isAdmin(){
        return $this->admin;
    }

}
