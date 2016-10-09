<?php

namespace App;

use App\Content;
use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class Element extends Model {
    
    
    protected $fillable = ['width','height','positionX','positionY'];

    use SingleTableInheritanceTrait;

    protected $table = "elements";
    protected static $singleTableTypeField = 'type';  
    protected static $singleTableSubclasses = [TextElement::class, ImageElement::class];
    
    public function page(){
        return $this->belongsTo(Page::class);
    }
        
    public function contents(){
        return $this->hasMany(Content::class,'element_id');
    }
    
    public function contentsForInstance($instanceId){
        return $this->hasMany(Content::class,'element_id')->where('template_instance_id',$instanceId);
    }
    
    public function toHtml($instanceId){
        return 
        "<div style='position: absolute; overflow: hidden; width: ".$this->width."px; height: ".$this->height."px; top: ".$this->positionY."px; left: ".$this->positionX."px; background-color: black; z-index:".($this->id + 1)."; opacity:0.25;'></div>"
        ."<div style='position: absolute; overflow: hidden; width: ".$this->width."px; height: ".$this->height."px; top: ".$this->positionY."px; left: ".$this->positionX."px; z-index: ".($this->id + 2)."; font-size: 12pt;'>"
        .$this->contentsForInstance($instanceId)->first()->toHtml()
        ."</div>";
    }
}

