<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 
?>
<script type="text/javascript" language="javascript">
function appendOptionLast(elSel, text, value) {
  var elOptNew = document.createElement('option');
  elOptNew.text = text;
  elOptNew.value = value;
//  var elSel = document.getElementById('selectX');

  try {
    elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
  }
  catch(ex) {
    elSel.add(elOptNew); // IE only
  }
}

var destvalue;
var desttext;
var destROM;
var destSPECIAL;

function submitPopupTestTemplatePicker(formelement, formlist){
//	var formelement = document.getElementById("rtnid");

	var formvalue=formelement.options[formelement.selectedIndex].value;
	var formtext=formelement.options[formelement.selectedIndex].text;
	var formROM=document.createElement("select");
	var formSPECIAL=document.createElement("select");

	for(i=0; i<formlist.length; i++) {
		var optiontext=formlist.options[i].text;
		var optionvalue=optiontext.split("|");
		if(optionvalue[0]==formvalue) {
			if(optionvalue[3]=='ROM') {
				appendOptionLast(formROM, optionvalue[2], optionvalue[1]);
			}
			if(optionvalue[3]=='SPECIAL') {
				appendOptionLast(formSPECIAL, optionvalue[2], optionvalue[1]);
			}
		}
	}
	if (opener && !opener.closed && opener.returnPopupTestTemplatePicker){
		opener.returnPopupTestTemplatePicker(destvalue, formvalue, desttext, formtext, destROM, formROM, destSPECIAL, formSPECIAL);
	}
	window.close();
}
</script>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$testtemplatearray=array();
if(isset($_POST['testtemplatearray'])) {
	$testtemplatearray=unserialize(urldecode($_POST['testtemplatearray']));
}
else {
	$array=array();
	$testtemplatesselect="SELECT rtnid, rtnname FROM report_test_templates";
	if($testtemplatesresult=mysqli_query($dbhandle,$testtemplatesselect)) {
		while($testtemplatesrow=mysqli_fetch_assoc($testtemplatesresult)) {
			$array[$testtemplatesrow['rtnid']] = array("code"=>$testtemplatesrow['rtnid'], "description"=>$testtemplatesrow['rtnname']);
		}
	}
		else echo "$testtemplatesselect<br>".mysqli_error($dbhandle);
	$testtemplatearray=$array;
}

$array=array();
$testselect="SELECT rttrtnid, rtid, rtname, rttype, rttdispseq FROM report_test_template_tests JOIN report_tests on rttrtid=rtid ORDER BY rttrtnid, rttdispseq, rttrtid";
if($testresult=mysqli_query($dbhandle,$testselect)) {
	while($testrow=mysqli_fetch_assoc($testresult)) {
		$array[$testrow['rtid']] = array("templateid"=>$testrow['rttrtnid'], "testid"=>$testrow['rtid'], "testname"=>$testrow['rtname'], "testtype"=>$testrow['rttype']);
	}
}
else echo "$testselect<br>".mysqli_error($dbhandle);
$testarray=$array;

$testtemplates=getSelectOptions($arrayofarrayitems=$testtemplatearray, $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption='', 	$addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array());

$tests=getSelectOptions($arrayofarrayitems=$testarray, $optionvaluefield='testid', $arrayofoptionfields=array('templateid'=>'|', 'testid'=>'|', 'testname'=>'|', 'testtype'=>''), $defaultoption='', $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array());
?>
<form id="TestTemplatePicker" method="post">
<select name="rtnid" id="rtnid" >
<?php echo $testtemplates; ?>
</select> 
<select name="rtid" id="rtid" >
<?php echo $tests; ?>
</select> 
<?php
$posttesttemplatearray=urlencode(serialize($testtemplatearray));
?>
<br />
<input id="submitbutton" name="submitbutton" type="button" value="Select this template" onclick="return submitPopupTestTemplatePicker(document.getElementById('rtnid'), document.getElementById('rtid'));" />
<input type="hidden" id="testtemplatearray" name="testtemplatearray" value="<?php echo $posttesttemplatearray; ?>" />
</form>
<script type="text/javascript">
var e=document.getElementById("rtnid");
e.value=destvalue.value;
e.focus();
</script>
