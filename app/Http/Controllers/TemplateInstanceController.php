<?php

namespace App\Http\Controllers;
use App\Services\TemplateInstanceService;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\TemplateInstance;

class TemplateInstanceController extends Controller
{
    
    public function __construct(TemplateInstanceService $service) {
        $this->service = $service;
        $this->middleware('auth');
    }
    
    
    public function index() {
        return $this->service->getAll();
    }
    
    public function show(TemplateInstance $templateInstance) {
        if(Auth::User()->can('show',$templateInstance)){
            $templateInstance = $this->service->findById($templateInstance->id);
            return $templateInstance;
        }else{
            abort(401);
        }
    }
    
    public function store() {
        if(Auth::user()->can('create',TemplateInstance::class)){
            $data = Input::all();
            $this->service->createTemplateInstance($data);
        }else{
            abort(401);
        }
        
    }
    
    public function update(TemplateInstance $templateInstance){
        if(Auth::user()->can('update',$templateInstance)){
            $data = Input::all();
            $templateInstance = $this->service->findById($templateInstance->id);
            $this->service->updateTemplateInstance($templateInstance,$data);
        }else{
            abort(401);
        }
    }
    
    public function destroy(TemplateInstance $templateInstance) {
        if(Auth::user()->can('delete',$templateInstance)){
            $this->service->deleteTemplateInstance($templateInstance->id);
        }else{
            abort(401);
        }
    }
    
    public function getAsHtml(TemplateInstance $templateInstance){
        if(Auth::User()->can('show',$templateInstance)){
            $templateInstance = $this->service->findById($templateInstance->id);
            echo $templateInstance->toHtml();
        }else{
            abort(401);
        }

    }
    
    public function getAsPdf(TemplateInstance $templateInstance){       
        /*$pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($this->service->findById($templateId)->toHtml());
        return $pdf->inline();*/
        if(Auth::User()->can('show',$templateInstance)){
            $pdf = \App::make('dompdf.wrapper');
            $page = $templateInstance->template()->first()->pages()->first();
            $pdf->loadHTML($this->service->findById($templateInstance->id)->toHtml());
            $size = 'A4';
            if($page->width > 100 && $page->height > 100){
                $size = [0,0,$page->width,$page->height];
            }
            return @$pdf->setPaper($size)->stream();
        }else{
            abort(401);
        }
    }
}
