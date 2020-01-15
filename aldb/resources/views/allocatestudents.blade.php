@extends('layouts.tabula')
@section('content')
<?php
   



?>
<div>
    <h2> This is Local Content </h2>
    
    {{ Form::open(array('/ps/allocatestudents')) }}
    
    
    Academic Year [{{$aYear}}] <select name="academicYear" id="academicYear" class="Filter" onchange="this.form.submit();">
    @foreach($aYears as $y)<option @if($aYear == $y->academicYear) Selected @endif >{{$y->academicYear}}</option>
    @endforeach
    </select>
    Pre-Sessional Year [{{$year}}] <select name="Year" id="Year" class="Filter" onchange="this.form.submit();">
    @foreach($years as $y)<option @if($year == $y->PS_Year) Selected @endif >{{$y->PS_Year}}</option>
    @endforeach
    </select>
    Pre-Sessional Phase [{{$phase}}] <select name="Phase_No" id="Phase_No" class="Filter" onchange="this.form.submit();">
    @foreach($phases as $p)<option @if($phase == $p->Phase_No) Selected @endif >{{$p->Phase_No}}</option>
    @endforeach
    </select>
    <br/>
    <pre>{{var_dump($groups)}}</pre>
    <select name="group" id="group" onchange="this.form.submit();">
    @foreach($groups as $g)<option @if($group == $g->Group_No) Selected @endif >
                                    {{$g->Group_No}} {{$g->Class_Room}} {{$g->TBS_Tutor_Inits}} {{$g->TBS_Tutor_ID}} {{$g->LNS_Tutor_Inits}}  {{$g->LNS_Tutor_ID}} </option>
    @endforeach
        
    </select>
        
 
    <!--
    
    
    
    
    
    
    
    
    
    

    
    -->
    {{ Form::close() }}
  
    
    
</div>
@endsection

@section('script')


@endsection