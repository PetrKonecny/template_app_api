<?php
namespace App\Services;

use App\Element;
use App\TextElement;
use App\Page;
use App\Services\ElementService;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PageService
 *
 * @author Petr2
 */
class PageService {
    
    public function getAll(){
        return Page::all();
    }
   
    public function findById($id)
    {
        return Page::find($id);
    }
    
    public function createPage($array){
        $elementService = new ElementService;
        $page = new Page($array);
        $page->save();
        if(isset($array['elements'])){
            foreach($array['elements'] as $element){
                $element = $elementService->createElement($element);
                $page->elements()->save($element);
            }
        }
        return $page;
    }
    
    public function deletePage($page){
        $page->delete();
    }
    
    public function updatePage($page, $array){
        
        $elementService = new ElementService;
        
        foreach ($page->elements as $element){
            $delete = true;
            foreach($array['elements'] as $element2){
                if(isset($element2['id']) && $element2['id'] === $element->id){
                    $delete = false;
                }
            }
            if($delete){
                $element->delete();
            }
        }
        
        $page->save();
        
        foreach ($array['elements'] as $element){
            if(isset($element['id'])){
                $elementService->updateElement($elementService->findById($element['id']), $element);
            }else if(isset($element['type'])){
                $element2 = $elementService->createElement($element);
                $page->elements()->save($element2);
            }
        }       
    }    
}
