<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login/{id}', [
    'uses' => 'UserController@login'
]);

Route::get('user/logout', [
    'uses' => 'UserController@logout'
]);

Route::get('user/current', [
    'uses' => 'UserController@getCurrent'
]);

Route::get('user/{user}/templates', [
	'uses' => 'UserController@getTemplates'
]);

Route::get('user/{user}/template-instances', [
	'uses' => 'UserController@getTemplateInstances'
]);

Route::get('user',[
	'uses' => 'UserController@getAll'
]);

Route::get('user/{user}',[
	'uses' => 'UserController@show'
]);

Route::get('template/search',[
    'uses' => 'TemplateController@search'
]);


Route::get('template/public',[
    'uses' => 'TemplateController@getPublicTemplates'
]);


Route::get('template/user/{id}',[
    'uses' => 'TemplateController@getUserTemplates'
]);

Route::resource('template', 'TemplateController');


Route::resource('templateInstance', 'TemplateInstanceController');
Route::resource('image', 'ImageController');
Route::get('templateInstance/{templateInstance}/html', [
    'uses' => 'TemplateInstanceController@getAsHtml'
]);

Route::get('templateInstance/{templateInstance}/pdf', [
    'uses' => 'TemplateInstanceController@getAsPdf'
]);

Route::get('templateInstance/user/{id}',[
    'uses' => 'TemplateInstanceController@getUserTemplateInstances'
]);

Route::resource('font', 'FontController');

Route::get('font/{id}/file', [
    'uses' => 'FontController@getFile'
]);

Route::resource('content', 'ContentController');

Route::resource('element', 'ElementController');

Route::resource('page', 'PageController');

Route::resource('album', 'AlbumController');

Route::post('album/{id}/upload', [
    'uses' => 'AlbumController@uploadTo'
]);

Route::post('album/{id}/move', [
    'uses' => 'AlbumController@moveTo'
]);

Route::get('img/{path}', function ($path, League\Glide\Server $server, Illuminate\Http\Request $request){
    return $server->getImageResponse($path, $request->all());
});

