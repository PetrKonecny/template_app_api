<?php
namespace App\Services;

use App\Image;
use Illuminate\Support\Facades\Storage;
/**
 * Service providing database access for Image model
 */
class ImageService {

    //user used for autorization
    private $user;

    public function setUser($user){
        $this->user = $user;
    }

    public function __construct($user = null){
        $this->user = $user;
    }
  
    /** 
    * gets all images
    * @return all images in the DB
    */
    public function getAll(){
        return Image::all();
    }
   
    /** 
    * finds image by id  
    * @param id - id of searched image
    * @return image or null if none found
    * @throws - exception if given user does not have access to the image
    */
    public function findById($id)
    {
        $image = Image::findOrFail($id);
        if($this->user == null || $this->user->can('show',$image)){
            return $image;
        }else{
            throw new \RuntimeException("Unauthorized access"); 
        }
    }
    
    /** 
    * creates new image from file
    * @param file - file containing image data
    * @return created image model associated with the file
    */
    public function createImage($file){
        $destinationPath = storage_path().'/app/images';
        $extension = $file->getClientOriginalExtension();
        $key = rand(11111,99999);
        $image = new Image;
        $image->name = $file->getClientOriginalName();
        $image->extension = $extension;
        $image->image_key = $key;
        $image->save();
        $fileName = $image->image_key.'.'.$extension;
        $file->move($destinationPath, $fileName);
        return $image;
    }
    
    /** 
    * deletes iamge from DB
    * @param image - iamge to delete
    */
    public function deleteImage($image){
        $image->delete();
    }
    
}

