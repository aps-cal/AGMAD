<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pre-Sessional Assessment</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <script type="application/javascript" src="../assets/js/psassess.js"></script>
         
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
        {{$assessments}}
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif
            <div class="content">
                <!--<h1>Pre-sessional Assessment Entry</h1>-->
                <table border="1" >
                    <tr>
                        <th colspan=2>Year 
                            <select name="Year" id="Year" class="Filter">
                                @foreach($years as $yearitem) 
                                <option
                                    @if ($year == $yearitem->PS_Year)
                                         Selected
                                    @endif>{{$yearitem->PS_Year}}</option>
                                @endforeach
                            </select></th>
                         <th>Group 
                             <select name="Group" id="Group" class="Filter">
                                @foreach ($groups as $groupitem) 
                                <option
                                    @if ($group == $groupitem->Group_No)
                                         Selected
                                    @endif>{{$groupitem->Group_No}}</option>
                                @endforeach
                             </select></th>
                          <th colspan="4" style="text-orientation:upright;">Tests</th>
                          <th colspan="4" style="text-orientation:upright;">Classroom </th>
                          <th colspan="2" style="text-orientation:upright;">Project </th>
                    </tr>
                    <tr>
                        <th colspan=3 >Tutors</th>
                          <th rowspan="1" style="text-orientation:upright;">Listen</th>
                          <th rowspan="1" style="text-orientation:upright;">Read</th>
                          <th rowspan="1" style="text-orientation:upright;">Write</th>
                          <th rowspan="1" style="text-orientation:upright;">Speak</th>
                          <th rowspan="1" style="text-orientation:upright;">Listen</th>
                          <th rowspan="1" style="text-orientation:upright;">Read</th>
                          <th rowspan="1" style="text-orientation:upright;">Write</th>
                          <th rowspan="1" style="text-orientation:upright;">Speak</th>
                          <th rowspan="1" style="text-orientation:upright;">Written</th>
                          <th rowspan="1" style="text-orientation:upright;">Seminar </th>
                    </tr><!--
                    'SELECT Student_No, Family_Name, First_Name, TBS_Report, LNS_Report,' 
                .'Listening, Reading, Writing,Speaking, Performance, Library_Project, '
                .'Classroom_listening, Classroom_Writing, Classroom_Reading, Presentation '
                .'FROM Students '
                .'WHERE Phase_2_Group = :Group_No '
                .'AND Presessional_Year = :Presessional_Year '
                .'ORDER BY Family_Name, First_Name', -->

                    @foreach ($assessments as $assessment) 
                    <tr>
                        <td>{{$assessment->Student_No}}</td>
                         <td>{{$assessment->First_Name}}</td>
                          <td>{{$assessment->Family_Name}}</td>
                          @foreach ($topics as $topic)
                          <!-- NOTE Select objects need a unique name -->
                          <td><select name='{{$topic.$mark['id']}}-{{$assessment->Student_No}}' class="Assess">
                                @foreach ($grades as $grade) 
                                <option 
                                    @if ($grade == $assessment->$topic)
                                         Selected
                                    @endif
                                        >{{$grade}}</option>
                                @endforeach
                              </select>
                          </td>
                          @endforeach
                    </tr>
                    @endforeach
                    <!--
                    <tr>
                        <th colspan='14'>Student: <input name='StudentID' width='10' type='text' readonly>
                        <input name='FirstName' width='20' type='text' readonly>
                        <input name='LastName' width='20' type='text' readonly>
                        </th>
                    </tr>
                    <tr>
                        <th colspan='6'>Test-Based Studies Report </th>
                        <th colspan='8'><input name='TBSTutor' width='40' type='text' readonly></th>
                    </tr>
                    <tr>
                        <th colspan='14'> <textarea name='TBSReport' style="width:98%;" rows=4 maxlength=400 > </textarea></th>
                    </tr>
                     <tr>
                        <th colspan='6'>Listening & Speaking Report </th>
                        <th colspan='8'><input name='LNSTutor' width='40' type='text' readonly></th>
                    </tr>
                    <tr>
                        <th colspan='14'><textarea name='LNSReport' style="width:98%;" rows=4 maxlength=400 ></textarea></th>
                    </tr>-->
                </table>            
             </div>
        </div>
    </body>
</html>
