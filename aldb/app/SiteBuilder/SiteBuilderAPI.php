<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



namespace App\SiteBuilder;

use \Illuminate\Http\Request;

use \Illuminate\Support\Facades\DB;

use App\Tabula\TabulaAPI;



class SiteBuilderAPI {
    
    
   
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
    public static function GetPayments(){
        $payments = array();
        $url = 'https://sitebuilder.warwick.ac.uk/sitebuilder2/forms/submissions/download.xml';
        $query = 'page=/fac/soc/al/study/short-courses/learn-english/pre-sessional/course-details/ps45payment';
        //$query = 'page=%2Ffac%2Fsoc%2Fal%2Fstudy%2Fshort-courses%2Flearn-english%2Fpre-sessional%2Fcourse-details%2Fps45payment';
        //$query.= '&startDate='.date(time()-(60 * 24 * 60 * 60)); 
        $query.= '&endDate='.date(time()); 
        $query.= '&startDate=1512180000'; //.date(time()-(390 * 24 * 60 * 60)); 
        //$query.= '&endDate=1501202359'; //.date(time()+(24 * 60 * 60)); 
        $query.= '&pagesize=20&filter=';
        //$query= 'page=%2Ffac%2Fsoc%2Fal%2Fstudy%2Fshort-courses%2Flearn-english%2Fpre-sessional%2Fcourse-details%2Fps45payment&pagesize=20&startDate=1512180000&endDate=1501202359&filter=';
        //$query.='&forceBasic=true';
        //$resObj = TabulaAPI::GetResponse($url,$query);
        $query2= '&startDate='.date(time()-(60 * 24 * 60 * 60)); 
        $query2.= '&endDate='.date('d/m/Y',time()); 
        
        
        $res = SiteBuilderAPI::GetResponse($url, $query); 
        //$res = str_replace("<filter/>","<filter></filter>",$res); // closed filter tag
        //$res = str_replace("formsbuider-submissions","submissions",$res); // closed filter tag
        dd($res);
        //$d = simplexml_load_string($res); // download
        //dd($d);
        //$resObj = json_decode($d);
        //dd($resObj);
        //$submissions = $d->submission;
        //    foreach($submissions as $p => $s){
        //        echo $p.' => '.$s;
        //    }
        //dd($res->submissions);
        //$d = simplexml_load_string($res); // download
        //$d = $res->submission;
        dd($d);
        foreach($d->submission as $s){
            dd($s->propery['Submission ID']);
            echo "<br/>".$s->propery['Submission ID'];
            echo "<br/>".$s['Submission time'];
            echo "<br/>".$s['Submission transactionID'];
            echo "<br/>".$s['Payment Status'];
            echo "<br/>".$s['Payment Amount'];
            echo "<br/>".$s['Title'];
            echo "<br/>".$s['Family_Name'];
            echo "<br/>".$s['First_Name'];
            echo "<br/>".$s['Email'];
            echo "<br/>".$s['Address'];
            echo "<br/>";
            
            
            //$submission  = simplexml_load_string($s);
            //dd($submission);
            
        }
            
        //if($resObj->success){
            // Add Tutors and Students
            echo "<br/>Payments";
            //$submissions = simplexml_load_string($xml);
            
            foreach($xml->submission as $s){
               echo ($s['Submission ID']);
               
                foreach($s->property as $p){
                    echo($p); 
                    //echo '<br/>'.$p->name;                
                }               
            }
            //return $resObj->groups;
        //} else { 
        //    return array();
       // }
        
        
        
        
        
        //echo '<br/>'.$url.'?'.$query.' vs '.$query2;
        //dd($resObj);
    }
    
    public static function GetResponse($pageURL,$query){
        error_reporting(E_STRICT); 
        echo '<br/>GetResponse ';
        try {
            //echo ("</p>".base64_encode('el-apiuser:Roberts1951')."<p>");
            //echo ("</p>".base64_decode('ZWwtYXBpdXNlcjpSb2JlcnRzMTk1MQ==')."<p>");
            //echo '<BR/>URL: '.$pageURL.'?'.$query.'<BR/>';
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_HTTPGET, true);  // Switch back to default GET Method
            // If sending values by the GET method then they need to be added to the URL string
            curl_setopt($ch, CURLOPT_URL, $pageURL.'?'.$query); 
            // If sending values by the POST method do not add to the URL but use POSTFIELDS
            curl_setopt($ch, CURLOPT_POST, true);  // Switch in Post Method
            // $query = array('academicYear: 18/19');
            curl_setopt($ch, CURLOPT_POSTFIELDS,  $query);
            // $postdata = array('academicYear' => '18/19');
            // curl_setopt($ch, CURLOPT_POSTFIELDS,  $postdata);
            // Alternative unsecure way to send Authentication
            curl_setopt($ch, CURLOPTUSERPWD, "el-apiuser:Roberts1951");
            // BASIC Authentication 
            $header = array(
                'Content-Type: application/xml', ///x-www-form-urlencoded',
                'User-Agent: Andrew P Smith, Applied Lingusitics, 07746412190, andrew.p.smith@warwick.ac.uk',
                'Authorization: Basic '.base64_encode('el-apiuser:Roberts1951')
            );
            //dd($header); 
            //curl_setopt($ch, CURLOPT_HEADER, false); // This value should not be set as adds header to response
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should prevent output to browser
            $res = curl_exec($ch); 
            echo "------";
            dd($res);
            //$res = str_replace('"""','',$res); // remove quotes
            //if(!$res->success){
            //  echo '<BR/>Status: '.$res->status; 
            //  TabulaAPI::LogErrors($res); // Log Failed Request
            //  $xml = '';
            //} else {
        //        $xml = simplexml_load_string($res);
                //echo $resObj->status; 
          //    echo Simplexml_load_string($resObj);
          //}
            
            //dd($xml);
        /*    
            
            //dd($res);
            $resObj = json_decode($res);
            dd($resObj);
            $res = str_replace("<filter/>","<filter></filter>",$res); // closed filter tag
            $res = str_replace(chr(-1),"",$res); // Remove illegal character
            
            echo '<br/> $Res: [['.$res.']]<br/>';
            $xml = simplexml_load_string($res);
            echo '<br/> $xml: <<['.$xml.']>><br/>';
            $resObj = json_decode($res);
            echo '<br/> ResObj: ['.$resObj->success.':'.$resObj->status.']<br/>';
          //  $GroupSet = 
          //  echo "<pre>"; print_r($resArr); echo "</pre>";
            
            
            
         //   $xml = Simplexml_load_string($res);
         //   echo $res;
         //    dd($xml);
         // echo ("<H1>TESTING</H1>");
          if(!$resObj->success){
              echo $resObj->status; 
              TabulaAPI::LogErrors($resObj); // Log Failed Request
          } else {
              //echo $resObj->status; 
          //    echo Simplexml_load_string($resObj);
          }

         */
        } catch (Exception $e) {
            echo ('Error message (if any): '.curl_error($ch).'\n\n');
            echo ("<p>");
            var_dump(curl_getinfo($ch)); 
            echo ("</p><p>");
            var_dump($res); 
            echo ("</p>");   
        }
        curl_close($ch);
        return($res);
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