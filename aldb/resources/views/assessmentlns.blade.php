@extends('layouts.pse')
@section('content')
<div style="margin:10px;">    
    <h3>LNS Classroom Assessment</h3>
{{ Form::open(array($pageURL)) }}
    <div id="Request"></div>
<div>
<table data-popout="false" data-wrapper="true"  class="Striped">
<thead>   
    <tr>
        <th colspan="3">   
    Year:  
    <select name="Year" id="Year" class="Filter" onchange="this.form.submit();">
    @foreach($years as $y)<option @if($year == $y->Year) Selected @endif >{{$y->Year}}</option>
    @endforeach
    </select>

    Group_No: 
    <select name="Group_No" id="Group_No" class="Filter" onchange="this.form.submit();">
    @foreach($groups as $g)<option @if($group == $g->Group_No) Selected @endif >{{$g->Group_No}}</option>
    @endforeach          
    </select>
        </th>
        <th colspan="4">Tutor:@if($tutor){{$tutor}}@endif</th>
        </tr>
<tr id="Heading">
    <th><input Name="RowID" type="radio" value="" onclick="listStudents();"></th>
    <th id="hd_student_no"><input type="button" value="Student_No" onclick="reorder('Student_No');" style="width: 100%;" /></th>
    <th id="hd_surname"><input type="button" value="Family_Name" onclick="reorder('Family_Name');" style="width: 100%;" /></th>
    <th id="hd_first_name"><input type="button" value="First Name" onclick="reorder('First_Name');" style="width: 100%;" /></th>
    <th id="hd_listening"><input type="button" value="Listening" onclick="reorder('Listen_Class');" style="width: 100%;" /></th>
    <th id="hd_speaking"><input type="button" value="Speaking" onclick="reorder('Speak_Class');" style="width: 100%;" /></th>
    <th id="hd_seminar"><input type="button" value="Seminar" onclick="reorder('Seminar');" style="width: 100%;" /></th>
</tr>
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
<tbody id="GroupStudents">
    
</tbody>
<tfoot id="LNSReport">
    
</tfoot>  
</table>
</div>
 {{ Form::close() }}
<div id="display"></div>    
</div>
@endsection

@section('script')
<script>
//Load variables and arrays
//var groups = <?php echo json_encode($groups, JSON_OBJECT_AS_ARRAY); ?>;
var students = [];<?php //echo json_encode($students, JSON_OBJECT_AS_ARRAY); ?>;
var grades = ['Dist.','Merit','Pass','B.S.'];
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
    $("#GroupStudents").html("<tr><td colspan=6><H6> &nbsp; Loading Data ... Please Wait &nbsp; </h6></td><tr>");
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
            //alert(students[1]["Family_Name"]);
 
            //reorderStudents('student_no'); // Order Students by Student Number 
            listLNS();
        }, 
        fail: function(responseTxt, statusTxt, xhr){
            if(statusTxt === "success")
                alert("External content loaded successfully!");
            if(statusTxt === "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        }
    });
};

function listLNS(){
    var html = "";
    for (var i=0; i<students.length;++i) {
        html=html + "<tr id=\"sn"+students[i]["Student_No"]+"\" >"
            +"<td><input Name=\"RowID\" type=\"radio\" value=\""+students[i]["RowID"]+"\" "
            +"onclick=\"editLNS("+nvl(students[i]["Student_No"])+","+i+");  \" ></td>"
             +'<td>'+nvl(students[i]['Student_No'])+'</td>'
            +'<td>'+nvl(students[i]['Family_Name'])+'</td>'
            +'<td>'+nvl(students[i]['First_Name'])+'</td>'
            +'<td>'+nvl(students[i]['Listen_Class'])+'</td>'
            +'<td>'+nvl(students[i]['Speak_Class'])+'</td>'
            +'<td>'+nvl(students[i]['Seminar'])+'</td>'
            +'</tr>'; 
    }
    $("#GroupStudents").html(html); 
    html="<tr><th colspan=7 wrap><h6><b>LNS Report</b></h6></th></tr>";
    html+="<tr><td colspan=7 wrap><textarea style=\"width:"+$("#LNSReport").width()
            +"px;\" rows=5 readonly>Select a student to view/edit reports</textarea></td></tr>";
    html+="<tr><td colspan=5></td>"
            +"<td><input width=100% type=\"button\" value=\"Cancel\" onclick=\"listStudents();\" disabled></td>"
            +"<td><input width=100% type=\"button\" value=\"Save\" onclick=\"saveRecord("+i+");\" disabled></td>" 
            +"</tr>";
    $("#LNSReport").html(html); 
}   

function nvl(val){
    return((val==null || typeof val == 'undefined'?'':val));
}
function selectRow(obj){
    $(".Selected").removeClass('Selected');
    $(obj).addClass('Selected'); 
}
function editLNS(sn,i){
    listLNS();
    var html="<td><input name=\"RowID\" type=\"radio\" value=\""+students[i]["RowID"]+"\" Checked></td>"
        +'<td>'+nvl(students[i]["Student_No"])+'</td>'
        +'<td>'+nvl(students[i]["Family_Name"])+'</td>'
        +'<td>'+nvl(students[i]["First_Name"])+'</td>'
        +'<td><select name="Listen_Class" onchange="recordChanged=true;">' // style="display:none;"
        +listGrades(nvl(students[i]["Listen_Class"]))+"</select></td>"
        +'<td><select name="Speak_Class" onchange="recordChanged=true;" >'
        +listGrades(nvl(students[i]["Speak_Class"]))+"</select></td>"
        +'<td><select name="Seminar" onchange="recordChanged=true;" >'
        +listGrades(nvl(students[i]["Seminar"]))+"</select></td>";
    $("#sn"+sn).html(html); 
    selectRow($("#sn"+sn));
    html="<tr><th colspan=7 wrap><h6><b>LNS Report</b></h6></th></tr>";
    html+="<tr><td colspan=7 wrap><textarea name=\"LNS_Report\" style=\"width:"+$("#LNSReport").width()+"px;\" rows=5 onchange=\"recordChanged=true;\">"
            +nvl(students[i]["LNS_Report"])+"</textarea></td></tr>";
    html+="<tr><td colspan=5></td>"
            +"<td><input width=100% type=\"button\" value=\"Cancel\" onclick=\"listStudents();\"></td>"
            +"<td><input width=100% type=\"button\" value=\"Save\" onclick=\"saveLNS("+i+");\"></td>" 
            +"</tr>";
    $("#LNSReport").html(html); 
}      
function listGrades(val){
    html="<option/>";
    for (var i=0; i<grades.length;++i) {
        html+="<option "+(grades[i]===val?"selected":"")+">"
            +grades[i]+"</option>";
    } 
    return(html);
}   
 

function reorderStudents(field){
    // Function needs to be run twice as otherwise previous sort is reversed
    students = students.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    students = students.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    listStudents();
}
    
function saveLNS(row){ 
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
            listStudents();
     }, 
        fail: function(responseTxt, statusTxt, xhr){
            console.log(responseText);
            alert('Update Failed!');
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
};
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
    listLNS();
}
</script>

@endsection