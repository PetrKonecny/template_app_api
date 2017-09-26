<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Font;

//model for template
class Template extends Model  {
    use \Conner\Tagging\Taggable;

    protected $fillable = ['name','public', 'type'];
    
    protected static function boot() {
        parent::boot();

        //on delete deletes all its template instances
        static::deleting(function($template) { // before delete() method call this
             $template->templateInstances()->delete();
             // do the rest of the cleanup...
        });
    }
    
    //relation to its pages
    public function pages() {
        return $this->hasMany(Page::class);
    }

    //relation to user who created it
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    //relation to instances made from this template
    public function templateInstances() {
        return $this->hasMany(TemplateInstance::class);
    }
    
    /**
    * creates html representation for the tempalte
    * @param instanceId - id of template instatnce which contents should be inserted
    * @return html string
    */ 
    public function toHtml($instanceId){
        //inserts every font into the head of html
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

    //helper function that loads all fonts in db and creates valid html style tags for them
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
    
    //helper function that transforms pages to html
    public function pagesToHtml($instanceId){
        $string = "";
        foreach ($this->pages as $page){
            $string .= $page->toHtml($instanceId);
        }
        return $string;
    }

}
