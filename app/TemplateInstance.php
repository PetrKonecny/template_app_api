<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Template;
use App\Content;
use App\User;

//model for template instance (document)
class TemplateInstance extends Model {
    //using library to manage tags
    use \Conner\Tagging\Taggable;

    protected $fillable = ['name'];
   
   //relation to its template
     public function template(){
        return $this->belongsTo(Template::class);
    }

    //relation to user who created it
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    //relation to its contents
    public function contents(){
        return $this->hasMany(Content::class);
    }
    
    //transforms template instance into hrml
    public function toHtml(){
        return $this->template->toHtml($this->id);
    }
}
