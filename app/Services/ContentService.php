<?php
namespace App\Services;

use App\Content;
use App\TextContent;
use App\ImageContent;
use App\TableContent;
use App\Image;
use App\Font;
use Illuminate\Support\Facades\Validator;
use App\Services\ImageService;


/**
 * Service providing database access for Content model
 */
class ContentService {

    //user used for autorization
    private $user;

    public function __construct($user = null){
        $this->user = $user;
        $this->imageService = new ImageService($this->user);
    }

    public function setUser($user){
        $this->user = $user;
    }

    /** 
    * gets all contents
    * @return all contents in the DB
    */
    public function getAll(){
        return Content::all();
    }
   
    /** 
    * finds content by id  
    * @param id - id of searched content
    * @return content or null if none found
    */
    public function findById($id)
    {
        return Content::find($id); 
    }

    //helper function to create text content
    private function createTextContent($array){
        return new TextContent($array);
    }

    //helper function to create image content
    private function createImageContent($array){
        $content = new ImageContent($array);
        if(isset($array['image']['id'])){
            $image = $this->imageService->findById($array['image']['id']);
            $content->image()->associate($image); 
        }
        return $content;
    }

    //helper function to greate table content
    private function createTableContent($array){
        $array['rows'] = json_encode($array['rows']);
        return new TableContent($array);
    }
    
    /** 
    * creates new image, text or table content
    * @param array - array representing content to create
    * @return created content
    */
    public function createContent($array){
        $this->validate($array);
        if($array['type'] == 'text_content'){
            $content = $this->createTextContent($array);
        }else if($array['type'] == 'image_content'){
            $content = $this->createImageContent($array);
        }else if($array['type'] == 'table_content'){
            $content = $this->createTableContent($array);
        }
        $content->save();
        return $content;
    }
    
    /** 
    * deletes content from DB
    * @param content - content to delete
    */
    public function deleteContent($content){
        $content->delete();
    }

    //helper function to update text content
    private function updateTextContent($content,$array){
        $content->text = $array['text']; 
        return $content;        
    }

    //helper function to update image content, if image with invalid id given removes image
    private function updateImageContent($content,$array){
        if(isset($array['image']['id'])){
            $image = Image::find($array['image']['id']);
            $content->image()->associate($image); 
        }else{
            $content->image()->dissociate();
        }
        if(isset($array['width'])){
            $content->width  = $array['width'];
        }
        if(isset($array['height'])){
            $content->height = $array['height'];
        }
        if(isset($array['left'])){
            $content->left = $array['left'];
        }
        if(isset($array['top'])){
            $content->top = $array['top'];
        }
        return $content;
    }

    //helper function to update table content
    private function updateTableContent($content,$array){
        $array['rows'] = json_encode($array['rows']);
        $content->rows = $array['rows'];
        return $content;
    }
    
    /** 
    * updates old text, image or table content with new data  
    * @param content - Content instance to update
    * @param array - new data to update
    * @return - updated content
    */
    public function updateContent($content, $array){
        $this->validate($array);
        $content;
        if($content['type'] == 'text_content'){
            $content =  $this->updateTextContent($content,$array);     
        }
        if($content['type'] == 'image_content'){
            $content =  $this->updateImageContent($content,$array);     
        }
        if($content['type'] == 'table_content'){
            $content =  $this->updateTableContent($content,$array);     
        }
        $content->save();
        return $content;
    }

    //uses framework to validate content
    public function validate($array){
        $validator = Validator::make($array, [
            'type' => "required|in:text_content,image_content,table_content",
            'width' => "sometimes|required|numeric",
            'height' => "sometimes|required|numeric",
            'left' => "sometimes|required|numeric",
            'top' => "sometimes|required|numeric",
        ]);
        if($validator->fails()){
            throw new \RuntimeException("validation error");
        }     
    }
}
