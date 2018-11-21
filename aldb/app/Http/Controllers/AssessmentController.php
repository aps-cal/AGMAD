<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AssessmentController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function getCookie(Request $request){
        return $request()->cookie('WarwickSSO');
    }
    
    public function reportlist() {
        return view('reportlist');
    } 
    public function reportedit() {
        return view('reportedit');
    } 
    public function reportsave() {
        return view('reportsave');
    } 
}