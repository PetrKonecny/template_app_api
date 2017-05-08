<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\ElementService;
use App\Element;

class ElementController extends Controller
{

	public function __construct(ElementService $service){
        $this->elementService = $service;
        $this->elementService->setUser(Auth::user());
        $this->middleware('auth');
    }

    public function index(){
        if(Auth::user()->can('index',Element::class)){
    	   return $this->elementService->getAll();
        }else{
            abort(401);
        }
    }
   
    public function show($id){
        $element = $this->elementService->findById($id);
        if(Auth::User()->can('show',$element)){
            return $element;
        }else{
            abort(401);
        }
    }
    
    public function store(Element $element){
        if(Auth::User()->can('store',$element)){
            return $this->elementService->createElement($element);
        }else{
            abort(401);
        }
    }

    public function update(Element $element){
        if(Auth::User()->can('update',$element)){
            return $this->elementService->updateElement($element);
        }else{
            abort(401);
        }    
    }

    public function remove(Element $element){
        if(Auth::user()->can('remove',$element)){
    	    $this->elementService->deleteElement($element);
        }else{
            abort(401);
        }
    }
}
