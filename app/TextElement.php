<?php

namespace App;
use App\Element;
use App\Font;

class TextElement extends Element {

    protected static $singleTableType = 'text_element';
    
    public function font(){
        return $this->BelongsTo(Font::class);
    }

}

?>