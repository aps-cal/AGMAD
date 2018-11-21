<?php
use App\Http\Middleware\WarwickSSO as sso;
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

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'ps', 'middleware' => 'sso'], function(){
    
    Route::get('testVue','PsController@testVue');// <-middleware('sso');  
    
    Route::get('assess','PsController@assess');// <-middleware('sso');  
    Route::get('report','PsController@report');// <-middleware('sso');  
    Route::get('rptedit','PsController@reportedit');// <-middleware('sso');  
    Route::get('mimictutor','PsController@mimictutor');
});

Route::group(['prefix' => 'vue', 'middleware' => 'sso'], function(){

    Route::get('test','VueController@test');// <-middleware('sso');  
    Route::get('testVue','VueController@testVue');// <-middleware('sso');  
    //Route::get('report','PsController@report');// <-middleware('sso');  
    //Route::get('rptedit','PsController@reportedit');// <-middleware('sso');  
    //Route::get('mimictutor','PsController@mimictutor');
});