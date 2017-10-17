<?php
namespace App\Services;

use App\Album;
use App\Image;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;

/** 
 * Service providing database access for Album mode
 */

class AlbumService {

    //user used for autorization
    private $user;

    //creates image service and takes user to autorize
    public function __construct($user = null){
        $this->user = $user;
        $this->imageService = new ImageService($this->user);
    }

    /** 
    * sets the user
    * @param user - user to set
    */
    public function setUser($user){
        $this->user = $user;
    }
  
    /** 
    * gets all albums
    * @return all albums in the DB
    */
    public function getAll(){
        return Album::all();
    }

    /** 
    * gets all albus that are public
    * @return all public albums in the DB
    */
    public function getPublicAlbums(){
        return Album::where('public',true)->with('tagged')->get();
    }

    public function getAlbumsByName($name){
        return Album::where('name', $name)->with('tagged')->get();
    }
   

    /** 
    * gets all albums for given user
    * @param user - user to get albums for
    * @return all user albums with taggs
    */
    public function getAlbumsForUser($user){
        return Album::where('user_id',$user->id)->with('tagged')->get();
    }

    public function getPublicAlbumsForUser($user){
        return Album::where('user_id',$user->id)->where('public',1)->with('tagged')->get();
    }
   
    /** 
    * finds album by id with its images and tags 
    * @param id - id of searched album
    * @return album or null if none found
    */
    public function findById($id)
    {
        $album = Album::with('tagged')->find($id);
        if($album !== null){
            $album->images;
        }
        return $album;
    }
    
    /** 
    * creates new album with taggs and associates user
    * @param album - array representing album to create
    * @return created album
    */
    public function createAlbum($album){
        $album2 = new Album($album);
        $album2->save();
        if(isset($album['tagged']) && is_array($album['tagged'])){
            $album2->tag(array_map(function($tag){return $tag['tag_name'];},$album['tagged']));
        }

        if($this->user !== null){
            $album2->user()->associate($this->user);
        }
        $album2->save();
        return $album2;
    }

    /** 
    * updates old album with new data including all its images and taggs
    * @param oldAlbum - Album instance to update
    * @param album - new data to update
    * @return - updated album
    */
    public function updateAlbum($oldAlbum, $album){
        $ids = [];
        if(isset($album['images'])){
            foreach ($album['images'] as $image) {
                $ids []= $image['id'];
            }
            Image::whereIn('id', $ids)->update(["album_id" => $oldAlbum->id]);
            Image::where('album_id', $oldAlbum->id)->whereNotIn('id', $ids)->update(["album_id" => null]);
        }

        if(isset($album['tagged']) && is_array($album['tagged'])){
            $oldAlbum->retag(array_map(function($tag){return $tag['tag_name'];},$album['tagged']));
        }

        $oldAlbum->name = $album['name'];
        $oldAlbum->public = $album['public'];
        $oldAlbum->save();
        return $oldAlbum;
    }

    /** 
    * moves all given images to the given album
    * @param album - album to move images to 
    * @param images - array representing images
    * @return album images were moved to
    */
    public function moveImagesToAlbum($album, $images){
        $imageService = new ImageService();
        $ids = array_map(function($image){return $image['id']; }, $images);
        Image::whereIn('id', $ids)->update(["album_id" => $album->id]);
        return $album;
    }

    /** 
    * adds new image model to the album
    * @param album - album to add image to
    * @param image - array of image to add 
    */
    public function addNewImageToAlbum($album,$image){
        $imageService = new ImageService();
        $image = $imageService->createImage($image);
        $image->album_id = $album->id;
        $image->save();
    }
    
    /** 
    * deletes album from DB
    * @param album - album to delete
    */
    public function deleteAlbum($album){
        $album->delete();
    }
    
}

