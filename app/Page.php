<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['elements','width','height'];

    public function template() {
        return $this->belongsTo(Template::class);
    }
    
    public function elements() {
        return $this->hasMany(Element::class);
    }
    
    public function toHtml($instanceId) {
        return 
        "<div style='position: relative; width: 100%; height: 100%;'>"
        .$this->elementsToHtml($instanceId)
        ."</div>";
    }
    
    public function elementsToHtml($instanceId) {
        $string = "";
        foreach($this->elements as $element){
            $string .= $element->toHtml($instanceId);
        }
        return $string;
    }
    
   
}
