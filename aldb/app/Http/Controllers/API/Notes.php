<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 

When wanting to get the User in an API call use the folloing

Auth::guard('api')->user();

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});












*/