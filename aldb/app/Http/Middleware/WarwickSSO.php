<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class WarwickSSO {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    //public $userdata;
     
    // A Closure is a simple function than can be passed like a variable
    public function handle(Request $request, Closure $next){     
        //$request = Request::capture();
        $userdata = array();
        //$userdata = ['id'=>'test', 'deptcode'=>'ET','warwickitsclass'=>'Staff'];
        $token = $request->cookie('WarwickSSO');
        //echo $token;
        $userdata = $this->getUser($token);                
        
        //$userdata = $this->loaduser($request);    
        //$request->merge(compact('userdata'));
        //return back()->with('WARNING', 'Access is currently restricted to the developer');
    
        if(!isset($userdata['id']) && !isset($userdata['user'])) { 
            return redirect('/')->with('WARNING', 'You are not logged in with a valid Warwick ID');
            //echo "<html><body><h2>You are not logged in with a valid Warwick ID</h2></body></html>";
            return redirect('/')->with('WARNING', 'You are not logged in with a valid Warwick ID');
        } elseif(!$userdata['user']=='XXelsiai' ) {
            return redirect('/')->with('WARNING', 'Access is currently restricted to the developer');    
        } elseif(!(($userdata['deptcode']=='ET' ||  $userdata['deptcode']=='IN' ) && $userdata['warwickitsclass'])){
           return redirect('/')->with('WARNING', 'You do not appear to be Applied Lingustics or ITS staff');
        }        
        echo 'Welcome back <b>'.$userdata['firstname'].'</b>' ; //     .' &nbsp; &nbsp; [User:'.$userdata['id'].'/'.$userdata['user'].']';       
        
        // In this case the closure (alias function) $next was passed in then 
        // called by return with the $request opject as a parameter
        $response = $next($request);
        
        // Now is there anything else that needs to be done or added to response 
        // before it is passed pack to the calling function via return?
        return($response); 
    }
    
    private function loaduser($request){
        $userdata = array();
        //$userdata = ['id'=>'test', 'deptcode'=>'ET','warwickitsclass'=>'Staff'];
        $token = $request->cookie('WarwickSSO');
        //echo $token;
        $userdata = $this->getUser($token);
        $userdata['token'] = $token;
        return($userdata);
    }
    
    public function userdata(){
        return $this->userdata;
    }
    private function getUser($token){
          try{
            //error_reporting(E_STRICT); 
            //$pageURL = "https://websignon.warwick.ac.uk/sentry?requestType=1&token=".$token;
            // &wsos_api_key=f0b29422ccba737bf49724604580fbfe
            //$pageURL = env('SSO_SERVER_URL')."&token=".$token;;
            //$ch = curl_init(); 
            // set URL and other appropriate options
            //curl_setopt($ch, CURLOPT_URL, $pageURL);
            //curl_setopt($ch, CURLOPT_HEADER, 0);
            //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should prevent output to browser
            //curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Authorization: Bearer '. $this->getSessionID()]);
            //$res = (string) curl_exec($ch); 
            
            
            error_reporting(E_STRICT); 
            $ch = curl_init(); 
            //$pageURL = "https://websignon.warwick.ac.uk/sentry?requestType=1&token=".$token;
            // Jan 2019 GET parameters were depreciated so POSTING key parameters 
            $pageURL = "https://websignon.warwick.ac.uk/sentry;";
            curl_setopt($ch, CURLOPT_POST, true);
            $query = "requestType=1&token=".$token;
            curl_setopt($ch, CURLOPT_POSTFIELDS,  $query);
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $pageURL);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should prevent output to browser
            $res = curl_exec($ch); 
          } catch (Exception $e) {
            echo ('Error message (if any): '.curl_error($ch).'\n\n');
            echo ("<p>");
            var_dump(curl_getinfo($ch)); 
            echo ("</p><p>");
            var_dump($res); 
            echo ("</p>");   
        }
        curl_close($ch);
        //echo $res;
        return $this->parse($res);
    }
    
    private function parse($returnSSOString){
        $array = array();   
        $pieces = explode("\n", $returnSSOString);
        foreach ($pieces as $line) {
            if(strpos($line,'=') !== false) {
                list($field, $string) = explode('=', $line);
                //echo $field."  =>  ".$string."<br>";
                if(!empty($field)) {
                    if($field == 'id') {
                        $array['id'] = (int) $string;
                    } else {
                        //$array[$field] = makesafe($string);
                        $array[$field] = $string;                       
                    }
                }
            }
        }
        return $array;
    }
}
