<?php

namespace App;

use App\Content;
//model for text content
class TextContent extends Content
{
        protected $fillable = ['text'];
        protected static $singleTableType = 'text_content';
        
        public function toHtml(){
            return $this->text;
        }
}
