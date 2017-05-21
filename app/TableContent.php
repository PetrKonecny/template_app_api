<?php

namespace App;
use App\Content;
use App\Font;

//content of the table
class TableContent extends Content {

    protected static $singleTableType = 'table_content';
    
    protected $fillable = ['rows','positionX','positionY'];
    
    //empty because this content is already html representantion
    public function toHtml($instanceId){
        
    }

}

?>