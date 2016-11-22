<?php

namespace App;
use App\Content;
use App\Font;

class TableContent extends Content {

    protected static $singleTableType = 'table_content';
    
    protected $fillable = ['rows','positionX','positionY'];
    
    public function toHtml($instanceId){
        
    }

}

?>