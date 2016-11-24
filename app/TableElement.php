<?php

namespace App;
use App\Element;
use App\Font;

class TableElement extends Element {

    protected static $singleTableType = 'table_element';
    
    protected $fillable = ['rows','positionX','positionY'];
    
    public function toHtml($instanceId){
        
    }

}

?>