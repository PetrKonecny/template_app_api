<?php

namespace App;
use App\Content;

//image content model
class ImageContent extends Content {

    protected static $singleTableType = 'image_content';
    protected $fillable = ['width','height','top','left'];

    public function image(){
        return $this->BelongsTo(Image::class);
    }
    
    //html representation of image content and its image
    public function toHtml() {
        if($this->image == null){
            return "";
        }else{
            $link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";        
            return "<div style='position: absolute; left:".$this->left."px; top:".$this->top."px;'><img style='width:".$this->width."px ; height: ".$this->height."px;' src='".$link."/img/".$this->image->image_key.".".$this->image->extension."'></div>";
        }
    }
    

}