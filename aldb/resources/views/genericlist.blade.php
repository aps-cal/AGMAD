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
    Records: <span id="RecordCount"></span>
    </div>
    <div>
        <table >
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
var records = <?php echo json_encode($records, JSON_OBJECT_AS_ARRAY); ?>;
var filtered = records;
// Launch when document ready 
$(document).ready(function(){
    displayHeaders();
    displayFilters();
    loadFilters();
    displayRecords();
    //displayFooter();
});   

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
        html=html+"<td><select class=\"filter\" name=\""+`${col}`+"\" id=\""+`${col}`+"\" onChange=\"filterRecords();\">"
            +"<option></option></select></td>";
    }  
    html=html+"</tr>";
    $("#Heading").html(html);
}
function displayRecords(){
    var html = "";  
    $("#RecordCount").html(filtered.length);
    for (var row=0;row<filtered.length;++row) {   
        record = filtered[row];
        html=html + "<tr>";
        firstColumn = true;    
        for (const col in record) {
            if(firstColumn){
                html=html+"<td><input Name=\"id\" id=\"id\" type=\"radio\" value=\""+`${record[col]}`+"\" onclick=\"selectRecord('"+`${record[col]}`+"');\"></td>";
                firstColumn = false;    
            }
            html=html+"<td nowrap>"+`${record[col]}`+"</td>";
        }   
        html=html+"</tr>";
    }
    $("#Records").html(html);
}

function selectRecord(id){
    if(id!=''){
        alert("Record: "+id);
    }
}
function loadFilters(){
    // Load Filter Dropdowns 
    var filter = [];
    var record = records[0]; 
    for (const col in record) {
        filter[col] = '';
        filter[col+'s'] = distinctlist(filtered,col);
        $html = aOptions(filter[col+'s'],filter[col]);
        $("#"+col).empty().append($html).val(filter[col]);
    }
}

function reorder(field){
    // Function needs to be run twice as otherwise previous sort is reversed
    filtered = filtered.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    filtered = filtered.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
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

function filterRecords(){
    var record = records[0]; 
    var filter = [];
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

function reorder(field){
    // Function needs to be run twice as otherwise previous sort is reversed
    records = records.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    records = records.sort(function(a,b){
        return(a[field]  > b[field]?1:-1);
    });
    displayRecords();
}
function aOptions(opts,value) { // Uses a simple array of values
    var result = '<option/>';
    for (var index = 0; index < opts.length; ++index) {
            result+="<option>"+opts[index]+"</option>";
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