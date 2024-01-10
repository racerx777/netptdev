<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script>
var cal = new CalendarPopup();
cal.setDisabledWeekDays(0,6);
document.title="Collection Resubmit Unpaid Bills Notes"
// Removes leading whitespaces
function LTrim( value ) {
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
}
// Removes ending whitespaces
function RTrim( value ) {
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
}
// Removes leading and ending whitespaces
function trim( value ) {
	return LTrim(RTrim(value));
}
function checkinput() {
	var applicationdate = document.getElementById("applicationdate");
	var datestring = trim(applicationdate.value);
	var submitbutton = document.getElementById("RecordApplication");
	if( datestring.length === 0 ) {
		submitbutton.disabled=true;
	}
	else
		submitbutton.disabled=false;		
}
</script>
<?php
// handle request parameters
unset($noid);
unset($app);
unset($appid);
unset($bnum);
unset($pnum);
unset($button);
if(!empty($_REQUEST['noid']))
	$noid=$_REQUEST['noid'];
if(!empty($_REQUEST['app']))
	$app=$_REQUEST['app'];
if(!empty($_REQUEST['appid']))
	$appid=$_REQUEST['appid'];
if(!empty($_REQUEST['bnum']))
	$bnum=$_REQUEST['bnum'];
if(!empty($_REQUEST['pnum']))
	$pnum=$_REQUEST['pnum'];
if(!empty($_REQUEST['button']))
	$button=$_REQUEST['button'];
unset($patientname);
if(!empty($_REQUEST['patientname']))
	$patientname=$_REQUEST['patientname'];

if( !empty($button) && ( 
	!empty( $noid) || 
	( !empty($app) && !empty($appid) ) || 
	( !empty($bnum) && !empty($pnum) ) 
	) ) {
//		ok
}
else {
	error("001","Missing required value/identifier. (noid:$noid) (app:$app appid:$appid) (bnum:$bnum pnum:$pnum) button:$button");
	displaysitemessages(); 
	echo '<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />';
	exit();
}

if(isset($_POST['RecordApplication'])) {
// Format message fields and use notes system to insert note
	$app="collections";
	$type='SYS';
	if(empty($date))
		$date=today();
	$note="Application for Adjudication Completed";
	foreach($_POST as $key=>$value)
		$dataarray[$key]=$value;
	$data=serialize($dataarray);
//	$data="$date $amount $sendvia $insadjuster $insname $insaddress $inscity $insstate $inszip";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/notes/notesSQLFunctions.php');
	noteAddSimple($type, $app, $appid, $bnum, $pnum, $button, $note, $data);

	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsQueueFunctions.php');
	collectionsQueueUpdate($appid, $button);

	unset($_POST['note']);
	$_SESSION['navigation']=$app;
	$_SESSION['id']=$appid;
	$_REQUEST['app']=$app;
	$_REQUEST['appid']=$appid;
	$_REQUEST['caid']=$appid;
	$_REQUEST['pnum']=$pnum;
	$_REQUEST['bnum']=$bnum;
	echo("<script>");
	echo("window.opener.location.href = window.opener.location.href;");
	echo("if (window.opener.progressWindow) window.opener.progressWindow.close();");
	echo("window.close();");
	echo("window.open('http://www.dir.ca.gov/dwc/FORMS/EAMS%20Forms/ADJ/DWC1.pdf');");
	echo("</script>");
}
else {
// Actions should be taken here.
?>

<div class="centerFieldset">
  <form method="post" name="noteEditForm">
    <fieldset style="text-align:center;">
      <legend>Display/Update Notes  for <?php echo "$bnum $pnum - $patientname" ?></legend>
      <table cellpadding="5" cellspacing="0">
        <tr>
          <th colspan="3">Record Application for Adjudication</th>
        </tr>
        <tr>
          <td>Application Date</td>
          <td colspan="2" nowrap="nowrap" style="text-decoration:none"><input id="applicationdate" name="applicationdate" type="text" size="10" maxlength="10" value="<?php echo $_POST['applicationdate']; ?>" onchange="checkinput(); validateDate(this.id);" onblur="checkinput(); validateDate(this.id);">
            <img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.noteEditForm.applicationdate,'anchor1','MM/dd/yyyy'); return false;" /></td>
        </tr>
        <tr>
          <td colspan="3"><input id="RecordApplication" name="RecordApplication" type="submit" value="Record Application for Adjudication Complete"/>
            <input name="close" type="button" value="Exit" onclick="window.close()" />
            <input name="noid" type="hidden" value="<?php echo $noid ?>" />
            <input name="app" type="hidden" value="<?php echo $app ?>" />
            <input name="appid" type="hidden" value="<?php echo $appid ?>" />
            <input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
            <input name="pnum" type="hidden" value="<?php echo $pnum ?>" /></td>
        </tr>
        <tr>
          <td colspan="3"><?php echo $notehtml; ?>
          <td>
        </tr>
      </table>
    </fieldset>
  </form>
</div>
<?php } ?>
