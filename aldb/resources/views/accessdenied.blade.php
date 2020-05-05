@extends('layouts.pse')
@section('content')
<h1>Pre-sessional English</h1>
<i>{{$userMessage}}</i>
<h4>Access to page {{$page}} for role {{$role}} is {{$access}}</h4>
@endsection
@section('script')
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

</script>
@endsection