@extends('layouts.pse')
@section('content')
<div style="margin:10px;">    
    <h3>{{$pageTitle}}</h3>
{{ Form::open(array($pageURL)) }}
    <div>
    @if($years!=null)
    Pre-Sessional Year  <select name="Year" id="Year" class="Filter" onchange="this.form.submit();">
    @foreach($years as $y)<option @if($year == $y->Year) Selected @endif >{{$y->Year}}</option>
    @endforeach
    </select>  &nbsp; 
    @endif
    @if($phases!=null)
    Phase <select name="Phase" id="Phase_No" class="Filter" onchange="this.form.submit();">
    @foreach($phases as $p)<option @if($phase == $p->Phase_No) Selected @endif >{{$p->Phase_No}}</option>
    @endforeach
    </select>
    @endif
    Records: <span id="RecordCount"></span>
    </div>
    <div>
        <table class="striped">
            <thead id="Heading" style="height:50px; ">
                
            </thead>
            <div style="max-height: 400px; overflow: auto; ">
            <tbody id="Records" style="max-height:400px; overflow: auto; ">
                
            </tbody>
            </div>
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
var records = []; <?php //echo json_encode($records, JSON_OBJECT_AS_ARRAY); ?>;
var filter = []; // Used for record filter and edit options
var filtered = []; //records;
// Launch when document ready 
$(document).ready(function(){
    selectRecords();
    /*
    displayHeaders();
    displayFilters();
    loadFilters();
    displayRecords();
    //displayFooter();*/
});   
function selectRecords() {
    var formData =  $("form").serialize();
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });  
    //alert(formData);
    $("#Records").html("<H4> &nbsp; Loading Data ... Please Wait &nbsp; </h4>");
    $.ajax({
        method: 'POST',
        url: "{{$selectURL}}",
        data: formData,
        dataType: 'json',
        crossDomain: false,
        timeout: 0,
        jsonp: 'data',
        jsonpCallback: 'data',
        //xhrFields: {// If you want to carry over the SSO token
        //    withCredentials: true 
        //},
        success: function(data){  
            records =  data.records;
            //filtered = records;
            //console.log(records);
            //alert(records[2]['RowID']);
            displayHeaders();
            displayFilters();
            filterRecords();
            loadFilters();
         //   clearFilters();
            //displayRecords(); 
        }, 
        fail: function(responseTxt, statusTxt, xhr){
            if(statusTxt === "success")
                alert("External content loaded successfully!");
            if(statusTxt === "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        }
    });
};
function getFields(){
    fields = records[0].getOwnPropertyNames();
    alert(fields[0]);
}

function displayHeaders(){
    var record = records[0];
    html="<tr><th><input type=\"button\" value=\"Clr\" onclick=\"clearFilter();\" style=\"width: 100%;\"></th>";
    for (const col in record) {
        html=html+"<th><input type=\"button\" value=\""+`${col}`+"\" onclick=\"reorder('"+`${col}`+"');\" style=\"width: 100%;\"></th>";
    }  
    html=html+"</tr>";
    $("#Heading").html(html);
}
function displayFilters(){
    var record = records[0];
    html=$("#Heading").html()+"<tr><td><input Name=\"id\" id=\"id\" type=\"radio\" value=\"\" onclick=\"selectRecord('');\"></td>";  
    for (const col in record) {
        html=html+"<td><select class=\"filter\" name=\"qry"+`${col}`+"\" id=\"qry"+`${col}`+"\" onChange=\"filterRecords();\">"
            +"<option></option></select></td>";
    }  
    html=html+"</tr>";
    $("#Heading").html(html);
}
/*function displayRecords(){
    var html = "";  
    $("#RecordCount").html(filtered.length);
    for (var row=0;row<filtered.length;++row) {   
        record = filtered[row];
        html=html + "<tr>";
        firstColumn = true;    
        for (const col in record) {
            if(firstColumn){
                html=html+"<td><input Name=\"id\" id=\"id\" type=\"radio\" value=\""+`${record[col]}`+"\" "
                    
                 +"onclick=\"selectRecord(this,'"+`${record[col]}`+"');\"></td>";
                firstColumn = false;    
            }
            html=html+"<td nowrap>"+`${record[col]}`+"</td>";
        }   
        html=html+"</tr>";
    }
    $("#Records").html(html);
}
function xreorder(field){
    // Function needs to be run twice as otherwise previous sort is reversed
    records = records.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    records = records.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    displayRecords();
}
*/

function displayRecords(){
    var html = "";  
    $("#RecordCount").html(filtered.length);
    for (var row=0;row<filtered.length;++row) {  
        html=html+displayRecord(row);
    }
    $("#Records").html(html);
    //addRecord();
}
function displayRecord(row){
    html=""; 
    firstColumn = true;  
    record = filtered[row];
    for (const col in record) {
        if(firstColumn){
            html=html+"<tr id=\"Record-"+`${record[col]}`+"\">";
            html=html+"<td><input Name=\""+`${col}`+"\" type=\"radio\" value=\""+`${record[col]}`+"\" "
            +"onclick=\"selectRecord(this,'"+`${record[col]}`+"');\"></td>";
            //+"onclick=\"/*if(!recordChanged){ */editRecord("+row+",'"+`${record[col]}`+"');/*}*/\"></td>";
            firstColumn = false;    
        } /*else {*/
            //html=html+"<td nowrap>"+`${record[col]}`+"</td>";
            val = (record[col]==null?'':record[col]);
            html=html+"<td nowrap>"+`${val}`+"</td>";
        /*}*/
    }   
    html=html+"<td></td>";
    html=html+"</tr>";   
    return(html);
}

function selectRecord(obj,id){
    $(".Selected").removeClass('Selected');
    $(obj).parent().parent().addClass('Selected'); 
}
function loadFilters(){
    // Load Filter Dropdowns 
    var filter = [];
    var record = records[0]; 
    for (const col in record) {
        filter[col] = '';
        filter[col+'s'] = distinctlist(filtered,col);
        $html = aOptions(filter[col+'s'],filter[col]);
        $("#qry"+col).empty().append($html).val(filter[col]);
    }
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

function clearFilter(){
    var record = records[0]; 
    for (const col in record) {
        $("#qry"+col).val('');
    }
    filtered = records;
    displayRecords();
}
/*
function filterRecords(){
    var record = records[0]; 
    var criteria = 'true';
    for (const col in record) {
        filter[col] = $("#"+col).val();
        criteria = criteria + (filter[col]!==''?" && el."+col+"==='"+filter[col]+"'":"");
    }   
    //alert(criteria);
    filtered = records.filter(function(el){
        return(eval(criteria));
    }); 
    displayRecords();
}
*/
function filterRecords(){
    var record = records[0]; 
    var criteria = 'true';
    var firstColumn = true;  
    for (const col in record) {
      //  if(firstColumn){
      //      firstColumn = false;  
      //  } else {
            filter[col] = $("#qry"+col).val();
            criteria = criteria + (filter[col]!==''?
            (filter[col]==='[blank]'?
            " && (el."+col+"===null || el."+col+"==='' ) ":
            " && el."+col+"==='"+filter[col]+"'"):
            "");
      //  }
    }   
    //alert(criteria);
    filtered = records.filter(function(el){
        return(eval(criteria));
    }); 
    displayRecords();
}

function aOptions(opts,value) { // Uses a simple array of values
    var result = '<option/>';
    for (var index = 0; index < opts.length; ++index) {
        val = (opts[index]==null?'[blank]':opts[index]);
        result+="<option>"+val+"</option>";
    }
    return(result);
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
</script>
@endsection