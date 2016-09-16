<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TextContent;
use App\Image;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class Content extends Model {
        
    use SingleTableInheritanceTrait;
        
    protected $fillable = ['type'];
    protected $table = "contents";
    protected static $singleTableTypeField = 'type';
    protected static $singleTableSubclasses = [TextContent::class, ImageContent::class];
    
    public function page(){
        return $this->belongsTo(TemplateInstance::class);
    }
    
    public function element() {
        return $this->belongsTo(Element::class);
    }
}

class ImageContent extends Content {

    protected static $singleTableType = 'image_content';
    public function image(){
        return $this->BelongsTo(Image::class);
    }
    
    public function toHtml() {
        return "<img src='http://localhost:8080/img/".$this->image->image_key.".".$this->image->extension."'>";
    }
    

}
