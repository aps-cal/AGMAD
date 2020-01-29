<?php

/* 
    The Small Group Set object
    The small group set object returned by Tabula has the following properties:

    id	A unique identifier for the set of small groups
    archived            A boolean value, true if the small group set is archived, false otherwise
    academicYear        A string representation of the academic year in yy/yy format, e.g. 13/14
    name                The name of the small group set
    format              The small groups format, one of:
                            seminar - Seminar
                            lab - Lab
                            tutorial - Tutorial
                            project - Project group
                            example - Example Class
                            workshop - Workshop
                            lecture - Lecture
                            meeting - Meeting
                            exam - Exam
    allocationMethod	The method for allocating students to groups, one of:
                            Manual - Students are manually allocated to groups by an administrator
                            StudentSignUp - Students sign up to an available group
                            Linked - Student membership is linked to allocation from a linked department small group set
                            Random - Students are randomly allocated to groups
    releasedToTutors	A boolean value, true if the allocations have been released to tutors, false otherwise
    releasedToStudents	A boolean value, true if the allocations have been released to students, false otherwise
    emailTutorsOnChange	A boolean value, true if changes should generate an email to tutors, false otherwise
    emailStudentsOnChange	A boolean value, true if changes should generate an email to students, false otherwise
    studentsCanSeeTutorName	A boolean value, true if students are allowed to see the name of the event tutor(s), false otherwise
    studentsCanSeeOtherMembers	A boolean value, true if students are allowed to see the details of students in the same group, false otherwise
    defaultMaxGroupSizeEnabled	A boolean value, true if newly created groups should use the set default max size, false otherwise
    defaultMaxGroupSize	If defaultMaxGroupSizeEnabled is true, this is the maximum group size to be used for newly created groups
    collectAttendance	A boolean value, true if attendance should be collected at events, false otherwise
    allowSelfGroupSwitching	Only if allocationMethod is StudentSignUp. A boolean value, true if students are allowed to switch groups until sign-up is closed, false otherwise
    openForSignups	Only if allocationMethod is StudentSignUp. A boolean value, true if sign-up is open, false otherwise
    linkedDepartmentGroupSet	Only if allocationMethod is Linked. The unique identifier of the linked department group set
    studentMembership	An object representing the membership of the small group set. Contains four properties:
    total - the total number of students linked to the small group set
    linkedSits - the number of students linked to SITS assessment groups
    included - the number of extra students added to the small group set and not linked to SITS
    excluded - the number of students who have been manually removed from the membership of the small group set
    users - an array of the users linked to the small group set. Each element has two keys, userId and universityId
    sitsLinks	An array of objects representing the active links to SITS membership for the small group set. Each object contains four properties:
    moduleCode
    assessmentGroup
    occurrence
    sequence
    groups                  An array of small group objects for each group in the set
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */

namespace App\Tabula;

use \App\Tabula\TabulaAPI;
use \Illuminate\Support\Facades\DB;

class SmallGroupSet { //extends SimpleXMLElement {
    
    // Class Properties
    var $PresessionalYear = '2019';
    var $id;
    var $archived = false;
    var $academicYear;
    var $name;
    var $format = 'seminar'; 
    var $allocationMethod = 'Manual'; 
    // Boolean Properties
    var $releasedToTutors = false; 
    var $releasedToStudents = false;
    var $emailTutorsOnChange = true;
    var $emailStudentsOnChange = true;
    var $studentsCanSeeTutorName = true;
    var $studentsCanSeeOtherMembers = false;
    var $defaultMaxGroupSizeEnabled = true;
    var $defaultMaxGroupSize = 30;
    var $collectAttendance = true;  //Attendance should be collected at events
    var $allowSelfGroupSwitching = false; 
    var $openForSignups = false;
    var $linkedDepartmentGroupSet = false;
    var $studentMembership = null; // This is an object {total, linkedSits, included, excluded, users[]}
    // Class Array Properties
    //var $users;     // An array each element has two keys, userId and universityId  NB. This array may be found in $studentMembership 
    var $sitsLinks; // An array of objects representing the active links to SITS membership for the small group set. Each object contains four properties:
    var $groups;    // An array of small group objects for each group in the set
    
    function __construct(){
        
    }
    /*
    function __construct($xml){
        if($xml){
            $this->ReadSet($xml);
        }
    }
    */
    public function CreateSet() {
        
    }
    
