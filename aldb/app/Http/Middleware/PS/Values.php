<?php

namespace App\Http\Middleware\PS;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

Use \Illuminate\Support\Facades\DB;

class Values extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    
    
    public function GetYears() {    
        try {
            $Years = DB::select('SELECT DISTINCT Presessional_Year as PS_Year FROM Groups ORDER BY Presessional_Year');
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Years);
    }
    public function GetGroups() {    
        try {
            $Groups = DB::select('SELECT DISTINCT Group_No FROM Groups ORDER BY Group_No');
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Groups);
    }
    
    public function SetYear($year) {
        DB::table('Current_Values')->update(['Presessional_Year' => $year]);    
    }
    
    public function GetYear() {
        $result = DB::table('Current_Values')->select('Presessional_Year')->get();
        //return $result->Presessional_Year;
        //return $result;
        return $result['Presessional_Year'];
    }
    
    public function SetPhase($phase) {
        DB::table('Current_Values')->update('Presessional_Phase', [$phase]);      
    }
    
    public function GetPhase() {
        return DB::table('Current_Values')->select('Presessional_Phase')->get();
    }
    
    public function SetPhaseWeek($week) {
        DB::table('Current_Values')->update('Phase_Week', [$week]);    
    }
    
    public function GetPhaseWeek() {
        return DB::table('Current_Values')->select('Phase_Week')->get();
    }
    
    public function SetTermWeek($week) {
        
    }
    
    public function GetTermWeek($week) {
        
    }
    public function ListTutors() {
        
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
