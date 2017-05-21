<?php

namespace App\Http\Controllers;
use App\Services\TemplateInstanceService;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\TemplateInstance;
use App\User;

class TemplateInstanceController extends Controller
{
    
    public function __construct(TemplateInstanceService $service) {
        $this->service = $service;
        $this->service->setUser(Auth::user());
        $this->middleware('auth');    
    }
    
    /**responds to route
    /templateInstance  GET
    gets all template instances (documents)  in the DB
    */     
    public function index() {
        if(Auth::user()->can('index',TemplateInstance::class)){
            return $this->service->getAll();
        }else{
            abort(401);
        }
    }
    
    /**responds to route
    /templateInstance/<id>  GET
    gets one document from the DB
    */ 
    public function show(TemplateInstance $templateInstance) {
        if(Auth::User()->can('show',$templateInstance)){
            $templateInstance = $this->service->findById($templateInstance->id);
            return $templateInstance;
        }else{
            abort(401);
        }
    }

    /**responds to route
    /templateInstance/user/<id>  GET
    gets all documents belonging to the given user
    */ 
    public function getUserTemplateInstances($id){
        if(Auth::user()->id == $id){
            return $this->service->getTemplateInstancesForUser(User::find($id));
        }else{
            abort(401);
        }
    }
    
    /**responds to route
    /templateInstance POST
    creates new document
    */ 
    public function store() {
        if(Auth::user()->can('create',TemplateInstance::class)){
            $data = Input::all();
            $templateInstance = $this->service->createTemplateInstance($data);
            return $this->service->findById($templateInstance->id);
        }else{
            abort(401);
        }
        
    }
    
    /**responds to route
    /templateInstance/<id> PUT
    updates existing document
    */ 
    public function update(TemplateInstance $templateInstance){
        if(Auth::user()->can('update',$templateInstance)){
            $data = Input::all();
            $templateInstance = $this->service->findById($templateInstance->id);
            $this->service->updateTemplateInstance($templateInstance,$data);
            return $this->service->findById($templateInstance->id);
        }else{
            abort(401);
        }
    }
    
    /**responds to route
    /templateInstance/<id> DELETE
    deletes given document
    */ 
    public function destroy(TemplateInstance $templateInstance) {
        if(Auth::user()->can('delete',$templateInstance)){
            $this->service->deleteTemplateInstance($templateInstance->id);
        }else{
            abort(401);
        }
    }
    
    /**responds to route
    /templateInstance/<id>/html GET
    returns document represented as html string
    */ 
    public function getAsHtml(TemplateInstance $templateInstance){
        if(Auth::User()->can('show',$templateInstance)){
            $templateInstance = $this->service->findById($templateInstance->id);
            echo $templateInstance->toHtml();
        }else{
            abort(401);
        }

    }
    
    /**responds to route
    /templateInstance/<id>/pdf GET
    returns document represented as exported pdf using dompdf library
    */ 
    public function getAsPdf(TemplateInstance $templateInstance){       
        if(Auth::User()->can('show',$templateInstance)){
            $pdf = \App::make('dompdf.wrapper');
            $page = $templateInstance->template()->first()->pages()->first();
            $pdf->loadHTML($this->service->findById($templateInstance->id)->toHtml());
            $size = 'A4';
            if($page->width > 100 && $page->height > 100){
                $width = $page->width * 0.0393701 * 72;
                $height = $page->height * 0.0393701 * 72;
                $size = [0,0,$width,$height]
                ;
            }
            return @$pdf->setPaper($size)->stream();
        }else{
            abort(401);
        }
    }
}
