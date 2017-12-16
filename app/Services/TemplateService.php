<?php

namespace App\Services;

use App\Template;
use App\Page;
use App\TextElement;
use App\Element;
use App\Services\PageService;
use App\Services\TemplateInstanceService;
use Illuminate\Support\Facades\DB;

/**
 * Service providing database access for Template model
 */
class TemplateService
{
    
    private $user;

    public function __construct($user = null){
        $this->user = $user;
        $this->pageService = new PageService($this->user);
    }

    public function setUser($user){
        $this->user = $user;
    }
    
    /**
    * gets all templates in the DB
    * @return - returns all templates with tags
    */
    public function getAll(){
        return Template::with('tagged', 'user')->get();
    }

    public function getDemo(){
        $template = Template::where('type','demo_template')->first();
        return $this->findByIdNested($template->id);
    }

    /**
    * gets all public templates 
    * @return all public templates with tags
    */
    public function getPublicTemplates(){
        return Template::where('public',true)->with('tagged', 'user')->get();
    }

    public function getTemplatesForUser($user, $type = null){
        if($type == null){
            return Template::where('user_id',$user->id)->where('type','')->with('tagged','user')->get();
        }else{
            return Template::where('user_id', $user->id)->where('type', $type)->with('tagged','user')->get();
        }
    }
   
    /**
    * finds template by id
    * @param id - id to search
    * @return template or null if none found
    */
    public function findById($id){
        return Template::find($id);    
    }
    
    /**
    * finds template with all pages and elements and contents in those pages
    * @param id - id to search
    * @return template with pages, elements and contents
    */
    public function findByIdNested($id){
        $template =  Template::with('pages','pages.elements')->find($id);
        $template->tagged;
        foreach($template->pages as $page){
            foreach($page->elements as $element){
                if($element->type == 'text_element'){
                    $element->font;
                }
                if($element->type == 'image_element'){
                    $element->image;
                }
                if($element->content && $element->content->type == 'image_content'){
                    $element->content->image;
                }
                if($element->type == 'table_element'){
                    $element->rows = json_decode($element->rows);
                }
                if($element->content && $element->content->type == 'table_content'){
                    $element->content->rows = json_decode($element->content->rows);
                }
            }
        }
        return $template;
    }
    
    /**
    * creates new template from the array
    * @param array - array to create template from
    * @return created template
    */
    public function createTemplate($array){       
        $pageService = $this->pageService;
        $template = new Template($array);
        if($this->user !== null){
            $template->user()->associate($this->user);
        }
        $template->save();
        if(isset($array['tagged']) && is_array($array['tagged'])){
            $template->tag(array_map(function($tag){return $tag['tag_name'];},$array['tagged']));
        }
        if(isset($array['pages']) && is_array($array['pages'])){
            $this->createPages($array['pages'],$template);
        }

        return $template;
    }
    
    /**
    * deletes template 
    * @param id - id of template to delete
    */
    public function deleteTemplate($id){
        $template = Template::find($id);
        if($template != null){
            $template->delete();
        }
    }

    /**
    *  searches all templates by their name and tag
    * @param query - query to search for
    * @return matching tempaltes with tags
    */
    public function search($query){
        $ids= DB::table('templates')->leftJoin('tagging_tagged',function($join){
            $join->on('templates.id','=','tagging_tagged.taggable_id')->where('tagging_tagged.taggable_type','=','App\Template');
        })->select("templates.id")
        ->where('tagging_tagged.tag_slug','like',$query)->orWhere('name','like',$query)->get();
        return Template::with('tagged')->find(array_map(function($i){return $i->id;},$ids));
    }
    
    /**
    *   updates template 
    * @param tempalte - template to update
    * @param array - array containing data to update
    */
    public function updateTemplate($template, $array){
        $pageService = $this->pageService;
        $template->name = $array['name'];

        if(isset($array['tagged'])){
            $template->retag(array_map(function($tag){return $tag['tag_name'];},$array['tagged']));
        }    

        if(isset($array['pages']) && is_array($array['pages'])){
            $this->deleteOrUpdatePages($array['pages'],$template);
            $this->createPages($array['pages'],$template);
        }

        $template->touch();
        $template->save();
        return $template;
    }

    public function deleteOrUpdatePages($array,$template){
        $pageService = $this->pageService;
        foreach ($template->pages as $page){                
            $delete = true;
            foreach($array as $pageArray){
                if(isset($pageArray['id']) && $pageArray['id'] === $page->id){
                    $delete = false;
                    $pageService->updatePage($page,$pageArray);
                }
            }
            unset($pageArray);
            if($delete){
                $page->delete();
            }
        }
    }

    public function createPages($array,$template){
        $pageService = $this->pageService;
        foreach($array as $pageArray){
            if(!isset($pageArray['id'])){
                $savedPage = $pageService->createPage($pageArray);
                $template->pages()->save($savedPage);
            }
        }
    }

    
    //gets pages for template
    public function getPagesForTemplate(Template $template){
        return $template->pages();
    }
    
    //adds pages to template
    public function addPagesToTemplate($template,$array){
        foreach ($array as $page){
            $page2 = new Page();
            $template->pages()->save($page2);
            foreach ($page['elements'] as $element){
                $page2->elements()->save(new TextElement($element));
            }
        }
    }
}

