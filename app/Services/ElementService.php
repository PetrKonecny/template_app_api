<?php
namespace App\Services;

use App\Element;
use App\TextElement;
use App\ImageElement;
use App\TableElement;
use App\FrameElement;
use App\Font;
use App\Content;
use App\Image;
use App\Services\ContentService;
use App\Services\ImageService;
use Illuminate\Support\Facades\Validator;

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

    private $user;

    public function __construct($user = null){
        $this->user = $user;
        $this->service = new ContentService($this->user);
        $this->imageService = new ImageService($this->user);
    }

    public function setUser($user){
        $this->user = $user;
    }
    
    public function getAll(){
        return Element::all();
    }
   

    public function findById($id)
    {
        return Element::find($id);
    }

    public function deleteElement($element){
        $element->delete();
    }

    private function createTextElement($array){
        return new TextElement($array);
    }

    private function createFrameElement($array){
        return new FrameElement($array);
    }

    private function createImageElement($array){
        $element = new ImageElement($array);
        if(isset($array['image']['id'])){
            $this->imageService->findById($array['image']['id']);
        }
        return $element;
    }

    private function createTableElement($array){
        if(isset($array['rows'])){
            $array['rows'] = json_encode($array['rows']);
        }
        return new TableElement($array);
    }
    
    public function createElement($array){
        $this->validate($array);
        $contentService = $this->service;
        $element;
        if ($array['type'] == 'text_element'){
            $element = $this->createTextElement($array);
        }else if($array['type'] == 'frame_element'){
            $element = $this->createFrameElement($array);
        }else if($array['type'] == 'image_element'){
            $element = $this->createImageElement($array);
        }else if($array['type'] == 'table_element'){
            $element = $this->createTableElement($array);
        }
        $element->save();
        if (isset ($array['content'])) {
            $content = $contentService->createContent($array['content']);
            $content->element()->associate($element);
            $content->save();
        }
        return $element;
    }
    
    public function updateElement($element, $array){
        $this->validate($array);
        $contentService = $this->service;
        if(isset($array['rows'])){
            $array['rows'] = json_encode($array['rows']);
            $element->rows = $array['rows'];
        }
        if (isset ($array['content'])) {
            if (isset ($array['content']['id']) && $element->content !== null ){
                $content = $contentService->updateContent($element->content,$array['content']);
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

    public function validate($array){
        $validator = Validator::make($array, [
            'type' => "required|in:text_element,image_element,frame_element,table_element",
            'width' => "required|numeric",
            'height' => "required|numeric",
            'positionX' => "required|numeric",
            'positionY' => "required|numeric",
        ]);
        if($validator->fails()){
            throw new \RuntimeException("validation error");
        } 
    }
}
