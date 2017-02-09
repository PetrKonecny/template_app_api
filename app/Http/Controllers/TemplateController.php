<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Template;
use App\Services\TemplateService;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller {

    
    public function __construct(TemplateService $service) {
        $this->service = $service;
        $this->middleware('auth');
    }
    
    
    public function index() {
            return $this->service->getPublicTemplates();
    }
    
    public function show(Template $template) {
        if(Auth::User()->can('show',$template)){
            $template = $this->service->findByIdNested($template->id);
            return $template;
        }else{
            abort(401);
        }
    }
    
    public function store () {
        if(Auth::user()->can('create',Template::class)){
            $data = Input::all();
            $template = $this->service->createTemplate($data);
            return $this->service->findByIdNested($template->id);
        }else{
            abort(401);
        }
    }
    
    public function destroy(Template $template) {
        if(Auth::user()->can('delete',$template)){
            $this->service->deleteTemplate($template->id);
        }else{
            abort(401);
        }
    }
    
    public function update(Template $template) {
        if(Auth::user()->can('update',$template)){
            $data = Input::all();
            $template = $this->service->findById($template->id);
            $this->service->updateTemplate($template,$data);
            return $this->service->findByIdNested($template->id);
        }else{
            abort(404);
        }
    }

}
