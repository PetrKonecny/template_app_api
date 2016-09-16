<?php

namespace App\Http\Controllers;
use App\Services\TemplateInstanceService;
use Illuminate\Support\Facades\Input;

class TemplateInstanceController extends Controller
{
    
    public function __construct(TemplateInstanceService $service) {
        $this->templateInstanceService = $service;
    }
    
    
    public function index() {
        return $this->templateInstanceService->getAll();
    }
    
    public function show($templateId) {
        $templateInstance = $this->templateInstanceService->findById($templateId);
        return $templateInstance;
    }
    
    public function store() {
        $data = Input::all();
        $this->templateInstanceService->createTemplateInstance($data);
        
    }
    
    public function update(){
        $data = Input::all();
        $templateInstance = $this->templateInstanceService->findById(Input::get('id',0));
        $this->templateInstanceService->updateTemplateInstance($templateInstance,$data);
    }
    
    public function getAsHtml($templateId){
        $templateInstance = $this->templateInstanceService->findById($templateId);
        echo $templateInstance->toHtml();

    }
    
    public function getAsPdf($templateId){       
        /*$pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($this->templateInstanceService->findById($templateId)->toHtml());
        return $pdf->inline();*/
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->templateInstanceService->findById($templateId)->toHtml());
        return $pdf->setPaper('A4', '[portrait')->stream();
    }
}
