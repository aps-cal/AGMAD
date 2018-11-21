<?php

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
})->middleware('auth');
//Route::get('login', function () { 
//    return view('welcome'); 
//});

/*
$route['it/(:any)'] = 'It_controller/page/$1';
$route['test/(:any)'] = 'Test_controller/page/$1';
$route['html/(:any)'] = 'Html_controller/html/$1';
$route['page/(:any)'] = 'Page_controller/page/$1';
*/


//Route::get('test/setequipment','apiController@setequipment');
//Route::get('html/setequipment','apiController@setequipment');
//Route::get('page/setequipment','apiController@setequipment');



//Route::get('page','PageController@page');
Route::get('page/{page}','PageController@page');

Route::get('page/{$page}', function($page) {
    return view($page);
});

/*Route::get('aldb/public/page/{$page}','PageController@page($page)');
 * 
 


Route::get('page/psassess', function() {
    return view("psassess");
});

 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('login', 'Auth\LoginController'); //->middleware('auth');
Route::get('login', function (){ return view('auth/login');});

Route::get('ps/rptlist','PsController@reportlist');
Route::get('ps/rptedit','PsController@reportedit');
Route::get('ps/rptsave','PsController@reportsave');

/*
Route::get('cookie', function(Request $request){
    //$response = New Response();
    var_dump($request()->all()); //cookie('WarwickSSO'));
     //echo 'Cookie'.$request->cookie('WarwickSSO');
     //return $response()->json($request->cookie('WarwickSSO'));
     //return $response()->json($request()->all());
});
*/