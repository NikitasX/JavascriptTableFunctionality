<!DOCTYPE html>
<?php
	$conn = mysqli_connect("Host","User","Password","Database");
?>
<html>
<body>
<style>
hr {
  border-top: 1px dashed red;
}
#graphs {
	width:50%;
	height:400px;
	margin-top:25px;
	margin-left:50px;
	border-right:1px solid #000;
	border-bottom:3px solid #000;
	border-left:3px solid #000;
    background-size: 40px 40px;
    background-image: linear-gradient(to right, grey 1px, transparent 1px), linear-gradient(to bottom, grey 1px, transparent 1px);
	position:relative;
	display:none;
}
#Columns {
	width:100%;
	height:100%;
}
#xaxis {
	position:absolute;
	bottom:-40px;left:0;
	width:100%;
	height:30px;
	z-index:999;
}
.yaxis {
	position:absolute;
	left:-50px;top:0;
	text-align:center;
	height:100%;
	width:auto;
	z-index:999;
}
.yaxis #y-max {
	width:30px;
	position:absolute;
	left:50%;top:0;
}
.yaxis #y-mid {
	width:30px;
	height:30px;
	position:absolute;
	left:50%;top:50%;
	margin-top:-15px;
}
.yaxis #y-min {
	width:30px;
	position:absolute;
	left:50%;bottom:0;
}
</style>
<h3>Πίνακας Javascript</h3>

<table class="uomTable">
  <thead>
      <tr>
       <td>Height</td>
        <td>Score</td>
        <td>Income</td>
        <td>Age</td>
      </tr>
  </thead>
  <tbody>
	<?php 
	$res = $conn -> query("SELECT * FROM ergasia_table WHERE 1 = 1");
	while($result = $res -> fetch_assoc()) { ?>
		<tr data-id = "<?php echo $result['id']; ?>">
		 <td><?php echo $result['Height']; ?></td>
			<td><?php echo $result['Score']; ?></td>
			<td><?php echo $result['Income']; ?></td>
			<td><?php echo $result['Age']; ?></td>
		</tr>
	<?php } ?>
  </tbody>
  <tfoot>
  </tfoot>
</table>

<div id="graphs">
	<div id="xaxis">
	</div>
	
	<div id = "Columns">
	</div>
	
	<div class="yaxis">	
		<span id="y-min"></span>
		<span id="y-mid"></span>		
		<span id="y-max"></span>
	</div>
</div>

<hr />

<h3>Αρχικός Πίνακας</h3>

<table>
  <thead>
      <tr>
       <td>Height</td>
        <td>Score</td>
        <td>Income</td>
        <td>Age</td>
      </tr>
  </thead>
  <tbody>
	<?php 
	$res = $conn -> query("SELECT * FROM ergasia_table WHERE 1 = 1");
	while($result = $res -> fetch_assoc()) { ?>
		<tr data-id = "<?php echo $result['id']; ?>">
		 <td><?php echo $result['Height']; ?></td>
			<td><?php echo $result['Score']; ?></td>
			<td><?php echo $result['Income']; ?></td>
			<td><?php echo $result['Age']; ?></td>
		</tr>
	<?php } ?>
  </tbody>
  <tfoot>
  </tfoot>
