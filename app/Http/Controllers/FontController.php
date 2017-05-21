<?php
namespace App\Http\Controllers;

use App\Services\FontService;
use Illuminate\Support\Facades\Input;

class FontController extends Controller
{
    public function __construct(FontService $service){
        $this->fontService = $service;
    }
     
    /**responds to route
    /font  GET
    gets all font in the DB
    */ 
    public function index(){
        return $this->fontService->getAll();
    }
   
    /**responds to route
    /font/<id>  GET
    gets one font from the DB
    */
    public function show($id)
    {
        return $this->fontService->findById($id);
    }
    
    /**responds to route
    /font  POST
    creates new font
    */
    public function store(){
        $font = Input::file("file");
        $this->fontService->createFont($font);
    }
    
    /**responds to route
    /font/<id>/file  GET
    creates new font
    */
    public function getFile($id){
        return $this->fontService->getFontFile($this->fontService->findById($id));
    }
    
    /**responds to route
    /font/<id>  DELETE
    removes the font 
    */
    public function destroy($font){
        //$font->delete();
    }
}
