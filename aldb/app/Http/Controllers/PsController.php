<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Illuminate\Http\Request;
//use Illuminate\Support\facades\Input;


use App\PS\Assessments;
use App\PS\Reports;
use App\PS\Tutors;
use App\PS\Values;
use App\PS\Groups;


class PsController extends Controller {
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    private $userdata = null;
    
    
    public function __construct(){
    }
    public function home(Request $request){
        return view('pshome');
    }
    
    public function groups(Request $request) {
        Values::SetYear('2018');
        $year = Values::GetYear();
        $year = '2017';
        echo $year;
        $phase = Values::GetPhase();
        $group = '03';
        $ModuleCode = 'ET751';
        $AcademicYear = '19/20';
        $groups = Groups::GetTeachingGroups($ModuleCode,$AcademicYear);
        //$years = Values::GetYears();
        //$groups = Values::GetGroups();
        //echo serialize( $groups);
        
        return view('psassess')->with(['groups'=> $groups]);
 
    } 
   
    public function assess(Request $request) {
        // This needs to be removed once testing in complete
        Values::SetYear('2017');
        // Load Year for dropdown
        $years = Values::GetYears();
        // Check for Year input
        if(!($year = $request->input('Year'))) {
           $year = Values::GetYear();
        }
        //Check year has data NB. This is a useful array function
        if (!array_search($year, array_column($years,'PS_Year'))) {
           $year = Values::GetYear();
        }
        // Load Groups for dropdown
        $groups = Values::GetGroups($year);
        // Check for Group Input
        if(!($group = $request->input('Group'))) {
           $group = '01';
        }
        // Check Year has Group
        if (!array_search($group, array_column($groups,'Group_No'))) {
        //if(!$groups->contains($group)){
           $group = '01';
        }
        $assessments = Assessments::GetAssessments($year,$group);
        //echo serialize( $groups);
        return view('psassess')
            ->with([
            'assessments'=>$assessments, 
            'years'=> $years, 
            'groups'=> $groups, 
            'year'=> $year, 
            'group'=> $group
        ]);
 
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
    public function mimictutor(Request $request) {
        $tutorID = $request->get('TutorID');
        echo 'TutorID: '.$tutorID;
        if( isset($tutorID) ) { //!== null and isset($tutorID)){
            
            Tutors::SetTutor($tutorID);
        }
        $tutors = Tutors::ListTutors();
        $tutorID = Tutors::GetTutor();
        return view('mimictutor')->with(['tutors'=>$tutors,'TutorID'=>($tutorID)]);
    } 
    
    public function reportlist(Request $request) {
        return view('reportlist');
    } 
    
    public function reportedit(Request $request) {
        //$userdata = $request->get('userdata');
        //$userdata = $request->userdata;
        //$userdata = WarwickSSO::userdata();
        //if (false) {
        /*
        if(!isset($userdata['id']) && !isset($userdata['user'])) { 
            //$next = (function () {
            //    return back()->with('WARNING', 'You are not logged in with a valid Warwick ID');
            //});
            return back()->with('WARNING', 'You are not logged in with a valid Warwick ID');
            //echo "<html><body><h2>You are not logged in with a valid Warwick ID</h2></body></html>";
            //return redirect('/');
            //return $next($request); // If theres nothing to record... don't
        } elseif(!(($userdata['deptcode']=='ET' ||  $userdata['deptcode']=='IN' ) && $userdata['warwickitsclass']=='Staff')) {
            return back()->with('WARNING', 'You do not appear to be Applied Lingustics or ITS staff');
            // Only allow staff from CAL or ITS to access aldb.warwick.ac.uk/jsonp
            //echo "<html><body><h2>You do not appear to be Applied Lingustics or ITS staff</h2></body></html>";
            //return redirect('/');
            //return $next($request); // If theres nothing to record... don't
        }
        //}
        */
        //$this->logUser($userdata);
        Values::SetYear('2018');
        $year = Values::GetYear();
        $year = '2017';
        //echo $year;
        $phase = Values::GetPhase();
        $group = '03';
        $assessments = Assessments::GetAssessments($year,$group);
        $years = Assessments::GetYears();
        $groups = Assessments::GetGroups();
        //echo serialize( $groups);
        return view('reportedit')->with(['assessments'=>$assessments, 'years'=> $years, 'groups'=> $groups, 'year'=> $year, 'group'=> $group]);
    } 
    public function reportsave(Request $request) {
        return view('reportsave');
    } 
    
    private function logUser($user){
        if(!isset($user['id']) && !isset($user['user'])) {
            return; // If theres nothing to record... don't
        }
        
    /*    $Assessments = DB::select("INSERT INTO user_log ("
            . "UniversityID,UserID,Name,Firstname,Lastname,Title, "
            ."Email, Phone, Dept, DeptCode, Student, Staff, ITSClass, "
            . "TeachingStaff, SignonIP, RemoteIP) "
            ."VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )", 
            ['Group_no' => $Group_No, 'Presessional_Year' => $Presessional_Year]
        );
    */    
        $sql = "INSERT INTO user_log (UniversityID,UserID,Name,Firstname,Lastname,Title, "
            ."Email, Phone, Dept, DeptCode, Student, Staff, ITSClass, TeachingStaff, "
            ."SignonIP, RemoteIP) "
            ."VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
        
        $values = array($user['id'], $user['user'], $user['name'], $user['firstname'], $user['lastname'], $user['title'],
            $user['email'], $user['telephoneNumber'],$user['dept'], $user['deptcode'], ($user['student'] == 'true' ? 1 : 0), ($user['staff'] == 'true' ? 1 : 0),
            $user['warwickitsclass'], ($user['warwickteachingstaff'] == 'true' ? 1 : 0), $user['urn:websignon:ipaddress'], ''); //$request('REMOTE_ADDR'));
        try{
            $Assessments = DB::select($sql, $values); 
            
            /*"INSERT INTO user_log ("
            . "UniversityID,UserID,Name,Firstname,Lastname,Title, "
            ."Email, Phone, Dept, DeptCode, Student, Staff, ITSClass, "
            . "TeachingStaff, SignonIP, RemoteIP) "
            ."VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )", 
            ['Group_no' => $Group_No, 'Presessional_Year' => $Presessional_Year]
        );
            $this->db->query($sql, $values);*/
        }catch (Exception $e) {
         //      echo $e->message();
        }  
 
        return;
    }
    
    
}