</table>
<br />
<hr />
<script src="jquery.js" type="text/javascript"></script>
<script>
var colors= ['#C0504E', '#4F81BC', '#E59566', '#33558B', '#77A033', '#7F6084', 
'#F79647', '#4AACC5', '#8064A1', '#23BFAA', '#9BBB58', '#C0504E', '#4F81BC'];
/*
*
* FUNCTION CODE BLOCK
*
*/
/*
*
* Calculation functions Start
*
*/
function Min(Numbers) {
	var Result = Numbers[0];
	for(var i = 0; i < Numbers.length; i++) {
		if(Numbers[i] < Result) {
			Result = Numbers[i];
		}
	}
	return Result;
}
function Max(Numbers) {
	var Result = Numbers[0];
	for(var i = 0; i < Numbers.length; i++) {
		if(Numbers[i] > Result) {
			Result = Numbers[i];
		}
	}
	return Result;
}
function Mean(Numbers) {
	var Result = 0;
	for(var i = 0; i < Numbers.length; i++) {
		Result += parseInt(Numbers[i]);
	}
	var TotalNumbers = Numbers.length;
	Result = Result / TotalNumbers;
	return Result;
}
function Mode(Numbers) {
    var numMapping = {};
    for(var i = 0; i < Numbers.length; i++){
        if(numMapping[Numbers[i]] === undefined){
            numMapping[Numbers[i]] = 0;
        }        
            numMapping[Numbers[i]] += 1;
    }
    var greatestFreq = 0;
    var mode;
    for(var prop in numMapping){
        if(numMapping[prop] > greatestFreq){
            greatestFreq = numMapping[prop];
            mode = prop;
        }
    }
    return parseInt(mode);
}
function Range(Numbers) {
	var Minimum = Numbers[0];
	var Maximum = Numbers[0];
	for(var i = 0; i < Numbers.length; i++) {
		if(Numbers[i] < Minimum) {
			Minimum = Numbers[i]
		}		
		if(Numbers[i] > Maximum) {
			Maximum = Numbers[i]
		}
	}
	var result = Maximum - Minimum;
	return result;
}
function Median(Numbers) {
    var Result = 0;
	var numsLen = Numbers.length;
    Numbers.sort();
    if (numsLen % 2 === 0) {
        Result = (Numbers[numsLen / 2 - 1] + Numbers[numsLen / 2]) / 2;
    } else { 
        Result = Numbers[(numsLen - 1) / 2];
    }
    return Result;
}
function Variance(Numbers) {
	Numbers.sort();
	var Result = 0;
	for(var i = 0; i < Numbers.length; i++) {
		Result += Math.pow(Numbers[i], 2);
	}
	Result = (Result / Numbers.length).toFixed(2);
	return Result;
}
function StandardDeviation(Numbers) {
	var Result = Variance(Numbers);
	Result = Math.sqrt(Result);
	return Result.toFixed(2);
}
/*
*
* Calculation functions End
*
*/
function CreateNewElement(tag, name, className) {
   var TagType = document.createElement(tag);
   var Text = document.createTextNode(name);
   TagType.appendChild(Text);
   
   if(typeof className != undefined) {
		TagType.setAttribute('class', className);
   }
   
   return TagType;
}

function SwiftTTable(TargetColumn) {
	var rows = document.querySelectorAll(".uomTable tbody tr");
	var NumArray = new Array();
    for(i = 0; i < rows.length; i++) {
    	var AllRows = rows[i].querySelectorAll("input");
        NumArray.push(AllRows[TargetColumn].value);
	}
	return NumArray;
}

function SortValues (ColumnName, ColumnAttributes) {
  var THeadTitle = ColumnName;
    var THeadTDS = document.querySelectorAll(".uomTable thead td");
     
    for(j=0; j < THeadTDS.length; j++) {        
        if(THeadTDS[j].innerHTML == THeadTitle) {
            var TargetColumn = j;
            if(typeof ColumnAttributes == undefined || ColumnAttributes == 'DESC') {
             THeadTDS[j].setAttribute('data-order', 'ASC');
            } else {
             THeadTDS[j].setAttribute('data-order', 'DESC');
            }
        }
    }
    
    var NumArray = new Array();
	var TRArray = new Array();
    var rows = document.querySelectorAll(".uomTable tbody tr");
	var Graphs = new Array();
	
    for(i = 0; i < rows.length; i++) {
    	var AllRows = rows[i].querySelectorAll("input");
        NumArray.push(AllRows[TargetColumn].value);
		Graphs.push(AllRows[TargetColumn].value);
		TRArray.push(AllRows);
    }
	
	if(typeof ColumnAttributes == undefined || ColumnAttributes == 'DESC') {
		NumArray = NumArray.sort(function(a, b){return a - b});
	} else {
		NumArray = NumArray.sort(function(a, b){return b - a});
	}
	
	for(var i = 0; i < TRArray.length; i++) {
		var Inputs = TRArray[i];
		var TargetInput = Inputs[TargetColumn].value;
		
		for(var j = 0; j < NumArray.length; j++) {
			if(TargetInput == NumArray[j]) {
				NumArray[j] = Inputs;
			}
		}
	}
		
	var ATI = document.querySelectorAll('.uomTable tbody input');
	var finalArray = new Array();
	
	for(var i = 0; i < NumArray.length; i++) {
		var Inputs = NumArray[i];		
		for(var j = 0; j < Inputs.length; j++) {
			finalArray.push(Inputs[j].value);
		}
	}
	
	for(var k = 0; k < ATI.length; k++) {
		ATI[k].value = finalArray[k];
	}
	return Graphs;
}

