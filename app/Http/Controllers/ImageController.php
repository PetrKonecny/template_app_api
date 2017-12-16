<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Image;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    
    public function __construct(ImageService $service){
        $this->imageService = $service;
        $this->imageService->setUser(Auth::user());
        $this->middleware('auth');
    }
    
    
    /**responds to route
    /image  GET
    gets all image in the DB
    */ 
    public function index(){
        return $this->imageService->getAll();
    }
   

   /**responds to route
    /image/<id>  GET
    gets one image from the DB
    */
    public function show($id)
    {
        return $this->imageService->findById($id);
    }
    
    /**responds to route
    /image  POST
    creates new image
    */
    public function store(){
        $image = Input::file("file");
        return $this->imageService->uploadImage($image);
    }
    
    /**responds to route
    /image/<id>  DELETE
    removes the image 
    */
    public function destroy(Image $image){
        $this->imageService->deleteImage($image);
    }
    
    
    
}
