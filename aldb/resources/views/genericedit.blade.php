@extends('layouts.pse')
@section('content')
<div style="margin:10px;">    
    <h3>{{$pageTitle}}</h3>
{{ Form::open(array($pageURL)) }}
    <div>
    Pre-Sessional Year  <select name="Year" id="Year" class="Filter" onchange="this.form.submit();">
    @foreach($years as $y)<option @if($year == $y->Year) Selected @endif >{{$y->Year}}</option>
    @endforeach
    </select>  &nbsp; 
    Academic Year  <select name="Academic_Year" id="Academic_Year" class="Filter" onchange="this.form.submit();">
    @foreach($acadYears as $a)<option @if($acadYear == $a->Academic_Year) Selected @endif >{{$a->Academic_Year}}</option>
    @endforeach
    </select>  &nbsp; 
    Phase No <select name="Phase_No" id="Phase_No" class="Filter" onchange="this.form.submit();">
    @foreach($phases as $p)<option @if($phase == $p->Phase_No) Selected @endif >{{$p->Phase_No}}</option>
    @endforeach
    </select>
    </div>
    <div>
        <table >
            <thead id="Header" style="height:50px; ">
                
            </thead>
            <tbody id="Records" style="max-height:400px; overflow: auto; ">
                
            </tbody>
            <tfoot id="Footer" style="height:50px;">
                
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
var id = null;
var records = <?php echo json_encode($records, JSON_OBJECT_AS_ARRAY); ?>;
//var noGroup = students;
//var inGroup = students;
//var group = '01'; // Default Group

// Launch when document ready 
$(document).ready(function(){
    //displayHeader();
    //displayFilter();
    displayRecords();
    //displayFooter();
    
 /*
 if($("#Group_No").val()===''){
        $("#Group_No").val(group);
    }
    groupListCount();   // Count Students in each group
    displayGroups();    // Display Groups
    reorderStudents('student_no'); // Order Students by Student Number 
        
        
    filterInGroup();
    //displayStudents(inGroup,"#in_group_students");
    //displayInGroup();
    filterNoGroup();
    //displayStudents(noGroup,"#no_group_students");
    //displayNoGroup();
    //loadinfilters();  // There is no need to filter students in a group
    loadnofilters();    // Filter Students not in a group to ease selection
 */
});   


/*
  0 => {#235 ▼
    +"Group_No": "01"
    +"Class_Room": "H0.05"
    +"TBS_Tutor_ID": "1574334"
    +"TBS_Tutor_Inits": "SL"
    +"LNS_Tutor_ID": "1274865"
    +"LNS_Tutor_Inits": "AL"
    +"Students": 0
  }
 */
function displayRecords(){
    var html = "";
 /*   records.forEach(function(fields, f){
        html=html + "<tr id=\"R"+fields[0]+"\" onclick=\"selectRecord('"+fields[0]+"',this);\" "
            +""+(fields[0] === id?"class=\"selected\"":"")+">";
        fields.forEach(function(value, v){
             html=html+"<td>"+value+"</td>"
        })
         html=html+"</tr>";
    })
    alert(html);
 */   
    var keys = (array) Object.keys(records);
    alert(keys);
    for (var r=0;r<records.length;++r) {   
        alert(record[0])
        record = records[r];
        html=html + "<tr id=\"R"+record[0]+"\" onclick=\"selectRecord('"+record[0]+"',this);\" "
            +""+(record[0] === id?"class=\"selected\"":"")+">";

            for(var c=0; c<record.length;++c) {   
                html=html+"<td>"+record[c]+"</td>"
            }
            html=html+"</tr>";
    }
    alert(html);
    $("#Records").html(html);
}

function groupListCount(){ 
    // Count Students in each group and update groups array
    for (var s=0; s<students.length;++s) {
        for (var g=0; g<groups.length;++g) {
            if(students[s]["group_no"] === groups[g]["Group_No"]){
                //alert (groups[g]["Students"]);
                groups[g]["Students"] = groups[g]["Students"]+1;
                break;
            }
        }    
    }
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

function selectGroup(group,obj){
    //alert("Row Click " + "#Group"+group);
    $(".selected").removeClass('selected');
    $("#Group"+group).addClass('selected');
    $("input[name='Group_No']").val(group); 
    filterInGroup();
    displayInGroup();
}

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
    //displayStudents(inGroup,"#in_group_students");
    filterNoGroup();
    displayNoGroup();
    //displayStudents(noGroup,"#no_group_students");
}

function filterInGroup(){
    phase = $("#Phase_No").val();
    group = $("#Group_No").val();
    if(group != ''){
        inGroup = students.filter(function(el){
            return (el.group_no === group && el.phase_no === phase);
        });
    }  
    displayInGroup();
}

function filterNoGroup(){
    phase = $("#Phase_No").val();
    studentNo = $("#noUnivID").val();
    familyName = $("#noFamily_Name").val();
    firstName = $("#noFirst_Name").val();
    gender = $("#noGender").val();
    nationality = $("#noNationality").val();
    course = $("#noCourse").val();
    //alert(nationality);
    noGroup = students.filter(function(el){
        return (el.group_no === '' 
            && el.phase_no === phase
            && (studentNo !== ''? el.student_no === studentNo : true)
            && (familyName !== ''? el.surname === familyName : true)
            && (firstName !== ''? el.first_name === firstName : true)
            && (gender !== ''? el.gender === gender : true)
            && (nationality !== ''? el.nationality === nationality : true)
            && (course !== ''? el.course === course : true)
        );
    });
    displayNoGroup();
}

/*
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
* /

 * @param {type} count
 * @returns {undefined} */
/*
function displayStudents(list, location){
    var html = ""
    for (var i=0; i<list.length;++i) {
        html=html + "<tr id=\"n"+list[i]["student_no"]+"\" onclick=\"addToGroup('"+list[i]["student_no"]+"');\" >"
            +'<td width="50px">'+list[i]["student_no"]+'</td>'
            +'<td width="100px">'+list[i]["surname"]+'</td>'
            +'<td width="100px">'+list[i]["first_name"]+'</td>'
            +'<td width="100px">'+list[i]["gender"]+'</td>'
            +'<td width="100px">'+list[i]["nationality"]+'</td>'
            +'<td width="100px">'+list[i]["course"]+'</td>'
            +'<td width="100px">'+list[i]["results"]+'</td>'
            +'<td width="100px">'+list[i]["phase_no"]+'</td>'
            +'<td width="100px">'+list[i]["group_no"]+'</td>'
            +'</tr>';
    }
    $(location).html(html);
}
*/

function updateInGroupStudents(count){
    // Recount students in current group
    group = $("#Group_No").val();
    var groupIndex = groupListIndex(group);
    if(groupIndex != null){
        //alert(groups[groupIndex]["Students"]);
        groups[groupIndex]["Students"] = count;
    } 
    displayGroups();
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
    updateInGroupStudents(count);
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

/*
$("#in_group-students.tr").click(function(){
    alert('#in_group-students.tr'+this.id);
});

$("select.nofilter").change(function() {
    alert("select.nofilter"+'Changed');
    filterNoGroup();
});

*/



      
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
}

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
    nofilter['Nationality'] = '';
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

/* 
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
*/

/* Redundant as easier to keep both Student Lists order the same
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
*/

/*   
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
    
    
$("#Groups.tr").click(function(e){
    alert("#Groups.tr"+".click!");
    //Clear the Selected Record
    $("input[name='Group_No']").prop("checked",false);
    $("input[name='AL_Tag']").val(0);
    //getEquip();
});
*/

</script>

@endsection