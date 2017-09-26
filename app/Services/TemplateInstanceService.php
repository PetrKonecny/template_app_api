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

/**
 * Service providing database access for TemplateInstance model
 */
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

    /**
    * gets all template instances in the DB
    * @return - returns all template instances with tags
    */
    public function getAll(){
        return TemplateInstance::with('tagged')->get();
    }
   
   /**
    * finds tmeplate instance by id
    * @param id - id of instance to be found
    * @return template instance or null if none found 
   */
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

    /**
    * gets all template instances for given user
    * @param user - user to find template instances for
    * @return template instances for user with tags
    */
    public function getTemplateInstancesForUser($user){
        return TemplateInstance::where('user_id',$user->id)->with('tagged')->get();
    }
    
    /**
    * deletes template instance
    *  @param id - id of template instance to be deleted
    */
    public function deleteTemplateInstance($id){
        TemplateInstance::destroy($id);
    }
    
    /**
    * creates new template instance from the array with contents and tags
    * @param array - array containing template instance data
    * @return created template instance
    */
    public function createTemplateInstance($array){
        $contentService = $this->contentService;
        $templateInst = new TemplateInstance($array);
        if(isset($array['template_id'])){
            $template = $this->templateService->findById($array['template_id']);
            $templateInst->template()->associate($template);
        }

        $templateInst->user()->associate($this->user);
        $templateInst->save();

        if(isset($array['tagged']) && is_array($array['tagged'])){
            $templateInst->tag(array_map(function($tag){return $tag['tag_name'];},$array['tagged']));
        }

        if(isset($array['contents'])){
            foreach($array['contents'] as $content) {
                if(isset($content['type'])){
                    $content2 = $contentService->createContent($content);
                    $templateInst->contents()->save($content2);
                    if(isset($content['element_id'])){
                        $element = $this->elementService->findById($content['element_id']);
                        $content2->element()->associate($element);
                        $content2->save();
                    }
                }
            }
        }
        return $templateInst;
    }

    public function createBlankInstance($array){
        $template = $this->templateService->createTemplate($array);
        $templateInstance = $this->createTemplateInstance(['template_id' => $template->id]);
        $templateInstance->blank = true;
        $templateInstance->save();
        return $templateInstance;
    }
    
    /** 
    * updates template instance
    * @param templateInst - tmeplate inst to be updated
    * @param array - array containing updated data
    */
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
                    }else if(isset($content2['type'])){
                        $content3 = $contentService->createContent($content2);
                        if(isset($content2['element_id'])){
                            $element = $this->elementService->findById($content2['element_id']);
                            $content3->element()->associate($element);
                            $templateInst->contents()->save($content3);
                            $content3->save();
                        }
                    }
                    $array['contents'] = array_splice($array['contents'], $index,1);
                }
                if($delete){
                    $content->delete();
                }
            }
        }

        $templateInst->save();  
        return $templateInst;    
    }
   
}
