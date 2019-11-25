<?php
use App\Http\Middleware\WarwickSSO as sso;
use App\Http\Middleware\WSSO as wsso;
/*
|--------------------------------------------------------------------------
| Web Routes 
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/noaccess', function () {
    return view('noaccess');
});


//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'ps', 'middleware' => 'sso'], function(){
    Route::get('/','PsController@menu');// <-middleware('sso');  
    Route::get('testVue','PsController@testVue');// <-middleware('sso');  
    Route::any('groups','PsController@groups');// <-middleware('sso');  
    Route::get('assess','PsController@assess');// <-middleware('sso');  
    Route::get('report','PsController@report');// <-middleware('sso');  
    Route::get('rptedit','PsController@reportedit');// <-middleware('sso');  
    Route::get('mimictutor','PsController@mimictutor');
});

Route::group(['prefix' => 'sits', 'middleware' => 'App\Http\Middleware\WSSO'], function(){  // 
    
    Route::any('groups','SitsController@groups');
    Route::get('students','SitsController@students');// <-middleware('sso');  
    Route::get('courses','SitsController@courses');// <-middleware('sso');  
    
});

Route::group(['prefix' => 'vue', 'middleware' => 'sso'], function(){

    Route::get('test','VueController@test');// <-middleware('sso');  
    Route::get('testVue','VueController@testVue');// <-middleware('sso');  
    //Route::get('report','PsController@report');// <-middleware('sso');  
    //Route::get('rptedit','PsController@reportedit');// <-middleware('sso');  
    //Route::get('mimictutor','PsController@mimictutor');
});

Route::middleware(config('saml2_settings.routesMiddleware'))
->prefix(config('saml2_settings.routesPrefix').'/')->group(function() {
    Route::prefix('{idpName}')->group(function() {
	$saml2_controller = config('saml2_settings.saml2_controller', 'app\Saml2\Http\Controllers\Saml2Controller');

        Route::get('/logout', array(
            'as' => 'saml2_logout',
            'uses' => $saml2_controller.'@logout',
        ));

        Route::get('/login', array(
            'as' => 'saml2_login',
            'uses' => $saml2_controller.'@login',
        ));

        Route::get('/metadata', array(
            'as' => 'saml2_metadata',
            'uses' => $saml2_controller.'@metadata',
        ));

        Route::post('/acs', array(
            'as' => 'saml2_acs',
            'uses' => $saml2_controller.'@acs',
        ));

        Route::get('/sls', array(
            'as' => 'saml2_sls',
            'uses' => $saml2_controller.'@sls',
        ));
    });
});
