<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Content;

class Image extends Model
{
        protected $fillable = ['image','name'];
        
        public function imageContent(){
            return $this->hasOne(ImageContent::class);
        }

}
