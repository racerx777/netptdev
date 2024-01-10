<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');

if(!empty($_POST['CreateProofOfService'])) {
//	$req="app=$app&appid=$appid&bnum=$bnum&pnum=$pnum&button=$button&language=en";
	foreach($_POST as $key=>$val)
		$req.="&$key=" . urlencode($val);
?>
<script>
window.open('/modules/collections/collectionsPrintForms.php?<?php echo $req; ?>','CreateProofOfService');
window.close();
</script>
<?php
}
?>
<script>
var cal = new CalendarPopup();
cal.setDisabledWeekDays(0,6);
document.title="Collection Print Proof Of Service"
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
	var posdate = document.getElementById("posdate");
	var documents = document.getElementById("documents");
	var collector = document.getElementById("collector");
	var address01 = document.getElementById("toaddress01");
	var address11 = document.getElementById("toaddress11");
	var address21 = document.getElementById("toaddress21");
	var address31 = document.getElementById("toaddress31");
	var address02 = document.getElementById("toaddress02");
	var address12 = document.getElementById("toaddress12");
	var address22 = document.getElementById("toaddress22");
	var address32 = document.getElementById("toaddress32");
	var address03 = document.getElementById("toaddress03");
	var address13 = document.getElementById("toaddress13");
	var address23 = document.getElementById("toaddress23");
	var address33 = document.getElementById("toaddress33");
	var address04 = document.getElementById("toaddress04");
	var address14 = document.getElementById("toaddress14");
	var address24 = document.getElementById("toaddress24");
	var address34 = document.getElementById("toaddress34");
	var address05 = document.getElementById("toaddress05");
	var address15 = document.getElementById("toaddress15");
	var address25 = document.getElementById("toaddress25");
	var address35 = document.getElementById("toaddress35");

	var posdatestring = trim(posdate.value);
	var documentsstring = trim(documents.value);
	var collectorstring = trim(collector.value);
	var address1string = trim(address01.value) + trim(address11.value) + trim(address21.value) + trim(address31.value);
	var address2string = trim(address02.value) + trim(address12.value) + trim(address22.value) + trim(address32.value);
	var address3string = trim(address03.value) + trim(address13.value) + trim(address23.value) + trim(address33.value);
	var address4string = trim(address04.value) + trim(address14.value) + trim(address24.value) + trim(address34.value);
	var address5string = trim(address05.value) + trim(address15.value) + trim(address25.value) + trim(address35.value);

	var submitbutton = document.getElementById("CreateProofOfService");

	if(posdatestring.length === 0 || documentsstring.length===0 || collectorstring.length === 0 ||
		(address1string.length ===0 && address2string.length ===0 && address3string.length ===0 &&
		address4string.length ===0 && address5string.length ===0))
		submitbutton.disabled=true;
	else
		submitbutton.disabled=false;
}