    public static function GetSets($moduleCode, $academicYear){
        $pageURL = "https://tabula.warwick.ac.uk/api/v1/module/".$moduleCode."/groups";
        $query = "academicYear=".$academicYear;
        $resObj = TabulaAPI::GetResponse($pageURL, $query);
        if($resObj->success){
            return $resObj->groups;
        } else { 
            return array();
        }
    }
    
    public static function GetSet($moduleCode, $academicYear, $smallGroupSetId){
        $pageURL = "https://tabula.warwick.ac.uk/api/v1/module/".$moduleCode."/groups";
        if($smallGroupSetId){
            $pageURL.="/".$smallGroupSetId;
        }
        $query = "academicYear=".$academicYear;
        $resObj = TabulaAPI::GetResponse($pageURL, $query);
        if($resObj->success){
            // Add Tutors and Students
            echo "<br/>Group Sets";
            foreach($resObj->groups as $gs){
                echo "<br/>Groups";
                foreach($gs->groups as $g){
                    // Replace Students array
                    echo "<br/>Students";
                    $g->studentDetails = SmallGroupSet::GetStudentDetails($g->students);
                    foreach($g->studentDetails as $s){
                        echo '<br/>'.$s->fullName;
                    }  
        
                    echo "<br/>Events";
                    foreach($g->events as $e){
                        // Replace Tutors array
                        echo "<br/>Tutors";
                        $e->tutorDetails = SmallGroupSet::GetTutorDetails($e->tutors);
                        foreach($e->tutorDetails as $t){
                             echo '<br/>'.$t->fullName;
                        }
        //                dd($e->tutors);
                    }
                                    
                }               
            }
            return $resObj->groups;
        } else { 
            return array();
        }
    }
    
    
    public static function GetTutorDetails($tutors){
        $pageURL = "https://tabula.warwick.ac.uk/api/v1/member/";
        $query = "fields=member.universityId,"
                //. "member.userId, "
                //. "member.userType,"
                //. "member.title, "
                . "member.fullName,"
                . "member.firstName,"
                . "member.lastName";
                //. "member.nationality, "
                //. "member.dateOfBirth, "
                //. "member.jobTitle, "
                //. "member.homeDepartment, "
                //. "member.phoneNumber, "
                //. "member.email, "
                //. "member.homeEmail, "
                //. "member.groupName";
        $tutorDetails = array();
        foreach($tutors as $t){
              //dd($t);
            $url = $pageURL.$t->universityId;
            //echo $url.'<br/>'.$query;
            $resObj = TabulaAPI::GetResponse($url, $query);
            //dd($resObj);
            
            if($resObj->success){
                $tutorDetails[] = $resObj->member; 
            } else {
                dd($resObj);
            }
        }
        //echo '<PRE>'.print_r($tutorDetails).'</PRE>';
        return $tutorDetails;
        
    }
        
