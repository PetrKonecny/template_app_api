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
            $family = "font-family: font".$this->font->id.";";
        }
        $opacity = $this->opacity > 0 ? $this->opacity/100 : 1;
        $fontSize = $this->font_size > 0 ? $this->font_size : 12;
        $string .= '<div style="'.$family.' position: absolute; overflow: hidden; width: '.$this->width.'px; height: '.$this->height.'px; top: '.$this->positionY.'px; left: '.$this->positionX.'px;  font-size: '.$fontSize.'px; background-color: '.$this->background_color.'; color: '.$this->text_color.'; opacity: '.$opacity.';">';    
        $string .= $contentHtml;
        $string .= "</div>";
        return $string;
    }

}

?>