function noteChangeCase(e, obj)  {
		checkinput();
		if(!e)
			var key = window.event.keyCode;
		else
			var key = e.which;
        if ((key >= 65) && (key <= 90))  {
                    obj.value+=String.fromCharCode(key).toLowerCase();
					if (e.preventDefault)
						e.preventDefault();
					e.returnValue = false;
                }
        if ((key >= 97) && (key <= 122)) {
					obj.value+=String.fromCharCode(key).toUpperCase();
					if (e.preventDefault)
						e.preventDefault();
					e.returnValue = false;
        }
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

if(isset($_POST['submitbutton'])) {
// Format message fields and use notes system to insert note
	$app="collections";
	$type='SYS';
	$date=displayDate($_POST['offerdate']);
	$viausmail=$_POST['viausmail'];
	$viafax=$_POST['viafax'];
	$viaemail=$_POST['viaemail'];
	$sendvia = trim("$viaemail $viafax $viausmail");
	$insname = $_POST['insname'];
	$insaddress = $_POST['insaddress'];
	$inscity = $_POST['inscity'];
	$insstate = $_POST['insstate'];
	$inszip = $_POST['inszip'];
	$insadjuster = $_POST['insadjuster'];
	$note="Confirmation Date:$date Amount:$amount Via:$sendvia Adj:$insadjuster Ins:$insname $insaddress $inscity $insstate $inszip";
	$data="$date $amount $sendvia $insadjuster $insname $insaddress $inscity $insstate $inszip";
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
	echo("</script>");
}
else {

if(empty($app) || empty($appid)) {
	if(empty($bnum) || empty($pnum)) {
		error("999","Missing required value/identifier. (noid:$noid) (app:$app appid:$appid) (bnum:$bnum pnum:$pnum) button:$button");
		displaysitemessages();
		echo '<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />';
		exit();
	}
	else
		$where = "bnum='$bnum' and pnum='$pnum'";
}
else
	$where = "caid='$appid'";
$query = "
SELECT *
FROM PTOS_Patients p
LEFT JOIN collection_accounts ca
ON bnum=cabnum and pnum=capnum
WHERE $where
";
//dump("query",$query);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$result = mysqli_query($dbhandle,$query);
if($result) {
	$numrows=mysqli_num_rows($result);
	if($numrows==0) {
		echo("No Patients or Collections Accounts Found for $bnum $pnum. No Row Fetched.");
		exit();
	}
	if($numrows > 1)
		echo("Multiple Records Found for $bnum $pnum. First Row Fetched.");
	$row = mysqli_fetch_assoc($result);
	if($row) {
		foreach($row as $fieldname=>$fieldvalue) {
			if(!empty($fieldvalue))
				$_POST[$fieldname] = $fieldvalue;
		}
	}
	else
		error('011', "Row error.$query<br>".mysqli_error($dbhandle));
}
else
	error('011', "SELECT error.$query<br>".mysqli_error($dbhandle));

// Actions should be taken here.
	if(empty($_POST['posdate']))
		$_POST['posdate']=today();
	if(empty($_POST['documents']))
		$_POST['documents']="Declaration of Readiness to Proceed";

	if(empty($_POST['toaddress01']))
		$_POST['toaddress01']=$_POST['payor'];

	if(empty($_POST['toaddress11']))
		$_POST['toaddress11']=$_POST['payadd1'];

	if(empty($_POST['toaddress21']))
		$_POST['toaddress21']=$_POST['payadd2'];

	if(empty($_POST['toaddress31']))
		$_POST['toaddress31']=$_POST['payadd3'];

	if(empty($_POST['bnum']))
		$_POST['bnum']=$_POST['bnum'];
	if(empty($_POST['collector']))
		$_POST['collector']=getusername();
?>
<div class="centerFieldset">
  <form method="post" name="noteEditForm">
    <fieldset style="text-align:center;">
      <legend> Send Proof of Service for <?php echo "$bnum $pnum - $patientname" ?></legend>
      <table cellpadding="5" cellspacing="0">
        <tr>
          <td nowrap="nowrap">Proof of Service date</td>
          <td colspan="2" nowrap="nowrap" style="text-decoration:none"><input id="posdate" name="posdate" type="text" size="10" maxlength="10" value="<?php echo $_POST['posdate']; ?>" onchange="checkinput(); validateDate(this.id);">
            <img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.noteEditForm.posdate,'anchor1','MM/dd/yyyy'); return false;" /></td>
        </tr>
        <tr>
          <td>Enclosed Document(s)</td>
          <td colspan="2"><input size="64" name="documents" id="documents" maxlength="255" type="text" value="<?php echo $_POST['documents']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>Collector Name:</td>
          <td colspan="2"><input name="collector" id="collector" type="text" size="35" maxlength="30"value="<?php echo $_POST['collector']; ?>" onchange="checkinput();" /></td>
        </tr>        <tr>
          <td>To</td>
          <td colspan="2"><input size="35" name="toaddress01" id="toaddress01" maxlength="64" type="text" value="<?php echo $_POST['toaddress01']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress11" id="toaddress11" type="text" size="35" value="<?php echo $_POST['toaddress11']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress21" id="toaddress21" size="35" type="text" value="<?php echo $_POST['toaddress21']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress31" id="toaddress31" size="35" type="text" value="<?php echo $_POST['toaddress31']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>To</td>
          <td colspan="2"><input size="35" name="toaddress02" id="toaddress02" maxlength="64" type="text" value="<?php echo $_POST['toaddress02']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress12" id="toaddress12" type="text" size="35" value="<?php echo $_POST['toaddress12']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress22" id="toaddress22" size="35" type="text" value="<?php echo $_POST['toaddress22']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress32" id="toaddress32" size="35" type="text" value="<?php echo $_POST['toaddress32']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>To</td>
          <td colspan="2"><input size="35" name="toaddress03" id="toaddress03" maxlength="64" type="text" value="<?php echo $_POST['toaddress03']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress13" id="toaddress13" type="text" size="35" value="<?php echo $_POST['toaddress13']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress23" id="toaddress23" size="35" type="text" value="<?php echo $_POST['toaddress23']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress33" id="toaddress33" size="35" type="text" value="<?php echo $_POST['toaddress33']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>To</td>
          <td colspan="2"><input size="35" name="toaddress04" id="toaddress04" maxlength="64" type="text" value="<?php echo $_POST['toaddress04']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress14" id="toaddress14" type="text" size="35" value="<?php echo $_POST['toaddress14']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress24" id="toaddress24" size="35" type="text" value="<?php echo $_POST['toaddress24']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress34" id="toaddress34" size="35" type="text" value="<?php echo $_POST['toaddress34']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>To</td>
          <td colspan="2"><input size="35" name="toaddress05" id="toaddress05" maxlength="64" type="text" value="<?php echo $_POST['toaddress05']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress15" id="toaddress15" type="text" size="35" value="<?php echo $_POST['toaddress15']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress25" id="toaddress25" size="35" type="text" value="<?php echo $_POST['toaddress25']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="toaddress35" id="toaddress35" size="35" type="text" value="<?php echo $_POST['toaddress35']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td colspan="2"><input name="CreateProofOfService" id="CreateProofOfService" type="submit" value="Create Proof of Service" disabled="disabled" /></td>
          <td><input name="close" type="button" value="Exit" onclick="window.close()" />
            <input name="noid" type="hidden" value="<?php echo $noid ?>" />
            <input name="app" type="hidden" value="<?php echo $app ?>" />
            <input name="appid" type="hidden" value="<?php echo $appid ?>" />
            <input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
            <input name="pnum" type="hidden" value="<?php echo $pnum ?>" />
            <input name="button" type="hidden" value="<?php echo $button ?>" />
            </td>
        </tr>
      </table>
    </fieldset>
  </form>
</div>
<?php } ?>
