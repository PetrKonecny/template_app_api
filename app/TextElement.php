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
        $content = $this->contentsForInstance($instanceId)->first();
        if($content == null){
            $content = $this->content()->first();
        }
        $contentHtml = "";
        if($content != null){
            $contentHtml = $content->toHtml();
        }
        
        $family = "";
        $string = "";
        if($this->font != null){
            $string .= "<style>";
                $string .= "@font-face {";
                $string .= "font-family: '" ."font" . $this->font->id . "';";
                $string .= "src: url('"."http://localhost:8080/font/".$this->font->id ."/file" ."'); format('truetype');";
                $string .= "}";
            $string .= "</style>";
            $family = "font-family: font".$this->font->id.";";
        }
        $string .= '<div style="'.$family.' position: absolute; overflow: hidden; width: '.$this->width.'px; height: '.$this->height.'px; top: '.$this->positionY.'px; left: '.$this->positionX.'px; z-index: '.($this->id + 2).'; font-size: '.$this->font_size.'px; background-color: '.$this->background_color.'; color: '.$this->text_color.';">';    
        $string .= $contentHtml;
        $string .= "</div>";
        return $string;
    }

}

?>