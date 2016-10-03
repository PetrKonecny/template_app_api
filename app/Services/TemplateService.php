<?php

namespace App\Services;

use App\Template;
use App\Page;
use App\TextElement;
use App\Element;

class TemplateService
{
    
    public function getAll(){
        return Template::all();
    }
   
    public function findById($id){
        return Template::find($id);
    }
    
    public function findByIdNested($id){
        return Template::with('pages','pages.elements')->find($id);
    }
    
    public function createTemplate($array){
        $pageService = new PageService();
        $template = new Template($array);
        $template->save();
        if(isset($array['pages'])){
            foreach($array['pages'] as $page){
                $page = $pageService->createPage($page);
                $template->pages()->save($page);
            }
        }
        return $template;
    }
    
    public function deleteTemplate(Template $template){
        $template->delete();
    }
    
    public function updateTemplate($template, $array){
        $pageService = new PageService();
        $template->name = $array['name'];
        
        foreach ($template->pages as $page3){
            $delete = true;
            foreach($array['pages'] as $page4){
                if(isset($page4['id']) && $page4['id'] === $page3->id){
                    $delete = false;
                }
            }
            if($delete){
                $page3->delete();
            }
        }
                
        foreach ($array['pages'] as $page){
            if((isset($page['id']))){
                $pageService->updatePage($pageService->findById($page['id']),$page);
            }else{
                $page = $pageService->createPage($page);
                $template->pages()->save($page);
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

