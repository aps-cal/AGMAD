<?php

namespace App\SITS;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

use \Illuminate\Support\Facades\DB;

class Students //extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    
    public static function GetStudents($Presessional_Year, $Group_No ) {
        
        try {
            dd(config('database.connections'));
            
            //dd(config('database.connections.oracle.database'));
            $dbconnection = 'oracle';
            $Students = DB::connection('oracle')->select(
                "SELECT C.CAP_STUC AS Student_No, U.STU_SURN as Surname, U.STU_FNM1 as First_Name, U.STU_TITL as Title, "
                .'U.STU_INIT as Inits, U.STU_NAME as Name, U.STU_DOB as DOB, U.STU_GEND as Gender, U.STU_HAEM as Home_Email, '
                .'U.STU_CAEM as Uni_Email, U.STU_CAT3 as Mobile, U.STU_UDF3 as ITS_Login, D.COD_NAME as Domicile, '
                .'B.COB_NAME as Birth_Place, N.NAT_NAME as Nationality, C.CAP_PRGC AS Programme, C.CAP_ROUC AS Route, '
                .'C.CAP_CRSC AS Course, C.CAP_STAG AS Stage, C.CAP_CRTD AS Created, C.CAP_AYRC AS Acad_Year, C.CAP_DPTC as Dept_Code, S.SPR_CODE as SPR_Code, '
                ."iif(CAP_CRSC = 'TETS-PSE10','Y','N') as Phase_4,  iif(CAP_CRSC = 'TETS-PSE6' or CAP_CRSC = 'TETS-PSE10','Y','N') as Phase_5, iif(CAP_RSP1 = 'F','Y', 'N' ) as Firm " 
                .'FROM (((((INTUIT_SRS_CAP C '
                .'INNER JOIN INTUIT_INS_STU AS U ON  U.STU_CODE = C.CAP_STUC) '
                .'INNER JOIN INTUIT_INS_SPR AS S ON S.SPR_STUC = U.STU_CODE) '
                .'LEFT JOIN INTUIT_SRS_COB AS B ON B.COB_CODE = U.STU_COBC) '
                .'LEFT JOIN INTUIT_SRS_COD AS D ON D.COD_CODE = U.STU_CODC) '
                .'LEFT JOIN INTUIT_SRS_NAT AS N ON N.NAT_CODE = U.STU_NATC) '
                ."WHERE CAP_CRSC IN ('TETS-PSE6','TETS-PSE10')"
            );
            /*
            $dbconnection = 'mysql';
            $Students = DB::connection('oracle')->select(
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

             */
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
        return($Students);
        
    }
}
