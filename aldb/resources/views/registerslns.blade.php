@extends('layouts.pse')
@section('content')
<div style="margin:10px;">    
    <h3>LNS Class Registers</h3>
{{ Form::open(array($pageURL)) }}
    <div id="Request"></div>
<div>
<table data-popout="false" data-wrapper="true"  class="Striped">
<thead>   
    <tr>
        <th colspan="14">   
    Year:  
    <select name="Year" id="Year" class="Filter" onchange="this.form.submit();">
    @foreach($years as $y)<option @if($year == $y->Year) Selected @endif >{{$y->Year}}</option>
    @endforeach
    </select>
    Phase: 
    <select name="Phase" id="Year" class="Filter" onchange="this.form.submit();">
    @foreach($phases as $p)<option @if($phase == $p->Phase_No) Selected @endif >{{$p->Phase_No}}</option>
    @endforeach
    </select>
    Week: 
    <select name="Week" id="Year" class="Filter" onchange="this.form.submit();">
    @foreach($weeks as $w)<option @if($week == $w->Week_No) Selected @endif >{{$w->Week_No}}</option>
    @endforeach
    </select>
    Group_No: 
    <select name="Group_No" id="Group_No" class="Filter" onchange="this.form.submit();">
    @foreach($groups as $g)<option @if($group == $g->Group_No) Selected @endif >{{$g->Group_No}}</option>
    @endforeach          
    </select>
    Module: 
    <select name="Module" id="Module" class="Filter" onchange="this.form.submit();">
        <option @if($module == "LNS") Selected @endif >LNS</option>
        <option @if($module == "TBS") Selected @endif >TBS</option>       
    </select>
    Tutor:@if($tutor){{$tutor}}@endif
    </th>
    <tr>
    <!--<th><input Name="RowID" type="radio" value="" onclick="listStudents();"></th>-->
        <th colspan="4">
        <input name="Student_No" id="Student_No" type="hidden" value="">
        <input name="Day_No" id="Day_No" type="hidden" value="">
        <input name="Class" id="Class" type="hidden" value="">
        <input name="Status" id="Status" type="hidden" value="">
        <input name="Note" id="Note" type="hidden" value="">
    </th>
   
    <th colspan="2">Mon</th>
    <th colspan="2">Tue</th>
    <th colspan="2">Wed</th>
    <th colspan="2">Thu</th>
    <th colspan="2">Fri</th>
    </tr>
<tr id="Heading">
    
    <th id="hd_student_no"><input type="button" value="Student_No" onclick="reorder('Student_No');" style="width: 100%;" /></th>
    <th id="hd_surname"><input type="button" value="Family_Name" onclick="reorder('Family_Name');" style="width: 100%;" /></th>
    <th id="hd_first_name"><input type="button" value="First Name" onclick="reorder('First_Name');" style="width: 100%;" /></th>
    <th> &nbsp; &nbsp; </th>
    <th>work &nbsp; </th><th>class &nbsp; </th>
    <th>work &nbsp; </th><th>class &nbsp; </th>
    <th>work &nbsp; </th><th>class &nbsp; </th>
    <th>work &nbsp; </th><th>class &nbsp; </th>
    <th>work &nbsp; </th><th>class &nbsp; </th>
</tr>
<!--<tr id="Heading">
    <th><input Name="RowID" type="radio" value="" onclick="listStudents();"></th>
    <th id="hd_student_no"><input type="button" value="Student_No" onclick="reorder('Student_No');" style="width: 100%;" /></th>
    <th id="hd_surname"><input type="button" value="Family_Name" onclick="reorder('Family_Name');" style="width: 100%;" /></th>
    <th id="hd_first_name"><input type="button" value="First Name" onclick="reorder('First_Name');" style="width: 100%;" /></th>
    <th colspan="2">Mon</th>
    <th colspan="2">Tue</th>
    <th colspan="2">Wed</th>
    <th colspan="2">Thu</th>
    <th colspan="2">Fri</th>
