<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 
// List all tests ordered by body part then test type
// if a bodypart is passed in then limit list to that body part
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

var returntestlist=new Array();
var destfield;
function submitPopupTestTemplateTestsPicker(){
	var formlist=document.getElementById("TestTemplateTestsPicker");
	var testcount=0;
	for(i=0; i<formlist.length; i++) {
		var element=formlist[i];
		if(element.type=='checkbox') {
			elementarray=element.name.split("_");
			if(elementarray.length==2) {
				testname=elementarray[0];
				if(testname=='test') {
					if(element.checked) 
						returntestlist[i]=elementarray[1];
					else
						returntestlist[i]=0;
				}
			}
		}
	}
	if (opener && !opener.closed && opener.returnPopupTestTemplateTestsPicker){
		opener.returnPopupTestTemplateTestsPicker(destfield, returntestlist);
	}
	window.close();
}

function selectTests() {
	testlist=opener.getTests()
	for(i=0; i < testlist.length; i++) {
		var testid=testlist[i];
		var testcheckbox="test_"+testid;
		var checkbox=document.getElementById(testcheckbox);
		checkbox.checked=true;
	}
}

function removeTests() {
	var testlist=new Array();
	testlist=opener.getTests()
	for(i=0; i < testlist.length; i++) {
		var testid=testlist[i];
		var testcheckbox="test_"+testid;
		var checkbox=document.getElementById(testcheckbox);
		checkbox.checked=true;
		checkbox.disabled=true;
	}
}

</script>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$testtemplatetests=array();
$array=array();
$select="SELECT rtnid, rtnname, rtid, rtname, rttype, rttdispseq FROM report_test_template_tests 
JOIN report_tests on rttrtid=rtid 
JOIN report_test_templates on rttrtnid=rtnid
ORDER BY rtname, rttype, rtnname, rttdispseq, rttrtid
";
$select="SELECT rtid, rtname, rttype, rttdispseq FROM report_test_template_tests 
JOIN report_tests on rttrtid=rtid 
ORDER BY rtname, rttype, rttdispseq, rttrtid
";
if($result=mysqli_query($dbhandle,$select)) {
	while($row=mysqli_fetch_assoc($result)) {
		$templateid=$row['rtnid'];
		$testid=$row['rtid'];
		$id="'$templateid:$testid'";
		$array["$id"]=$row;
	}
	$testtemplatetests=$array;
}
else 
	echo "$select<br>".mysqli_error($dbhandle);
?>

<form id="TestTemplateTestsPicker" name="TestTemplateTestsPicker" method="post">
	<table border="1">
		<tr>
			<th> Select </th>
			<th> Test Name </th>
			<th> Test Type </th>
		</tr>
		<?php
	foreach($testtemplatetests as $id=>$test) {
		$checkbox='<input type="checkbox" id="test_'.$test['rtid'].'" name="test_'.$test['rtid'].'" value="'.$test['rtid'].'">';
		$testtype=$test['rttype'];
		$testname=$test['rtname'];
?>
		<tr>
			<td><?php echo $checkbox; ?></td>
			<td><?php echo $testname; ?> </td>
			<td><?php echo $testtype; ?> </td>
		</tr>
		<?php
	}
?>
		<tr>
			<td align="center" colspan="3"><input id="select" name="select" type="button" value="Return Selected Tests" onclick="return submitPopupTestTemplateTestsPicker();" />
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript" language="javascript">
window.onload=removeTests();
</script>