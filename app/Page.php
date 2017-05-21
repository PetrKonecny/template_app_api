<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//page model
class Page extends Model
{
    protected $fillable = ['width','height'];

    //relation to its template
    public function template() {
        return $this->belongsTo(Template::class);
    }
    
    //relation to its elements
    public function elements() {
        return $this->hasMany(Element::class);
    }
    
    /**
    * creates html representation for the page
    * @param instanceId - id of template instatnce which contents should be inserted
    * @return html string
    */ 
    public function toHtml($instanceId) {
        return 
        "<div style='position: relative; width: 100%; height: 100%;'>"
        .$this->elementsToHtml($instanceId)
        ."</div>";
    }
    
    //helper function to get page elements into html
    public function elementsToHtml($instanceId) {
        $string = "";
        foreach($this->elements as $element){
            $string .= $element->toHtml($instanceId);
        }
        return $string;
    }
    
   
}
