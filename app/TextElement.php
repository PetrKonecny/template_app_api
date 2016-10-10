<?php

namespace App;
use App\Element;
use App\Font;

class TextElement extends Element {

    protected static $singleTableType = 'text_element';
    
    public function font(){
        return $this->BelongsTo(Font::class);
    }
    
    public function toHtml($instanceId){
        $family = "";
        $string = "<div style='position: absolute; overflow: hidden; width: ".$this->width."px; height: ".$this->height."px; top: ".$this->positionY."px; left: ".$this->positionX."px; background-color: black; z-index:".($this->id + 1)."; opacity:0.25;'></div>";
        if($this->font != null){
            $string .= "<style>";
                $string .= "@font-face {";
                $string .= "font-family: '" ."font" . $this->font->id . "';";
                $string .= "src: url('"."http://localhost:8080/font/".$this->font->id ."/file" ."'); format('truetype');";
                $string .= "}";
            $string .= "</style>";
            $family = "font-family: font".$this->font->id.";";
        }
        $string .= '<div style="'.$family.'; position: absolute; overflow: hidden; width: '.$this->width.'px; height: '.$this->height.'px; top: '.$this->positionY.'px; left: '.$this->positionX.'px; z-index: '.($this->id + 2).'; font-size: '.$this->font_size.'">';
        $string .= $this->contentsForInstance($instanceId)->first()->toHtml();
        $string .= "</div>";
        return $string;
    }

}

?>