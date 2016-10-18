<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TextContent;
use App\ImageContent;
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
