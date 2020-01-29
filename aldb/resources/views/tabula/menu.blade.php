@extends('layouts.tabula')
@section('content')
<?php
    $thisYear = '18/19';
    



?>
<div>
    <h2> This is Tabula Content </h2>
    
    {{ Form::open(array('route' => 'tabula.set')) }}
    
    
    Academic Year <select name="academicYear" id="academicYear" class="Filter" onchange="this.form.submit();">
    @foreach($aYears as $y) 
        <option
        @if($thisYear == $y->academicYear) Selected @endif >{{$y->academicYear}}</option>
    @endforeach
    </select>
    Module <select name="Module" id="Module" class="Filter" onchange="this.form.submit();">
    @foreach($Modules as $m) 
        <option
        @if($Module == $m->Module) Selected @endif >{{$m->Module.' - '.$m->Module_Name}}</option>
    @endforeach
    </select>
    
    
    
    
    {{ Form::close() }}
  
    
    
</div>
@endsection