    public static function GetStudentDetails($students){
        $pageURL = "https://tabula.warwick.ac.uk/api/v1/member/";
        $query = "fields=member.universityId,"
                . "member.fullName,"
                . "member.userId,"
                . "member.userType,"
                . "member.title,"
                . "member.firstName,"
                . "member.lastName,"
                . "member.nationality,"
                . "member.dateOfBirth,"
                . "member.jobTitle,"
                . "member.homeDepartment,"
                . "member.phoneNumber,"
                . "member.email,"
                . "member.homeEmail,"
                . "member.groupName";
       
        $studentDetails = array();
        foreach($students as $s){
             //dd($s);
            $url = $pageURL.$s->universityId;
            //echo $url.'<br/>'.$query;
            //dd($url);
            $resObj = TabulaAPI::GetResponse($url, $query);
            //dd($resObj);
            if($resObj->success){
                $studentDetails[] = $resObj->member; 
            } else {
                dd($resObj);
            }
            
        }
        
       // echo '<PRE>'.print_r($studentDetails).'</PRE>';
        return $studentDetails;
    }
    
    
    public function RetrieveSet($moduleCode, $academicYear, $smallGroupSetId) {
        echo '<br/>RetrieveSet';
        $pageURL = "https://tabula.warwick.ac.uk/api/v1/module/".$moduleCode."/groups";
        if($smallGroupSetId){
            $pageURL.="/".$smallGroupSetId;
        }
        $query = "academicYear=".$academicYear;
        
        $resObj = TabulaAPI::GetResponse($pageURL, $query);
        //echo $moduleCode. ' '. $academicYear; 
        
        if($resObj->success){
                $SmallGroupSets = array();
                foreach($resObj->groups as $gs){
                    echo '<br/>'.$gs->id.'<br/>';
                    //$newGroupSet = new SmallGroupSet($gs);
                    //$newGroupSet->SaveSet();
                    
                    //$newGroupSet = simplexml_load_string($gs, 'SmallGroupSet');
                    //echo 'New Class ID = '.$newGroupSet->id;
                    //$newGroupSet->SaveSet();
                    $sgs = $this->ReadSet($gs);
                    
                    // Add New GroupSet to Array
                    $SmallGroupSets[] = $sgs;
                    // Save New groupSet to db
                    $sgs->SaveSet();
                    
                    // Delete Groups 
                    //$this->ClearGroups();   // This method is not working ... all groups are deleted!
                    // Save Groups
                    foreach($sgs->groups as $g){
                        $sgs->SaveGroup($g);
                    }
                    if($sgs->studentMembership) echo 'XML has members';
                    $sgs->ClearUsers(); 
                    
                    if($sgs->studentMembership){
                        echo "<br/>StudentMembership: User Count ".count($sgs->studentMembership->users).'<br/>';
                        if($this->studentMembership->users){
                            foreach($sgs->studentMembership->users as $u){
                                $sgs->SaveUser($u);
                            }
                        } else {
                             echo '<br/>No studentMembership Users<br/>';
                        }
                    } else {
                        echo '<br/>No studentMembership<br/>';
                    }
                    //var_dump($this);
                    //echo '<PRE>'.print_r($this).'</PRE>';
                    //$this::RetrieveSet($moduleCode, $academicYear, $gs->id);
                }
            //} else { 
            //    $this->ReadSet($resObj->groups[0]);
            //    dd($this);
            //}
            //$gs = new SmallGroupSet;
            //$gs = $resObj->groups[0]; //Simplexml_load_string($resObj->groups[0]);
            //dd($gs);
        } else {
            TabulaAPI::LogErrors($resObj);
        }
    }
    
