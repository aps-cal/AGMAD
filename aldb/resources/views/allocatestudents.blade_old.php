@extends('layouts.pse')
@section('content')
<?php
?>


<div style="margin:10px;">    
    <h6>Class Allocation: Students</h6>
{{ Form::open(array('/ps/allocatestudents')) }}

<!--{{ Form::open(array('/ps/allocatestudents')) }}-->  
    <table  width="400px">
        <tr>
            <th colspan="3"><input name="Group_No" id="Group_No" value="{{$group}}" style="width:20px;" />
            Academic Year  <select name="Academic_Year" id="Academic_Year" class="Filter" onchange="this.form.submit();">
    @foreach($acadYears as $a)<option @if($acadYear == $a->Academic_Year) Selected @endif >{{$a->Academic_Year}}</option>
    @endforeach
    </select><!--
    Pre-Sessional Year  <select name="Year" id="Year" class="Filter" onchange="this.form.submit();">
    @foreach($years as $y)<option @if($year == $y->Year) Selected @endif >{{$y->Year}}</option>
    @endforeach
            
            
    </select>-->
            </th>
            <th colspan="2">
    Phase No <select name="Phase_No" id="Phase_No" class="Filter" onchange="this.form.submit();">
    @foreach($phases as $p)<option @if($phase == $p->Phase_No) Selected @endif >{{$p->Phase_No}}</option>
    @endforeach
    </select>
            </th>
        </tr>
        <tr>
            <th width="50px">Group</th> 
            <th width="50px">Room</th>
            <th width="300px">LNS Tutor</th>
            <th width="300px">TBS Tutor</th>
            <th width="50px">Count</th>        
        </tr>
        <tr>        
        </tr>
    </table >
    <div style=" height: 100px; overflow: auto; width:400px">
        <table id="GroupList" width="400px"><!--
            @foreach($groups as $g)
            <tr name="Group{{$g->Group_No}}" onclick="selectGroup('{{$g->Group_No}}',this); " @if($group == $g->Group_No) class="selected" @else class="unselected" @endif >
                <td width="50px">{{$g->Group_No}}</td>
                <td width="100px"> {{$g->Class_Room}} </td>
                <td width="200px"> {{$g->LNS_Tutor_ID}} {{$g->LNS_Tutor_Inits}} </td>
                <td width="200px">{{$g->TBS_Tutor_ID}} {{$g->TBS_Tutor_Inits}}  </td>
                <td width="50px">0</td>
                 
            </tr>
            @endforeach  -->
        </table>
    </div>
        {{ Form::close() }}
</div>
<div style="margin:10px;"><h6>Students in Group</h6> 
<form name="in_group_students" id="In_group_students" action="/" method="post">
<input name="inListOrder" type="hidden" id="inListOrder" value="Location" />
<table data-popout="false" data-wrapper="true">
<thead>
<tr id="Heading">
    <th><input type="button" value="Univ.ID" onclick="reorderStudents('student_no');" style="width: 100%;" /></th>
    <th><input type="button" value="Family Name" onclick="reorderStudents('surname');" style="width: 100%;" /></th>
    <th><input type="button" value="First Name" onclick="reorderStudents('first_name');" style="width: 100%;" /></th>
    <th><input type="button" value="Gender" onclick="reorderStudents('gender');" style="width: 100%;" /></th>
    <th><input type="button" value="Nationality" onclick="reorderStudents('nationality');" style="width: 100%;" /></th>
    <th><input type="button" value="Department" onclick="reorderStudents('dept_code');" style="width: 100%;" /></th>
    <th><input type="button" value="Course" onclick="reorderStudents('course');" style="width: 100%;" /></th>
    <th><input type="button" value="Results" onclick="reorderStudents('results');" style="width: 100%;" /></th>
</tr>
<tr>
    <td><select class="Filter" name="UnivID" id="inUnivID">
        <option></option>
        </select></td>
    <td><select class="Filter" name="inFamily_Name" id="inFamily_Name">
        <option></option>
        </select></td>
    <td><select class="Filter" name="inFirst_Name" id="inFirst_Name">
        <option></option>
        </select></td>
    <td><select class="Filter" name="inGender" id="inGender">
    <option></option>
    </select></td>
    <td><select class="Filter" name="Nationality" id="inNationality">
    <option></option>
    </select></td>
    <td><select class="Filter" name="Department" id="inDepartment">
    <option></option>
    </select></td>
    <td><select class="Filter" name="Course" id="inCourse">
    <option></option>
    </select></td>
    <td><select class="Filter" name="Results" id="inResults">
    <option></option>
    </select></td>
