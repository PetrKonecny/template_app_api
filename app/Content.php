<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TextContent;
use App\ImageContent;
use App\TableContent;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

//content model 
class Content extends Model {
        
    //library that alows easily use single table inheritance in Eloquent models
    use SingleTableInheritanceTrait;
        
    //mass asignable params
    protected $fillable = ['type','element_id'];
    //table name for model
    protected $table = "contents";
    //field that tells what field determines type
    protected static $singleTableTypeField = 'type';
    //filed that tells it what are its types
    protected static $singleTableSubclasses = [TextContent::class, ImageContent::class, TableContent::class];
    
    //relation to template instance
    public function page(){
        return $this->belongsTo(TemplateInstance::class);
    }
    
    //relation to eleemnt
    public function element() {
        return $this->belongsTo(Element::class);
    }
}
