<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model {

    protected $fillable = ['name'];
    
    protected static function boot() {
        parent::boot();

        static::deleting(function($template) { // before delete() method call this
             $template->templateInstances()->delete();
             // do the rest of the cleanup...
        });
    }
    
    public function pages() {
        return $this->hasMany(Page::class);
    }
    
    public function templateInstances() {
        return $this->hasMany(TemplateInstance::class);
    }
    
    public function toHtml($instanceId){
        return
        "<!DOCTYPE html>"
        ."<html>"
            ."<head>"
            ."<style>"
            ."@page { margin: 0px; }"
            ."body { margin: 0px; }"
            ."</style>"
            ."</head>"
            ."<body>"
            .$this->pagesToHtml($instanceId)
            ."</body>"
        ."</html>";
    }
    
    public function pagesToHtml($instanceId){
        $string = "";
        foreach ($this->pages as $page){
            $string .= $page->toHtml($instanceId);
        }
        return $string;
    }

}
