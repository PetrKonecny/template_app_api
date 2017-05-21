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

    /**responds to route
    /template  GET
    gets all templates  in the DB
    */         
    public function index() {
        $this->authorize('index',Template::class);
        return $this->service->getAll();
    }

    /**responds to route
    /template/user/<id>
    gets templates for given user
    */
    public function getUserTemplates($id){
        if(Auth::user()->id == $id || Auth::user()->admin){
            return $this->service->getTemplatesForUser(User::find($id));
        }else{
            abort(401);
        }
    }

    /**responds to route
    /template/public  GET
    gets all public templates  in the DB
    */  
    public function getPublicTemplates(){
        return $this->service->getPublicTemplates();
    }


    public function getInstancesForTemplate(Template $template){
        $this->authorize('show',$template);
        return $template->templateInstances;
    }

    /**responds to route
    /template/<id>  GET
    gets one template from the DB
    */
    public function show(Template $template) {
        $this->authorize('show',$template);
        $template = $this->service->findByIdNested($template->id);
        return $template;
    }

    /**responds to route
    /template/search  GET
    searches templates based on tags and name
    */
    public function search(){
        return $this->service->search(Input::get('query'));
    }
    
    /**responds to route
    /template  POST
    creates new template
    */
    public function store () {
        $this->authorize('create',Template::class);
        $data = Input::all();
        $template = $this->service->createTemplate($data);
        return $this->service->findByIdNested($template->id);
    }
    
    /**responds to route
    /template/<id>  DELETE
    removes the template 
    */
    public function destroy(Template $template) {
        $this->authorize('delete',$template);
        $this->service->deleteTemplate($template->id);
    }
    
    /**responds to route
    /template/<id>  PUT
    updates the template 
    */
    public function update(Template $template) {
        $this->authorize('update',$template);
        $template = $this->service->findById($template->id);
        $this->service->updateTemplate($template,Input::all());
        return $this->service->findByIdNested($template->id);
    }

}
