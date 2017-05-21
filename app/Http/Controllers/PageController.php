<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\PageService;
use App\Page;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{

    public function __construct(PageService $service){
        $this->pageService = $service;
        $this->pageService->setUser(Auth::user());
        $this->middleware('auth');
    }


    /**responds to route
    /page  GET
    gets all page in the DB
    */ 
    public function index(){
        if(Auth::user()->can('index',Page::class)){
           return $this->pageService->getAll();
        }else{
            abort(401);
        }
    }
   
   /**responds to route
    /page/<id>  GET
    gets one page from the DB
    */
    public function show($id){
        $page = $this->pageService->findById($id);
        if(Auth::User()->can('show',$page)){
            return $page;
        }else{
            abort(401);
        }
    }
    
    /**responds to route
    /page  POST
    creates new page
    */
    public function store(Page $page){
        if(Auth::User()->can('store',$page)){
            return $this->pageService->createPage($page);
        }else{
            abort(401);
        }
    }

    /**responds to route
    /page/<id>/file  GET
    creates new page
    */
    public function update(Page $page){
        if(Auth::User()->can('update',$page)){
            return $this->pageService->updatePage($page);
        }else{
            abort(401);
        }    
    }

    /**responds to route
    /page/<id>  DELETE
    removes the page 
    */
    public function destroy(Page $page){
        if(Auth::user()->can('destroy',$page)){
            $this->pageService->deletePage($page);
        }else{
            abort(401);
        }
    }
}
