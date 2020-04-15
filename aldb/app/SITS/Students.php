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
    
    
    private static function SortYearAsc ($a, $b) {
        if ($a['acad_year'] == $b['acad_year']) {
            return 0;
        }
        return ($a['acad_year'] < $b['acad_year']) ? -1 : 1;
    }
    
    public function comparator($object1, $object2) { 
            return $object1->surname > $object2->surname; 
    }        
    public static function GetSITStudents($Academic_Year ) {
        
        try {
            
            //dd(config('database.connections'));
            //dd(config('database.connections.oracle.database'));
            //$dbconnection = 'oracle';
            
            // Note: SPR_CODE removed as it is was creating duplicates even with DISTINCT 
       
            $SQL = "SELECT DISTINCT ACAD_YEAR, BATCH, PS_YEAR, PSE_ROUTE,  "
                ."STUDENT_NO, SURNAME, FIRST_NAME, PREF_NAME, TITLE, INITS, "
                ."DOB, GENDER, HOME_EMAIL, UNI_EMAIL, MOBILE, "
                . "ITS_LOGIN, UPDATED, DOMICILE, BIRTH_PLACE, NATIONALITY, "
                ."DEPARTMENT as DEPT_CODE, COURSE_CODE, COURSE, DECISION, PHASE_4, PHASE_5, FIRM, "
                . "DECODE(ROUTE,'PSEP4','Y','PSEP5','N','PSE4','N','PSE5','N','PSE6','N','PSE8','Y','PSE10','Y','PSE12','Y') AS Phase4, "
                . "'Y' AS Phase5, "
                ."'' as Phase_4_Group, '' as Phase_5_Group, '' as Results, '' as Group_No, '' as Phase_No  " 
                . "FROM UWTABS.V_UW_CALUA "
                . "WHERE ACAD_YEAR = '".$Academic_Year."' "
                . "ORDER BY SURNAME, FIRST_NAME, DOB ";
            $Students = (array) DB::connection("EL_APIUSER_PR")->select($SQL); 
                
            //dd($Students);
               
            //usort($Students, 'comparator');
            /*
            foreach ($Students as $key => $row) {
                $sort[$key]  = $row['surname'];
            }
            // Sort the data with mid descending
            // Add $data as the last parameter, to sort by the common key
            array_multisort($sort, SORT_DESC, $Students);
           
            
           $SQL = "SELECT C.CAP_STUC AS Student_No, U.STU_SURN as Surname, U.STU_FNM1 as First_Name, "
                . "U.STU_TITL as Title, U.STU_INIT as Inits, U.STU_NAME as Name, U.STU_DOB as DOB, "
                . "U.STU_GEND as Gender, U.STU_HAEM as Home_Email, U.STU_CAEM as Uni_Email, "
                . "U.STU_CAT3 as Mobile, U.STU_UDF3 as ITS_Login, D.COD_NAME as Domicile, "
                . "B.COB_NAME as Birth_Place, N.NAT_NAME as Nationality, C.CAP_PRGC AS Programme, C.CAP_ROUC AS Route, "
                . "C.CAP_CRSC AS Course, C.CAP_STAG AS Stage, C.CAP_CRTD AS Created, C.CAP_AYRC AS Acad_Year, "
                . "C.CAP_DPTC as Dept_Code, S.SPR_CODE as SPR_Code, "
                . "DECODE(C.CAP_ROUC,'PSEP4','Y','PSEP5','N','PSE4','N','PSE5','N','PSE6','N','PSE8','Y','PSE10','Y','PSE12','Y','N') AS Phase_4, "
                . "'Y' AS Phase_5,'' as Phase_4_Group, '' as Phase_5_Group, iif(CAP_RSP1 = 'F','Y', 'N') as Firm " 
                . "FROM (((((INTUIT.SRS_CAP C "
                . "INNER JOIN INTUIT.INS_STU U ON  U.STU_CODE = C.CAP_STUC) "
                . "INNER JOIN INTUIT.INS_SPR S ON S.SPR_STUC = U.STU_CODE) "
                . "LEFT JOIN INTUIT.SRS_COB B ON B.COB_CODE = U.STU_COBC) "
                . "LEFT JOIN INTUIT.SRS_COD D ON D.COD_CODE = U.STU_CODC) "
                . "LEFT JOIN INTUIT.SRS_NAT N ON N.NAT_CODE = U.STU_NATC) "
                ."WHERE CAP_CRSC IN ('TETS-PSE6','TETS-PSE10') ";
/*
            $SQL = "SELECT C.CAP_STUC AS Student_No, U.STU_SURN as Surname, U.STU_FNM1 as First_Name, U.STU_TITL as Title, "
                ."U.STU_INIT as Inits, U.STU_NAME as Name, U.STU_DOB as DOB, U.STU_GEND as Gender, U.STU_HAEM as Home_Email, "
                ."U.STU_CAEM as Uni_Email, U.STU_CAT3 as Mobile, U.STU_UDF3 as ITS_Login, D.COD_NAME as Domicile, "
                ."B.COB_NAME as Birth_Place, N.NAT_NAME as Nationality, C.CAP_PRGC AS Programme, C.CAP_ROUC AS Route, "
                ."C.CAP_CRSC AS Course, C.CAP_STAG AS Stage, C.CAP_CRTD AS Created, C.CAP_AYRC AS Acad_Year, C.CAP_DPTC as Dept_Code, S.SPR_CODE as SPR_Code, "
                ."DECODE(C.CAP_ROUC,'PSEP4','Y','PSEP5','N','PSE4','N','PSE5','N','PSE6','N','PSE8','Y','PSE10','Y','PSE12','Y','N') AS Phase_4, "
                ."'Y' AS Phase_5,'' as Phase_4_Group, '' as Phase_5_Group, iif(CAP_RSP1 = 'F','Y', 'N') as Firm " 
                ."FROM (((((INTUIT_SRS_CAP C "
                ."INNER JOIN INTUIT_INS_STU AS U ON  U.STU_CODE = C.CAP_STUC) "
                ."INNER JOIN INTUIT_INS_SPR AS S ON S.SPR_STUC = U.STU_CODE) "
                ."LEFT JOIN INTUIT_SRS_COB AS B ON B.COB_CODE = U.STU_COBC) "
                ."LEFT JOIN INTUIT_SRS_COD AS D ON D.COD_CODE = U.STU_CODC) "
                ."LEFT JOIN INTUIT_SRS_NAT AS N ON N.NAT_CODE = U.STU_NATC) "
                ."WHERE CAP_ROUC IN ('PSEP4','PSEP5','PSE4','PSE5','PSE6','PSE8','PSE10','PSE12') ";
            echo $SQL;
            //."WHERE CAP_CRSC IN ('TETS-PSE6','TETS-PSE10')"
            //."iif(CAP_CRSC = 'TETS-PSE10','Y','N') as Phase_4,  "
            //."iif(CAP_CRSC = 'TETS-PSE6' or CAP_CRSC = 'TETS-PSE10','Y','N') as Phase_5, "
            
            $Students = DB::connection("Oracle")->select($SQL); 
*/
    
            //dd($Students);
            /*
            $dbconnection = 'mysql';
            $Students = DB::connection("Oracle")->select(
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
    public static function GetApplications($Year, $Phase) {   
        try {
            // Note: Temporary Solution While Waiting for View
       
            $SQL = "SELECT S.STU_CODE AS Student_No, S.STU_SURN AS Surname, S.STU_FNM1 AS First_Name, "
                ."S.STU_FUSD AS Pref_Name, S.STU_INIT AS Inits, S.STU_TITL AS Title, "
                ."S.STU_DOB AS DOB, S.STU_GEND AS Gender, S.STU_HAEM AS Home_Email, "
                ."S.STU_CAEM AS Uni_Email, S.STU_CAT3 AS Mobile, S.STU_UDF3 AS ITS_Login, "
                ."D.COD_NAME AS Domicile, B.COB_NAME AS Birth_Place, N.NAT_NAME AS Nationality, "
                ."C.CRS_SNAM, C.CRS_NAME, C.CRS_TITL, C.CRS_DPTC, T.DPT_NAME, "
                ."A.APF_UDFA, DECODE(A.APF_UDFA,'OPT1','Y','OPT2','N',NULL) AS Accommodation, "
                ."P.CAP_RSP1, P.CAP_RSPD, DECODE(P.CAP_RSP1,'F',P.CAP_RSPD,NULL) AS Accepted_Date, "
                ."F.Scholarship, DECODE(Q.SQE_EQEC, 'LANG:IU','Y','N') AS UKVI, "
                ."Q.IELTS_Listening, Q.IELTS_Reading, Q.IELTS_Writing, Q.IELTS_Speaking, Q.IELTS_Total, "
                ."E.SCE_DPTC, E.SCE_CRSC, E.SCE_ROUC, E.PSE_ROUC, E.Phase_4, E.Phase_5, "
                ."E.PS_Year, E.SCE_AYRC AS Acad_Year, P.CAP_DSCC As Decision, P.CAP_RSP1 As Response, "
                ."E.SCE_AYRC, E.SCE_STUC, E.SCE_SEQ2, E.SCE_BTCH, A.APF_STUC, A.APF_SEQN, P.CAP_AYRC, P.CAP_STUC, P.CAP_MCRC, Q.SQE_STUC, Q.SQE_SEQN, F.SPD_STUC, F.SPD_UDF4, F.SPD_UDF5, "
                ."S.STU_UPDD AS Updated "
                ."FROM ((((((((((("
                ."SELECT DECODE(SCE_BTCH, NULL, PSYear2,PSYear1) AS PS_Year, "
		."SCE_AYRC, SCE_STUC, SCE_SEQ2, SCE_DPTC, SCE_CRSC, SCE_ROUC, SCE_BTCH, "
		."DECODE(SCE_BTCH, NULL, 'P-'||SCE_ROUC, SCE_BTCH) AS PSE_ROUC, "
		."DECODE(SCE_BTCH, NULL, Phase42,Phase41) AS Phase_4, Phase_5 "
                ."FROM ( "
		."SELECT SCE_STUC, SCE_SEQ2, SCE_BTCH, SCE_ROUC, SCE_AYRC, SCE_CRSC, SCE_DPTC, "
		."'20'||DECODE(SCE_BTCH,'P-PSE10',SUBSTR(SCE_AYRC,-2),'P-PSEP4',SUBSTR(SCE_AYRC,-2),'PSEP4',SUBSTR(SCE_AYRC,-2),'PSE8',"
		."SUBSTR(SCE_AYRC,-2),'PSE10',SUBSTR(SCE_AYRC,-2),SUBSTR(SCE_AYRC,1,2)) AS PSYear1, "
		."'20'||DECODE(SCE_ROUC,'PSE10',SUBSTR(SCE_AYRC,-2),'PSEP4',SUBSTR(SCE_AYRC,-2),SUBSTR(SCE_AYRC,1,2)) AS PSYear2,"
		."DECODE(SCE_BTCH,'P-PSE10','Y','P-PSEP4','Y','PSEP4','Y','PSE8','Y','PSE10','Y','PSE12','Y','N') AS Phase41, "
		."DECODE(SCE_ROUC,'PSE10','Y','PSEP4','Y','N') AS Phase42, 'Y' AS Phase_5 "
		."FROM INTUIT.SRS_SCE "
		."WHERE (SCE_BTCH LIKE '%PSE%' OR  (SCE_ROUC LIKE '%PSE%' AND SCE_BTCH IS NULL)) AND SCE_AYRC > '11/12') "
                .") E "
                ."INNER JOIN INTUIT.INS_STU S ON S.STU_CODE = E.SCE_STUC)"
                ."LEFT JOIN INTUIT.SRS_COB B ON B.COB_CODE = S.STU_COBC) "
                ."LEFT JOIN INTUIT.SRS_COD D ON D.COD_CODE = S.STU_CODC) "
                ."LEFT JOIN INTUIT.SRS_NAT N ON N.NAT_CODE = S.STU_NATC) "
                ."LEFT JOIN INTUIT.INS_DPT T ON T.DPT_CODE = E.SCE_DPTC) "
                ."LEFT JOIN INTUIT.SRS_CRS C ON C.CRS_CODE = E.SCE_CRSC) "
                ."LEFT JOIN INTUIT.SRS_APF A ON A.APF_STUC = E.SCE_STUC) " // AND A.APF_SEQN = E.SCE_SEQ2) "
                ."LEFT JOIN ( "
                ."SELECT CAP_AYRC, CAP_STUC, CAP_SEQN, CAP_APFS, CAP_MCRC, "
                ."CAP_DSCC, CAP_CRSC, CAP_ROUC, CAP_RSP1, CAP_RSPD "
                ."FROM INTUIT.SRS_CAP "
                ."WHERE CAP_DSCC IN ('1P','1UAF')) P "
                ."ON (P.CAP_STUC = E.SCE_STUC AND P.CAP_AYRC = E.SCE_AYRC AND P.CAP_MCRC = E.PSE_ROUC)) "
                ."LEFT JOIN (SELECT SQE_STUC, SQE_SEQN, SQE_EQEC, "
                ."SQE_UDF6 AS IELTS_Listening, SQE_UDF7 AS IELTS_Reading, SQE_UDF8 AS IELTS_Writing, "
                ."SQE_UDF9 AS IELTS_Speaking, SQE_UDFA AS IELTS_Total "
                ."FROM INTUIT.SRS_SQE WHERE SQE_EQEC LIKE 'LANG%' AND SQE_UDFJ LIKE '%P-PSE%') Q "
                ."ON Q.SQE_STUC = P.CAP_STUC ) " // AND Q.SQE_SEQN = P.CAP_SEQN) "
                ."LEFT JOIN ( "
                ."SELECT SPD_STUC, SPD_UDF4, SPD_UDF5, SUBSTR(SPD_KEYW,13) AS Scholarship "
                ."FROM UWTABS.V_UW_CALSPD "
                ."WHERE SPD_KEYW LIKE 'SCHOLARSHIP-%' OR SPD_KEYW LIKE 'EMPLOYER-%') F "
                ."ON F.SPD_STUC = P.CAP_STUC AND F.SPD_UDF4 = P.CAP_APFS AND F.SPD_UDF5 = P.CAP_SEQN) "
                ."WHERE E.PS_Year = '".$Year."' ".($Phase===4?"AND E.Phase_4 = 'Y' ":"") 
                ."ORDER BY E.PS_Year DESC, E.SCE_AYRC DESC, S.STU_SURN, S.STU_FNM1, S.STU_DOB, S.STU_CODE";
 
            $SQL = "SELECT DISTINCT PSYR, CYRC, AYRC, PHASE, "
                ."S.STU_CODE AS Student_No, S.STU_SURN AS Surname, S.STU_FNM1 AS First_Name, S.STU_FUSD AS Pref_Name, S.STU_INIT AS Inits, S.STU_TITL AS Title, "
                ."S.STU_DOB AS DOB, S.STU_GEND AS Gender, S.STU_HAEM AS Home_Email, S.STU_CAEM AS Uni_Email, S.STU_CAT3 AS Mobile, S.STU_UDF3 AS ITS_Login, "
                ."D.COD_NAME AS Domicile, B.COB_NAME AS Birth_Place, N.NAT_NAME AS Nationality, "
                ."C.CRS_NAME AS Course, C.CRS_DPTC AS Dept_Code, T.DPT_NAME AS Department, "
                ."Q.IELTS_Listening, Q.IELTS_Reading, Q.IELTS_Writing, Q.IELTS_Speaking, Q.IELTS_Total, "
                ."DECODE(Q.SQE_EQEC, 'LANG:IU','Y','N') AS UKVI, F.SCHOLARSHIP, "
                ."A.APF_UDFA, DECODE(A.APF_UDFA,'OPT1','Y','OPT2','N',NULL) AS Accommodation, "
                ."O.CAP_DSCC AS CRS_DSCC, O.CAP_RSP1 AS CRS_RSP1, DECODE(O.CAP_RSP1,'F',SUBSTR(O.CAP_RSPD,1,10),NULL) AS CRS_Accepted_Date, "
                ."P.CAP_DSCC AS PSE_DSCC, P.CAP_RSP1 AS PSE_RSP1, DECODE(P.CAP_RSP1,'F',SUBSTR(P.CAP_RSPD,1,10),NULL) AS PSE_Accepted_Date, "
                ."O.CAP_SEQN AS CRS_SEQN, O.CAP_APFS AS CRS_APFS, O.CAP_ROUC AS CRS_ROUC, P.CAP_SEQN, P.CAP_APFS, P.CAP_DPTC,"
                ."SUBSTR(S.STU_UPDD,1,10) AS Updated "
                ."FROM ((((( (((((( "
                ."(SELECT DISTINCT PSYR, AYRC, PHASE, STUC, PSEP, ROUC, SRCE, "
                ."DECODE (PHASE,5,AYRC,4,SUBSTR(AYRC,4,2)||'/'||TO_CHAR(TO_NUMBER(SUBSTR(AYRC,4,2))+1)) AS CYRC "
                ."FROM ( "
                ."SELECT  CAP_STUC AS STUC, CAP_AYRC AS AYRC, CAP_ROUC AS PSEP, CAP_ROUC AS ROUC, "
                ."'20'||DECODE(CAP_ROUC,'PSEP4',SUBSTR(CAP_AYRC,4,2),'PSEP5',SUBSTR(CAP_AYRC,1,2)) AS PSYR, "
                ."SUBSTR(CAP_ROUC,-1,1) AS PHASE, 'CAP_ROUC' AS SRCE "
                ."FROM INTUIT.SRS_CAP "
                ."WHERE CAP_ROUC LIKE '%PSEP%' AND CAP_AYRC > '15/16' "
                ."UNION "
                ."SELECT SCE_STUC AS STUC, SCE_AYRC AS AYRC, SUBSTR(SCE_BTCH,3) AS PSEP, SCE_ROUC AS ROUC, "
                ."'20'||DECODE(SCE_BTCH,'P-PSEP4',SUBSTR(SCE_AYRC,4,2),'P-PSEP5',SUBSTR(SCE_AYRC,1,2)) AS PSYR, "
                ."SUBSTR(SCE_BTCH,-1,1) AS PHASE, 'SCE_BTCH' AS SRCE "
                ."FROM INTUIT.SRS_SCE "
                ."WHERE SCE_BTCH LIKE '%PSEP%' AND SCE_AYRC > '15/16' "
                .")) X "
                ."INNER JOIN INTUIT.INS_STU S ON S.STU_CODE = X.STUC) "
                ."INNER JOIN (SELECT CAP_STUC, CAP_AYRC, CAP_SEQN, CAP_APFS, CAP_ROUC, CAP_CRSC, CAP_DPTC, CAP_DSCC, CAP_RSP1, CAP_RSPD "
                ."FROM INTUIT.SRS_CAP WHERE CAP_ROUC LIKE '%PSEP%' AND CAP_DSCC NOT IN ('W') AND CAP_RSP1 = 'F') O "
                ."ON O.CAP_STUC = X.STUC AND O.CAP_AYRC = X.CYRC ) "
                ."LEFT JOIN (SELECT CAP_STUC, CAP_AYRC, CAP_ROUC, CAP_DPTC, CAP_SEQN, CAP_APFS, CAP_DSCC, CAP_RSP1, CAP_RSPD "
                ."FROM INTUIT.SRS_CAP WHERE CAP_ROUC LIKE '%PSEP%' AND CAP_DSCC NOT IN ('W','1R')) P "
                ."ON P.CAP_STUC = X.STUC AND P.CAP_AYRC = X.AYRC AND P.CAP_ROUC = X.PSEP) "
                ."LEFT JOIN "
                ."(SELECT SQE_STUC, SQE_SEQN, SQE_EQEC, "
                ."SQE_UDF6 AS IELTS_Listening, SQE_UDF7 AS IELTS_Reading, SQE_UDF8 AS IELTS_Writing, SQE_UDF9 AS IELTS_Speaking, SQE_UDFA AS IELTS_Total "
                ."FROM INTUIT.SRS_SQE WHERE SQE_EQEC LIKE 'LANG%' AND SQE_UDFJ LIKE '%P-PSE%') Q "
                ."ON (Q.SQE_STUC = X.STUC)) "
                ."LEFT JOIN "
                ."(SELECT SPD_STUC, SPD_UDF4, SPD_UDF5, 'Y' AS SCHOLARSHIP FROM UWTABS.V_UW_CALSPD "
                ."WHERE SPD_KEYW LIKE 'SCHOLARSHIP-%' OR SPD_KEYW LIKE 'EMPLOYER-%' "
                ."GROUP BY SPD_STUC, SPD_UDF4, SPD_UDF5 HAVING COUNT(1)>0) F "
                ."ON (F.SPD_STUC = X.STUC AND F.SPD_UDF4 = O.CAP_APFS AND F.SPD_UDF5 = O.CAP_SEQN)) "
                ."LEFT JOIN INTUIT.SRS_COB B ON B.COB_CODE = S.STU_COBC) "
                ."LEFT JOIN INTUIT.SRS_COD D ON D.COD_CODE = S.STU_CODC) "
                ."LEFT JOIN INTUIT.SRS_NAT N ON N.NAT_CODE = S.STU_NATC) "
                ."LEFT JOIN INTUIT.INS_DPT T ON T.DPT_CODE = O.CAP_DPTC) "
                ."LEFT JOIN INTUIT.SRS_CRS C ON C.CRS_CODE = O.CAP_CRSC) "
                ."LEFT JOIN (SELECT APF_STUC, APF_UDFA FROM INTUIT.SRS_APF "
                ."WHERE APF_UDFA IS NOT NULL) A ON A.APF_STUC = X.STUC)"
                ."WHERE PSYR = '".$Year."' ".($Phase===''?"":" AND PHASE = '".$Phase."' ")
                ."ORDER BY X.PSYR DESC, X.PHASE DESC, S.STU_SURN, S.STU_FNM1, S.STU_CODE";
          //  $SQL = "SELECT * FROM UWTABS.V_UW_CALUA ";
    // $Applications = (array) DB::connection("Oracle")->select($SQL);
   //               return($Applications);
            $SQL = "SELECT DISTINCT PSYR, CYRC, AYRC, PHASE, 
	S.STU_CODE AS Student_No, S.STU_SURN AS Surname, S.STU_FNM1 AS First_Name, S.STU_FUSD AS Pref_Name, S.STU_INIT AS Inits, S.STU_TITL AS Title,
    SUBSTR(S.STU_DOB,1,10) AS DOB, S.STU_GEND AS Gender, S.STU_HAEM AS Home_Email, S.STU_CAEM AS Uni_Email, S.STU_CAT3 AS Mobile, S.STU_UDF3 AS ITS_Login,
    D.COD_NAME AS Domicile, B.COB_NAME AS Birth_Place, N.NAT_NAME AS Nationality,
    C.CRS_SNAM, C.CRS_NAME, /*C.CRS_TITL,*/ C.CRS_DPTC, T.DPT_NAME,
	O.CAP_SEQN AS CRS_SEQN, O.CAP_APFS AS CRS_APFS, O.CAP_ROUC AS CRS_ROUC, /*O.CAP_MCRC AS CRS_MCRS, O.CAP_CRSC AS CRS_CRSC, O.CAP_DPTC AS CRS_DPTC, */ 
    P.CAP_SEQN, P.CAP_APFS, P.CAP_ROUC, P.CAP_DPTC, P.CAP_SCHC, 
	Q.IELTS_Listening, Q.IELTS_Reading, Q.IELTS_Writing, Q.IELTS_Speaking, Q.IELTS_Total,
	 DECODE(Q.SQE_EQEC, 'LANG:IU','Y','N') AS UKVI, 
	A.APF_UDFA, DECODE(A.APF_UDFA,'OPT1','Y','OPT2','N',NULL) AS Accommodation,
    F.SCHOLARSHIP, 
	O.CAP_DSCC AS CRS_DSCC, O.CAP_RSP1 AS CRS_RSP1, /*SUBSTR(O.CAP_RSPD,1,10) AS CRS_RSPD,*/ DECODE(O.CAP_RSP1,'F',SUBSTR(O.CAP_RSPD,1,10),NULL) AS CRS_Accepted_Date,
	P.CAP_DSCC AS PSE_DSCC, P.CAP_RSP1 AS PSE_RSP1, /*SUBSTR(P.CAP_RSPD,1,10) AS PSE_RSPD,*/ DECODE(P.CAP_RSP1,'F',SUBSTR(P.CAP_RSPD,1,10),NULL) AS PSE_Accepted_Date,
	SUBSTR(S.STU_UPDD,1,10) AS Updated
FROM ((((( ((((((
	(SELECT DISTINCT PSYR, AYRC, PHASE, STUC, PSEP, ROUC, SRCE,  
	DECODE (PHASE,5,AYRC,4,SUBSTR(AYRC,4,2)||'/'||TO_CHAR(TO_NUMBER(SUBSTR(AYRC,4,2))+1)) AS CYRC
	FROM (
	SELECT CAP_STUC AS STUC, CAP_AYRC AS AYRC, CAP_ROUC AS PSEP, CAP_ROUC AS ROUC, 
	'20'||DECODE(CAP_ROUC,'PSEP4',SUBSTR(CAP_AYRC,4,2),'PSEP5',SUBSTR(CAP_AYRC,1,2)) AS PSYR, 
	SUBSTR(CAP_ROUC,-1,1) AS PHASE, 'CAP_ROUC' AS SRCE
	FROM INTUIT.SRS_CAP 
	WHERE CAP_ROUC LIKE '%PSEP%' AND CAP_AYRC > '15/16'
	UNION
	SELECT SCE_STUC AS STUC, SCE_AYRC AS AYRC, SUBSTR(SCE_BTCH,3) AS PSEP, SCE_ROUC AS ROUC,
		'20'||DECODE(SCE_BTCH,'P-PSEP4',SUBSTR(SCE_AYRC,4,2),'P-PSEP5',SUBSTR(SCE_AYRC,1,2)) AS PSYR, 
		SUBSTR(SCE_BTCH,-1,1) AS PHASE, 'SCE_BTCH' AS SRCE
	FROM INTUIT.SRS_SCE 
	WHERE SCE_BTCH LIKE '%PSEP%' AND SCE_AYRC > '15/16'
	) /*WHERE (PSYR < '2020' AND PSEP <> ROUC) OR SRCE = 'CAP_ROUC' */ 
) X
INNER JOIN INTUIT.INS_STU S ON S.STU_CODE = X.STUC)
LEFT JOIN (SELECT CAP_STUC, CAP_AYRC, CAP_SEQN, CAP_APFS, CAP_ROUC, /*CAP_MCRC,*/  CAP_CRSC, CAP_DPTC, CAP_DSCC, CAP_RSP1, CAP_RSPD
	FROM INTUIT.SRS_CAP WHERE CAP_ROUC NOT LIKE '%PSEP%' AND CAP_DSCC NOT IN ('W') AND CAP_RSP1 = 'F') O 
	ON O.CAP_STUC = X.STUC AND O.CAP_AYRC = X.CYRC /*AND O.CAP_ROUC = X.ROUC*/)
LEFT JOIN (SELECT CAP_STUC, CAP_AYRC, CAP_ROUC, CAP_DPTC, CAP_SCHC, CAP_SEQN, CAP_APFS, CAP_DSCC, CAP_RSP1, CAP_RSPD
	FROM INTUIT.SRS_CAP WHERE CAP_ROUC LIKE '%PSEP%' AND CAP_DSCC NOT IN ('W','1R') /*AND CAP_RSP1 = 'F'*/) P 
	ON P.CAP_STUC = X.STUC AND P.CAP_AYRC = X.AYRC AND P.CAP_ROUC = X.PSEP)
LEFT JOIN 
	(SELECT SQE_STUC, SQE_SEQN, SQE_EQEC,
	SQE_UDF6 AS IELTS_Listening, SQE_UDF7 AS IELTS_Reading, SQE_UDF8 AS IELTS_Writing, SQE_UDF9 AS IELTS_Speaking, SQE_UDFA AS IELTS_Total
	FROM INTUIT.SRS_SQE WHERE SQE_EQEC LIKE 'LANG%' AND SQE_UDFJ LIKE '%P-PSE%') Q 
	ON (Q.SQE_STUC = X.STUC))
LEFT JOIN 
	(SELECT SPD_STUC, SPD_UDF4, SPD_UDF5, 'Y' AS SCHOLARSHIP 
	FROM UWTABS.V_UW_CALSPD
	WHERE SPD_KEYW LIKE 'SCHOLARSHIP-%' OR SPD_KEYW LIKE 'EMPLOYER-%'
	GROUP BY SPD_STUC, SPD_UDF4, SPD_UDF5 HAVING COUNT(1)>0) F 
	ON (F.SPD_STUC = X.STUC AND F.SPD_UDF4 = O.CAP_APFS AND F.SPD_UDF5 = O.CAP_SEQN))
LEFT JOIN INTUIT.SRS_COB B ON B.COB_CODE = S.STU_COBC)
LEFT JOIN INTUIT.SRS_COD D ON D.COD_CODE = S.STU_CODC)
LEFT JOIN INTUIT.SRS_NAT N ON N.NAT_CODE = S.STU_NATC)
LEFT JOIN INTUIT.INS_DPT T ON T.DPT_CODE = O.CAP_DPTC)
LEFT JOIN INTUIT.SRS_CRS C ON C.CRS_CODE = O.CAP_CRSC)
LEFT JOIN (SELECT APF_STUC, APF_UDFA FROM INTUIT.SRS_APF WHERE APF_UDFA IS NOT NULL) A ON A.APF_STUC = X.STUC)"
 ."WHERE PSYR = '".$Year."' ".($Phase===''?"":" AND PHASE = '".$Phase."' ")
                ."ORDER BY X.PSYR DESC, X.PHASE DESC, S.STU_SURN, S.STU_FNM1, S.STU_CODE";
   //dd($SQL);
     //       $SQL = "SELECT * FROM UWTABS.V_UW_CALUA ";
          //dd(DB::connection("Oracle"));
            $Applications = (array) DB::connection("Oracle")->select($SQL);
          //  $SQL = "SELECT * FROM UWTABS.V_UW_CALUA ";
           // $Students = (array) DB::connection("Oracle")->select($SQL);
                
           // dd($Applications);
          //  dd($SQL);   
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Applications);
        
    }
    public static function GetSITStudentsRAW($Year, $Phase) {
        
        try {
            // Note: Temporary Solution While Waiting for View
       
            $SQL = "SELECT S.STU_CODE AS Student_No, S.STU_SURN AS Surname, S.STU_FNM1 AS First_Name, "
                ."S.STU_FUSD AS Pref_Name, S.STU_INIT AS Inits, S.STU_TITL AS Title, "
                ."S.STU_DOB AS DOB, S.STU_GEND AS Gender, S.STU_HAEM AS Home_Email, "
                ."S.STU_CAEM AS Uni_Email, S.STU_CAT3 AS Mobile, S.STU_UDF3 AS ITS_Login, "
                ."D.COD_NAME AS Domicile, B.COB_NAME AS Birth_Place, N.NAT_NAME AS Nationality, "
                ."C.CRS_SNAM, C.CRS_NAME, C.CRS_TITL, C.CRS_DPTC, T.DPT_NAME, "
                ."A.APF_UDFA, DECODE(A.APF_UDFA,'OPT1','Y','OPT2','N',NULL) AS Accommodation, "
                ."P.CAP_RSP1, P.CAP_RSPD, DECODE(P.CAP_RSP1,'F',P.CAP_RSPD,NULL) AS Accepted_Date, "
                ."F.Scholarship, DECODE(Q.SQE_EQEC, 'LANG:IU','Y','N') AS UKVI, "
                ."Q.IELTS_Listening, Q.IELTS_Reading, Q.IELTS_Writing, Q.IELTS_Speaking, Q.IELTS_Total, "
                ."E.SCE_DPTC, E.SCE_CRSC, E.SCE_ROUC, E.PSE_ROUC, E.Phase_4, E.Phase_5, "
                ."E.PS_Year, E.SCE_AYRC AS Acad_Year, P.CAP_DSCC As Decision, P.CAP_RSP1 As Response, "
                ."E.SCE_AYRC, E.SCE_STUC, E.SCE_SEQ2, E.SCE_BTCH, A.APF_STUC, A.APF_SEQN, P.CAP_AYRC, P.CAP_STUC, P.CAP_MCRC, Q.SQE_STUC, Q.SQE_SEQN, F.SPD_STUC, F.SPD_UDF4, F.SPD_UDF5, "
                ."S.STU_UPDD AS Updated "
                ."FROM ((((((((((("
                ."SELECT DECODE(SCE_BTCH, NULL, PSYear2,PSYear1) AS PS_Year, "
		."SCE_AYRC, SCE_STUC, SCE_SEQ2, SCE_DPTC, SCE_CRSC, SCE_ROUC, SCE_BTCH, "
		."DECODE(SCE_BTCH, NULL, 'P-'||SCE_ROUC, SCE_BTCH) AS PSE_ROUC, "
		."DECODE(SCE_BTCH, NULL, Phase42,Phase41) AS Phase_4, Phase_5 "
                ."FROM ( "
		."SELECT SCE_STUC, SCE_SEQ2, SCE_BTCH, SCE_ROUC, SCE_AYRC, SCE_CRSC, SCE_DPTC, "
		."'20'||DECODE(SCE_BTCH,'P-PSE10',SUBSTR(SCE_AYRC,-2),'P-PSEP4',SUBSTR(SCE_AYRC,-2),'PSEP4',SUBSTR(SCE_AYRC,-2),'PSE8',"
		."SUBSTR(SCE_AYRC,-2),'PSE10',SUBSTR(SCE_AYRC,-2),SUBSTR(SCE_AYRC,1,2)) AS PSYear1, "
		."'20'||DECODE(SCE_ROUC,'PSE10',SUBSTR(SCE_AYRC,-2),'PSEP4',SUBSTR(SCE_AYRC,-2),SUBSTR(SCE_AYRC,1,2)) AS PSYear2,"
		."DECODE(SCE_BTCH,'P-PSE10','Y','P-PSEP4','Y','PSEP4','Y','PSE8','Y','PSE10','Y','PSE12','Y','N') AS Phase41, "
		."DECODE(SCE_ROUC,'PSE10','Y','PSEP4','Y','N') AS Phase42, 'Y' AS Phase_5 "
		."FROM INTUIT.SRS_SCE "
		."WHERE (SCE_BTCH LIKE '%PSE%' OR  (SCE_ROUC LIKE '%PSE%' AND SCE_BTCH IS NULL)) AND SCE_AYRC > '11/12') "
                .") E "
                ."INNER JOIN INTUIT.INS_STU S ON S.STU_CODE = E.SCE_STUC)"
                ."LEFT JOIN INTUIT.SRS_COB B ON B.COB_CODE = S.STU_COBC) "
                ."LEFT JOIN INTUIT.SRS_COD D ON D.COD_CODE = S.STU_CODC) "
                ."LEFT JOIN INTUIT.SRS_NAT N ON N.NAT_CODE = S.STU_NATC) "
                ."LEFT JOIN INTUIT.INS_DPT T ON T.DPT_CODE = E.SCE_DPTC) "
                ."LEFT JOIN INTUIT.SRS_CRS C ON C.CRS_CODE = E.SCE_CRSC) "
                ."LEFT JOIN INTUIT.SRS_APF A ON A.APF_STUC = E.SCE_STUC) " // AND A.APF_SEQN = E.SCE_SEQ2) "
                ."LEFT JOIN ( "
                ."SELECT CAP_AYRC, CAP_STUC, CAP_SEQN, CAP_APFS, CAP_MCRC, "
                ."CAP_DSCC, CAP_CRSC, CAP_ROUC, CAP_RSP1, CAP_RSPD "
                ."FROM INTUIT.SRS_CAP "
                ."WHERE CAP_DSCC IN ('1P','1UAF')) P "
                ."ON (P.CAP_STUC = E.SCE_STUC AND P.CAP_AYRC = E.SCE_AYRC AND P.CAP_MCRC = E.PSE_ROUC)) "
                ."LEFT JOIN (SELECT SQE_STUC, SQE_SEQN, SQE_EQEC, "
                ."SQE_UDF6 AS IELTS_Listening, SQE_UDF7 AS IELTS_Reading, SQE_UDF8 AS IELTS_Writing, "
                ."SQE_UDF9 AS IELTS_Speaking, SQE_UDFA AS IELTS_Total "
                ."FROM INTUIT.SRS_SQE WHERE SQE_EQEC LIKE 'LANG%' AND SQE_UDFJ LIKE '%P-PSE%') Q "
                ."ON Q.SQE_STUC = P.CAP_STUC ) " // AND Q.SQE_SEQN = P.CAP_SEQN) "
                ."LEFT JOIN ( "
                ."SELECT SPD_STUC, SPD_UDF4, SPD_UDF5, SUBSTR(SPD_KEYW,13) AS Scholarship "
                ."FROM UWTABS.V_UW_CALSPD "
                ."WHERE SPD_KEYW LIKE 'SCHOLARSHIP-%' OR SPD_KEYW LIKE 'EMPLOYER-%') F "
                ."ON F.SPD_STUC = P.CAP_STUC AND F.SPD_UDF4 = P.CAP_APFS AND F.SPD_UDF5 = P.CAP_SEQN) "
                ."WHERE E.PS_Year = '".$Year."' ".($Phase===4?"AND E.Phase_4 = 'Y' ":"") 
                ."ORDER BY E.PS_Year DESC, E.SCE_AYRC DESC, S.STU_SURN, S.STU_FNM1, S.STU_DOB, S.STU_CODE";
            
        //    $SQL = "SELECT DISTINCT ACAD_YEAR, BATCH, PS_YEAR, PSE_ROUTE,  "
        //        ."STUDENT_NO, SURNAME, FIRST_NAME, PREF_NAME, TITLE, INITS, "
        //        ."DOB, GENDER, HOME_EMAIL, UNI_EMAIL, MOBILE, "
        //        . "ITS_LOGIN, UPDATED, DOMICILE, BIRTH_PLACE, NATIONALITY, "
        //        ."DEPARTMENT as DEPT_CODE, COURSE_CODE, COURSE, DECISION, PHASE_4, PHASE_5, FIRM, "
        //        . "DECODE(ROUTE,'PSEP4','Y','PSEP5','N','PSE4','N','PSE5','N','PSE6','N','PSE8','Y','PSE10','Y','PSE12','Y') AS Phase4, "
        //        . "'Y' AS Phase5, "
        //        ."'' as Phase_4_Group, '' as Phase_5_Group, '' as Results, '' as Group_No, '' as Phase_No  " 
        //        . "FROM UWTABS.V_UW_CALUA "
        //        . "WHERE ACAD_YEAR = '".$Academic_Year."' "
        //        . "ORDER BY SURNAME, FIRST_NAME, DOB ";
            
           $Students = (array) DB::connection("Oracle")->select($SQL);
           // $SQL = "SELECT * FROM UWTABS.V_UW_CALUA ";
           // $Students = (array) DB::connection("Oracle")->select($SQL);
                
            //dd($Students);
               
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Students);
        
    }
    
    public static function SITS2PSE($Students) {
        
        try {
            //dd($Students);
            foreach($Students as $s){
            
                $Students = (array) DB::connection('mysql')->select(
                    "INSERT INTO PSE_Students "
                    ."(Student_No, Acad_year, Family_Name, First_Name, "
                    ."Phase_4, Phase_5 ) "
                    ."SELECT ?, ?, ?, ?, ?, ? FROM DUAL " 
                    ."WHERE NOT EXISTS ("
                    ."SELECT Student_No, Acad_Year FROM PSE_Students "
                    . "WHERE Student_No = ? AND Acad_Year = ? ) ", 
                    [$s->student_no, $s->acad_year, $s->surname, $s->first_name, 
                     $s->phase_4, $s->phase_5, $s->student_no, $s->acad_year ]);
            }
  
        } catch (PDOException $e) {
            echo($exception->getMessage());
        }
        return;
        
    }
    
    public static function GetPSEStudents($Academic_Year, $Group_No ) {
        
        try {
         
            //dd(config('database.connections'));
            
            //dd(config('database.connections.oracle.database'));
            //$dbconnection = 'oracle';
            
            $Students = (array) DB::connection('mysql')->select('SELECT * FROM PSE_Students WHERE Acad_Year LIKE '
                    ."'".$Academic_Year."'". ' ORDER BY Family_Name, First_Name' );
            
            //dd($Students);
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Students);
        
    }
    
    public static function GetStudentGroups($Students, $Academic_Year,$Phase_No ) {
        
        try {
         
            //dd(config('database.connections'));
            
            //dd(config('database.connections.oracle.database'));
            //$dbconnection = 'oracle';
            
            // Read SITS Students into Array
           // $Students = (array) DB::connection('oracle')->select("SELECT DISTINCT "
           //     ."Acad_Year, Student_No, Surname, First_Name, Gender, Nationality, "
           //     ."Dept_Code as Department, Course, '' as Results, '' as Group_No, '' as Phase_No "
           //     ."FROM UWTABS.V_UW_CALUA UA "
           //     ."WHERE Acad_Year LIKE '".$Academic_Year."' ");
            //dd($Students);
            // Read PSE Student Groups into Array
            $Groups = (array) DB::connection('mysql')->select("SELECT Acad_Year, Student_No, "
                ."Phase_1, Phase_2, Phase_3, Phase_4, Phase_5, "
                ."Phase_1_Group, Phase_2_Group, Phase_3_Group, Phase_4_Group, Phase_5_Group "
                ."FROM PSE_Students "
                ."WHERE Acad_Year LIKE '".$Academic_Year."' AND Phase_".$Phase_No);
            //dd($Groups);
            // Update Students Array with Approprate Groups 
            foreach($Students as $k => $s) {
                //$found = false;
                foreach($Groups as $g) {
                    if($s->student_no ==  $g->Student_No AND $s->acad_year == $g->Acad_Year){
                        $s->group_no = ($Phase_No == '1'? $g->Phase_1_Group: 
                                       ($Phase_No == '2'? $g->Phase_2_Group: 
                                       ($Phase_No == '3'? $g->Phase_3_Group:
                                       ($Phase_No == '4'? $g->Phase_4_Group: $g->Phase_5_Group)))); 
                        $s->phase_no = $Phase_No;
                        //$found = true;
                    }                      
                }
                // Not considered safe to remove elements of an array while itterating through it. 
                //if(!$found){
                //    // Student no in this Phase => delete
                //    unset($Students[$k]);
                //}
            }
           //dd($Students);
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Students);
        
    }
    
    public static function SetStudentGroup($aYear, $Phase_No, $Group_No, $Student_No){
        $data['Result'] = 'Failed';
        //$Group_No = ($Group_No =''?'NULL':$Group_No);
        try {
            DB::connection('mysql')->select("UPDATE PSE_Students "
                ."SET Phase_".$Phase_No."_Group = '".$Group_No."' "
                ."WHERE Acad_Year = '".$aYear."' "
                ."AND Student_No = '".$Student_No."' ");     
            $Student = DB::connection('mysql')->select("SELECT Phase_".$Phase_No."_Group AS Group_No "
                . "FROM PSE_Students "
                ."WHERE Acad_Year = '".$aYear."' "
                ."AND Student_No = '".$Student_No."' ");  
            // Test for record set and correct new value
            if($Student AND $Student[0]->Group_No == $Group_No){
                $data['Result']  = 'Success';
            }
        } catch (PDOException $e) {
            //die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }        
        return($data); 
    }  
}