/*
*
* STYLE CODE BLOCK
*
*/

//CHANGE TDS TO INPUTS
var HeaderTDS = document.querySelector('.uomTable thead'); 

HeaderTDS.style.fontWeight = "900";
HeaderTDS.style.cursor = 'pointer';

var BodyTDS = document.querySelectorAll('.uomTable tbody td');

var HTDS = document.querySelectorAll('.uomTable thead td'); 

var TableFooter = document.querySelectorAll('.uomTable tfoot');

var ControlRow = CreateNewElement('TR', '', 'ControlGroup');

for(var i = 0; i < HTDS.length; i++) {
	var ControlTD = CreateNewElement('TD', '', 'Control-' + i);
	
	var Select = CreateNewElement('SELECT', '', 'Select-'+i);
	
	var OptionEmpty = CreateNewElement('OPTION', '[Select method]');
	var OptionMin = CreateNewElement('OPTION', 'Min', 'Min-'+i);
	var OptionMax = CreateNewElement('OPTION', 'Max', 'Max-'+i);
	var OptionMean = CreateNewElement('OPTION', 'Mean', 'Mean-'+i);
	var OptionMode = CreateNewElement('OPTION', 'Mode', 'Mode-'+i);
	var OptionMedian = CreateNewElement('OPTION', 'Median', 'Median-'+i);
	var OptionRange = CreateNewElement('OPTION', 'Range', 'Range-'+i);
	var OptionVariance = CreateNewElement('OPTION', 'Variance', 'Variance-'+i);
	var OptionStdDev = CreateNewElement('OPTION', 'Standard Deviation', 'StandardDeviation-'+i);
	
	Select.appendChild(OptionEmpty);
	Select.appendChild(OptionMin);
	Select.appendChild(OptionMax);	
	Select.appendChild(OptionMean);
	Select.appendChild(OptionMode);
	Select.appendChild(OptionMedian);
	Select.appendChild(OptionRange);
	Select.appendChild(OptionVariance);
	Select.appendChild(OptionStdDev);
	
	ControlTD.appendChild(Select);	
	ControlRow.appendChild(ControlTD);
}
TableFooter[0].appendChild(ControlRow);


var OutputRow  = CreateNewElement('TR', '', 'OutputGroup');
for(var i = 0; i < HTDS.length; i++) {
	var OutputTD = CreateNewElement('TD', '', 'TDPut-'+i);
	var OutputInput = CreateNewElement('INPUT', '', 'OutPut-'+i);

	OutputRow.appendChild(OutputTD);
	OutputTD.appendChild(OutputInput);
}
TableFooter[0].appendChild(OutputRow);

var MassSave = CreateNewElement('BUTTON', 'Mass Save', 'MassSave');
TableFooter[0].appendChild(MassSave);

var MassSaveSelect = document.getElementsByClassName('MassSave');

for(var i = 0; i < MassSaveSelect.length; i++) {
	MassSaveSelect[i].style.background = '#EFAC4E';
	MassSaveSelect[i].style.width = '100%';
	MassSaveSelect[i].style.color = '#fff';
	MassSaveSelect[i].style.border = '2px solid #EFAC4E';
	MassSaveSelect[i].style.padding = '6px';
	MassSaveSelect[i].style.borderRadius = '15px';
	MassSaveSelect[i].style.margin = '10px 0';
	MassSaveSelect[i].style.cursor = 'pointer';   
}

for(i = 0; i < BodyTDS.length; i++) {
	var TDVal = BodyTDS[i].innerHTML;
    BodyTDS[i].innerHTML = BodyTDS[i].innerHTML.replace(TDVal, '<input type="number" value = "'+TDVal+'">');
}

//CREATE DUPLICATE/DELETE BUTTONS
var BodyTRS = document.querySelectorAll('.uomTable tbody tr');
for(i = 0; i < BodyTRS.length; i++) {
   
   var Duplicate = CreateNewElement('BUTTON', 'Duplicate', 'DupBtn');
   var Delete = CreateNewElement('BUTTON', 'Delete', 'DelBtn');
   var Save = CreateNewElement('BUTTON', 'Save', 'SaveBtn');
   
   BodyTRS[i].appendChild(Save); 
   BodyTRS[i].appendChild(Duplicate);
   BodyTRS[i].appendChild(Delete); 
}

