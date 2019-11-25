<?php

namespace App\PS;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

use \Illuminate\Support\Facades\DB;

class Groups extends Middleware {
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    
    public function SetGroupSets($Presessional_Year, $Group_No ) {
    
      try {
      
    
    
      } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Assessments);
        
    }
    
    public function GetGroupSet($Presessional_Year, $Group_No ) {
    
      try {
          
          
    
    
      } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Assessments);
        
    }
    
    public function GetGroups($ModuleCode, $AcademicYear) {
        
        try {
            //echo ("</p>".base64_encode('el-apiuser:Roberts1951')."<p>");
            //echo ("</p>".base64_decode('ZWwtYXBpdXNlcjpSb2JlcnRzMTk1MQ==')."<p>");
            error_reporting(E_STRICT); 
            $ch = curl_init(); 
            $pageURL = "https://tabula.warwick.ac.uk/api/v1/module/".$ModuleCode."/groups";
            //$pageURL = "https://tabula.warwick.ac.uk/api/v1/member/1853285/attendance/2018";
            //$pageURL = 'http://requestbin.net/';
            
            curl_setopt($ch, CURLOPT_HTTPGET, true);  // Switch back to default GET Method
            // If sending values by the GET method then they need to be added to the URL string
            $query = "academicYear=".$AcademicYear;
            curl_setopt($ch, CURLOPT_URL, $pageURL.'?'.$query); 
            // If sending values by the POST method do not add to the URL but use POSTFIELDS
            //curl_setopt($ch, CURLOPT_POST, true);  // Switch in Post Method
            // $query = array('academicYear: 18/19');
            // curl_setopt($ch, CURLOPT_POSTFIELDS,  $query);
            // $postdata = array('academicYear' => '18/19');
            // curl_setopt($ch, CURLOPT_POSTFIELDS,  $postdata);
            // Alternative unsecure way to send Authentication
            ////curl_setopt($ch, CURLOPTUSERPWD, "el-apiuser:Roberts1951");
            // BASIC Authentication 
            //$header = array(
            //    'Content-Type: application/json',
            //    'Authorization: Basic ZWwtYXBpdXNlcjpSb2JlcnRzMTk1MQ=='
            //);
            $header = array(
                'Content-Type: application/json',
                'Authorization: Basic '.base64_encode('el-apiuser:Roberts1951')
            );
            curl_setopt($ch, CURLOPT_HEADER, true); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
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
        //echo ("</p>".$res."<p>");
        echo ("</p>".$res."<p>");
            //var_dump($res); 
            echo ("</p>");   
        $groups = Groups::parseXML($res);    
        return($groups);
    }
    
    public function SetGroups($Presessional_Year, $Group_No ) {
    
      try {
      
    
    
      } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Assessments);
        
    }
    
    
    
    public function GetAssessments($Presessional_Year, $Group_No ) {
        
        try {
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
        return($Assessments);
        
    }
    
    
    
    public function parseXML($reponse){
        $array = array();   
        $pieces = explode("\n", $reponse);
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
