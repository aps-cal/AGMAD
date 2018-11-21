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
    public function handle($request, Closure $next){
        //$wsos_api_key = '&wsos_api_key=f0b29422ccba737bf49724604580fbfe';
        //echo $request;       
        //if(isnull($request)){
            //$request = Illuminate\Http\Request::capture();
            $request = Request::capture();
        //}
        $userdata = array();
        //$userdata = ['id'=>'test', 'deptcode'=>'ET','warwickitsclass'=>'Staff'];
        $token = $request->cookie('WarwickSSO');
        //echo $token;
        $userdata = $this->getUser($token);                
        $LoginPage = 'https://websignon.warwick.ac.uk/origin/slogin?'
              .'target=http://agmad.lnx.warwick.ac.uk'
              .'&providerID='
              .'urn:troja.csv.warwick.ac.uk:mycal-online:service';  
        $target = $request->url(); //.$request->header('Path');
        $providerId = 'urn:troja.csv.warwick.ac.uk:mycal-online:service';
        $LoginPage = 'https://websignon.warwick.ac.uk/origin/slogin'
                .'?target='.$target
                .'&providerId='.$providerId;
               
        //$userdata = $this->loaduser($request);    
        //$request->merge(compact('userdata'));
        //echo 'User:'.$userdata['id'].'/'.$userdata['user'].' Welcome back '.$userdata['firstname'];       
        if(!isset($userdata['id']) && !isset($userdata['user'])) { 
            //return redirect()->route('/home')->with('WARNING', 'You are not logged in with a valid Warwick ID');
            //echo "<html><body><h2>You are not logged in with a valid Warwick ID</h2></body></html>";
            return redirect($LoginPage);
            //return $next($request); // If theres nothing to record... don't
        } elseif(!(($userdata['deptcode']=='ET' ||  $userdata['deptcode']=='IN' ) && $userdata['warwickitsclass']=='Staff')) {
            return back()->with('WARNING', 'You do not appear to be Applied Lingustics or ITS staff');
            // Only allow staff from CAL or ITS to access aldb.warwick.ac.uk/jsonp
            //echo "<html><body><h2>You do not appear to be Applied Lingustics or ITS staff</h2></body></html>";
            //return redirect('/');
            //return $next($request); // If theres nothing to record... don't
        }        
//        if (Auth::guard($guard)->check()) {
//            return redirect('/home');
//        }        
        // In this case the closure (alias function) $next was passed in then 
        // called by return with the $request opject as a parameter
        return $next($request);
    }
    
    private function loaduser($request){
        $userdata = array();
        //$userdata = ['id'=>'test', 'deptcode'=>'ET','warwickitsclass'=>'Staff'];
        $token = $request->cookie('WarwickSSO');
        //echo $token;
        $userdata = $this->getUser($token);
        return($userdata);
    }
    
    public function userdata(){
        return $this->userdata;
    }
    private function getUser($token){
          try{
            error_reporting(E_STRICT); 
            $pageURL = "https://websignon.warwick.ac.uk/sentry?requestType=1&token=".$token;
            // &wsos_api_key=f0b29422ccba737bf49724604580fbfe
            //$pageURL = env('SSO_SERVER_URL')."&token=".$token;;
            $ch = curl_init(); 
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $pageURL);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should prevent output to browser
            //curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Authorization: Bearer '. $this->getSessionID()]);
            $res = (string) curl_exec($ch); 
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
