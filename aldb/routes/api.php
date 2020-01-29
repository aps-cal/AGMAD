<?php

use Illuminate\Http\Request;
use App\Http\Middleware\Saml2SSO as Saml2;  
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});


Route::group(['prefix' => 'api', 'middleware' => 'App\Http\Middleware\Saml2SSO'], function(){  
//    Route::any('tabula/set','ApiaController@GetTabulaSet')->name('api.tabula.set');   
    Route::get('assess','ApiController@assess');// <-middleware('sso');  
    Route::get('report','ApiController@report');// <-middleware('sso');  
});
/*
Route::group(['prefix' => 'api', 'middleware' => 'sso'], function(){
   
});

 */