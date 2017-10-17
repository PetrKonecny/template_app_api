<?php

namespace App\Http\Controllers;

use App\Services\AlbumService;
use App\Services\TemplateService;

class DemoController extends Controller
{

    public function __construct(TemplateService $templateService, AlbumService $albumService ) {
        $this->templateService = $templateService;
        $this->albumService = $albumService;
    }

     /**responds to route
    /template/demo GET
    gets one template for app demo
    */
    public function getDemoTemplate(){
        return $this->templateService->getDemo();
    }

    public function getDemoAlbum(){
        return $this->albumService->getAlbumsByName("demo")[0];
    }   
}
