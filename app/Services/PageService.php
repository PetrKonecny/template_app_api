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

    private $user;

    public function __construct($user = null){
        $this->user = $user;
        $this->elementService = new ElementService($this->user);
    }

    public function setUser($user){
        $this->user = $user;
    }  
      
    public function getAll(){
        return Page::all();
    }
   
    public function findById($id)
    {
        return Page::find($id);
    }
    
    public function createPage($array){
        $elementService = $this->elementService;
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
        
        $elementService = $this->elementService;
        
        if(isset($array['width'])){
            $page->width = $array['width'];
        }
        if(isset($array['height'])){
            $page->height = $array['height'];
        }

        foreach ($page->elements as $element){                
            if(isset($array['elements'])){
                $delete = true;
                foreach($array['elements'] as $index => $element2){
                    if(isset($element2['id']) && $element2['id'] === $element->id){
                        $delete = false;
                        $elementService->updateElement($element,$element2);
                    }else{
                        $element2 = $elementService->createelement($element2);
                        $page->elements()->save($element2);
                    }
                    $array['elements'] = array_splice($array['elements'], $index,1);
                }
                if($delete){
                    $element->delete();
                }
            }
        }

        $page->save();
    }    
}
