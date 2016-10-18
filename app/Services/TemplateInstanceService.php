<?php

namespace App\Services;

use App\TemplateInstance;
use App\Image;
use App\Content;
use App\ImageContent;
use App\TextContent;
use App\Element;
use App\Template;
use App\Services\ContentService;

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
        $contentService = new ContentService();
        $templateInst = new TemplateInstance($array);
        $template = Template::find($array['template_id']);
        $templateInst->template()->associate($template);
        $templateInst->save();
        foreach($array['contents'] as $content) {
            $content2 = $contentService->createContent($content);
            $element = Element::find($content['element_id']);
            $content2->element()->associate($element);
            $templateInst->contents()->save($content2);
        }
    }
    
    public function updateTemplateInstance($templateInst, $array){
        $contentService = new ContentService();
        $templateInst->name = $array['name'];
        $templateInst->save();        
        foreach($array['contents'] as $content) {
            if(isset($content['id'])){
                $content2 = $contentService->updateContent(Content::find($content['id']), $content);
            }else{
                $content2 = $contentService->createContent($content);
            }
            $element = Element::find($content['element_id']);
            $content2->element()->associate($element);
            $templateInst->contents()->save($content2);
        }    
    }
   
}
