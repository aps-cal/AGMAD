<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Illuminate\Http\Request;

//use App\Http\Middleware\WarwickSSO as SSO;
//use App\Http\Middleware\PS\Assessments;
//use App\Http\Middleware\PS\Reports;
//use App\Http\Middleware\PS\Tutors;
//use App\Http\Middleware\PS\Values;
//use App\Http\Middleware\PS\Groups;


class AldbController extends Controller {
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function __construct(){
    }
    public function menu(Request $request){
        return view('aldbmenu');
    }
}