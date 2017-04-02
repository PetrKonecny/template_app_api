<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\PageService;

class PageController extends Controller
{
	public function __construct(PageService $service){
        $this->pageService = $service;
    }

    public function index(){
    	return $this->pageService->getAll();
    }
   
    public function show($id)
    {
    	return $this->pageService->findById($id);
    }
    
    public function store($page){
        return $this->pageService->createPage($page);
    }

    public function update($page){
        return $this->pageService->updatePage($page);
    }

    public function remove($page){
    	$this->pageService->deletePage($page);
    }
}
