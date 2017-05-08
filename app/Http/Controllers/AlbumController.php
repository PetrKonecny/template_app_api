<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Album;
use App\Services\AlbumService;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    
    public function __construct(AlbumService $service){
        $this->albumService = $service;
        $this->albumService->setUser(Auth::user());
        $this->middleware('auth');    
    }
    
    
    public function index(){
        if(Auth::user()->can('index',Album::class)){
            return $this->albumService->getAll();
        }else{
            abort(401);
        }
    }
   
    public function show($id)
    {   
        if(Auth::user()->can('show',Album::class)){
            return $this->albumService->findById($id);
        }else{
            abort(401);
        }
    }
    
    public function store(Album $album){
        if(Auth::user()->can('store',Album::class)){
            return $this->albumService->createAlbum(Input::all());
        }else{
            abort(401);
        }
    }

    public function update($id){
        $album = $this->albumService->findById($id);
        if(Auth::user()->can('update',$album)){
            return $this->albumService->updateAlbum($oldAlbum,Input::all());
        }else{
            abort(401);
        }
    }

    public function uploadTo($id){
        $album = $this->albumService->findById($id);
        if(Auth::user()->can('update',$album)){
            $image = Input::file("file");
            $this->albumService->addNewImageToAlbum($album,$image);
        }else{
            abort(401);
        }
    }

    public function moveTo($id){
        $album = $this->albumService->findById($id);
        if(Auth::user()->can('update',$album)){
            $this->albumService->moveImagesToAlbum($album,Input::all());
        }else{
            abort(401);
        }
    }
    
    public function remove(Album $album){
        if(Auth::user()->can('remove',$album)){
            $this->albumService->deleteAlbum($album);
        }
    }
    
    
    
}
