<?php

namespace App;

use App\Content;
use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class Element extends Model {
    
    
    protected $fillable = ['width','height','positionX','positionY','positionZ','font_size','background_color','text_color','opacity'];

    protected static function boot() {
        parent::boot();

        
        static::deleting(function($element) { // before delete() method call this
             $element->content()->delete();
             // do the rest of the cleanup...
        });
    }

    use SingleTableInheritanceTrait;

    protected $table = "elements";
    protected static $singleTableTypeField = 'type';  
    protected static $singleTableSubclasses = [TextElement::class, ImageElement::class, TableElement::class, FrameElement::class];
    
    public function page(){
        return $this->belongsTo(Page::class);
    }
        
    public function contents(){
        return $this->hasMany(Content::class,'element_id');
    }
    
    public function content(){
        return $this->hasOne(Content::class,'element_id')->where('template_instance_id',0);
    }
    
    public function contentsForInstance($instanceId){
        return $this->hasMany(Content::class,'element_id')->where('template_instance_id',$instanceId);
    }
    
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
        "<div style='position: absolute; overflow: hidden; width: ".$this->width."px; height: ".$this->height."px; top: ".$this->positionY."px; left: ".$this->positionX."px; font-size: 12pt;' opacity: ".$opacity." z-index: ".$this->positionZ.";>"
        .$contentHtml
        ."</div>";
    }
}