var classStyleDub = document.getElementsByClassName('DupBtn');
for (var i = 0; i < classStyleDub.length; i++) {
  classStyleDub[i].style.background = '#cce5ff';
  classStyleDub[i].style.color = '#004085';
  classStyleDub[i].style.border = '2px solid #b8daff';
  classStyleDub[i].style.padding = '6px';
  classStyleDub[i].style.borderRadius = '15px';
  classStyleDub[i].style.margin = '0 5px';
  classStyleDub[i].style.cursor = 'pointer';   
}
var classStyleDel = document.getElementsByClassName('DelBtn');
for (var i = 0; i < classStyleDel.length; i++) {
  classStyleDel[i].style.background = '#F8D7DA';
  classStyleDel[i].style.color = '#721c24';
  classStyleDel[i].style.border = '2px solid #f5c6cb';
  classStyleDel[i].style.padding = '6px 14px';
  classStyleDel[i].style.borderRadius = '15px';
  classStyleDel[i].style.cursor = 'pointer';  
  
}
var classStyleDel = document.getElementsByClassName('SaveBtn');
for (var i = 0; i < classStyleDel.length; i++) {
  classStyleDel[i].style.background = '#5cb85c';
  classStyleDel[i].style.color = '#fff';
  classStyleDel[i].style.border = '2px solid #4cae4c';
  classStyleDel[i].style.padding = '6px 14px';
  classStyleDel[i].style.borderRadius = '15px';
  classStyleDel[i].style.cursor = 'pointer';  
  
}
var SelectStyle = document.querySelectorAll('.uomTable .ControlGroup select'); 
for(var i = 0; i < SelectStyle.length; i++) {
	SelectStyle[i].parentElement.style.padding = '8px 0 10px 0';
	SelectStyle[i].parentElement.style.borderTop = '2px solid #222';
	SelectStyle[i].style.width = '100%';
	SelectStyle[i].style.padding = '5px 0';
	SelectStyle[i].style.borderRadius = '20px';
}

var OutputInputStyle = document.querySelectorAll('.uomTable .OutputGroup input'); 
for(var i = 0; i < OutputInputStyle.length; i++) {
	OutputInputStyle[i].style.padding = '5px 0';
	OutputInputStyle[i].style.border = '1px solid grey';
	OutputInputStyle[i].style.borderRadius = '20px';
}

/*
*
* EVENT CODE BLOCK
*
*/

document.querySelector('.uomTable thead').addEventListener('click', function(e){

	document.getElementById('graphs').style.display = 'block';

	var TargetTD = e.target.innerHTML;
    var TargetTDAttr = e.target.getAttribute('data-order');
    var Result = SortValues(TargetTD, TargetTDAttr);
    
    if(typeof TargetTDAttr == undefined || TargetTDAttr == 'DESC') {
	    Result.sort(function(a, b){return a - b;});
		e.target.style.color = 'red';
    } else {
		Result.sort(function(a, b){return b - a;});
		e.target.style.color = 'green';
    }
    	
	var MinRes = Math.min.apply(null, Result);
	var MaxRes = Math.max.apply(null, Result);
	
	var IndexElement = document.getElementById('xaxis');
	
	IndexElement.innerHTML = '';
	document.getElementById('Columns').innerHTML = '';
	
	if(IndexElement.getElementsByTagName('*').length == 0) {
		for(var i = 0; i < Result.length; i++) {
			var Element = CreateNewElement('div', i + 1, 'xaxis-div');
			var NewCanvasColumn = CreateNewElement('div', Result[i], 'canvas-div');
			IndexElement.appendChild(Element);
			document.getElementById('Columns').appendChild(NewCanvasColumn);
		}
		
		var CountRes = (100 / Result.length);
		var NewDivs = document.getElementsByClassName('xaxis-div');
		var ColumnDivs = document.getElementsByClassName('canvas-div');
		
		for(var j = 0; j < NewDivs.length; j++) {
			NewDivs[j].style.width = CountRes + '%';
			NewDivs[j].style.textAlign = 'center';
			NewDivs[j].style.display = 'inline-block';
			
			var ColHeight = (100 - ((MaxRes - Result[j]) / MaxRes) * 100);
						
			ColumnDivs[j].style.width = 'calc('+CountRes + '% - 20px)';
			ColumnDivs[j].style.height = ColHeight+'%';
			ColumnDivs[j].style.textAlign = 'center';
			ColumnDivs[j].style.color = '#fff';
			ColumnDivs[j].style.fontSize = '100%';
			ColumnDivs[j].style.verticalAlign = 'bottom';
			ColumnDivs[j].style.display = 'inline-block';
			ColumnDivs[j].style.margin = '0 10px';
			if(TargetTDAttr == null || TargetTDAttr == 'ASC') {
				ColumnDivs[j].style.backgroundColor = colors[j];
			} else {
				ColumnDivs[j].style.backgroundColor = colors[NewDivs.length - j - 1];
			}
		}
	}
	
	document.getElementById('y-min').innerHTML = '0';
	document.getElementById('y-mid').innerHTML = MaxRes * 0.5;
	document.getElementById('y-max').innerHTML = MaxRes;
	
});

