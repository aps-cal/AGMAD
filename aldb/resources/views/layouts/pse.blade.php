<!DOCTYPE html/>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" ></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               
            }
        });
    </script>
    <title>{{ config('app.name', 'Laravel') }}</title>
   
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pse.css') }}" rel="stylesheet">
    <link href="{{ asset('css/menu.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tabula.css') }}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/f57f94c42a.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="menubar">
    <ul class="menu-level-1">
        <li class="">
            <a href="/ps/"><b>PSE</b></a>
        </li>        
        <li class="">
            <a href="">Admin</a>
            <ul class="menu-level-2">                
            	<li class="">
                    <a href="/sits/applications">List Applications</a>
                </li>
                <li class="">
                    <a href="/sits/listsitsstudents">List SITS Students</a>
                </li>
                <li class="">
                    <a href="/sits/loadstudents">Load PSE From SITS</a>
                </li>
                <li class="">
                    <a href="/ps/editstudents">Edit PSE Students</a>
                </li>                 
            </ul>
        </li>       
         <li class="">
            <a href="">Manager</a>
            <ul class="menu-level-2">     
                 <li class="">
                    <a href="/ps/edittutors">Edit Tutors</a>
                </li>
                <li class="">
                    <a href="/ps/editgroups">Edit Groups</a>
                </li>
                <li class="">
                    <a href="">Tutor Group Allocation</a>
                </li>
		<li class="">
                    <a href="/ps/allocatestudents">Student Group Allocation</a>
                </li>
            </ul>
        </li>       
         <li class="">
            <a href="">Tutor</a>
            <ul class="menu-level-2">
                <li class="">
                    <a href="">Class Registers</a>
                </li>              
                <li class="">
                    <a href="">Assessments</a>
                    <ul class="menu-level-3">
                		<li class="">
                    		<a href="/ps/assesslns">LNS Classroom</a>
                		</li>              
                		<li class="">
                    		<a href="/ps/assesstbs">TBS Classroom</a>
                		</li>
                		<li class="">
                    		<a href="/ps/assess">Test Results Entry</a>              
                		</li>
            		</ul>                   
                </li>
                <li class="">
                    <a href="">Personal Tutees</a>              
                </li>
            </ul>
        </li>        
        <li class="">
            <a href="">Reports</a>
            <ul class="menu-level-2">
                <li class="">
                    <a href="">Attendence</a>  
                </li>
                <li class="">
                    <a href="">Assessment</a>  
                </li>
                <li class="">
                    <a href="">Feedback</a>  
                </li>
            </ul>
        </li>  
        <li class="">
            <a href="">SysAdmin</a>
            <ul class="menu-level-2">
                <li class="">
                    <a href="/ps/userroles">User Roles</a>  
                </li>
                <li class="">
                    <a href="/ps/pageroles">Page Access</a>  
                </li>
                <li class="">
                    <a href="/ps/useraccess">User Access</a>  
                </li>
            </ul>
        </li>               
    </ul>    
</div> 

    <div id="app">
        
        <main>
            @yield('content')
        </main>
    </div>
</body> 
@yield('script')
</html>


