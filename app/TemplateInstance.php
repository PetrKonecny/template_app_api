<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Template;
use App\Content;

class TemplateInstance extends Model {
    
    protected $fillable = ['name'];
   
     public function template(){
        return $this->belongsTo(Template::class);
    }
    
    public function contents(){
        return $this->hasMany(Content::class);
    }
    
    public function toHtml(){
        return $this->template->toHtml($this->id);
    }
}
