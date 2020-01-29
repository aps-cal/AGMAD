<?php

namespace App\PS;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

Use \Illuminate\Support\Facades\DB;

class Values {
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    
    /*
     * public static functions are used as no object of type Values will be 
     * created and these functions will all be called as static functions
     */
    
    
    public static function GetAcadYear($year) {    
        try {
            $aYear = DB::select('SELECT right(Academic_year,5) as academicYear FROM Years WHERE Calendar_Year = ? ',[$year]);
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($aYear);
    }
    public static function GetAcadYears() {    
        try {
            $aYears = DB::select('SELECT right(Academic_year,5) as academicYear FROM Years ORDER BY Academic_year');
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($aYears);
    }
    
    public static function GetModules($aYear) {   // Academic Year 
        try {
            $Modules = DB::select('SELECT Presessional_Year, academicYear, Phase_No, Module, Module_Name '
                    .'FROM Modules WHERE academicYear = ? ORDER BY Module',[$aYear]);
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Modules);
    }
    public static function GetPhases() {    
        //$Phases = [['Phase_No'=>'1'], ['Phase_No'=>'2'],['Phase_No'=>'4'], ['Phase_No'=>'3'],['Phase_No'=>'4'], ['Phase_No'=>'5']];
         try {
            $Phases = DB::select('SELECT DISTINCT Phase_No FROM Phase_Week_Numbers ORDER BY Phase_No');
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Phases);
    }
    
    public static function GetYears() {    
        try {
            $Years = DB::select('SELECT DISTINCT Calendar_Year as PS_Year FROM Years ORDER BY Calendar_Year');
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Years);
    }
    public static function GetGroups($year,$phase) {  
        try {
            $Groups = DB::select('SELECT Group_No, Class_Room, '
                    .'TBS_Tutor_ID, TBS_Tutor_Inits, LNS_Tutor_ID, LNS_Tutor_Inits '
                    .'FROM Groups '
                    .'WHERE Presessional_Year = ? AND Phase_No = ? '
                    .'ORDER BY Group_No', [$year, $phase]);
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Groups);
    }
    
    public static function SetAcadYear($aYear) {
        DB::table('Current_Values')->update(['Academic_Year' => $aYear]);    
    }
    public static function SetYear($year) {
        DB::table('Current_Values')->update(['Presessional_Year' => $year]);    
    }
    
    public static function GetYear() {
        $result = DB::table('Current_Values')->select('Presessional_Year')->get();
        return($result[0]->Presessional_Year);
    }
    
    public static function SetPhase($phase) {
        DB::table('Current_Values')->update('Presessional_Phase', [$phase]);      
    }
    
    public static function GetPhase() {
         $result = DB::table('Current_Values')->select('Presessional_Phase')->get();
         return($result[0]->Presessional_Phase);
    }
    
    public static function SetPhaseWeek($week) {
        DB::table('Current_Values')->update('Phase_Week', [$week]);    
    }
    
    public static function GetPhaseWeek() {
        $result = DB::table('Current_Values')->select('Phase_Week')->get();
        return($result[0]->Phase_Week);
    }
    
    public static function SetTermWeek($week) {
        
    }
    
    public static function GetTermWeek($week) {
        
    }
    public static function ListTutors() {
        
    //    try {
    //        DB::connection()->getPdo();
    //    } catch (\Exception $e) {
    //          die("Could not connect to the database.  Please check your configuration.");
    //    }
        /*
        $tutors = DB::select(
            'SELECT Tutor_ID, Tutor_Inits, First_Name, Last_Name '
                .'FROM Tutor_Names '
                .'ORDER BY Last_Name, First_Name', 
            []
        );
         * 
         * */
       
        //echo var_dump($Assessments);
        
        $tutors = DB::table('Tutor_Names') 
                ->select('Tutor_ID', 'Tutor_Inits', 'First_Name', 'Last_Name')
                ->orderBy('Last_Name', 'asc', 'First_Name', 'asc')
                ->get();
        /* 
         $UpdateCount = DB:table('Students')
                ->where('Student_No', '=', $Student_No)
                ->update('Listening', $Listening, 'Reading', $Reading); 
         * 
         * 
         * 
         * 
         */
        //echo $tutors;
        
        return($tutors);
        
    }
    
    
    
    
    
    
    
    
}
