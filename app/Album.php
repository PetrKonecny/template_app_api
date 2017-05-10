<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Image;

class Album extends Model {
    use \Conner\Tagging\Taggable;  
              
    protected $fillable = ['name','public'];
    
    public function images(){
        return $this->hasMany(Image::class);
    }
    
}
