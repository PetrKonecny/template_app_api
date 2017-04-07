<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Template;
use App\Content;
use App\User;

class TemplateInstance extends Model {
    use \Conner\Tagging\Taggable;

    protected $fillable = ['name'];
   
     public function template(){
        return $this->belongsTo(Template::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function contents(){
        return $this->hasMany(Content::class);
    }
    
    public function toHtml(){
        return $this->template->toHtml($this->id);
    }
}
