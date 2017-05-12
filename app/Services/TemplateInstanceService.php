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
use App\Services\ElementService;
use App\Services\TemplateService;

class TemplateInstanceService {

    private $user;

    public function __construct($user = null){
        $this->user = $user;
        $this->elementService = new ElementService($this->user);
        $this->contentService = new ContentService($this->user);
        $this->templateService = new TemplateService($this->user);
    }

    public function setUser($user){
        $this->user = $user;
    }

    public function getAll(){
        return TemplateInstance::with('tagged')->get();
    }
   
    public function findById($id)
    {
        $templateInst = TemplateInstance::with('contents')->findOrFail($id);
        $templateInst->tagged;
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

    public function getTemplateInstancesForUser($user){
        return TemplateInstance::where('user_id',$user->id)->with('tagged')->get();
    }
    
    public function deleteTemplateInstance($id){
        TemplateInstance::destroy($id);
    }
    
    public function createTemplateInstance($array){
        $contentService = new ContentService();
        $templateInst = new TemplateInstance($array);
        if(isset($array['template_id'])){
            $template = $this->templateService->findById($array['template_id']);
            $templateInst->template()->associate($template);
        }

        $templateInst->user()->associate($this->user);
        $templateInst->save();

        if(isset($array['tagged'])){
            $templateInst->tag(array_map(function($tag){return $tag['tag_name'];},$array['tagged']));
        }

        if(isset($array['contents'])){
            foreach($array['contents'] as $content) {
                $content2 = $contentService->createContent($content);
                $templateInst->contents()->save($content2);
                if(isset($content['element_id'])){
                    $element = $this->elementService->findById($content['element_id']);
                    $content2->element()->associate($element);
                }
            }
        }
        return $templateInst;
    }
    
    public function updateTemplateInstance($templateInst, $array){
        $contentService = $this->contentService;
        $templateInst->name = $array['name'];

        if(isset($array['tagged'])){
            $templateInst->retag(array_map(function($tag){return $tag['tag_name'];},$array['tagged']));
        }

        foreach ($templateInst->contents as $content){                
            if(isset($array['contents'])){
                $delete = true;
                foreach($array['contents'] as $index => $content2){
                    if(isset($content2['id']) && $content2['id'] === $content->id){
                        $delete = false;
                        $contentService->updateContent($content,$content2);
                    }else{
                        $content2 = $contentService->createContent($content2);
                        if(isset($content2['element_id'])){
                            $element = $this->elementService->findById($content['element_id']);
                            $content2->element()->associate($element);
                        }
                    }
                    $array['pages'] = array_splice($array['pages'], $index,1);
                }
                if($delete){
                    $page3->delete();
                }
            }
        }

        $templateInst->save();  
        return $templateInst;    
    }
   
}
