<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pre-Sessional Assessment</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!--<script type="application/javascript" src="../assets/js/psassess.js"></script>-->
         
        <style>
              tr:nth-of-type(even) {
              background-color:#ccc;
            }
        </style>
    </head>
    

    <body>
        <div id="app">
                <psmarks></psmarks>
        </div>
        <script type="text/javascript" src="ps/js/app.js"></script>
        {{$assessments}}
        <div class="flex-center position-ref full-height">
            @if(Route::has('login'))
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
                <h1>Pre-sessional English</h1>
                          
             </div>
        </div>
    </body>
</html>
