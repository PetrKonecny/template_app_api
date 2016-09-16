<?php
namespace App\Services;

use App\Element;
use App\TextElement;
use App\ImageElement;

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
        if($array['type'] == 'text_element'){
            $element = new TextElement($array);
        }else if($array['type'] == 'image_element'){
            $element = new ImageElement($array);
        }
        $element->save();
        return $element;
    }
    
    public function deleteElement($element){
        $element->delete();
    }
    
    public function updateElement($element, $array){
       
        $element->width = $array['width'];
        $element->height = $array['height'];
        $element->positionX = $array['positionX'];
        $element->positionY = $array['positionY'];
        $element->save();     
    }
}
