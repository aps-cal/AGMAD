<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



namespace App\Tabula;

use \Illuminate\Http\Request;

use \Illuminate\Support\Facades\DB;



class TabulaAPI {
    
    
   
/*    Now done in GroupSet Class
    public static function RetrieveSet($ModuleCode, $AcademicYear, $smallGroupSetId) {
        if(!$smallGroupSetId) {
            $smallGroupSetId = '';
        }
        $pageURL = "https://tabula.warwick.ac.uk/api/v1/module/".$ModuleCode."/groups".$smallGroupSetId ;
        $query = "academicYear=".$AcademicYear;
        $xmlSet = TabulaAPI::GetResponse($pageURL, $query);
        
        // Parse XML 
        
        // Question: Do I pass back the XML for the calling object to create itself
        //      OR:  Do I parse the XML and create an object that I pass back from here
        // Where will the object be saved? 
        //  Presumaly you create the object and it then saves itself to the database?
    }
*/    
    
    public static function GetResponse($pageURL,$query){
        error_reporting(E_STRICT); 
        echo '<br/>GetResponse ';
        try {
            //echo ("</p>".base64_encode('el-apiuser:Roberts1951')."<p>");
            //echo ("</p>".base64_decode('ZWwtYXBpdXNlcjpSb2JlcnRzMTk1MQ==')."<p>");
            
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_HTTPGET, true);  // Switch back to default GET Method
            // If sending values by the GET method then they need to be added to the URL string
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
            $header = array(
                'Content-Type: application/xml',
                'Authorization: Basic '.base64_encode('el-apiuser:Roberts1951')
            );
            //curl_setopt($ch, CURLOPT_HEADER, true); // This value should not be set as adds header to response
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should prevent output to browser
            
            $res = curl_exec($ch); 
            $resObj = json_decode($res);
          //  echo $resObj->success.':'.$resObj->status;
          //  $GroupSet = 
          //  echo "<pre>"; print_r($resArr); echo "</pre>";
            
            
            
         //   $xml = Simplexml_load_string($res);
         //   echo $res;
         //    dd($xml);
         // echo ("<H1>TESTING</H1>");
          if(!$resObj->success){
              TabulaAPI::LogErrors($resObj); // Log Failed Request
          } else {
              echo $resObj->status; 
          //    echo Simplexml_load_string($resObj);
          }
        } catch (Exception $e) {
            echo ('Error message (if any): '.curl_error($ch).'\n\n');
            echo ("<p>");
            var_dump(curl_getinfo($ch)); 
            echo ("</p><p>");
            var_dump($res); 
            echo ("</p>");   
        }
        curl_close($ch);
        return($resObj);
    }
    
    
    
    
    
    
    
    
    
    public function CreateGroup() {
        
    }
    
    public function UpdateGroup() {
        
    }
    
    public function DeleteGroup() {
        
    }
    
    public static function LogErrors($resObj) {
        try {
            echo 'ERROR ('.$resObj->status. '):'; 
            foreach($resObj->errors as $error){
                echo '<p>'.$error->message.'</p>'; 
                $sql = DB::insert('INSERT INTO Tabula_API_Errors (status, message) VALUES (?,?)',
                    [$error->status, $error->message]);
            }
        } catch (PDOException $e) {
            die("Could not connect to the database.  Please check your configuration.".$exception->getMessage());
        }  
    }
    
}