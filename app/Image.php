<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Album;

//image model
class Image extends Model
{
    protected $fillable = ['image','name'];
    
    //relation to album image belongs to
    public function album(){
    	return $this->belongsTo(Album::class);
    }

}
