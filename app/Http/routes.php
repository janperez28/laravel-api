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

//dd(App::make('request'));

Route::group(['prefix' => 'v1'], function($router)
{
    $router->get('/', function() 
    {    
        return Response::api(true, ['health' => 'ok']);
    });

    // Request access token for the resource
    $router->post('token', function() 
    {        
        $token = Auth::attempt(Request::only('email', 'password'));
        
        return Response::api(true, compact('token'));
    });
        
    $router->group(['middleware' => ['jwt.auth']], function() use ($router)
    {
        $router->post('verify', function()
        {            
            
        });
        
        $router->get('logout', function()
        {
            Auth::logout();
            
            return Response::api(true);
        });
    });
});
