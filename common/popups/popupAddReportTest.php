<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 
?>
<script>
// fields set/passed in by parent
var selectobject;
var grtid;
var ginrtname;
var ginrttype;
var grtmeasurecode;

function returnSelection(){
	var nrtid = document.getElementById("rtid");
	var nrtname = document.getElementById("rtname");
	var nrttype = document.getElementById("rttype");
	var nrtmeasurecode = document.getElementById("rtmeasurecode");

	var rttypevalue=nrttype.options[nrttype.selectedIndex].value;
	var rttypetext=nrttype.options[nrttype.selectedIndex].text;

	if (opener && !opener.closed && opener.updateReportTestValues){
		opener.updateReportTestValues(selectobject, nrtid, nrtname+"("+nrttype+")");
	}
	window.close();
}
</script>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(isset($_POST['submit'])) {
	unset($sql);
	$rtid=$_POST['rtid'];
	$rtname=$_POST['rtname'];
	$rttype=$_POST['rttype'];
	$rtmeasurecode=$_POST['rtmeasurecode'];
	if(empty($_POST['rtid'])) {
		if(!empty($_POST['rtname']) && !empty($_POST['rttype']) ) {
			$sql="INSERT INTO report_tests (rtname,rttype,rtmeasurecode) VALUES('$rtname','$rttype','$rtmeasurecode')";
			if($result=mysqli_query($dbhandle,$sql))
				notify("000","$loop Inserted.");
			else
				error("999","$loop NOT INSERTED!<br />$sql<br />".mysqli_error($dbhandle));
		}
	}
	else {
		$field=array();
		if($rtname<>$_POST['save']['rtname'])
			$field['rtname']=mysqli_real_escape_string($dbhandle,stripslashes($rtname));
		if($rttype<>$_POST['save']['rttype'])
			$field['rttype']=mysqli_real_escape_string($dbhandle,stripslashes($rttype));
		if($rtmeasurecode<>$_POST['save']['rtmeasurecode'])
			$field['rtmeasurecode']=mysqli_real_escape_string($dbhandle,stripslashes($rtmeasurecode));
		if(count($field)>0) { // Update 
			$sql="UPDATE report_tests SET rtname='$rtname', rttype='$rttype', rtmeasurecode='$rtmeasurecode' WHERE rtid='$rtid'";
			if($result=mysqli_query($dbhandle,$sql))
				notify("000","$rtid Updated.");
			else
				error("999","$rtid NOT UPDATED!<br />$sql<br />".mysqli_error($dbhandle));
		}
	}
?>
<script type="text/javascript" language="javascript">
returnSelection();
</script>
<?php
}
?>
<form name="formAddReportTest" id="formAddReportTest" method="post">
	<input type="hidden" name="rtid" id="rtid" value="<?php echo $rtid; ?>" />
	<input type="hidden" name="save[rtname]" id="savertname" value="<?php echo $rtname; ?>" />
	<input type="hidden" name="save[rttype]" id="saverttype" value="<?php echo $rttype; ?>" />
	<input type="hidden" name="save[rtmeasurecode]" id="savertmeasurecode" value="<?php echo $rtmeasurecode; ?>" />
	<div>Test Name
		<input type="text" name="rtname" id="rtname" size="64" maxlength="255" />
	</div>
	<div>Test Type
		<select name="rttype" id="rttype">
			<option value="ROM">Range Of Motion</option>
			<option value="SPECIAL" selected="selected">Special Test</option>
		</select>
	</div>
	<div>Measure Code
		<input type="text" name="rtmeasurecode" id="rtmeasurecode" />
	</div>
	<div>
		<input type="submit" name="submit" value="Submit">
	</div>
</form>
<script type="text/javascript" language="javascript">
//document.getElementById('rtid').value = rtid.value
//document.getElementById('rtname').value = rtname.value
//document.getElementById('rttype').value = rttype.value
//document.getElementById('rtmeasurecode').value = rtmeasurecode.value
var e = document.getElementById('rtname');
e.focus();
</script>
