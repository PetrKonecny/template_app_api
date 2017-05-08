<?php
namespace App\Services;

use App\Image;
use Illuminate\Support\Facades\Storage;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ImageService {

    private $user;

    public function __construct($user = null){
        $this->user = $user;
    }
  
    public function getAll(){
        return Image::all();
    }
   
    public function findById($id)
    {
        $image = Image::findOrFail($id);
        if($this->user == null || $this->user->can('show',$image)){
            return $image;
        }else{
            throw new \RuntimeException("Unauthorized access"); 
        }
    }
    
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
    
    public function deleteImage($image){
        $image->delete();
    }
    
}

