<?php
use App\Http\Middleware\WarwickSSO as sso;
use App\Http\Middleware\Saml2SSO as saml2;  
//use App\Http\Middleware\WSSO as wsso;
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
// Old Root Page - Now called directly
Route::get('/laravel', function () {
    return view('laravel');
});
Route::get('/', function () {
    return view('aldbhome');
});
/*
Route::get('/login', function () {
    $Page = 'https://websignon.warwick.ac.uk/origin/slogin?'
              .'target=http://agmad.lnx.warwick.ac.uk/aldb/menu'
              .'&providerId='
              .'urn:troja.csv.warwick.ac.uk:mycal-online:service';  
    //$target = $request->url(); //.$request->header('Path');
    //$providerId = 'urn:troja.csv.warwick.ac.uk:mycal-online:service';
    //$LoginPage = 'https://websignon.warwick.ac.uk/origin/slogin'
    //            .'?target='.$target
    //            .'&providerId='.$providerId;
    return redirect($Page);
});
Route::get('/logout', function () {
    $Page = 'https://websignon.warwick.ac.uk/origin/logout?'
              .'target=http://agmad.lnx.warwick.ac.uk/';  
    return redirect($Page);
});
Route::get('/noaccess', function () {
    return view('noaccess');
});
*/
//This route is temporary just for testing the view blade pages
//Route::get('page','PageController@page');
Route::get('page/{page}','PageController@page');

Route::get('page/{$page}', function($page) {
    return view($page);
});

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'aldb', 'middleware' => 'sso'], function(){  //  
    Route::get('menu','AldbController@menu');
});

Route::group(['prefix' => 'tabula', 'middleware' => 'App\Http\Middleware\Saml2SSO'], function(){
    Route::get('/','TabulaController@Menu');
    Route::any('set/','TabulaController@GetSet')->name('tabula.set');
    Route::get('getset/','TabulaController@GetSmallGroupSet');
});

Route::group(['prefix' => 'ps', 'middleware' => 'App\Http\Middleware\Saml2SSO'], function(){
    Route::get('/','PsController@home');// <-middleware('sso');  
    Route::get('testVue','PsController@testVue');// <-middleware('sso');  
    Route::any('allocateclassrooms','PsController@allocateclassrooms');// <-middleware('sso');  
    Route::any('allocatestudents','PsController@allocatestudents');// <-middleware('sso');  
    Route::any('allocatetutors','PsController@allocatetutors');// <-middleware('sso');  
    Route::any('getpayments','PsController@getpayments');// <-middleware('sso');  
    
    Route::any('groups','PsController@groups');// <-middleware('sso');  
    Route::get('assess/{year}/{group}','PsController@assess($year,$group)');// <-middleware('sso');  
    Route::any('assess','PsController@assess')->name('ps.assess');// <-middleware('sso');  
    Route::get('report','PsController@report');// <-middleware('sso');  
    Route::get('rptedit','PsController@reportedit');// <-middleware('sso');  
    Route::get('mimictutor','PsController@mimictutor');
});

Route::group(['prefix' => 'sits', 'middleware' => 'App\Http\Middleware\Saml2SSO'], function(){  // 
    
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
/*
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
*/