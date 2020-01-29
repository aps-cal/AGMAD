<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\SITS\Students;
use App\PS\Groups;
use App\PS\Values;

use App\Tabula\TabulaAPI;
use App\Tabula\SmallGroupSet;
use App\Tabula\SmallGroup;
use App\Tabula\SmallGroupEvent;

class ApiController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    
    public function GetTabulaSet(){
        $Module = Input::get('Module');
        if(!$Module) $Module = 'ET751';
        $aYear = Input::get('aYear');
        if(!$aYear) $aYear = '18/19';
        $SetID = Input::get('SetID');
        $aYears = Values::GetAcadYears();
        $Modules = Values::GetModules($aYear);
        //var_dump($Modules);
        $Sets = SmallGroupSet::GetSet($Module,$aYear,$SetID);
        //$Sets = new SmallGroupSet;
        //$Sets->RetrieveSet($Module,$aYear,$SetID); //'82f0db24-a2fc-4bfe-ae64-f366fcbdfc76'
        //$printout = print_r($smallGroupSet);
        $data = ['aYear'=>$aYear, 'aYears'=>$aYears, 
            'Module' => $Module, 'Modules'=>$Modules, 
            'SetID' => $SetID, 'Sets'=>$Sets];
        //return view('tabula.set')->with(['aYear'=>$aYear, 'aYears'=>$aYears, 
        //    'Module' => $Module, 'Modules'=>$Modules, 
        //    'SetID' => $SetID, 'Sets'=>$Sets]); 
           
    }
    
    
    public function assess(Request $request) {
        Values::SetYear('2018');
        $year = Values::GetYear();
        $year = '2017';
        //echo $year;
        $phase = Values::GetPhase();
        $group = '03';
        $assessments = Assessments::GetAssessments($year,$group);
        $years = Values::GetYears();
        $groups = Values::GetGroups();
        //echo serialize( $groups);
        return view('psassess')->with(['assessments'=>$assessments, 'years'=> $years, 'groups'=> $groups, 'year'=> $year, 'group'=> $group]);
 
    } 
    public function report(Request $request) {
        Values::SetYear('2018');
        $year = Values::GetYear();
        $year = '2017';
        //echo $year;
        $phase = Values::GetPhase();
        $group = '03';
        $reports = Reports::GetReports($year,$group);
        $years = Values::GetYears();
        $groups = Values::GetGroups();
        //echo serialize( $groups);
        return view('psreport')->with(['reports'=>$reports, 'years'=> $years, 'groups'=> $groups, 'year'=> $year, 'group'=> $group]);
 
    } 

    
    
    public function checkuser(){
        $user = $this->loaduser();
        if(!isset($user['id']) && !isset($user['user'])) {       
            echo "<html><body><h2>You are not logged in with a valid Warwick ID</h2></body></html>";
            return; // If theres nothing to record... don't
        } elseif(!(($user['deptcode']=='ET' ||  $user['deptcode']=='IN' ) && $user['warwickitsclass']=='Staff')) {
            // Only allow staff from CAL or ITS to access aldb.warwick.ac.uk/jsonp
            echo "<html><body><h2>You do not appear to be Applied Lingustics or ITS staff</h2></body></html>";
            return; // If theres nothing to record... don't
        }
    }
    
    
        
        
    private function loaduser(){
        //$userdata = array();
        //try{
            $token = get_cookie("WarwickSSO");
            $userdata = $this->getUser($token);
            $this->logUser($userdata);
        //}
        return($userdata);
    }
    private function getUser($token){
        try{
            error_reporting(E_STRICT); 
            $pageURL = "https://websignon.warwick.ac.uk/sentry?requestType=1&token=".$token;
            $ch = curl_init(); 
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $pageURL);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should prevent output to browser
            $res = curl_exec($ch); 
        } catch (Exception $e) {
            printf('Error message (if any): '.curl_error($ch).'\n\n');
            printf("<p>");
            var_dump(curl_getinfo($ch)); 
            printf("</p><p>");
            var_dump($res); 
            printf("</p>");
        }
        curl_close($ch);
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
    
    private function logUser($user){
        if(!isset($user['id']) && !isset($user['user'])) return; // If theres nothing to record... don't
        
        
        $sql = "INSERT INTO user_log (UniversityID,UserID,Name,Firstname,Lastname,Title, "
            ."Email, Phone, Dept, DeptCode, Student, Staff, ITSClass, TeachingStaff, "
            ."SignonIP, RemoteIP) "
            ."VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
        $values = array($user['id'], $user['user'], $user['name'], $user['firstname'], $user['lastname'], $user['title'],
            $user['email'], $user['telephoneNumber'],$user['dept'], $user['deptcode'], ($user['student'] == 'true' ? 1 : 0), ($user['staff'] == 'true' ? 1 : 0),
            $user['warwickitsclass'], ($user['warwickteachingstaff'] == 'true' ? 1 : 0), $user['urn:websignon:ipaddress'], $_SERVER['REMOTE_ADDR']);
        try{
            $this->db->query($sql, $values);
        }catch (Exception $e) {
         //      echo $e->message();
        }   
    }
}
