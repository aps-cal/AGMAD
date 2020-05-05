<?php

namespace App\PS;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

use Illuminate\Support\Facades\DB;

class Registers
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    
    public static function RegisterStudents($request) {
        try {
            $SQL="
                SELECT R.RowID, G.PS_Year, G.Phase_No, G.Student_No, G.Family_Name, G.First_Name, G.Group_No, G.Week_No, W.Day_No, W.Module, W.Class, R.Status, R.Note
                FROM (((SELECT P.PS_Year, P.Phase_No, CASE 
                  WHEN P.Phase_No = 1 then Phase_1_Group
                  when P.Phase_No = 2 then Phase_2_Group
                  when P.Phase_No = 3 then Phase_3_Group
                  when P.Phase_No = 4 then Phase_4_Group
                  else Phase_5_Group
                END AS Group_No, S.Student_No, S.Family_Name, S.First_Name, P.Week_No
                FROM PSE_Students S,  Phase_Week_Numbers P 
                WHERE P.PS_Year = S.PS_Year) G
                INNER JOIN Week_Classes W ON W.PS_Year = G.PS_Year AND W.Phase_No = G.Phase_No)
                LEFT JOIN PSE_Registers R 
                ON R.PS_Year = G.PS_year AND R.Phase_No = G.Phase_No 
                AND R.Group_No = G.Group_No AND R.Week_No = G.Week_No 
                AND R.Module = W.Module AND R.Day_No = W.Day_No 
                AND R.Class = W.Class AND R.Student_No = G.Student_No)   
                WHERE G.PS_Year = ? AND G.Phase_No = ? AND G.Group_No = ? AND G.Week_No = ? AND W.Module = ?
                ORDER BY G.Student_No, Day_No, Module, Class
                    ";
        //    dd([request("Year"), request("Phase"), request("Group_No"), request("Week"), request("Module")]);
            $Registers= (array) DB::select($SQL,
                [request("Year"), request("Phase"), request("Group_No"), request("Week"), request("Module")]
            );
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        return($Registers);
        
    }
    
    public static function  updateRegister($request){
        $data = [];
        $Result = 'Failed';
        $Year = request("Year");
        $Phase = request("Phase");
        $Group = request("Group_No");
        $Week = request("Week");
        $Module = request("Module"); 
        $Day = request("Day_No");
        $Class = request("Class");
        $Student = request("Student_No");
        $Status =  request("Status");
        $Note = request("Note");
        try {
            $SQL = "SELECT 1 FROM PSE_Registers 
                    WHERE PS_Year = ? AND Phase_No = ? AND Group_No = ? AND Week_No = ? 
                    AND Module = ? AND Day_No = ? AND Class = ? AND Student_No = ? ";
            $Result = (array) DB::select($SQL,
                [$Year,$Phase, $Group, $Week, $Module, $Day, $Class, $Student]); 
            if(!$Result){
                $SQL = "INSERT INTO PSE_Registers 
                    (PS_Year, Phase_No, Group_No, Week_No, Module, Day_No, Class, Student_No) 
                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )";     
                $Result = (array) DB::insert($SQL,
                        [$Year,$Phase, $Group, $Week,$Module, $Day, $Class, $Student]);        
            /*request("Family_Name"),request("First_Name"), */
            }
            $SQL== "UPDATE PSE_Registers SET Status = ? 
                    WHERE PS_Year = ? AND Phase_No = ? AND Group_No = ? AND Week_No ? 
                    AND Module = ? AND Day_No = ? AND Class = ? AND Student_No = ? ) ";
            /*, Note = COALESCE(? ,' ')*/
            $Result = (array) DB::update($SQL,
                    [$Status,$Year,$Phase, $Group, $Week,$Module, $Day, $Class, $Student]);
            /*request("Note"), */
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        $data['Result'] = $Result;
        return($data);   
    }
    
    public static function  SaveLNSRegister($request){
        $data = [];
        $Result = 'Failed';
        try {
            if(request("RowID")){
                $SQL="
                    UPDATE PSE_Registers SET 
                        LNS_AM = ?, LNS_PM = ?, LNS_Notes = ? 
                    WHERE RowID = ? ";
                $Result = (array) DB::update($SQL,[request("LNS_AM"),request("LNS_PM"),
                    request("LNS_Notes"),request("RowID")]);
            }
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        $data['Result'] = $Result;
        return($data);   
    }

    public static function  SaveTBSRegister($request){
        $data = [];
        $Result = 'Failed';
        try {
            if(request("RowID")){
                $SQL="
                    UPDATE PSE_Registers SET 
                        TBS_AM = ?, TBS_PM = ?, TBS_Report = ? 
                    WHERE RowID = ? ";
                $Result = (array) DB::update($SQL,[request("TBS_AM"),request("TBS_PM"),
                    request("TBS_Notes"),request("RowID")]);
            }
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }
        $data['Result'] = $Result;
        return($data);   
    }
    
}
