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
        } elseif($request->server('staff') == 'member;staff') {  // Any Staff
            $this->UserAccess($request); 
            $this->LogUser($request); 
        } elseif($request->server('staff') <> 'member;staff') {  // Any non-staff
            $this->UserAccess($request); 
            $this->LogUser($request); 
/*        } elseif($request->server('ou') == 'Centre for Applied Linguistics' && $request->server('staff') == 'member;staff') {
            
            $this->LogUser($request);
            echo 'Welcome back '.$request->server('givenName').' - '.$request->server('ou').' ('.$request->server('staff').')';
            return $next($request);
        } elseif($request->server('ou') == 'Information Technology Services') { 
            $this->LogUser($request);
            echo 'Welcome back '.$request->server('givenName').' - '.$request->server('ou').' ('.$request->server('staff').')';
            return $next($request); //->with('message', 'Welcome back '.$request->server('givenName').'.');
*/        } elseif ($request->server('cn') == 'elsiai') {
            $this->LogUser($request);
            echo 'Welcome back AP - You have Developer access!';
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
            'RemoteIP' => $request->server('REMOTE_ADDR'),
            'Role' => session('userRole')    
            ]);    
    }
    private function UserAccess($request){
        try {
            // Check if User is already registered in Saml2UserAccess
            $cursor = DB::select('SELECT Username, Role FROM Saml2UserRoles WHERE Username = ?', [$request->server('cn')]); 
            if($cursor){
                $Role = $cursor[0]->Role;
                // Registered - Retrieve last login access
                $cursor = DB::select('SELECT MAX(Timestamp) as LastVisit FROM Saml2UserLog WHERE Username = ?', [$request->server('cn')]); 
                $LastVisit = $cursor[0]->LastVisit;
                $userMessage = 'Welcome back '.$request->server('givenName').', your access role is '.$Role.'. Your last access was at '.$LastVisit;
            } else {
                // Default Roles 
                if ($request->server('staff') == "member;staff") {
                    switch($request->server('ou')):
                        case 'Centre for Applied Linguistics': 
                            $Role = 'Tutor'; break;
                        case 'Information Technology Services': 
                            $Role = 'IT Support'; break;
                        default:
                            $Role = 'Pending'; 
                    endswitch;
                } else {
                    $Role = 'Student'; 
                }
                // Register first time access to system
                DB::insert('INSERT INTO Saml2UserRoles (Username, Surname, GivenName, Role) VALUES ( ?, ?, ?, ?)',
                    [$request->server('cn'), $request->server('sn'), $request->server('givenName'), $Role]);
                $userMessage = 'Welcome '.$request->server('givenName').', your have been granted an access role of '.$Role.'.';
            }
            echo $userMessage;
            // Store User Role
            session()->put('userRole',$Role);  // c.f. session('userRole',$Role);
            // Get User Role 
            $userRole = session()->get('userRole','No Access'); // $userRole = session('userRole');
            if($userRole <> $Role){
                echo ' - WARNING: User Role '.$Role.' not set in session';
            }
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
    }
}