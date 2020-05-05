<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Class Allocation</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!--<script type="application/javascript" src="../assets/js/psassess.js"></script>-->
         
        <style>
              tr:nth-of-type(even) {
              background-color:#ccc;
            }
        </style>
    </head>
    
<?php
   // $years =  ['2017','2018'];
   // $groups = ['01','02','03','04','05','06','07','02','03','01','02','03','01','02','03',];
    //$topics = ['TL','TR','TW','TS','CL','CR','CW','CS','PW','PS'];
    /*
    $topics = ['Listening','Reading','Writing','Speaking','Performance', 'Library_Project', 
                'Classroom_listening', 'Classroom_Writing', 'Classroom_Reading', 'Presentation'];
    
    $grades = ['Dist.','Merit','Pass','Fail'];
    $marks[0]= ['id'=>1000000,'first'=>'John','last'=>'Smith',
        'TL'=>'Dist.','TR'=>'Merit','TW'=>'Pass','TS'=>'Fail',
        'CL'=>'Dist.','CR'=>'Merit','CW'=>'Pass','CS'=>'Fail',
        'PW'=>'Dist.','PS'=>'Merit'];
    $marks[1]= ['id'=>1000001,'first'=>'Jane','last'=>'Doe',
        'TL'=>'Dist.','TR'=>'Merit','TW'=>'Pass','TS'=>'Fail',
        'CL'=>'Dist.','CR'=>'Merit','CW'=>'Pass','CS'=>'Fail',
        'PW'=>'Dist.','PS'=>'Merit'];
    
    
    /*
     * 
     * NOTE: There is currently no form tag in this HTML
     * 
     * 
     * 
     * 
     */
?>
    <body>
        
        <script type="text/javascript" src="ps/js/app.js"></script>
  
         {{ Form::open(array('route' => 'sits.classallocation')) }}
            <div class="content">
                <table border="1" >
                    <tr>
                        <th colspan=2>Academic Year 
                            <select name="Academic_Year" id="Academic_Year" class="Filter" onchange="this.form.submit();">
                                @foreach($acadYears as $yearItem) 
                                <option
                                    @if ($acadYear == $yearItem->Academic_Year)
                                         Selected
                                    @endif>{{$yearItem->Academic_Year}}</option>
                                @endforeach
                            </select></th>
                            <th>Phase 
            
                             <select name="Phase" id="Phase" class="Filter" onchange="this.form.submit();">
                                @foreach ($phases as $p) 
                                <option
                                    @if ($phase == $p->Phase_No)
                                         Selected
                                    @endif>{{$p->Phase_No}}</option>
                                @endforeach
                             </select></th>
                             
                             <th>Group 
            
                             <select name="Group" id="Group" class="Filter" ">
                                @foreach ($groups as $groupitem) 
                                <option
                                    @if ($group == $groupitem->Group_No)
                                         Selected
                                    @endif>{{$groupitem->Group_No}}</option>
                                @endforeach
                             </select></th>
                             
                    </tr>
                    <tr>
                         <th>acad_year</th>
                         <th>student_no</th>
                         <th>surname</th>
                         <th>first_name</th>
                         <th>title</th>
                         <th>inits</th>
                         <th>name</th>
                         <th>dob</th>
                         <th>gender</th>
                         <th>home_email</th>
                         <th>uni_email</th>
                         <th>mobile</th>
                         <th>its_login</th>
                         <th>domicile</th>
                         <th>birth_place</th>
                         <th>nationality</th>
                         <th>programme</th>
                         <th>route</th>
                         <th>course</th>
                         <th>stage</th>
                         <th>dept_code</th>
                         <th>spr_code</th>
                         <th>phase_4</th>
                         <th>phase_5</th>
                         <th>firm</th>
                         
                    </tr>
                
                <?php $Count = 0;?>
                @foreach ($sits_students as $student) 
                    <tr>
                        <td>{{$student->acad_year}}</td>
                        <td>{{$student->student_no}}</td>
                        <td>{{$student->surname}}</td>
                        <td>{{$student->first_name}}</td>
                        <td>{{$student->title}}</td>
                        <td>{{$student->inits}}</td>
                        <td>{{$student->name}}</td>
                        <td>{{$student->dob}}</td>
                        <td>{{$student->gender}}</td>
                        <td>{{$student->home_email}}</td>
                        <td>{{$student->uni_email}}</td>
                        <td>{{$student->mobile}}</td>
                        <td>{{$student->its_login}}</td>
                        <td>{{$student->domicile}}</td>
                        <td>{{$student->birth_place}}</td>
                        <td>{{$student->nationality}}</td>
                        <td>{{$student->programme}}</td>
                        <td>{{$student->route}}</td>
                        <td>{{$student->course}}</td>
                        <td>{{$student->stage}}</td>
                        <td>{{$student->dept_code}}</td>
                        <td>{{$student->spr_code}}</td>
                        <td>{{$student->phase_4}}</td>
                        <td>{{$student->phase_5}}</td>
                        <td>{{$student->firm}}</td>
                    </tr>
                    <?php $Count++; ?>
                    @endforeach
                </table> Students <?=$Count; ?> <br/>   
            
                <table border="1" >
                    
                    <tr>
                         <th>Acad_year</th>
                         <th>Student_No</th>
                         <th>Family_Name</th>
                         <th>First_Name</th>
                         
                         
                    </tr>
                
                <?php $Count = 0; ?>
                @foreach ($pse_students as $student) 
                    <tr>
                        <td>{{$student->Acad_Year}}</td>
                        <td>{{$student->Student_No}}</td>
                        <td>{{$student->Family_Name}}</td>
                        <td>{{$student->First_Name}}</td>
                        
                    </tr>
                    <?php $Count++; ?>
                    @endforeach
                </table> Students <?=$Count; ?> <br/>   
            
                      
             </div>
        {{ Form::close() }}
    </body>
</html>
