<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Album;
use App\Services\AlbumService;

class AlbumController extends Controller
{
    
    public function __construct(AlbumService $service){
        $this->albumService = $service;
    }
    
    
    public function index(){
        return $this->albumService->getAll();
    }
   
    public function show($id)
    {
        return $this->albumService->findById($id);
    }
    
    public function store(Album $album){
        return $this->albumService->createAlbum(Input::all());
    }

    public function update(Album $album){
        $oldAlbum = $this->albumService->findById($album->id);
        return $this->albumService->updateAlbum($oldAlbum,Input::all());
    }

    public function uploadTo($id){
        $album = $this->albumService->findById($id);
        $image = Input::file("file");
        $this->albumService->addNewImageToAlbum($album,$image);
    }
    
    public function remove(Album $album){
        $this->albumService->deleteAlbum($album);
    }
    
    
    
}
