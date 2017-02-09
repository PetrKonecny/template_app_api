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
use Illuminate\Support\Facades\Auth;

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
            if($content->type == 'table_content'){
                $content->rows = json_decode($content->rows);
            }
        }
        return $templateInst;
    }
    
    public function deleteTemplateInstance($id){
        TemplateInstance::destroy($id);
    }
    
    public function createTemplateInstance($array){
        $contentService = new ContentService();
        $templateInst = new TemplateInstance($array);
        $template = Template::find($array['template_id']);
        $templateInst->template()->associate($template);
        $templateInst->user()->associate(Auth::user());
        $templateInst->save();
        foreach($array['contents'] as $content) {
            if(isset($content)){
                $content2 = $contentService->createContent($content);
                $element = Element::find($content['element_id']);
                $content2->element()->associate($element);
                $templateInst->contents()->save($content2);
            }
        }
    }
    
    public function updateTemplateInstance($templateInst, $array){
        $contentService = new ContentService();
        $templateInst->name = $array['name'];
        $templateInst->save();        
        foreach($array['contents'] as $content) {
            if(isset($content)){
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
   
}
