@extends('layouts.pse')
@section('content')
<div style="margin:10px;">    
    <h3>{{$pageTitle}}</h3>
{{ Form::open(array($pageURL)) }}
    <?php echo csrf_field(); ?><!-- Required for Cross Platform AJAX -->
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <div id="Request2">
    Pre-Sessional Year  <select name="Year" id="Year" class="Filter" onchange="this.form.submit();">
    @foreach($years as $y)<option @if($year == $y->Year) Selected @endif >{{$y->Year}}</option>
    @endforeach
    </select>  &nbsp; <!--
    Academic Year  <select name="Academic_Year" id="Academic_Year" class="Filter" onchange="this.form.submit();">
    @foreach($acadYears as $a)<option @if($acadYear == $a->Academic_Year) Selected @endif >{{$a->Academic_Year}}</option>
    @endforeach
    </select>  &nbsp; -->
    Phase No <select name="Phase_No" id="Phase_No" class="Filter" onchange="this.form.submit();">
    @foreach($phases as $p)<option @if($phase == $p->Phase_No) Selected @endif >{{$p->Phase_No}}</option>
    @endforeach
    </select>
    Records: <span id="RecordCount"></span>
    </div>
    <div>
        <table >
            <thead id="Request" style="height:50px; "><!--class="scrolling_table" -->
            </thead>
            <thead id="Heading" style="height:50px; "><!--class="scrolling_table" -->
                
            </thead>
            <thead id="Filter" style="height:50px; "><!--class="scrolling_table" -->
            </thead>
            <tbody id="Records" style="max-height:300px; overflow: auto;">
                
            </tbody>
            <tfoot id="Footer" style="height:50px;">
                
            </tfoot>
        </table>

    </div>
    
{{ Form::close() }}
    <!--<div id="editmenu" style="
    position: absolute;
    top: 0px;
    left: 0%;
    height: 500px;
    width: 500px;
    //z-index: 99;
    ">
        <input type="button" value="Cancel" />
        <input type="button" value="SAVE" />
    </div>  -->
    <div id="display"></div>    
</div>

@endsection

@section('script')
<script type="text/javascript">// <![CDATA[

