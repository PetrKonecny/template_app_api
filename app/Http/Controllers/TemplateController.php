<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Template;
use App\User;
use App\Services\TemplateService;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller {

    
    public function __construct(TemplateService $service) {
        $this->service = $service;
        $this->service->setUser(Auth::user());
        $this->middleware('auth');
    }

        
    public function index() {
        $this->authorize('index',Template::class);
        return $this->service->getAll();
    }

    public function getUserTemplates($id){
        if(Auth::user()->id == $id || Auth::user()->admin){
            return $this->service->getTemplatesForUser(User::find($id));
        }else{
            abort(401);
        }
    }

    public function getPublicTemplates(){
        return $this->service->getPublicTemplates();
    }

    public function getInstancesForTemplate(Template $template){
        $this->authorize('show',$template);
        return $template->templateInstances;
    }

    public function show(Template $template) {
        $this->authorize('show',$template);
        $template = $this->service->findByIdNested($template->id);
        return $template;
    }

    public function search(){
        return $this->service->search(Input::get('query'));
    }
    
    public function store () {
        $this->authorize('create',Template::class);
        $data = Input::all();
        $template = $this->service->createTemplate($data);
        return $this->service->findByIdNested($template->id);
    }
    
    public function destroy(Template $template) {
        $this->authorize('delete',$template);
        $this->service->deleteTemplate($template->id);
    }
    
    public function update(Template $template) {
        $this->authorize('update',$template);
        $template = $this->service->findById($template->id);
        $this->service->updateTemplate($template,Input::all());
        return $this->service->findByIdNested($template->id);
    }

}
