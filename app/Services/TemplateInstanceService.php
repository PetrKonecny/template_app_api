<?php

namespace App\Services;

use App\TemplateInstance;
use App\Image;
use App\Content;
use App\ImageContent;
use App\TextContent;
use App\Element;
use App\Template;

class TemplateInstanceService {

    public function getAll(){
        return TemplateInstance::all();
    }
   
    public function findById($id)
    {
        $templateInst = TemplateInstance::with('contents')->find($id);
        foreach($templateInst->contents as $content){
            if($content->type == 'image_content'){
                $content->image;
            }
        }
        return $templateInst;
    }
    
    public function createTemplateInstance($array){
        $templateInst = new TemplateInstance($array);
        $template = Template::find($array['template_id']);
        $templateInst->template()->associate($template);
        $templateInst->save();
        foreach($array['contents'] as $content){
            if($content['type'] == 'text_content'){
                $content2 = new TextContent($content);
            }
            if($content['type'] == 'image_content'){
                $content2 = new ImageContent($content);
                $image = Image::find($content['image']['id']);
                $content2->image()->associate($image);
            }
            $element = Element::find($content['element_id']);
            $content2->element()->associate($element);
            $templateInst->contents()->save($content2);
        }
    }
    
    public function updateTemplateInstance($templateInst, $array){
        $templateInst->name = $array['name'];
        $templateInst->save();        
        foreach($array['contents'] as $content) {
            if($content['type'] == 'text_content'){
                if(isset($content['id'])){
                    $content2 = Content::find($content['id']);
                }else{
                    $content2 = new TextContent();
                }
                $content2->text = $content['text'];
            }
            if($content['type'] == 'image_content'){
                if(isset($content['id'])){
                    $content2 = Content::find($content['id']);
                }else{
                    $content2 = new ImageContent();
                }
                $image = Image::find($content['image']['id']);
                $content2->image()->associate($image); 
            }
            $element = Element::find($content['element_id']);
            $content2->element()->associate($element);
            $templateInst->contents()->save($content2);
        }    
    }
   
}
