<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Template extends Model  {
    use \Conner\Tagging\Taggable;

    protected $fillable = ['name','public'];
    
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

    public function user() {
        return $this->belongsTo(User::class);
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
            ."<style>"
            ."@font-face { font-family: 'font1'; src: url('http://sablony.skauting.cz/font/1/file');  font-style: normal; font-weight: bold; format('truetype');}"
            ."</style>"
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
