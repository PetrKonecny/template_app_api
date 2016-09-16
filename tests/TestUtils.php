<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestUtils
 *
 * @author Petr2
 */
namespace Tests;

class TestUtils {
    public static function fillTemplate($template){
            $template
            ->pages()->save(factory(App\Page::class,4)->make());
            //->each(function($p){
            //   $p->save(factory(App\TextElement::class,4)->make()); 
        
            
    //});       
    }
}
