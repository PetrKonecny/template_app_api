<?php
namespace App\Services;

use App\Element;
use App\TextElement;
use App\ImageElement;
use App\Font;
use App\Content;
use App\Services\ContentService;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ElementService
 *
 * @author Petr2
 */
class ElementService {
    
    public function getAll(){
        return Element::all();
    }
   
    public function findById($id)
    {
        return Element::find($id);
    }
    
    public function createElement($array){
        echo (json_encode($array));
        $contentService = new ContentService();
        if ($array['type'] == 'text_element'){
            $element = new TextElement($array);
            if(isset($array['font']) && (isset($array['font']['id']))){
                $element->font()->associate(Font::find($array['font']['id']));
            }
        }else if($array['type'] == 'image_element'){
            $element = new ImageElement($array);
        }
        $element->save();
        if (isset ($array['content'])) {
            $content = $contentService->createContent($array['content']);
            $content->element()->associate($element);
            $content->save();
        }
        return $element;
    }
    
    public function deleteElement($element){
        $element->delete();
    }
    
    public function updateElement($element, $array){
        $contentService = new ContentService();
        if(isset($array['font']) && (isset($array['font']['id']))){
            $font = Font::find($array['font']['id']);
            $element->font()->associate($font);
        }
        if(isset($array['fontSize'])){
            $element->font_size = $array['fontSize'];
        }
        if (isset ($array['content'])) {
            if (isset ($array['content']['id'])){
                $content = $contentService->updateContent(Content::find($array['content']['id']),$array['content']);
            }else{
                $content = $contentService->createContent($array['content']);
                $content->element()->associate($element);
            }
        }
        $element->width = $array['width'];
        $element->height = $array['height'];
        $element->positionX = $array['positionX'];
        $element->positionY = $array['positionY'];
        $element->save();     
    }
}
