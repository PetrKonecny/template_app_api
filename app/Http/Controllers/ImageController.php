<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Image;
use App\Services\ImageService;

class ImageController extends Controller
{
    
    public function __construct(ImageService $service){
        $this->imageService = $service;
    }
    
    
    public function index(){
        return $this->imageService->getAll();
    }
   
    public function show($id)
    {
        return $this->imageService->findById();
    }
    
    public function store(){
        $image = Input::file("file");
        $this->imageService->createImage($image);
    }
    
    public function delete($image){
        $image->delete();
    }
    
    
    
}
