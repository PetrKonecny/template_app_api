<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\ContentService;

class ContentController extends Controller
{

	public function __construct(ContentService $service){
        $this->contentService = $service;
    }

    public function index(){
    	return $this->contentService->getAll();
    }
   
    public function show($id)
    {
    	return $this->contentService->findById($id);
    }
    
    public function store($content){
        return $this->contentService->createContent($content);
    }

    public function update($content){
        return $this->contentService->updateContent($content);
    }

    public function remove($content){
    	$this->contentService->deleteContent($content);
    }
}
