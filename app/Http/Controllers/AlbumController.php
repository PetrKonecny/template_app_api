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
        $this->middleware('auth')->except('show');    
    }
    
    /**responds to route
    /album  GET
    gets all albums in the DB
    */
    public function index(){
        if(Auth::user()->can('index',Album::class)){
            return $this->albumService->getAll();
        }else{
            abort(401);
        }
    }
   
   /**responds to route
    /album/<id>  GET
    gets one album from the DB
    */
    public function show(Album $album)
    {   
        if($album->isDemo()){
            return $this->albumService->findById($album->id);
        }else{
            $this->authorize('show',$album);
            return $this->albumService->findById($album->id);
        }
    }

    /**responds to route
    /album/user/<id>  GET
    gets all albums for currently logged in user
    */
    public function getUserAlbums($id){
        if(Auth::user()->id == $id || Auth::user()->admin){
            return $this->albumService->getAlbumsForUser(User::find($id));
        }else{
            return $this->albumService->getPublicAlbumsForUser(User::find($id));
        }
    }

    /**responds to route
    /album/public  GET
    gets all public albums
    */
    public function getPublicAlbums(){
        return $this->albumService->getPublicAlbums();
    }
    
    /**responds to route
    /album  POST
    creates new album
    */
    public function store(Album $album){
        if(Auth::user()->can('create',Album::class)){
            $album = $this->albumService->createAlbum(Input::all());
            return $this->albumService->findById($album->id);
        }else{
            abort(401);
        }
    }

    /**responds to route
    /album  PUT
    updates existing album
    */
    public function update($id){
        $album = $this->albumService->findById($id);
        if(Auth::user()->can('update',$album)){
            $album = $this->albumService->updateAlbum($album,Input::all());
            return $this->albumService->findById($album->id);
        }else{
            abort(401);
        }
    }

    /**responds to route
    /album/<id>/upload  POST
    adds image file into the given album 
    */
    public function uploadTo($id){
        $album = $this->albumService->findById($id);
        if(Auth::user()->can('update',$album)){
            $image = Input::file("file");
            $this->albumService->addNewImageToAlbum($album,$image);
        }else{
            abort(401);
        }
    }

    /**responds to route
    /album/<id>/move  POST
    moves image into the given album 
    */
    public function moveTo($id){
        $album = $this->albumService->findById($id);
        if(Auth::user()->can('update',$album)){
            $this->albumService->moveImagesToAlbum($album,Input::all());
        }else{
            abort(401);
        }
    }
    
    /**responds to route
    /album/<id>  DELETE
    removes the album 
    */
    public function destroy(Album $album){
        if(Auth::user()->can('remove',$album)){
            $this->albumService->deleteAlbum($album);
        }
    }
    
    
    
}
