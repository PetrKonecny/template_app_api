<?php
namespace App\Services;

use App\Album;
use App\Image;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class AlbumService {
  
    public function getAll(){
        return Album::all();
    }
   
    public function findById($id)
    {
        $album = Album::find($id);
        $album->images;
        return $album;
    }
    
    public function createAlbum($album){
        $album = new Album($album);
        $album->save();
        return $album;
    }

    public function updateAlbum($oldAlbum, $album){
        $ids = [];
        foreach ($album['images'] as $image) {
            $ids []= $image['id'];
        }
        Image::whereIn('id', $ids)->update(["album_id" => $oldAlbum->id]);
        Image::where('album_id', $oldAlbum->id)->whereNotIn('id', $ids)->update(["album_id" => null]);
        $oldAlbum->name = $album['name'];
        $oldAlbum->save();
        return $oldAlbum;
    }

    public function moveImagesToAlbum($album, $images){
        $imageService = new ImageService();
        $ids = array_map(function($image){return $image['id']; }, $images);
        Image::whereIn('id', $ids)->update(["album_id" => $album->id]);
        return $album;
    }

    public function addNewImageToAlbum($album,$image){
        $imageService = new ImageService();
        $image = $imageService->createImage($image);
        $image->album_id = $album->id;
        $image->save();
    }
    
    public function deleteAlbum($album){
        $album->delete();
    }
    
}

