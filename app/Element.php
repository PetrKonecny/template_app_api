<?php

namespace App;

use App\Content;
use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

//element model
class Element extends Model {
    
    
    protected $fillable = ['width','height','positionX','positionY','positionZ','font_size','background_color','text_color','opacity'];

    protected static function boot() {
        parent::boot();

        //this tells it to delete all its contents on delete
        static::deleting(function($element) { // before delete() method call this
             $element->content()->delete();
             // do the rest of the cleanup...
        });
    }

    //library that alows easily use single table inheritance in Eloquent models
    use SingleTableInheritanceTrait;
    
    //table name for model
    protected $table = "elements";
    //field that tells what field determines type
    protected static $singleTableTypeField = 'type';
    //filed that tells it what are its types  
    protected static $singleTableSubclasses = [TextElement::class, ImageElement::class, TableElement::class, FrameElement::class];
    
    //relation to the page
    public function page(){
        return $this->belongsTo(Page::class);
    }
        
    //relation to its contents
    public function contents(){
        return $this->hasMany(Content::class,'element_id');
    }
    
    //relation to one default content
    public function content(){
        return $this->hasOne(Content::class,'element_id')->where('template_instance_id',0);
    }
    
    //relation to contents for specific instance
    public function contentsForInstance($instanceId){
        return $this->hasMany(Content::class,'element_id')->where('template_instance_id',$instanceId);
    }
    
    //creates html representation from the model
    public function toHtml($instanceId){
        $content = $this->contentsForInstance($instanceId)->first();
        if($content == null){
            $content = $this->content()->first();
        }
        $contentHtml = "";
        if($content != null){
            $contentHtml = $content->toHtml();
        }
        $opacity = $this->opacity > 0 ? $this->opacity/100 : 100;
        return 
        "<div style='position: absolute; overflow: hidden; width: ".$this->width."px; background-color:".$this->background_color."; height:" .$this->height."px; top: ".$this->positionY."px; left: ".$this->positionX."px; font-size: 12pt;' opacity: ".$opacity." z-index: ".$this->positionZ.";>"
        .$contentHtml
        ."</div>";
    }
}

