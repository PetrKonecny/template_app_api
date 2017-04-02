<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\ElementService;

class ElementController extends Controller
{

	public function __construct(ElementService $service){
        $this->elementService = $service;
    }

    public function index(){
    	return $this->elementService->getAll();
    }
   
    public function show($id)
    {
    	return $this->elementService->findById($id);
    }
    
    public function store($element){
        return $this->elementService->createElement($element);
    }

    public function update($element){
        return $this->elementService->upladeElement($element);
    }

    public function remove($element){
    	$this->elementService->deleteElement($element);
    }
}
