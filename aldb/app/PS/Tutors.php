<?php

namespace App\PS;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

Use \Illuminate\Support\Facades\DB;

class Tutors  {
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    
    protected $except = [
        //
    ];
    
    public function SetTutor($TutorID) {
        $values = array();
        $values['currentTutorID'] = $TutorID;
        echo $values;
        session($values);
    }
    
    public function GetTutor() {
        echo session('currentTutorID','Not Set');
        return(session('currentTutorID','0580813'));
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
