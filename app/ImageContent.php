<?php

namespace App;
use App\Content;

class ImageContent extends Content {

    protected static $singleTableType = 'image_content';
    public function image(){
        return $this->BelongsTo(Image::class);
    }
    
    public function toHtml() {
        return "<img src='http://localhost:8080/img/".$this->image->image_key.".".$this->image->extension."'>";
    }
    

}