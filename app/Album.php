<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Image;
use App\User;

//model for Album
class Album extends Model {
    use \Conner\Tagging\Taggable;  
              
    //mass asignable parameters 
    protected $fillable = ['name','public'];
    
    //relation to images 
    public function images(){
        return $this->hasMany(Image::class);
    }

    //relation to user
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function isDemo() {
        return $this->name == "Demo";
    }
    
}
