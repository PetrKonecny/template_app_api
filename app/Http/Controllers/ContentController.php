<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\ContentService;
use App\Content;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{

    public function __construct(ContentService $service){
        $this->contentService = $service;
        $this->contentService->setUser(Auth::user());
        $this->middleware('auth');
    }

    /**responds to route
    /content  GET
    gets all content in the DB
    */
    public function index(){
        if(Auth::user()->can('index',Content::class)){
           return $this->contentService->getAll();
        }else{
            abort(401);
        }
    }
   
   /**responds to route
    /content/<id>  GET
    gets one content from the DB
    */
    public function show($id){
        $content = $this->contentService->findById($id);
        if(Auth::User()->can('show',$content)){
            return $content;
        }else{
            abort(401);
        }
    }
    
    /**responds to route
    /content  POST
    creates new content
    */
    public function store(Content $content){
        if(Auth::User()->can('store',$content)){
            return $this->contentService->createContent($content);
        }else{
            abort(401);
        }
    }

    /**responds to route
    /content  PUT
    updates existing content
    */
    public function update(Content $content){
        if(Auth::User()->can('update',$content)){
            return $this->contentService->updateContent($content);
        }else{
            abort(401);
        }    
    }

    /**responds to route
    /content/<id>  DELETE
    removes the content 
    */
    public function remove(Content $content){
        if(Auth::user()->can('remove',$content)){
            $this->contentService->deleteContent($content);
        }else{
            abort(401);
        }
    }
}
