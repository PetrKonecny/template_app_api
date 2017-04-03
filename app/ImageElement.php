<?php

namespace App;
use App\Element;

class ImageElement extends Element {

    protected static $singleTableType = 'image_element';

    public function image(){
        return $this->BelongsTo(Image::class);
    }

    public function toHtml($instanceId){
        $opacity = $this->opacity > 0 ? $this->opacity/100 : 1;
        $link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";        
        return 
        "<img style='position: absolute; width: ".$this->width."px; opacity: ".$opacity."; height: ".$this->height."px; top: ".$this->positionY."px; left: ".$this->positionX."px; z-index: ".$this->positionZ.";' src='".$link."/img/".$this->image->image_key.".".$this->image->extension."'>";
    }

}
