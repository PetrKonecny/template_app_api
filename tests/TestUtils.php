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

class TestUtils {
    public static function fillTemplate($template){
            $template
            ->pages()->save(factory(App\Page::class,4)->make());
            //->each(function($p){
            //   $p->save(factory(App\TextElement::class,4)->make()); 
        
            
    //});       
    }

    public static function getNonAdminUser($id = 22){
        $user = new App\User;
        $user->admin = false;
        $user->id = $id;
        return $user;
    }

    public static function getAdminUser($id = 1){
        $user = new App\User;
        $user->admin = true;
        $user->id = $id;
        return $user;
    }

    public static function getTextContentArray(){
        return [
            'type' => 'text_content',
            'text' => str_random(10)
        ];
    }

    public static function getImageContentArray(){
        return [
            'type' => 'image_content',
            'image' => null
        ];
    }

    public static function getTableContentArray(){
        $rows = [['text'=>'cell_1'],['text'=>'cell_2']];
        return [
            'type' => 'table_content',
            'rows' => json_encode($rows)
        ];
    }

    public static function getImageArray(){
        return [
        'name' => "picture",
        ];
    }

    public static function getAlbumArray(){
        return [
        'name' => "album",
        'public' => true
        ];
    }

    public static function getArrayFromAlbum($album){
        return [
        'id' => $album['id'],
        'name' => $album['name'],
        'public' => $album['public']
        ];

    }

    public static function getPageArray(){
        return [
         'width' => 300,
         'height' => 600
        ];
    }

    public static function getTemplateArray(){
        return [
          'name' => 'template',
        ];
    }

    public static function getTemplateInstanceArray(){
        return [
          'name' => 'template',
        ];
    }

    public static function getArrayFromTemplate($template){
        return [
          'id' => $template['id'],
          'name' => $template['name']
        ];
    }

    public static function getArrayFromPage($page){
        return [
          'id' => $page->id,
          'width' => $page->width,
          'height' => $page->height
        ];
    }

    public static function fillElement($element){
        $element->content()->save(factory(App\Content::class)->make()); 
    }

    public static function fillAlbum($album){
        $album->images()->saveMany(factory(App\Image::class,4)->make()); 
    }

    public static function getElementArray(){
        return [
            'type' => 'text_element',
            'width' => rand(50, 300),
            'height' => rand(50, 300),
            'positionX' => rand(50, 300),
            'positionY' => rand(50, 300),
        ];
    }

    public static function getArrayFromElement($element){
        return [
            'id' => $element['id'],
            'type' => $element['type'],
            'width' => $element['width'],
            'height' => $element['height'],
            'positionX' => $element['positionX'],
            'positionY' => $element['positionY'],
        ];
    }      
}
