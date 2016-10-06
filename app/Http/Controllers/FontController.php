<?php
namespace App\Http\Controllers;

use App\Services\FontService;
use Illuminate\Support\Facades\Input;

class FontController extends Controller
{
    public function __construct(FontService $service){
        $this->fontService = $service;
    }
    
    
    public function index(){
        return $this->fontService->getAll();
    }
   
    public function show($id)
    {
        return $this->fontService->findById($id);
    }
    
    public function store(){
        $font = Input::file("file");
        $this->fontService->createFont($font);
    }
    
    public function getFile($id){
        return $this->fontService->getFontFile($this->fontService->findById($id));
    }
    
    public function delete($font){
        $font->delete();
    }
}