</tr>->
<!--
<tr>
    <td></td>
    <td><select class="nofilter" name="qryStudent_No" id="qryStudent_No" onChange="filter();">
        <option></option>
        </select></td>
        <td><select class="nofilter" name="qrySurname" id="qrySurname" onChange="filter();">
        <option></option>
        </select></td>
    <td><select class="nofilter" name="qryFirst_Name" id="qryFirst_Name" onChange="filter();">
        <option></option>
        </select></td>
    <td><select class="Filter" name="qrListen_Class" id="qryListen_Class" onChange="filter();">
    <option></option>
    </select></td>
    <td><select class="Filter" name="qrySpeak_Class" id="qrySpeak_Class" onChange="filter();">
    <option></option>
    </select></td>
    <td><select class="Filter" name="qrySeminar" id="qrySeminar" onChange="filter();">
    <option></option>
    </select></td>
    
</tr>
-->
</thead>
<tbody id="Register">
    
</tbody>  
</table>
    <div id="Message"></div>
</div>
 {{ Form::close() }}
<div id="display"></div>    
</div>
@endsection

@section('script')
<script>
//Load variables and arrays
var students = [];
var group = '01'; // Default Group

// Launch when document ready 
$(document).ready(function(){
    if($("#Group_No").val()===''){
        $("#Group_No").val(group);
    }
    listStudents();
});  

