<?php
namespace App\Services;

use App\Content;
use App\TextContent;
use App\ImageContent;
use App\Image;
use App\Font;
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
    
    public function getAll(){
        return Content::all();
    }
   
    public function findById($id)
    {
        return Content::find($id);
    }
    
    public function createContent($array){
        if($array['type'] == 'text_content'){
            $content = new TextContent($array);
            if(isset($array['font']['id'])){
                $font = Font::find($array['font']['id']);
                $content->font()->associate($font); 
            }
        }
        if($array['type'] == 'image_content'){
            $content = new ImageContent($array);
            if(isset($array['image']['id'])){
                $image = Image::find($array['image']['id']);
                $content->image()->associate($image); 
            }
        }
        $content->save();
        return $content;
    }
    
    public function deleteContent($content){
        $content->delete();
    }
    
    public function updateContent($content, $array){
        if($content['type'] == 'text_content'){
            $content->text = $array['text'];         
        }
        if($content['type'] == 'image_content'){
            if(isset($array['image']['id'])){
                $image = Image::find($array['image']['id']);
                $content->image()->associate($image); 
            }else{
                $content->image()->dissociate();
            }
            $content->width  = $array['width'];
            $content->height = $array['height'];
            $content->left = $array['left'];
            $content->top = $array['top'];
        }
        $content->save();
        return $content;
    }
}
