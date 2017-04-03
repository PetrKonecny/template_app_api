<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Font;

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
            .'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'
            ."<style>"
            ."@page { margin: 0px; }"
            ."body { margin: 0px; }"
            ."</style>"
            ."</head>"
            ."<body>"
            .$this->loadCustomFonts()
            .$this->pagesToHtml($instanceId)
            ."</body>"
        ."</html>";
    }

    public function loadCustomFonts(){
        $link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";        
        $fonts = Font::all();
        $string = "";
        foreach ($fonts as $font) {
            $string .= "<style>";
                $string .= "@font-face {";
                $string .= "font-family: '" ."font" . $font->id . "';";
                $string .= "src: url('".$link."/font/".$font->id ."/file" ."'); format('truetype');";
                $string .= "}";
            $string .= "</style>";
        }
        return $string;
    }
    
    public function pagesToHtml($instanceId){
        $string = "";
        foreach ($this->pages as $page){
            $string .= $page->toHtml($instanceId);
        }
        return $string;
    }

}