function listStudents() {
    var formData =  $("form").serialize();
    $("#Register").html("<tr><td colspan=6><H6> &nbsp; Loading Data ... Please Wait &nbsp; </h6></td><tr>");
    $.ajax({
        method: 'GET',
        url: '{{$selectURL}}',
        data: formData,
        dataType: 'json',
        crossDomain: false,
        timeout: 0,
        jsonp: 'data',
        jsonpCallback: 'data',
        success: function(data){  
            students = data.students;
            showRegister();
        }, 
        fail: function(responseTxt, statusTxt, xhr){
            if(statusTxt === "success")
                alert("External content loaded successfully!");
            if(statusTxt === "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        }
    });
};

function showRegister(){
    var html = "";
    var classes = ['AM','PM'];
    for (var i=0; i<students.length;++i) {
        html+="<tr >"
            //+"<td><input Name=\"RowID\" type=\"radio\" value=\""+students[i]["RowID"]+"\" "
            //+"onclick=\"editLNS("+nvl(students[i]["Student_No"])+","+i+");  \" ></td>"
            +'<td>'+nvl(students[i]['Student_No'])+'</td>'
            +'<td>'+nvl(students[i]['Family_Name'])+'</td>'
            +'<td>'+nvl(students[i]['First_Name'])+'</td>'
            +'<td></td>';
        for(var d=1; d<6; ++d){ 
            for(var c=0; c<2; ++c){
                if(i<students.length){
                html+="<td><i onclick=\"markRegister(this,"
                    +"'"+students[i]["Student_No"]+"'"
                    +",'"+d+"','"+classes[c]+"',"
                    + "'"+students[i]["None"]+"'"
                    +");\" ";
                if(students[i]["Status"]==null || students[i]["Status"]==''){
                    html+='class="fas fa-minus"';
                } else if(students[i]["Status"]=='Present'){
                    html+='class="fas fa-check"';
                } else if(students[i]["Status"]=='Abscent'){
                    html+='class="fas fa-times"';
                } else if(students[i]["Status"]=='Approved'){
                    html+='class="fas fa-check-circle"';
                } else {
                    html+='class="fas fa-minus"';
                }    
                html+="></i>"
                    //+students[i]['Day_No']+"&nbsp;"+students[i]['Class']
                    +"</td>";
            }
                i++;
            }
        }
        --i;
        html+="</tr>"; 
    }
    $("#Register").html(html); 
}   
function nvl(val){
    return((val==null || typeof val == 'undefined'?'':val));
}
function markRegister(obj, stu, day, cls, note){
    var status = '';
    if($(obj).hasClass('fa-minus')){
        $(obj).removeClass('fa-minus');
        $(obj).addClass('fa-check');
        status = 'Present';
    } else if($(obj).hasClass('fa-check')){
        $(obj).removeClass('fa-check');
        $(obj).addClass('fa-times red');
         status = 'Absent';
    } else if($(obj).hasClass('fa-times')){
        $(obj).addClass('fa-check-circle');
        note = prompt("Please enter a note", "Authorised Absence -  Reason given:");
        if(note == null || note == ""){
          alert("A note is required for authorised absence");
        } else {
            $(obj).removeClass('fa-times');
            $(obj).addClass('fa-check-circle');
            status = 'Authorised';
        }
    } else if($(obj).hasClass('fa-check-circle')){
        if(note){
             note = prompt("Please remove note", note);
             if(note == ""){
                $(obj).removeClass('fa-check-circle');
                $(obj).addClass('fa-minus');
                note = '';
                status = '';
             }
         } else {
              $(obj).removeClass('fa-check-circle');
              $(obj).addClass('fa-minus');
              note = '';
              status = '';
         }
    }
    $("#Student_No").val(stu);
    $("#Day_No").val(day);
    $("#Class").val(cls);
    $("#Status").val(status);
    $("#Note").val(nvl(note));
    updateRegister();
    //$("Student_No").val('');
    //$("Day_No").val('');
    //$("Class").val('');
    //$("Status").val('');
    //$("Note").val('');
}
function updateRegister(){ 
    var formData =  $("form").serialize(); 
    //formData['Student_No']=stu;
    //formData['Day_No']=day;
    //formData['Class']=cls;
    //formData['Status']=status;
    //formData['Note']=note;
    alert(formData);
    $.ajax({
        method: 'GET',
        url: "{{$updateURL}}",
        data: formData,
        dataType: 'json',
        crossDomain: false,
        timeout: 0,
        jsonp: 'data',
        jsonpCallback: 'data',
        success: function(data){  
            //showRegister();
        }, 
        fail: function(responseTxt, statusTxt, xhr){
            console.log(responseText);
            alert('Update Failed!');
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
}

function selectRow(obj){
    $(".Selected").removeClass('Selected');
    $(obj).addClass('Selected'); 
}

function reorderStudents(field){
    // Function needs to be run twice as otherwise previous sort is reversed
    students = students.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    students = students.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    showRegister();
}
    
function updateRegister(){ 
    //var data = [];
    //data['RowID'] = $('RowID').val();
    //data['Listen_Class'] = $('Listen_Class').val();
    //data['Speak_Class'] = $('Speak_Class').val();
    //data['Seminar'] = $('Seminar').val();
    //data['LNS_Report'] = $('LNS_Report').val();
    var formData =  $("form").serialize();
    //alert(formData);
    $.ajax({
        type: "GET",
        url: "{{$updateURL}}",
        data: formData,
        jsonpCallback: 'data',
        success: function( data ) {
            showRegister();
        }, 
        fail: function(responseTxt, statusTxt, xhr){
            console.log(responseText);
            alert('Update Failed!');
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
}
function aOptions(opts,value) { // Uses a simple array of values
    var result = '<option/>';
    for (var index = 0; index < opts.length; ++index) {
           val = (opts[index]==null?'[blank]':opts[index]);
        result+="<option>"+val+"</option>"; 
    }
    return(result);
}


function distinctlist(studentlist,field){
    var unique = [];
    var distinct = [];
    for( let i = 0; i < studentlist.length; i++ ){
        if( !unique[studentlist[i][field]]){
            distinct.push(studentlist[i][field]);
            unique[studentlist[i][field]] = 1;
        }
    }
    //alert(distinct.sort());
    return (distinct.sort());
    //var d = document.getElementById("d");
    //d.innerHTML = "" + distinct;
}

function reorder(field){
    // Function needs to be run twice as otherwise previous sort is reversed
    students = students.sort(function(a,b){
        return(a[field]>b[field]?1:-1);
    });
    students = students.sort(function(a,b){
        return(a[field]>b[field]?1:-1);
    });
    showRegister();
}
</script>

@endsection