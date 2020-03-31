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
var filtered = records;
var filter = []; // Used for record filter and edit options
var oldID, oldRow = null;
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
    html=$("#Heading").html()+"<tr><td><input Name=\"id\" id=\"id\" type=\"radio\" value=\"\" onclick=\"editRecord('',0);\"></td>";  
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
        html=html+displayRecord(row);

    /*    record = filtered[row];
        firstColumn = true;    
        for (const col in record) {
            if(firstColumn){
                html=html + "<tr id=\"Record-"+`${record[col]}`+"\">";
                html=html+"<td><input Name=\"id\" id=\"id\" type=\"radio\" value=\""+`${record[col]}`+"\" onclick=\"selectRecord('Record-"+`${record[col]}`+"');\"></td>";
                firstColumn = false;    
            }
            html=html+"<td nowrap>"+`${record[col]}`+"</td>";
        }   
        html=html+"</tr>";
    */
    }
    $("#Records").html(html);
}

function displayRecord(row){
    html=""; 
    firstColumn = true;  
    record = filtered[row];
    for (const col in record) {
        if(firstColumn){
            html=html+"<tr id=\"Record-"+`${record[col]}`+"\">";
            html=html+"<td><input Name=\"id\" type=\"radio\" value=\""+`${record[col]}`+"\" onclick=\"editRecord('"+`${record[col]}`+"',"+row+");\"></td>";
            firstColumn = false;    
        }
        html=html+"<td nowrap>"+`${record[col]}`+"</td>";
    }   
    html=html+"</tr>";   
    return(html);
}
function viewRecord(row,id){
    html=""; 
    firstColumn = true;  
    record = filtered[row];
    for (const col in record) {
        if(firstColumn){
            //html=html+"<tr id=\"Record-"+`${record[col]}`+"\">";
            html=html+"<td><input Name=\"id\" type=\"radio\" value=\""+`${record[col]}`+"\" onclick=\"editRecord('"+`${record[col]}`+"',"+row+");\"></td>";
            firstColumn = false;    
        }
        html=html+"<td nowrap>"+`${record[col]}`+"</td>";
    }   
    //html=html+"</tr>";   
    //alert(html);
    $("#Record-"+id).html(html);
}
function editRecord(id,row){
    var options = [];
    if(oldID){
        //alert(oldID+"("+oldRow+") =>"+id);
        viewRecord(oldRow,oldID);
    }
    oldID = id;
    oldRow = row; 
    html=""; 
    record = filtered[row]; 
    firstColumn = true;    
    for (const col in record) {
        if(firstColumn){
            //html=html+"<tr id=\"Record-"+`${record[col]}`+"\">";
            html=html+"<td><input Name=\"id\" type=\"radio\" value=\""+`${record[col]}`+"\" checked ></td>";
            firstColumn = false;    
        }
        opt = filter[col+'s']; 
        for(var i=0;i<opt.length;++i){
            options[][col] = opt[i];
        } 
        html=html+"<td nowrap>"+Combo(col,options,record[col])+"</td>";
        // Copy Combobox options from equivalent filter dropdown
        //$("#lst"+col).empty().append($("#"+col).html());
    }   
    //html=html+"</tr>";     
    //alert(html);
    $("#Record-"+id).html(html);
}

function selectRecord(id){
    if(id!=''){
        alert("Record: "+id);
    }
}
function loadFilters(){
    // Load Filter Dropdowns 
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
    //var filter = [];
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




        /*********
	* Toggle *
	*********/
	function Toggle(field,value) {
		var item, opts = {};
		var result='<select class="Toggle" id="txt'+field+'" >';	
		result+='<option value="N">No</option><option value="Y">Yes</option></select>';
		return(result);
	}
	/********
	* Alist * Little pair of functions to create and work a Combo class 
	********/
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
	/********
	* COMBO * Little pair of functions to create and work a Combo class 
	********/
	function Combo(field,options,value) {
		var item, opts = {};
		if(typeof(value) == "undefined"){value='';}
		var result='<input class="Combo" type="text" id="txt'+field+'" name="txt'+field+'" value="'+value+'"/>'
			   +'<input class="Combo" type="button" id="btn'+field+'" value="V" onclick="oCombo(this);" />'
		+'<select class="Combo"id="lst'+field+'" onchange="oCombo(this);" style="display:none;">';	
		opts = options;
		for (index = 0; index < opts.length; ++index) {
			opt = opts[index];
			fld = opt[field];
			result+="<option>"+fld+"</option>";
		}
		//for(var i in opts){
		//	result+='<option>'+opts[i]+'</option>';
	//	}		   
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
	}	
	function Options(field,options,value) {
		var item, opts = {};
//		var result='<input class="Combo" type="text" id="txt'+field+'" name="'+field+'" value="'+value+'"/>'
//			   +'<input class="Combo" type="button" id="btn'+field+'" value="V" onclick="oCombo(this);" />'
//		+'<select class="Combo"id="lst'+field+'" onchange="oCombo(this);" style="display:none;">';	
		opts = options;
		var result = '<option/>';
		for (index = 0; index < opts.length; ++index) {
			opt = opts[index];
			fld = opt[field];
			result+="<option>"+fld+"</option>";
		}
//		result+='</select>';
		return(result);
	}






</script>
@endsection