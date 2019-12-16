<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Closure;

class Saml2SSO {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    // A Closure is a simple function than can be passed like a variable
    public function handle(Request $request, Closure $next){  
        if($request->server('SERVER_NAME')  == 'localhost' ) {
            echo 'DEVELOPMENT MODE - Server running on http://localhost.';
            return $next($request); 
        }elseif(!$request->server('cn')) {   
            echo 'SAML2 SSO is still not configured.';
            dd($request->server());
            return $next($request); //->with('message', 'SAML2 SSO is still not configured.');
            //return redirect('back')->with('WARNING', 'You are not logged in with a valid Warwick ID');
        } elseif($request->server('ou') == 'Centre for Applied Linguistics' && $request->server('staff') == 'member;staff') {
            $this->LogUser($request);
            echo 'Welcome back '.$request->server('givenName').' - '.$request->server('ou').' ('.$request->server('staff').')';
            return $next($request);
        } elseif($request->server('ou') == 'Information Technology Services') { 
            $this->LogUser($request);
            echo 'Welcome back '.$request->server('givenName').' - '.$request->server('ou').' ('.$request->server('staff').')';
            return $next($request); //->with('message', 'Welcome back '.$request->server('givenName').'.');
        } elseif ($request->server('cn') == 'elsiai') {
            $this->LogUser($request);
            echo 'Welcome back Andrew - You have Developer access!';
            return $next($request); //-.->with('message', 'Welcome back Andrew - You have Developer access!');
        } else {
            LogUser($request);
            dd($request->server());
            return redirect('back')->with('WARNING', 'You are not logged in with a valid Warwick ID');
        }
 
    //    echo 'Welcome back <b>'.$userdata['firstname'].'</b>' ; //     .' &nbsp; &nbsp; [User:'.$userdata['id'].'/'.$userdata['user'].']';       
        
        // In this case the closure (alias function) $next was passed in then 
        // called by return with the $request opject as a parameter
        $response = $next($request);
        
        // Now is there anything else that needs to be done or added to response 
        // before it is passed pack to the calling function via return?
        return($response); 
    }
    
    private function LogUser($request){
        DB::table('Saml2UserLog')->insert([
            'Username' => $request->server('cn'),
            'Surname' => $request->server('sn'),
            'GivenName' => $request->server('givenName'),
            'Email' => $request->server('mail'),
            'Department' => $request->server('ou'),
            'Member' => $request->server('staff'),
            'RequestURI' => $request->server('REQUEST_URI'),
            'RemoteIP' => $request->server('REMOTE_ADDR')       
            ]);    
    }
}