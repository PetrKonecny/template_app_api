<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Album;
use App\User;
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
   
    public function show(Album $album)
    {   
        $this->authorize('show',$album);
        return $this->albumService->findById($album->id);
    }

    public function getUserAlbums($id){
        if(Auth::user()->id == $id || Auth::user()->admin){
            return $this->albumService->getAlbumsForUser(User::find($id));
        }else{
            abort(401);
        }
    }

    public function getPublicAlbums(){
        return $this->albumService->getPublicAlbums();
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
            $album = $this->albumService->updateAlbum($album,Input::all());
            return $this->albumService->findById($album->id);
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
