@extends('layouts.tabula')
@section('content')
<?php
   



?>
<div>
    <h2> This is Tabula Content </h2>
    
    {{ Form::open(array('route' => 'tabula.set')) }}
    
    
    Academic Year <select name="academicYear" id="academicYear" class="Filter" onchange="this.form.submit();">
    @foreach($aYears as $y)<option @if($aYear == $y->academicYear) Selected @endif >{{$y->academicYear}}</option>
    @endforeach
    </select>
    Module <select name="Module" id="Module" class="Filter" onchange="this.form.SetID = null; this.form.submit();">
    @foreach($Modules as $m) <option value="{{$m->Module}}" @if($Module == $m->Module) Selected @endif >{{$m->Module}}</option>
    @endforeach
    </select>
    SetID <select name="SetID" id="SetID" class="Filter" onchange="this.form.submit();">
    @foreach($Sets as $s) <option value="{{$s->id}}" @if($SetID == $s->id) Selected @endif >{{$s->name}}</option>
    @endforeach
    </select>
    @if($Sets[0]->groups) 
    Module <select name="GroupID" id="Module" class="Filter" >
    @foreach($Sets[0]->groups as $g) <option value="{{$g->id}}" @if($GroupID == $g->id) Selected @endif >{{$g->name}}</option>
    @endforeach
    </select>
    
    <table>
        <tr><th>Events</th> <th> Students </th></tr>   
        <tr><td id="Events"></td> <td id="Students"></td></tr>
    </table>
    
    
    
    @endif
    @if($Sets[0]->groups[0]) 
        @foreach($Sets[0]->groups as $g) 
        <h1> Group {{$g->name}}</h1>
            <h2>Events</h2>
            <table border="1">
                <tr><th>Event</th><th>Weeks</th><th>Times</th><th>Location</th><th>Tutors</th></tr>
            @foreach($g->events as $e) 
                <tr><td>{{$e->title}}</td>
                <td>@foreach($e->weeks as $w){{$w->minWeek}}-{{$w->maxWeek}}, @endforeach</td>
                <td>{{$e->startTime}} - {{$e->endTime}}</td>
                <td>{{$e->location->name}}</td>
                <td>@foreach($e->tutors as $t){{$t->tutors[0]->userId}}({{$e->tutors[0]->universityId}}), @endforeach</td></tr>
            @endforeach
            </table>
            <h2>Students</h2>
            @foreach($g->students as $s) 
            {{$s->userId}} / {{$s->universityId}}<br/>
            @endforeach
        @endforeach
    @endif
    
    
    {{ Form::close() }}
  
    
    
</div>
@endsection

@section('script')
<script type="text/javascript">// <![CDATA[
 $(document).ready(function(){
     data = getdata('https://agmad.lnx.warwick.ac.uk/api/tabula/set');
     alert('Page loaded!');
     
     $.fn.displayEvents = function(events) {
        var row = '';
        $('#Events').empty();
        for (index = 0; index < events.length; ++index) {
            event = events[index];
            row = 
                row = '<tr id="Summary">'
                +'<td align="left"><input name="AL_Tag" value="'+cpu['ALTAG']+'" type="radio"/>'
                + '  '+cpu['Status'] + '</td>' 
                + '<td>'+cpu['Location'] + '</td>'
                + '<td>'+cpu['User'] + '</td>'
        + '<td>'+cpu['Type'] + '</td>'
                + '<td>'+cpu['Make'] + '</td>'
        + '<td>'+cpu['Model'] + '</td>'
                + '<td>'+cpu['Serial_No'] + '</td>'
                + '<td>'+cpu['Computer_Name'] + '</td>'
        //+ '<td>'+cpu['Tag_No'] + '</td>'		
        +'</tr>';
                $('#Computers').append(rowSummary);	
        }
        $("input:radio").click(function(){getEquip();});
    };
     
     
     
     
     
 }
 // ]]></script>
@endsection