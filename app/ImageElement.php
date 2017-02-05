<?php

namespace App;
use App\Element;

class ImageElement extends Element {

    protected static $singleTableType = 'image_element';

    public function image(){
        return $this->BelongsTo(Image::class);
    }

    public function toHtml($instanceId){        
        return 
        "<img style='position: absolute; width: ".$this->width."px; height: ".$this->height."px; top: ".$this->positionY."px; left: ".$this->positionX."px; z-index: ".($this->id + 2).";' src='/img/".$this->image->image_key.".".$this->image->extension.";'>";
    }

}
