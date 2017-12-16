<?php
namespace App\Services;

use App\Element;
use App\TextElement;
use App\Page;
use App\Services\ElementService;


/**
 * Service providing database access for Page model
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
    
    /**
    * gets all pages from DB
    * @return all pages from db
    */  
    public function getAll(){
        return Page::all();
    }
   
   /** 
    * finds page by id  
    * @param id - id of searched page
    * @return page or null if none found
    */
    public function findById($id)
    {
        return Page::find($id);
    }
    
    /** 
    * creates new page with given elements
    * @param array - array containing image data
    * @return create page model
    */
    public function createPage($array){
        $elementService = $this->elementService;
        $page = new Page($array);
        $page->save();
        if(isset($array['elements']) && is_array($array['elements'])){
            $this->createElements($array['elements'],$page);
        }
        return $page;
    }
    
    /** 
    * deletes the page
    * @param page - page to be deleted
    */
    public function deletePage($page){
        $page->delete();
    }
    
    /** 
    * updates given page with new data
    * @param page - page to be updated
    * @param array - array containing new data
    */
    public function updatePage($page, $array){
        
        $elementService = $this->elementService;
        
        if(isset($array['width'])){
            $page->width = $array['width'];
        }
        if(isset($array['height'])){
            $page->height = $array['height'];
        }

        if(isset($array['elements']) && is_array($array['elements'])){
            $this->deleteOrUpdateElements($array['elements'],$page);
            $this->createElements($array['elements'],$page);
        }

        $page->touch();
        $page->save();
        return $page;
    }

    public function createElements($array,$page){
        $elementService = $this->elementService;
        foreach($array as $elementArray){
            if(!isset($elementArray['id'])){
                $savedElement = $elementService->createElement($elementArray);
                $page->elements()->save($savedElement);
            }
        }
    }

    public function deleteOrUpdateElements($array,$page){
        $elementService = $this->elementService;
        foreach ($page->elements as $element){                
            $delete = true;
            foreach($array as $elementArray){
                if(isset($elementArray['id']) && $elementArray['id'] === $element->id){
                    $delete = false;
                    $elementService->updateElement($element,$elementArray);
                }
            }
            unset($elementArray);
            if($delete){
                $element->delete();
            }
        }
    }    
}