document.querySelector('.uomTable tbody').addEventListener('click', function(e){
	var Target = e.target;
    
    switch(Target.innerHTML) {
    	case 'Duplicate':
          var uomTable = document.querySelector('.uomTable tbody');
          var RTC = Target.parentElement;
          var NewTR = RTC.cloneNode(true);
		  NewTR.removeAttribute('data-id', '');
          uomTable.appendChild(NewTR);
          break;
       	case 'Delete':
		   var uomTable = document.querySelector('.uomTable tbody');
		   var CloseTBody = Target.closest('.uomTable tbody');
			
			var TRCount = CloseTBody.querySelectorAll('tr');
			
			if(TRCount.length != 1) {
				var RTC = Target.parentElement;
				var ID = RTC.getAttribute('data-id');
				RTC.remove();
			} else {
				alert('I am the captain now!');
			}
			jQuery.ajax({
				url: 'delete.php',
				type: 'post',
				data: {'id':ID}
			});
        break;
    }
});

document.querySelector('.uomTable tfoot').addEventListener('change', function(e){
	var Target = e.target.getAttribute('class');
	Target = Target.split('-');
	Target = Target[1];
	var method = e.target.value;
	var results = SwiftTTable(Target);	
	
	var TFootOutput = document.querySelectorAll('.uomTable tfoot input');	
	switch(method) {
		case 'Min':
			TFootOutput[Target].value = Min(results);
			break;		
		case 'Max':
			TFootOutput[Target].value = Max(results);
			break;		
		case 'Mean':
			TFootOutput[Target].value = Mean(results);
			break;		
		case 'Mode':
			TFootOutput[Target].value = Mode(results);
			break;		
		case 'Median':
			TFootOutput[Target].value = Median(results);
			break;		
		case 'Range':
			TFootOutput[Target].value = Range(results);
			break;		
		case 'Variance':
			TFootOutput[Target].value = Variance(results);
			break;		
		case 'Standard Deviation':
			TFootOutput[Target].value = StandardDeviation(results);
			break;
	}
});


$('.uomTable tbody').on('click', function(e){
	if(e.target.innerHTML == 'Save') {
		var LineSave = {};
		
		var Targ = $(e.target).closest('tr');
		
		$.each($('thead td'), function(j, HeaderElement){
			var nthChild = j + 1;
			var TargetNumber = Targ.find('td:nth-of-type('+nthChild+') input').val();			
			LineSave[$(this).text()] = TargetNumber;
		});
		
		if(typeof Targ.attr('data-id') !== typeof undefined && Targ.attr('data-id') !== false) {
			LineSave['id'] = Targ.attr('data-id');
		}
		
		$.ajax({
			url: 'InsUpdate.php',
			type: 'post',
			data: {'SaveArray':LineSave},
			success: function(data){
				window.location.reload();
			}
		});
	}
});

$('.MassSave').on('click', function(e){
		var Titles = [];
		var LineValues = {};
		
		$.each($('thead td'), function(){
			Titles.push($(this).text());
		});
		$.each($('tbody tr'), function(i, tr){
			LineValues['tr-' + i] = {};
			$.each($(tr).find('td'), function(j, td){
				LineValues['tr-' + i][Titles[j]] = $(td).find('input').val();
			});
		});		
		$.ajax({
			url: 'MassInsert.php',
			type: 'post',
			data: {'SaveArray':LineValues},
			success: function(data){
				window.location.reload();
			}
		});
});

</script>
</body>
</html>