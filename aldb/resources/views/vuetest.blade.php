<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>VUE Testing Page</title>

        <!-- Fonts 
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <script type="application/javascript" src="../assets/js/psassess.js"></script>
         
        <style>
              tr:nth-of-type(even) {
              background-color:#ccc;
            }
        </style>-->
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
        <div id="test">
            <p>{{test}}</p>
        </div>
        <script src="/js/vue.js"></script>
        <script>
            new Vue({
                el: '#test',
                data: {test: 'Hello World - Test Complete!' }
                
            });
        </script>
        <!--
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
                    </tr>

                    @foreach ($assessments as $assessment) 
                    <tr>
                        <td>{{$assessment->Student_No}}</td>
                         <td>{{$assessment->First_Name}}</td>
                          <td>{{$assessment->Family_Name}}</td>
                          @foreach ($topics as $topic)
                          
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
                    
             </div>
        </div>-->
    </body>
</html>