//Load variables and arrays
var id = null;
var records = <?php echo json_encode($records, JSON_OBJECT_AS_ARRAY); ?>;
var filtered = records;
var filter = []; // Used for record filter and edit options
var oldID, oldRow = null;
var recordChanged = false;
var indexField = 'RowID';
// Launch when document ready 
$(document).ready(function(){
    selectRecords();
    //displayRequest();
 //   displayHeaders();
 //   displayFilters();
 //   loadFilters();
 //   displayRecords(); 
    //displayFooter();
});    
/*
function displayRequest(){  
    var record = (array) records[0];
    var cols = record.length;
    html="<tr><th colspan="+cols+">"
    html=html+$("#Request2").html();
    html=html+"</th></tr>";
    $("#Request").html(html);
}
*/  
function selectRecords() {
    var formData =  $("form").serialize();
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });  
    alert(recordData);
    $.ajax({
        type: 'POST',
        url: "{{$selectURL}}",
        data: formData,
        dataType: 'jsonp',
        //crossDomain: true,
        timeout: 0,
        jsonp: 'data',
        jsonpCallback: 'data',
        //xhrFields: {// If you want to carry over the SSO token
        //    withCredentials: true 
        //},
        success: function(data){
            alert(data);
            records = data.records;
            displayHeaders();
            displayFilters();
            loadFilters();
            displayRecords(); 
        },
        fail: function(responseTxt, statusTxt, xhr){
            if(statusTxt === "success")
                alert("External content loaded successfully!");
            if(statusTxt === "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        }
    });
};
function displayHeaders(){
    html="<tr>"; 
    firstColumn = true;  
    var record = records[0];
    for (const col in record) { 
        if(firstColumn){
            firstColumn = false;  
            html=html+"<th><input type=\"button\" value=\"Clr\" onclick=\"clearFilter();\" style=\"width: 100%;\"></th>";
        } else {
            html=html+"<th><input type=\"button\" value=\""+`${col}`+"\" onclick=\"reorder('"+`${col}`+"');\" style=\"width: 100%;\"></th>";
        }
    }  
    html=html+"<td></td></tr>";
    $("#Heading").html(html);
}
function reorder(field){
    // Function needs to be run twice as otherwise previous sort is reversed
    filtered = filtered.sort(function(a,b){
        return(a[field]>b[field]?1:-1);
    });
    filtered = filtered.sort(function(a,b){
        return(a[field]>b[field]?1:-1);
    });
    displayRecords();
}
function displayFilters(){
    html="";
    //html=$("#Heading").html()+"<tr>"; 
    firstColumn = true;  
    var record = records[0];
    for (const col in record) {
        if(firstColumn){
            firstColumn = false;  
            html=html+"<td><input Name=\""+`${col}`+"\" id=\""+`${col}`+"\" type=\"radio\" value=\"\" onclick=\"displayRecords();\"></td>";  
        } else {
            html=html+"<td><select class=\"filter\" name=\"qry"+`${col}`+"\" id=\"qry"+`${col}`+"\" onChange=\"filterRecords();\">"
            +"<option></option></select></td>";
        }
    }  
    html=html+"<td></td></tr>";
    //$("#Heading").html(html);
    $("#Filter").html(html);
}
function loadFilters(){
    // Load Filter Dropdowns 
    var record = records[0]; 
    for (const col in record) {
        filter[col] = '';
        filter[col+'s'] = distinctlist(filtered,col);
        $html = aOptions(filter[col+'s'],filter[col]);
        $("#qry"+col).empty().append($html).val(filter[col]);
    }
}
function distinctlist(list,field){
    var unique = [];
    var distinct = [];
    for( let i = 0; i < list.length; i++ ){
        if( !unique[list[i][field]]){
            distinct.push(list[i][field]);
            unique[list[i][field]] = 1;
        }
    }
    return (distinct.sort());
}
function aOptions(opts,value) { // Uses a simple array of values
    var result = '<option/>';
    for (var index = 0; index < opts.length; ++index) {
        val = (opts[index]==null?'[blank]':opts[index]);
        result+="<option>"+val+"</option>";
    }
    return(result);
}
function filterRecords(){
    var record = records[0]; 
    //var filter = [];
    var criteria = 'true';
    var firstColumn = true;  
    for (const col in record) {
        if(firstColumn){
            firstColumn = false;  
        } else {
            filter[col] = $("#qry"+col).val();
            criteria = criteria + (filter[col]!==''?
            (filter[col]==='[blank]'?
            " && (el."+col+"===null || el."+col+"==='' ) ":
            " && el."+col+"==='"+filter[col]+"'"):
            "");
        }
    }   
    //alert(criteria);
    filtered = records.filter(function(el){
        return(eval(criteria));
    }); 
    displayRecords();
}
function clearFilter(){
    var record = records[0]; 
    for (const col in record) {
        $("#"+col).val('');
    }
    filtered = records;
    displayRecords();
}
function displayRecords(){
    var html = "";  
    $("#RecordCount").html(filtered.length);
    for (var row=0;row<filtered.length;++row) {  
        html=html+displayRecord(row);
    }
    $("#Records").html(html);
    addRecord();
}
function displayRecord(row){
    html=""; 
    firstColumn = true;  
    record = filtered[row];
    for (const col in record) {
        if(firstColumn){
            html=html+"<tr id=\"Record-"+`${record[col]}`+"\">";
            html=html+"<td><input Name=\""+`${col}`+"\" type=\"radio\" value=\""+`${record[col]}`+"\" onclick=\"editRecord("+row+",'"+`${record[col]}`+"');\"></td>";
            firstColumn = false;    
        } else {
            //html=html+"<td nowrap>"+`${record[col]}`+"</td>";
            val = (record[col]==null?'':record[col]);
            html=html+"<td nowrap>"+`${val}`+"</td>";
        }
    }   
    html=html+"<td></td>";
    html=html+"</tr>";   
    return(html);
}
function addRecord(){
    oldID = null;
    oldRow = null; 
    html=""; 
    firstColumn = true;  
    record = filtered[0];
    row = records.length+1;
    for (const col in record) {
        if(firstColumn){
            html=html+"<tr id=\"Record-\">";
            html=html+"<td><input Name=\""+`${col}`+"\" type=\"radio\" value=\"\" onclick=\"editRecord("+row+",'');\"></td>";
            firstColumn = false;    
        } else {
            html=html+"<td nowrap></td>";
        }
    }   
    html=html+"<td></td>";
    html=html+"</tr>";  
    $("#Records").html($("#Records").html()+html);
}
function editRecord(row,id){
    checkRecord();
    viewRecord(oldRow,oldID); 
    oldID = id;
    oldRow = row; 
    var html=""; 
    var options = [];
    var newRecord = false;
     if(id===''){
    //if(row>records.length){
        newRecord = true;
        record = filtered[0]; 
    } else {
        record = filtered[row]; 
    }
    firstColumn = true;    
    for (const col in record) {
        if(newRecord){
            val = '';
        } else {
            val = (record[col]==null?'':record[col]);
        }
        if(firstColumn){
            indexField = col;
            //html=html+"<tr id=\"Record-"+`${record[col]}`+"\">";
            html=html+"<td><input Name=\""+`${col}`+"\" type=\"radio\" value=\""+id+"\" checked ></td>";
            //RowID = val;
            firstColumn = false;    
        } else {
            opts = filter[col+'s']; 
            html=html+"<td nowrap>"+Combo(col,opts,val)+"</td>";
        } 
    }     
    html=html+"<td><input type=\"button\" value=\"X\" onclick=\"deleteRecord("+row+",'"+id+"');\"></td>";
    //alert(row+" > "+records.length);
    $("#Record-"+id).html(html);
    $("#Record-"+id).parent().focusout(checkRecord());
    //$("#Record-"+id).parent().blur(checkRecord());
}
function viewRecord(row,id){
    html=""; 
    firstColumn = true;  
    record = filtered[row];
    for (const col in record) {
        if(firstColumn){
            //html=html+"<tr id=\"Record-"+`${record[col]}`+"\">";
            html=html+"<td><input Name=\""+`${col}`+"\" type=\"radio\" value=\""+`${record[col]}`+"\" onclick=\"editRecord("+row+",'"+`${record[col]}`+"');\"></td>";
            firstColumn = false;    
        } else {
            //html=html+"<td nowrap>"+`${record[col]}`+"</td>";
            val = (record[col]==null?'':record[col]);
            html=html+"<td nowrap>"+`${val}`+"</td>";
        }
    }  
    html=html+"<td></td>";
    //html=html+"</tr>";   
    $("#Record-"+id).html(html);
}
function checkRecord(){
    if(oldRow){
       if(recordChanged){
            if(oldID){
                updateRecord(); 
            } else {
                insertRecord();
            } 
            backfilled(oldRow, oldID);
            //refreshRecord(oldRow); 
            viewRecord(oldRow,oldID); 
            recordChanged = false; 
            alert(oldID+"("+oldRow+") =>"+id);
           
       }
       
    }  
}
function updateRecord() {
    var row = oldRow; // Copy of Row taken here as oldRow updated before ajax call returns
    $("#"+indexField).val(oldID); // Put back old indexField value before serializing
    //alert("#"+indexField+" = "+$("#"+indexField).val());
    //$("form").refresh();
    var recordData =  $("form").serialize();
    
    alert(recordData);
    $("#"+indexField).val(id); // Replace new indexField value after serializing
    // $("form").refresh();
    var id = oldID;
    // Line added to ensure filter is passed back to page
    alert(recordData);
    //$.ajaxSetup({ dataType: "json" });
    $.ajax({
        type: "GET",
        url: "{{$updateURL}}",  // '//agmad.lnx.warwick.ac.uk/api/save',
        data: recordData, 
        contentType: 'application/x-www-form-urlencoded',
        //crossDomain: true,
        dataType: "json",
        //jsonp: 'data',
        //jsonpCallback: 'data',
        //xhrFields: {// If you want to carry over the SSO token
        //    withCredentials: true 
        //},
        success: function(data){  
            //backfillRecord(row,id);
            //refreshRecord(row); // Write updated values back in to the local array
            //viewRecord(row,id); 
            alert('Group Saved!');
        }, 
        fail: function(responseTxt, statusTxt, xhr){
            console.log(responseText);
            alert('Group Save Failed!');
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
    alert(recordData);
};
function refreshRecord(row){
    firstColumn = true;  
    record = filtered[row];
    for (const col in record) {
        if(firstColumn){
             firstColumn = false;  
        } else {
            filtered[row][col] = $("#txt"+`${col}`).val();
            // Note: row only applies to filtered not records
            // records[row][col] = $("#txt"+`${col}`).val();
        }
    } 
    alert('Record Refreshed!');
}
function backfillRecord(row,id){
    // Scroll Through Records 
    var recordFound = false;
    for (var r=0;r<records.length;++r) {
        // Check each first col against id 
        firstColumn = true; 
        for (const col in records[r]) {
            if(firstColumn){
                if(records[r][col]===id) {
                    recordFound = true;  
                } else {
                    exit;
                }
            } else {
               record[r][col] = $("#txt"+`${col}`).val();
           }
       }
       if(recordFound){
           exit;
       }
    }
    alert('Record Backfilled!');
}
function insertRecord(row,id){
    if(confirm("Insert record ?")) {
    var recordData =  $("form").serialize();
    // Line added to ensure filter is passed back to page
    //alert(recordData);
    //$.ajaxSetup({ dataType: "json" });
    $.ajax({
        type: "GET",
        url: "{{$insertURL}}",
        data: recordData, 
        contentType: 'application/x-www-form-urlencoded',
        dataType: "json",
        success: function(data){
            records[row] = records[0];
            //records[row][indexField] = id;
            //refreshRecord(row);
            displayRecords; // Remove Data from local array
            //alert('Record Inserted!');
        }, 
        fail: function(responseTxt, statusTxt, xhr){
            console.log(responseText);
            alert('Record NOT Inserted!');
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
    }
};
function deleteRecord(row,id){
    if(confirm("Delete record ?")) {
    var recordData =  $("form").serialize();
    // Line added to ensure filter is passed back to page
    //alert(recordData);
    //$.ajaxSetup({ dataType: "json" });
    $.ajax({
        type: "GET",
        url: "{{$deleteURL}}",
        data: recordData, 
        contentType: 'application/x-www-form-urlencoded',
        dataType: "json",
        success: function(data){
            removeRecord(row); // Remove Data from local array
            displayRecords();
            //alert('Record Deleted!');
        }, 
        fail: function(responseTxt, statusTxt, xhr){
            console.log(responseText);
            alert('Record NOT Deleted!');
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
    }
};
function removeRecord(row){
    records.splice(row,1);
    displayRecords();
}





/*
function getFields(){
    fields = records[0].getOwnPropertyNames();
    alert(fields[0]);
}
function selectRecord(id){ 
    if(id!=''){
        alert("Record: "+id);
    }
}
function Toggle(field,value) {
    var item, opts = {};
    var result='<select class="Toggle" id="txt'+field+'" >';	
    result+='<option value="N">No</option><option value="Y">Yes</option></select>';
    return(result);
}


// Little pair of functions to create and work a Combo class 
function Alist(field,options,value) {
    var item, opts = {};
    if(typeof(value) == "undefined"){value='';}
    var result='<input class="Alist" type="text" id="txt'+field+'" name="txt'+field+'" value="'+value+'" onchange="oAlist(this);" style="display:none;"/>'
            +'<select class="Alist" id="lst'+field+'" onChange="oAlist(this);" >';
    opts = options;
    for (index = 0; index < opts.length; ++index) {
            opt = opts[index];
            fld = opt[field];
            result+="<option>"+fld+"</option>";
    }
    result+="<option>Other...</option>";
    result+='</select>';
    return(result);
 }
 function oAlist(obj) {
    $obj = obj.id;
    btn = "#btn"+$obj.substr(3);
    txt = "#txt"+$obj.substr(3);
    lst = "#lst"+$obj.substr(3);
    // Which object called the function?
    if(obj.id.substr(0,3) === 'txt'){
        if($(txt).val()!='Other...' && $(txt).val()!=''){
            //$(btn).height($(txt).height());
            //$(lst).width($(txt).width()+$(btn).width()+2);
            //$(lst).children().height($(txt).height());
            $(lst).show();
            $val = $(txt).val();
            $(lst).append("<option>"+$val+"</option>");
            $(lst).val($val);
            $(lst).focus();
            $(txt).hide();
            //$(btn).hide();
        }		
    }
    if(obj.id.substr(0,3) === 'lst'){
        if($(lst).val()=='Other...'){
            $(txt).height($(lst).height());
            $(txt).width($(lst).width());
            $(txt).show();
            $val = $(lst).val();
            $(txt).val($val);
            $(txt).focus();
            $(lst).hide();
            //$(btn).show();
        } else {
            $val = $(lst).val();
            $(txt).val($val);
        }
    }
    
}
*/

// Little pair of functions to create and work a Combo class 
function Combo(field,options,value) {
    var item, opts = {};
    if(typeof(value) == "undefined"){value='';}
    var result='<input class="Combo" type="text" id="txt'+field+'" name="txt'+field+'" value="'+value+'" onchange="recordChanged = true;"/>'
        +'<input class="Combo" type="button" id="btn'+field+'" value="V" onclick="oCombo(this);" />'
        +'<select class="Combo"id="lst'+field+'" onchange="oCombo(this);" style="display:none;">';	
    opts = options;
    for (index = 0; index < opts.length; ++index) {
            fld = opts[index];
            // Check if field value is in a key->Value array / object else go with supplied value. 
            //if(typeof(fld[field])!='undefined'){
            //    fld = fld[field]
            //}
            result+="<option>"+fld+"</option>";
    }		   
    result+='</select>';
    return(result);
}
function oCombo(obj) {
    $obj = obj.id;
    btn = "#btn"+$obj.substr(3);
    txt = "#txt"+$obj.substr(3);
    lst = "#lst"+$obj.substr(3);
    // Which object called the function?
    if(obj.id.substr(0,3) === 'btn'){
        $(btn).height($(txt).height());
        $(lst).width($(txt).width()+$(btn).width()+2);
        $(lst).children().height($(txt).height());
        $(lst).show();
        $val = $(txt).val();
        $(lst).val($val);
        $(lst).focus();
        $(txt).hide();
        $(btn).hide();
    }
    if(obj.id.substr(0,3) === 'lst'){
        $(btn).height($(lst).height());
        //$(txt).width($(lst).width()-$(btn).width());
        $(txt).show();
        $val = $(lst).val();
        $(txt).val($val);
        $(txt).focus();
        $(lst).hide();
        $(btn).show();
    }
    //updateRecord(); 
    //alert('Saved');
    recordChanged = true;
}	

function Options(field,options,value) {
    var item, opts = {};
 //      var result='<input class="Combo" type="text" id="txt'+field+'" name="'+field+'" value="'+value+'"/>'
 //	+'<input class="Combo" type="button" id="btn'+field+'" value="V" onclick="oCombo(this);" />'
 //      +'<select class="Combo"id="lst'+field+'" onchange="oCombo(this);" style="display:none;">';	
    opts = options;
    var result = '<option/>';
    for (index = 0; index < opts.length; ++index) {
            opt = opts[index];
            fld = opt[field];
            result+="<option>"+fld+"</option>";
    }
 //	result+='</select>';
    return(result);
}

function confirmSave() {
    var retVal = confirm("Save changes ?");
    if( retVal == true ) {
       recordChanged = true;
       //updateRecord(); 
       return true;
    } else {
      // document.write ("User does not want to continue!");
       return false;
    }
}


// ]]></script>
@endsection