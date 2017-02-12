<?php

namespace App;
use App\Content;

class ImageContent extends Content {

    protected static $singleTableType = 'image_content';
    protected $fillable = ['width','height','top','left'];

    public function image(){
        return $this->BelongsTo(Image::class);
    }
    
    public function toHtml() {
        if($this->image == null){
            return "";
        }else{
            return "<div style='position: absolute; left:".$this->left."px; top:".$this->top."px;'><img style='width:".$this->width."px ; height: ".$this->height."px;' src='".env('APP_URL')."/img/".$this->image->image_key.".".$this->image->extension."'></div>";
        }
    }
    

}