    private function ReadSet($gs) {
        $sgs = new SmallGroupSet();
        $sgs->id = $gs->id;
        $sgs->archived = $gs->archived;
        $sgs->academicYear = $gs->academicYear;
        $sgs->academicYear = '19/20';
        $sgs->name = $gs->name;
        $sgs->format = $gs->format; 
        $sgs->allocationMethod = $gs->allocationMethod; 
        // Boolean Properties
        $sgs->releasedToTutors = $gs->releasedToTutors; 
        $sgs->releasedToStudents = $gs->releasedToStudents;
        $sgs->emailTutorsOnChange = $gs->emailTutorsOnChange;
        $sgs->emailStudentsOnChange = $gs->emailStudentsOnChange;
        $sgs->studentsCanSeeTutorName = $gs->studentsCanSeeTutorName;
        $sgs->studentsCanSeeOtherMembers = $gs->studentsCanSeeOtherMembers;
        $sgs->defaultMaxGroupSizeEnabled = $gs->defaultMaxGroupSizeEnabled;
        $sgs->defaultMaxGroupSize = $gs->defaultMaxGroupSize;
        $sgs->collectAttendance = $gs->collectAttendance;  //Attendance should be collected at events
        $sgs->allowSelfGroupSwitching = $gs->allowSelfGroupSwitching; 
        $sgs->openForSignups = $gs->openForSignups;
        $sgs->linkedDepartmentGroupSet = $gs->linkedDepartmentGroupSet;
        // Class Array Properties
        $sgs->studentMembership = $gs->studentMembership; 
        $sgs->users = $gs->users;     // An array each element has two keys, userId and universityId
        $this->sitsLinks = $gs->sitsLinks; // An array of objects representing the active links to SITS membership for the small group set. Each object contains four properties:
        $sgs->groups = $gs->groups;    // An array 
        /*
        $this->id = $gs->id;
        $this->archived = $gs->archived;
        $this->academicYear = $gs->academicYear;
        if (is_null($this->academicYear) ) $this->academicYear = '19/20';
        $this->name = $gs->name;
        $this->format = $gs->format; 
        $this->allocationMethod = $gs->allocationMethod; 
        // Boolean Properties
        $this->releasedToTutors = $gs->releasedToTutors; 
        $this->releasedToStudents = $gs->releasedToStudents;
        $this->emailTutorsOnChange = $gs->emailTutorsOnChange;
        $this->emailStudentsOnChange = $gs->emailStudentsOnChange;
        $this->studentsCanSeeTutorName = $gs->studentsCanSeeTutorName;
        $this->studentsCanSeeOtherMembers = $gs->studentsCanSeeOtherMembers;
        $this->defaultMaxGroupSizeEnabled = $gs->defaultMaxGroupSizeEnabled;
        $this->defaultMaxGroupSize = $gs->defaultMaxGroupSize;
        $this->collectAttendance = $gs->collectAttendance;  //Attendance should be collected at events
        $this->allowSelfGroupSwitching = $gs->allowSelfGroupSwitching; 
        $this->openForSignups = $gs->openForSignups;
        $this->linkedDepartmentGroupSet = $gs->linkedDepartmentGroupSet;
        // Class Array Properties
        $this->studentMembership = $gs->studentMembership; 
        $this->users = $gs->users;     // An array each element has two keys, userId and universityId
        $this->sitsLinks = $gs->sitsLinks; // An array of objects representing the active links to SITS membership for the small group set. Each object contains four properties:
        $this->groups = $gs->groups;    // An array 
         * */
       
        return $sgs;
    }
    
    
    private function SaveSet() {
        //echo '<br/>SaveSet';
        try {
            $cursor = DB::select('SELECT id FROM GroupSets WHERE id = ?', [$this->id]); 
            // Check the first itel in the list
            if($cursor[0]->id == $this->id){
                //echo 'GROUPSET FOUND -> UPDATE!';
                DB::update('UPDATE GroupSets SET Presessional_year = ?, '
                    .'archived = ?, academicYear = ?, name = ?, format = ?, allocationMethod = ?, '
                    .'releasedToTutors = ?, releasedToStudents = ?, '
                    .'emailTutorsOnChange = ?, emailStudentsOnChange = ?, '
                    .'studentsCanSeeTutorName = ?, studentsCanSeeOtherMembers = ?, '
                    .'defaultMaxGroupSizeEnabled = ?, defaultMaxGroupSize = ?, '
                    .'collectAttendance = ?, allowSelfGroupSwitching = ?, '
                    .'openForSignups = ?, linkedDepartmentGroupSet = ? '
                    .'WHERE id = ? ',
                    [$this->PresessionalYear, $this->archived, $this->academicYear, 
                     $this->name, $this->format,  $this->allocationMethod, 
                     $this->releasedToTutors, $this->releasedToStudents, 
                     $this->emailTutorsOnChange, $this->emailStudentsOnChange, 
                     $this->studentsCanSeeTutorName, $this->studentsCanSeeOtherMembers, 
                     $this->defaultMaxGroupSizeEnabled, $this->defaultMaxGroupSize,
                     $this->collectAttendance, $this->allowSelfGroupSwitching,
                     $this->openForSignups, $this->linkedDepartmentGroupSet,
                     $this->id]);
                   
            } else {
                //echo 'GROUPSET NOT FOUND -> SAVE';
                DB::insert('INSERT INTO GroupSets (Presessional_Year, '
                    .'id, archived, academicYear, name, format, allocationMethod, '
                    .'releasedToTutors, releasedToStudents, '
                    .'emailTutorsOnChange, emailStudentsOnChange, '
                    .'studentsCanSeeTutorName, studentsCanSeeOtherMembers, '
                    .'defaultMaxGroupSizeEnabled, defaultMaxGroupSize, '
                    .'collectAttendance, allowSelfGroupSwitching, '
                    .'openForSignups, linkedDepartmentGroupSet ' 
                    .') VALUES ( ?, ?, ?, ?, ?, ?, ?, '
                    . '?,?, ?,?, ?,?, ?,?, ?,?, ?,? )',
                    [$this->PresessionalYear, $this->id, $this->archived, $this->academicYear, 
                     $this->name, $this->format,  $this->allocationMethod, 
                     $this->releasedToTutors, $this->releasedToStudents, 
                     $this->emailTutorsOnChange, $this->emailStudentsOnChange, 
                     $this->studentsCanSeeTutorName, $this->studentsCanSeeOtherMembers, 
                     $this->defaultMaxGroupSizeEnabled, $this->defaultMaxGroupSize,
                     $this->collectAttendance, $this->allowSelfGroupSwitching,
                     $this->openForSignups, $this->linkedDepartmentGroupSet]);
        
                // Excluded Array Properties - studentMembership, users, sitsLinks, groups    
            }
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
    }
    private function ClearGroups() {  // This method is not working ... all groups are deleted!
        //echo '<br/>ClearGroups';
        try {
            $grouplist = 'XXX';
            foreach($this->groups as $g){
                $grouplist.= ",'".$g->id."'";
            }
            //echo '<br/>GroupList:'.$grouplist;
            DB::delete('DELETE FROM Groups WHERE SetID = ? AND GroupID NOT IN ( ? )', [$this->id,$grouplist]); 
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
    }
    
    
    private function SaveGroup($g) {
        //echo '<br/>SaveGroup';
        try {
            $cursor = DB::select('SELECT GroupID as id FROM Groups WHERE SetID = ? AND GroupID = ? ', [$this->id, $g->id]); 
            //$cursor = DB::select('SELECT GroupID as id FROM Groups WHERE SetID = ?  ', [$this->id]); 
            //$cursor = DB::select("SELECT GroupID as id FROM Groups WHERE GroupID LIKE TRIM(?) ", [$g->id]); 
            //foreach($cursor as $c){
            //   echo $c->id.',';
            //}
            //echo $cursor[0]->id.' == '.$g->id.' ? ';
            
            if($cursor[0]->id == $g->id){
                //echo 'Group '.$g->name.' Found -> UPDATE!';
                DB::update('UPDATE Groups SET Presessional_year = ?, '
                    .'SetID = ?, Group_No = ?, maxGroupSize = ? '
                    .'WHERE GroupID = ? ',
                    [$this->PresessionalYear, $this->id, $g->name, $g->maxGroupSize, $g->id]);
                   
            } else {
                //echo 'Group '.$g->name.' Not found -> INSERT!';
                DB::insert('INSERT INTO Groups (Presessional_Year, '
                    .'SetID, GroupID, Group_No, maxGroupSize) VALUES (?, ?, ?, ?, ?)',
                    [$this->PresessionalYear, $this->id, $g->id, $g->name, $g->maxGroupSize]);  
            }
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
    }
    
    private function ClearUsers() {  // This method is not working ... all groups are deleted!
        //echo '<br/>ClearUsers';
        try {
            DB::delete('DELETE FROM GroupSetUsers WHERE SetID = ? ', [$this->id]); 
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
    }
    
    
    private function SaveUser($u) {
        //echo '<br/>SaveUser'.$u->userId .'/'. $u->universityId;
        try {       
            if($u->userId && $u->universityId){
                DB::insert('INSERT INTO GroupSetUsers (SetID, UserID, UniversityID) VALUES (?,?,?)',
                    [$this->id, $u->userId, $u->universityId]);  
            }
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
    }
    
    private function LoadSet() {
        try{
            $Assessments = DB::select(
                'SELECT Student_No, Family_Name, First_Name,  ' 
                    .'Listening, Reading, Writing, Speaking, '
                    .'Classroom_listening, Classroom_Writing, Classroom_Reading, '
                    .'Performance, Library_Project, Presentation '
                    .'FROM Students '
                    .'WHERE Phase_2_Group = ? '        // NOTE Phase_5_Group not populated in test data
                    .'AND Presessional_Year = ? '
                    .'ORDER BY Family_Name, First_Name', 
                [$Group_No, $Presessional_Year]
            );
           // dd($Assessments);
        //echo var_dump($Assessments);
        /*
        $Assessments = DB::table('Students') 
                ->select('Student_No', 'Family_Name', 'First_Name', 'TBS_Report', 'LNS_Report', 
                    'Listening', 'Reading', 'Writing','Speaking', 'Performance', 'Library_Project',
                    'Classroom_listening', 'Classroom_Writing', 'Classroom_Reading', 'Presentation')
                ->where('Phase_2_Group', $Group_No, 'Presessional_Year', $Presessional_Year)
                ->orderBy('Family_Name', 'asc', 'First_Name', 'asc')
                ->get();
         
         $UpdateCount = DB:table('Students')
                ->where('Student_No', '=', $Student_No)
                ->update('Listening', $Listening, 'Reading', $Reading); 
         * 
         * 
         * 
         * 
         */
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
    }
    public function UpdateSet() {
        
    }
    
    public function ListSets() {
        
    }
    
    public function RetrieveSetAllocations() {
        
    }
    
    public function UpdateSetAllocations() {
        
    }
    
    
}