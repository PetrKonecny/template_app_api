<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TextElement;
//font model
class Font extends Model
{
    protected $fillable = [];
        
    public function textElements(){
        return $this->hasMany(TextElement::class);
    }
    
}
