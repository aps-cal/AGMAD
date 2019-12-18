<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Illuminate\Http\Request;

//use App\Http\Middleware\WSSO as WSSO;
use App\SITS\Students;
use App\PS\Groups;
use App\PS\Values;

use App\Tabula\TabulaAPI;
use App\Tabula\SmallGroupSet;
use App\Tabula\SmallGroup;
use App\Tabula\SmallGroupEvent;


class TabulaController extends Controller {
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function __construct(){   
    }
    
    public function Menu(){
        return view('tabula.menu');
    }
    
    public function GetSmallGroupSet(){
        $smallGroupSet = new SmallGroupSet();
        $smallGroupSet->RetrieveSet('ET751','18/19',null);
        return view('aldbhome');
    }
    
    
    
    
    
    
    
    /*
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     */

    public function groups(Request $request) {
        Values::SetYear('2018');
        //$year = Values::GetYear();
        //$year = '2017';
        //echo $year;
        //$phase = Values::GetPhase();
        //$group = '03';
        $ModuleCode = 'ET751';
        $AcademicYear = '18/19';
        $groups = array();
        $groups = Groups::GetGroups($ModuleCode,$AcademicYear);
        $years = Values::GetYears();
        $groups = Values::GetGroups();
        //echo serialize( $groups);
        return view('aldbhome');
        //return view('psassess')->with(['groups'=> $groups]);
 
    } 
    public function students(Request $request) {
        Values::SetYear('2018');
        $year = Values::GetYear();
        $year = '2017';
        //echo $year;
        $phase = Values::GetPhase();
        $group = '01';
        $students = Students::GetStudents($year,$group);
        $years = Values::GetYears();
        $groups = Values::GetGroups();
        //echo serialize( $groups);
        return view('sitsstudents')->with(['assessments'=>$students, 'years'=> $years, 'groups'=> $groups, 'year'=> $year, 'group'=> $group]);
 
    } 
/*    public function report(Request $request) {
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
 */
    
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