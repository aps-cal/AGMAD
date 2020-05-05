@extends('layouts.pse')
@section('content')
<h1>{{$data['pageTitle']}}</h1>
<i>{{$data['userMessage']}}</i>
<div>
    <h3>Please Note: </h3>
    <p>Navigation of the new PSE application has changed. 
        There is now a drop-down menu at the top which will appear on all pages. 
        This should make it easier to navigate around the application. </p>
    <p>Currently all users can see all options but some pages are accessible by all users. 
        This can be tailored by Granting or Forbidding access to each page based on the Role assigned to each user. <br/>
        The roles are currently, Admin (Office), Manager (DOS) and Tutor. </p>
    
</div>
<!--
{{ Form::open() }}

<input type="button" value="List SITS/UA Student" class="menubutton" onclick="openInOwnTab('/sits/liststudents');" >
<input type="button" value="List PSE Applications" class="menubutton" onclick="openInOwnTab('/sits/applications');" >
<input type="button" value="Edit PSE Students" class="menubutton" onclick="openInOwnTab('/ps/editstudents');" >
<input type="button" value="Edit Groups" class="menubutton" onclick="openInOwnTab('/ps/editgroups');">
<input type="button" value="Edit Tutors" class="menubutton" onclick="openInOwnTab('/ps/edittutors');">
<input type="button" value="Allocate Student to Groups" class="menubutton" onclick="openInOwnTab('/ps/allocatestudents');">
<input type="button" value="Classroom LNS Assessments" class="menubutton" onclick="openInOwnTab('/ps/assesslns');">
<input type="button" value="Classroom TBS Assessments" class="menubutton" onclick="openInOwnTab('/ps/assesstbs');">
{{ Form::close() }}-->
@endsection
@section('script')<!--
<script>
function openInOwnTab(url) {
  var win = window.open(url, url);
  win.focus();
}
function openInNewTab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}
//Load variables and arrays
//            
// Launch when document ready 
$(document).ready(function(){
   
});   
</script>-->
@endsection