<?php

namespace App\Services;

use App\Template;
use App\Page;
use App\TextElement;
use App\Element;
use App\Services\PageService;
use Illuminate\Support\Facades\DB;

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
    
    public function getAll(){
        return Template::with('tagged')->get();
    }

    public function getPublicTemplates(){
        return Template::where('public',true)->with('tagged')->get();
    }
   

    public function getTemplatesForUser($user){
        return Template::where('user_id',$user->id)->with('tagged')->get();
    }

    public function findById($id){
        return Template::find($id);    
    }
    
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
    
    public function createTemplate($array){       
        $pageService = $this->pageService;
        $template = new Template($array);
        if($this->user !== null){
            $template->user()->associate($this->user);
        }
        $template->save();
        if(isset($array['tagged'])){
            $template->tag(array_map(function($tag){return $tag['tag_name'];},$array['tagged']));
        }
        if(isset($array['pages'])){
            foreach($array['pages'] as $page){
                $page = $pageService->createPage($page);
                $template->pages()->save($page);
            }
        }
        return $template;
    }
    
    public function deleteTemplate($id){
        $template = Template::find($id);
        if($template != null){
            $template->delete();
        }
    }

    public function search($query){
        $ids= DB::table('templates')->leftJoin('tagging_tagged',function($join){
            $join->on('templates.id','=','tagging_tagged.taggable_id')->where('tagging_tagged.taggable_type','=','App\Template');
        })->select("templates.id")
        ->where('tagging_tagged.tag_slug','like',$query)->orWhere('name','like',$query)->get();
        return Template::with('tagged')->find(array_map(function($i){return $i->id;},$ids));
    }
    
    public function updateTemplate($template, $array){
        $pageService = $this->pageService;
        $template->name = $array['name'];

        if(isset($array['tagged'])){
            $template->retag(array_map(function($tag){return $tag['tag_name'];},$array['tagged']));
        }
        
        foreach ($template->pages as $page3){                
            if(isset($array['pages'])){
                $delete = true;
                foreach($array['pages'] as $index => $page4){
                    if(isset($page4['id']) && $page4['id'] === $page3->id){
                        $delete = false;
                        $pageService->updatePage($page3,$page4);
                    }else{
                        $page4 = $pageService->createPage($page4);
                        $template->pages()->save($page4);
                    }
                    $array['pages'] = array_splice($array['pages'], $index,1);
                }
                if($delete){
                    $page3->delete();
                }
            }
        }
        $template->save();
    }
    
    public function getPagesForTemplate(Template $template){
        return $template->pages();
    }
    
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

