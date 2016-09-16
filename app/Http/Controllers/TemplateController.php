<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Template;
use App\Services\TemplateService;

class TemplateController extends Controller {

    
    public function __construct(TemplateService $service) {
        $this->templateService = $service;
    }
    
    
    public function index() {
        return $this->templateService->getAll();
    }
    
    public function show($id) {
        $template = $this->templateService->findByIdNested($id);
        return $template;
    }
    
    public function store () {
        $data = Input::all();
        $template = $this->templateService->createTemplate($data);
    }
    
    public function update() {
        $data = Input::all();
        $template = $this->templateService->findById(Input::get('id',0));
        $this->templateService->updateTemplate($template,$data);
    }

}
