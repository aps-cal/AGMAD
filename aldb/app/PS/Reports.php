<?php

namespace App\PS;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

use \Illuminate\Support\Facades\DB;

class Reports //extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    
    public static function GetReports($Presessional_Year, $Group_No ) {
        
        try {
            
            // NOTE - Can we link to Groups Table and pull in the name of the appropriate tutor for each report?
            
            $Reports = DB::select(
                'SELECT Student_No, Family_Name, First_Name, '
                    .'if(length(trim(TBS_Report))>10,1,0) AS TBS, '
                    .'if(length(trim(LNS_Report))>10,1,0) AS LNS, '
                    .'TBS_Report, LNS_Report ' 
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
        return($Reports);
        
    }
      
}
