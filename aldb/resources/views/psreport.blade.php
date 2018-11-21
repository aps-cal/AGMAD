<!DOCTYPE HTML>
<html lang="{{ app()->getLocale() }}">
    <head>
        <base href="/">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pre-Sessional Reporting</title>
    <!--    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"/> -->

     <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
     <!--  <script type="application/javascript" src="../../public/js/psreport.js"></script>
       <script type="text/javascript" src="{!! asset('/assets/js/psreport.js') !!}"></script>-->
        
        <style>
            tr.Reports{
              background-color:#ccc;
            }
          
        </style>
        <script>
/*           var vm = new Vue({
                       
                   }) 
            

 * $(document).ready(function(){
    alert ('Fired!');
    $('#Reports').hidden=true;
});
 
$('.Student').mouseover(function (){
    $('.Reports').hidden=false;
});
    
$('.Student').mouseout(function (){
    $('.Reports').hidden=true;
});     
    */</script>
    </head>
    
<?php
 
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
   
        
        {{$reports}}
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
                        <th colspan=4>Students  </th>  
                        <th >Year 
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
                         
                          
                          
                    </tr>
                    <tr>
                        <th colspan=3 class='Students'>Students </th>
          
                        <th colspan=3></th>
                          
                         
                    </tr><!--
                    'SELECT Student_No, Family_Name, First_Name, TBS_Report, LNS_Report,' 
                .'Listening, Reading, Writing,Speaking, Performance, Library_Project, '
                .'Classroom_listening, Classroom_Writing, Classroom_Reading, Presentation '
                .'FROM Students '
                .'WHERE Phase_2_Group = :Group_No '
                .'AND Presessional_Year = :Presessional_Year '
                .'ORDER BY Family_Name, First_Name', -->

                    @foreach ($reports as $report) 
                    <tr class="Student" id='Student-{{$report->Student_No}}' @mouseenter="rpt.Show(this)" @mouseleave="rpt.Hide(this)">
                        <td>{{$report->Student_No}}</td>
                         <td>{{$report->First_Name}}</td>
                          <td>{{$report->Family_Name}}</td>
                          <td><input type="checkbox" name='LNS-{{$report->Student_No}}' 
                                     @if ($report->LNS == 1) 
                                     Checked 
                                     @endif >LNS Report
                          </td>
                          <td><input type="checkbox" name='TBS-{{$report->Student_No}}' 
                                     @if ($report->TBS == 1) 
                                     Checked 
                                     @endif >TBS Report
                          </td>
                          <td>
                              
                          </td>
                    </tr>
            
                    <tr class="Report" id='Reports-{{$report->Student_No}}' >
                        <td colspan=3 width='50%'><h4>LNS Report  [Tutor Name]</h4>{{$report->LNS_Report}}
                        </td>
                        <td colspan=3 width='50%'><h4>TBS Report  [Tutor Name]</h4>{{$report->TBS_Report}}
                        </td>
                    </tr>
                    @endforeach
               
                </table>            
             </div>
        </div>
        @yield('js') 
    </body>
    <script>
        /*
        rpt = new Vue({
            el:'.Report',
            data: visible = false,
            methods:{
                Show: function(e){
                    visible = true
                },
                Hide: function(e){
                    visible = false
                }
                
            }
        })
        */
    </script>
  
</html>

    
