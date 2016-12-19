<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TextContent;
use App\ImageContent;
use App\TableContent;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class Content extends Model {
        
    use SingleTableInheritanceTrait;
        
    protected $fillable = ['type','element_id'];
    protected $table = "contents";
    protected static $singleTableTypeField = 'type';
    protected static $singleTableSubclasses = [TextContent::class, ImageContent::class, TableContent::class];
    
    public function page(){
        return $this->belongsTo(TemplateInstance::class);
    }
    
    public function element() {
        return $this->belongsTo(Element::class);
    }
}
