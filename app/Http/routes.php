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
Route::group(['middleware' => ['web']], function () {

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

    Route::get('template/{template}/pdf', [
        'uses' => 'TemplateController@getAsPdf'
    ]);

    Route::get('template/demo', [
        'uses' => 'DemoController@getDemoTemplate'
    ]);

    Route::resource('template', 'TemplateController');

    Route::get('template/{template}/templateInstance',[
        'uses' => 'TemplateController@getInstancesForTemplate'
    ]);


    Route::resource('templateInstance', 'TemplateInstanceController');

    Route::get('templateInstance/{templateInstance}/html', [
        'uses' => 'TemplateInstanceController@getAsHtml'
    ]);

    Route::get('templateInstance/{templateInstance}/pdf', [
        'uses' => 'TemplateInstanceController@getAsPdf'
    ]);

    Route::resource('font', 'FontController');

    Route::get('font/{id}/file', [
        'uses' => 'FontController@getFile'
    ]);

    Route::resource('content', 'ContentController');

    Route::resource('element', 'ElementController');

    Route::resource('page', 'PageController');

    Route::get('album/demo',[
        'uses' => 'DemoController@getDemoAlbum'
    ]);

    Route::get('album/public',[
        'uses' => 'AlbumController@getPublicAlbums'
    ]);

    Route::get('album/user/{id}',[
        'uses' => 'AlbumController@getUserAlbums'
    ]);

    Route::resource('album', 'AlbumController');

    Route::post('album/{id}/upload', [
        'uses' => 'AlbumController@uploadTo'
    ]);

    Route::post('album/{id}/move', [
        'uses' => 'AlbumController@moveTo'
    ]);

    Route::resource('image', 'ImageController');

    Route::get('img/{path}', function ($path, League\Glide\Server $server, Illuminate\Http\Request $request){
        $split = explode('.', $path);
        if($split[1] == 'svg'){
            $image = Storage::get('images/'.$path);
               return response($image, 200)
                      ->header('Content-Type', 'image/svg+xml');
        }
        return $server->getImageResponse($path, $request->all());
    });

});


