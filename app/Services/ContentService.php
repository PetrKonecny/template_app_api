<?php
namespace App\Services;

use App\Content;
use App\TextContent;
use App\ImageContent;
use App\TableContent;
use App\Image;
use App\Font;
use Illuminate\Support\Facades\Validator;
use App\Services\ImageService;

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
class ContentService {

    private $user;

    public function __construct($user = null){
        $this->user = $user;
        $this->imageService = new ImageService($this->user);
    }

    public function setUser($user){
        $this->user = $user;
    }

    public function getAll(){
        return Content::all();
    }
   
    public function findById($id)
    {
        return Content::find($id); 
    }

    private function createTextContent($array){
        return new TextContent($array);
    }

    private function createImageContent($array){
        $content = new ImageContent($array);
        if(isset($array['image']['id'])){
            $image = $this->imageService->findById($array['image']['id']);
            $content->image()->associate($image); 
        }
        return $content;
    }

    private function createTableContent($array){
        $array['rows'] = json_encode($array['rows']);
        return new TableContent($array);
    }
    
    public function createContent($array){
        $this->validate($array);
        if($array['type'] == 'text_content'){
            $content = $this->createTextContent($array);
        }else if($array['type'] == 'image_content'){
            $content = $this->createImageContent($array);
        }else if($array['type'] == 'table_content'){
            $content = $this->createTableContent($array);
        }
        $content->save();
        return $content;
    }
    
    public function deleteContent($content){
        $content->delete();
    }

    private function updateTextContent($content,$array){
        $content->text = $array['text']; 
        return $content;        
    }

    private function updateImageContent($content,$array){
        if(isset($array['image']['id'])){
            $image = Image::find($array['image']['id']);
            $content->image()->associate($image); 
        }else{
            $content->image()->dissociate();
        }
        if(isset($array['width'])){
            $content->width  = $array['width'];
        }
        if(isset($array['height'])){
            $content->height = $array['height'];
        }
        if(isset($array['left'])){
            $content->left = $array['left'];
        }
        if(isset($array['top'])){
            $content->top = $array['top'];
        }
        return $content;
    }

    private function updateTableContent($content,$array){
        $array['rows'] = json_encode($array['rows']);
        $content->rows = $array['rows'];
        return $content;
    }
    
    public function updateContent($content, $array){
        $this->validate($array);
        $content;
        if($content['type'] == 'text_content'){
            $content =  $this->updateTextContent($content,$array);     
        }
        if($content['type'] == 'image_content'){
            $content =  $this->updateImageContent($content,$array);     
        }
        if($content['type'] == 'table_content'){
            $content =  $this->updateTableContent($content,$array);     
        }
        $content->save();
        return $content;
    }

    public function validate($array){
        $validator = Validator::make($array, [
            'type' => "required|in:text_content,image_content,table_content",
            'width' => "sometimes|required|numeric",
            'height' => "sometimes|required|numeric",
            'left' => "sometimes|required|numeric",
            'top' => "sometimes|required|numeric",
        ]);
        if($validator->fails()){
            throw new \RuntimeException("validation error");
        }     
    }
}