</tr>
</thead>
</table>
<div class="alert alert-success" style="display:none"> </div>
<div style="height: 200px; overflow: auto; width:800px">
    <table id="in_group_students" width="800px">
    </table>
</div>
</form>
</div>

<div style="margin:10px;"><h6>Unplaced Students </h6> 
<form name="No_group_students" id="No_group_students" action="/" method="post">
<input name="NoListOrder" type="hidden" id="NoListOrder" value="Location" />
<table data-popout="false" data-wrapper="true">
<thead>
<tr id="Heading">
    <th><input type="button" value="Univ.ID" onclick="reorderStudents('student_no');" style="width: 100%;" /></th>
    <th><input type="button" value="Family Name" onclick="reorderStudents('surname');" style="width: 100%;" /></th>
    <th><input type="button" value="First Name" onclick="reorderStudents('first_name');" style="width: 100%;" /></th>
    <th><input type="button" value="Gender" onclick="reorderStudents('gender');" style="width: 100%;" /></th>
    <th><input type="button" value="Nationality" onclick="reorderStudents('nationality');" style="width: 100%;" /></th>
    <th><input type="button" value="Department" onclick="reorderStudents('dept_code');" style="width: 100%;" /></th>
    <th><input type="button" value="Course" onclick="reorderStudents('course');" style="width: 100%;" /></th>
    <th><input type="button" value="Results" onclick="reorderStudents('results');" style="width: 100%;" /></th>
</tr>
<tr>
    <td><select class="nofilter" name="noUnivID" id="noUnivID" onChange="filterNoGroup();">
        <option></option>
        </select></td>
        <td><select class="nofilter" name="noFamily_Name" id="noFamily_Name" onChange="filterNoGroup();">
        <option></option>
        </select></td>
    <td><select class="nofilter" name="noFirst_Name" id="noFirst_Name" onChange="filterNoGroup();">
        <option></option>
        </select></td>
    <td><select class="Filter" name="noGender" id="noGender" onChange="filterNoGroup();">
    <option></option>
    </select></td>
    <td><select class="Filter" name="noNationality" id="noNationality" onChange="filterNoGroup();">
    <option></option>
    </select></td>
    <td><select class="Filter" name="noDepartment" id="noDepartment" onChange="filterNoGroup();">
    <option></option>
    </select></td>
    <td><select class="Filter" name="noCourse" id="noCourse" onChange="filterNoGroup();">
    <option></option>
    </select></td>
    <td><select class="Filter" name="noResults" id="noResults" onChange="filterNoGroup();">
    <option></option>
    </select></td>
</tr>
</thead>
</table>
<div style=" height: 200px; overflow: auto; width:800px">
    <table id="no_group_students" width="800px"><!--
            @foreach($students as $s)
            <tr id="n{{$s->student_no}}" onclick="" >
                <td width="50px">{{$s->student_no}}</td>
                <td width="100px"> {{$s->surname}} </td>
                <td width="100px"> {{$s->first_name}} </td>
                <td width="100px"> {{$s->gender}} </td>
                <td width="100px"> {{$s->nationality}} </td>
                <td width="100px"> {{$s->dept_code}} </td>
                <td width="100px"> {{$s->course}} </td>
                <td width="100px"> {{$s->results}} </td>
                <td width="100px"> {{$s->group_no}} </td>
                 
            </tr>
            @endforeach  -->
    </table>
</div>
</form>
<div id="display"></div>    
</div>


@endsection

@section('script')
<script>
var students = <?php echo json_encode($students, JSON_OBJECT_AS_ARRAY); ?>;
var groups = <?php echo json_encode($groups, JSON_OBJECT_AS_ARRAY); ?>;
//alert (groups);
var noGroup = students;
var inGroup = students;
var group = '01';

//alert(typeof(inGroup));
// Launch when document ready 

$(document).ready(function(){
    //inGroup = filterGroup(students,'01');
    //noGroup = filterGroup(students,null);
    reorderStudents('student_no');
    
    //alert(group);
    groupListCount();
    displayGroups();
    filterInGroup();
    displayInGroup();
    filterNoGroup();
    displayNoGroup();
    //reorderInGroup('student_no');
    //reorderNoGroup('student_no');
    
    //students = students.sort(function(a,b){
    //    return(a['first_name']  > b['first_name']?1:-1);
    //})
    //$("#display").html(students);
    //var nofilter = new array();
    //var infilter = new array();
    loadinfilters();
    loadnofilters();
    //  alert('Begin');
});   
/*
function filterGroup(students, group){
    array = students;
    for(var i=0; i < array.length; i++){
        //if (students[i]["group_no"] == group){
        //    filtered.push(students[i]);
        //}
        if (array[i]["group_no"] != group){
            array.splice(i,1);
        }
    } 
    return(array);
}
*/
function reorderStudents(field){
    // Function needs to be run twice as otherwise previous sort is reversed
    students = students.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    students = students.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    filterInGroup();
    displayInGroup();
    filterNoGroup();
    displayNoGroup();
}
function reorderInGroup(field){
    // Function needs to be run twice as otherwise previous sort is reversed
    inGroup = inGroup.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    inGroup = inGroup.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    displayInGroup();
}
function filterInGroup(){
    phase = $("#Phase_No").val();
    group = $("#Group_No").val();
    if(group === ''){
        inGroup = students.filter(function(el){
            return (el.group_no === group && el.phase_no === phase);
    });
   // inGroup = students;
    /*
    for(var i=0; i < inGroup.length; i++){
        //if (students[i]["group_no"] == group){
        //    filtered.push(students[i]);
        //}
        if (inGroup[i]["group_no"] != group){
            inGroup.splice(i,1);
        }
    }  
        */    
}
function filterNoGroup(){
    //surname = 'BARK';
    phase = $("#Phase_No").val();
    noGroup = students.filter(function(el){
        return (el.group_no === '' && el.phase_no === phase);
    });
    //noGroup = students.filter(function(el){
        
    //    return el.group_no == '' ; //&& el.surname == surname; 
     //       && ($("#noUnivID").val() !=''? el.student_no == $("#noUnivID").val():true)
     //       && ($("#noFamily_Name").val()!= '' ? el.surname_no == $("#noFamily_Name").val():true)
     //   );
    //});
    //alert('filtered');
 /*   
    for(var i=0; i < noGroup.length; i++){
        //if (students[i]["group_no"] == null){
        //    filtered.push(students[i]);
        //} 
        if (noGroup[i]["group_no"] != null){
            alert(noGroup[i]["group_no"]);
            noGroup.splice(i,1);
        }
        /*
        var obj = students[i];
        for(var key in obj){
            if(typeof(obj[key] == "object")){
                var item = obj[key];
                if(item[group_no] == null){
                    filtered.push(item);
                }
            }
        }

    }   
  */ 
}

function displayGroups(){
    var html = "";
    for (var i=0; i<groups.length;++i) {   
        html=html + "<tr id=\"Group"+groups[i]["Group_No"]+"\" onclick=\"selectGroup('"+groups[i]["Group_No"]+"',this);\" "
            +""+(groups[i]["Group_No"] == group?"class=\"selected\"":"")+">"
            +"<td width=\"50px\">"+groups[i]["Group_No"]+"</td>"
            +"<td width=\"50px\">"+groups[i]["Class_Room"]+"</td>"
            +"<td width=\"300px\">"+groups[i]["LNS_Tutor_ID"]+" "+groups[i]["LNS_Tutor_Inits"]+"</td>"
            +"<td width=\"300px\">"+groups[i]["TBS_Tutor_ID"]+" "+groups[i]["TBS_Tutor_Inits"]+"</td>"
            +"<td width=\"50px\">"+groups[i]["Students"]+"</td>"
            +"</tr>";
    }
    //alert (groups);
    $("#GroupList").html(html);
}
function displayInGroup(){
    var html = "";
    var count = 0;
    for (var i=0; i<inGroup.length;++i) {
        html=html + "<tr id=\"i"+inGroup[i]["student_no"]+"\" onclick=\"removeGroup('"+inGroup[i]["student_no"]+"');\" >"
            +'<td width="50px">'+inGroup[i]["student_no"]+'</td>'
            +'<td width="100px">'+inGroup[i]["surname"]+'</td>'
            +'<td width="100px">'+inGroup[i]["first_name"]+'</td>'
            +'<td width="100px">'+inGroup[i]["gender"]+'</td>'
            +'<td width="100px">'+inGroup[i]["nationality"]+'</td>'
            +'<td width="100px">'+inGroup[i]["course"]+'</td>'
            +'<td width="100px">'+inGroup[i]["results"]+'</td>'
            +'<td width="100px">'+inGroup[i]["phase_no"]+'</td>'
            +'<td width="100px">'+inGroup[i]["group_no"]+'</td>'
            +'</tr>';
        count++;
    }
    $("#in_group_students").html(html);
    group = $("#Group_No").val();
    var groupIndex = groupListIndex(group);
    if(groupIndex != null){
        //alert(groups[groupIndex]["Students"]);
        groups[groupIndex]["Students"] = count;
    } 
    displayGroups();
}

function groupListCount(){
    for (var s=0; s<students.length;++s) {
        for (var g=0; g<groups.length;++g) {
            if(students[s]["Group_No"] = groups[g]["Group_No"]){
                //alert (groups[g]["Students"]);
                groups[g]["Students"] = groups[g]["Students"]+1;
                break;
            }
        }    
    }
}

function groupListIndex(group){
    var index = null;
    for (var i=0; i<groups.length;++i) {
       if(groups[i]["Group_No"] == group){
           index = i;
           break;
       }
    }
    return(index);
}
function displayNoGroup(){
    var html = ""
    for (var i=0; i<noGroup.length;++i) {
        html=html + "<tr id=\"n"+noGroup[i]["student_no"]+"\" onclick=\"addToGroup('"+noGroup[i]["student_no"]+"');\" >"
            +'<td width="50px">'+noGroup[i]["student_no"]+'</td>'
            +'<td width="100px">'+noGroup[i]["surname"]+'</td>'
            +'<td width="100px">'+noGroup[i]["first_name"]+'</td>'
            +'<td width="100px">'+noGroup[i]["gender"]+'</td>'
            +'<td width="100px">'+noGroup[i]["nationality"]+'</td>'
            +'<td width="100px">'+noGroup[i]["course"]+'</td>'
            +'<td width="100px">'+noGroup[i]["results"]+'</td>'
            +'<td width="100px">'+noGroup[i]["phase_no"]+'</td>'
            +'<td width="100px">'+noGroup[i]["group_no"]+'</td>'
            +'</tr>';
    }
    $("#no_group_students").html(html);
    //alert(noGroup.length);
}
function reorderNoGroup(field){
    // Function needs to be run twice as otherwise previous sort is reversed
    noGroup = noGroup.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    noGroup = noGroup.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    displayNoGroup(noGroup);
}


function addToGroup(student_no){
    //alert(SaveGroup(student_no,$("#Group_No").val()));
    
    //if(
    SaveGroup(student_no,$("#Group_No").val());//){
    //alert(data['Result']);
        i = students.findIndex(item => item.student_no === student_no);
        students[i].group_no = $("#Group_No").val();
        filterInGroup();
        displayInGroup();
        filterNoGroup();
        displayNoGroup();
    //} else {
    //    alert('Student '+student_no+' - Update Failed');
    //}
    //alert (student_no);
}
function removeGroup(student_no){ 
    //if(
    SaveGroup(student_no,null);//){
      //alert(data['Result']);
        i = students.findIndex(item => item.student_no === student_no);
        students[i].group_no = '';
        filterInGroup();
        displayInGroup();
        filterNoGroup();
        displayNoGroup();
    //} else {
    //    alert('Student '+student_no+' - Update Failed');
    //}
    //alert (student_no);
}


$("#in_group-students.tr").click(function(){
    alert('#in_group-students.tr'+this.id);
});

$("select.nofilter").change(function() {
    alert("select.nofilter"+'Changed');
    filterNoGroup();
});





function loadinfilters(){
    // Load Filter Dropdowns 
    var nofilter = [];
    nofilter['UnivID'] = '';
    nofilter['UnivIDs'] = distinctlist(inGroup,'student_no');
    $html = aOptions(nofilter['UnivIDs'],nofilter['UnivID']);
    $("#inUnivID").empty().append($html).val(nofilter['UnivID']);
    nofilter['Family_Name'] = '';
    nofilter['Family_Names'] = distinctlist(inGroup,'surname');
    $html = aOptions(nofilter['Family_Names'],nofilter['Family_Name']);
    $("#inFamily_Name").empty().append($html).val(nofilter['Family_Name']);
    nofilter['First_Name'] = '';
    nofilter['First_Names'] = distinctlist(inGroup,'first_name');
    $html = aOptions(nofilter['First_Names'],nofilter['First_Name']);
    $("#inFirst_Name").empty().append($html).val(nofilter['First_Name']);
    nofilter['Gender'] = '';
    nofilter['Genders'] = distinctlist(inGroup,'gender');
    $html = aOptions(nofilter['Genders'],nofilter['Gender']);
    $("#inGender").empty().append($html).val(nofilter['Gender']);
    nofilter['Nationality'] = 'Japanese';
    nofilter['Nationalities'] = distinctlist(inGroup,'nationality');
    $html = aOptions(nofilter['Nationalities'],nofilter['Nationality']);
    $("#inNationality").empty().append($html).val(nofilter['Nationality']);
    nofilter['Department'] = '';
    nofilter['Departments'] = distinctlist(inGroup,'dept_code');
    $html = aOptions(nofilter['Departments'],nofilter['Department']);
    $("#inDepartment").empty().append($html).val(nofilter['Departments']);
    nofilter['Course'] = '';
    nofilter['Courses'] = distinctlist(inGroup,'course');
    $html = aOptions(nofilter['Courses'],nofilter['Course']);
    $("#inCourse").empty().append($html).val(nofilter['Course']);
    nofilter['Result'] = '';
    nofilter['Results'] = distinctlist(inGroup,'result');
    $html = aOptions(nofilter['Results'],nofilter['Result']);
    $("#inResult").empty().append($html).val(nofilter['Result']);
    //$("#display").html($html);
}


function loadnofilters(){
    // Load Filter Dropdowns 
    var nofilter = [];
    nofilter['UnivID'] = '';
    nofilter['UnivIDs'] = distinctlist(noGroup,'student_no');
    $html = aOptions(nofilter['UnivIDs'],nofilter['UnivID']);
    $("#noUnivID").empty().append($html).val(nofilter['UnivID']);
    nofilter['Family_Name'] = '';
    nofilter['Family_Names'] = distinctlist(noGroup,'surname');
    $html = aOptions(nofilter['Family_Names'],nofilter['Family_Name']);
    $("#noFamily_Name").empty().append($html).val(nofilter['Family_Name']);
    nofilter['First_Name'] = '';
    nofilter['First_Names'] = distinctlist(noGroup,'first_name');
    $html = aOptions(nofilter['First_Names'],nofilter['First_Name']);
    $("#noFirst_Name").empty().append($html).val(nofilter['First_Name']);
    nofilter['Gender'] = '';
    nofilter['Genders'] = distinctlist(noGroup,'gender');
    $html = aOptions(nofilter['Genders'],nofilter['Gender']);
    $("#noGender").empty().append($html).val(nofilter['Gender']);
    nofilter['Nationality'] = 'Japanese';
    nofilter['Nationalities'] = distinctlist(noGroup,'nationality');
    $html = aOptions(nofilter['Nationalities'],nofilter['Nationality']);
    $("#noNationality").empty().append($html).val(nofilter['Nationality']);
    nofilter['Department'] = '';
    nofilter['Departments'] = distinctlist(noGroup,'dept_code');
    $html = aOptions(nofilter['Departments'],nofilter['Department']);
    $("#noDepartment").empty().append($html).val(nofilter['Departments']);
    nofilter['Course'] = '';
    nofilter['Courses'] = distinctlist(noGroup,'course');
    $html = aOptions(nofilter['Courses'],nofilter['Course']);
    $("#noCourse").empty().append($html).val(nofilter['Course']);
    nofilter['Result'] = '';
    nofilter['Results'] = distinctlist(noGroup,'result');
    $html = aOptions(nofilter['Results'],nofilter['Result']);
    $("#noResult").empty().append($html).val(nofilter['Result']);
    //$("#display").html($html);
}
function selectGroup(group,obj){
    //alert("Row Click " + "#Group"+group);
    $(".selected").removeClass('selected');
    $("#Group"+group).addClass('selected');
    $("input[name='Group_No']").val(group); 
    filterInGroup();
    displayInGroup();
}

$("#Groups.tr").click(function(e){
    alert("#Groups.tr"+".click!");
    //Clear the Selected Record
    $("input[name='Group_No']").prop("checked",false);
    $("input[name='AL_Tag']").val(0);
    getEquip();
});
        
function getGroupStudents(){
 
    var alTag = $("input[name='AL_Tag']:checked").val();
    if(alTag){//alert("alTag = "+alTag+" ");
    } else {alTag = 0;}

    var formdata = $("#computers").serialize();
    formdata = '&'+formdata+'&ALTag='+alTag;
    //alert(formdata);
    $.ajax({
            type: 'GET',
            url: '//aglum.lnx.warwick.ac.uk/it/getequipment',
            data: formdata, 
            contentType: 'application/x-www-form-urlencoded',
            dataType: 'jsonp',
            crossDomain: true,
            jsonp: 'data',
            jsonpCallback: 'data',
            xhrFields: {// If you want to carry over the SSO token
                    withCredentials: true 
            },
    success: function(data){
            $("#computers").hide();
            $("#equipment").show();
            $("fieldset#EditIT").show();
            $.fn.loadequip(data);	
            },
    fail: function(responseTxt, statusTxt, xhr){
            $("#computers").show();
            $("#equipment").show();
            $("fieldset#EditIT").show();
            if(statusTxt === "success")
                    alert("External content loaded successfully!");
            if(statusTxt === "error")
                    alert("Error: " + xhr.status + ": " + xhr.statusText);
            }
    });
};

function aOptions(opts,value) { // Uses a simple array of values
    var result = '<option/>';
    for (var index = 0; index < opts.length; ++index) {
            result+="<option>"+opts[index]+"</option>";
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

function filterByProperty(array, prop, value){
    var filtered = [];
    for(var i = 0; i < array.length; i++){

        var obj = array[i];

        for(var key in obj){
            if(typeof(obj[key] == "object")){
                var item = obj[key];
                if(item[prop] == value){
                    filtered.push(item);
                }
            }
        }

    }    

    return filtered;

}
/*
//WITH FIRST COLUMN
arr = arr.sort(function(a,b) {
    return a[0] - b[0];
});


//WITH SECOND COLUMN
arr = arr.sort(function(a,b) {
    return a[1] - b[1];
});
*/

/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL
 * Returns  : The textual representation of the array.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 */
function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0; 
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}

//  $.fn.SaveGroup = function(Student_No, Group_No){

function SaveGroup(Student_No, Group_No){
    //$('#submit').on('submit', function (e) {
    //    e.preventDefault();
    var data = {};
    var aYear = $("#Academic_Year").val();
    var phase = $("#Phase_No").val();
    data['aYear'] = aYear;
    data['Phase_No'] = phase;
    data['Group_No'] = Group_No;
    data['Student_No'] = Student_No;
    $.ajax({
        type: "POST",
        url: '//agmad.lnx.warwick.ac.uk/api/allocateStudent',
        data: data,
            success: function( data ) {
                //alert(data['Result']);
               // $("#ajaxResponse").append("<div>"+msg+"</div>");
            }
        });
    //});
}

function Save2Group(Student_No, Group_No){
    var formdata = {};
    //var formdata = new Array();
    var aYear = $("#Academic_Year").val();
    var phase = $("#Phase_No").val();
    //alert(aYear + ' '+ phase)
    formdata['aYear'] = aYear;
    formdata['Phase_No'] = phase;
    formdata['Group_No'] = Group_No;
    formdata['Student_No'] = Student_No;
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
    });
    $.ajax({
        type: 'GET',
        url: '//agmad.lnx.warwick.ac.uk/api/allocateStudent',
        data: {
            aYear: aYear,
            Phase_No: phase,
            Group_No: Group_No,
            Student_No: Student_No
        },    
        // Note: it looks like there is a problem caused by empty entries being returned
        contentType: 'application/json',  //application/x-www-form-urlencoded
        dataType: 'jsonp',
        crossDomain: true,
        jsonp: 'data',
        jsonpCallback: 'data',
        //xhrFields: {// If you want to carry over the SSO token
        //        withCredentials: true 
        //},
    success: function(result){
        console.log(result);
        jQuery('.alert').show();
        jQuery('.alert').html(result.success);
    },
    //success: function(data){
    //    alert(data.Result);
    //    //alert('Saved!');
    //    //$.fn.loaddata(data);	
    //    },
    fail: function(responseTxt, statusTxt, xhr){
        alert(data.Result);
        if(statusTxt === "success")
                alert("External content loaded successfully!");
        if(statusTxt === "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        }
    });
    //return(Result);
};

//This is how the function is called. In this example we will give a complex array as the argument.
/*
//Calling the function...
function init() {
	var arra = new Array("So long",'s',42,42.13,"Hello World");
	var assoc = {
		"val"  : "New",
		"number" : 8,
		"theting" : arra
	};
	
	alert(dump(assoc));
}
window.onload=init;

students = students.sort(function(a,b){
    return(a['first_name']  > b['first_name']?1:-1);
})

function reorderGroup(array, field){
    // Function needs to be run twice as otherwise previous sort is reversed
    array = array.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    array = array.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    return(array);
    //displayInGroup(inGroup);
}
*/

</script>

@endsection