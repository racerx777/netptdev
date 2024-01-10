<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script>
var cal = new CalendarPopup();
cal.setDisabledWeekDays(0,6);

document.title="Collection Print Demand Letter"

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
	var vofferdate = document.getElementById("offerdate");
	var vviausmail = document.getElementById("viausmail");
	var vviafax = document.getElementById("viafax");
	var vviaemail = document.getElementById("viaemail");
	var vinsname = document.getElementById("insname");
	var vinsaddress1 = document.getElementById("insaddress1");
	var vinsaddress2 = document.getElementById("insaddress2");
	var vinsaddress3 = document.getElementById("insaddress3");
	var vadjusterfname = document.getElementById("adjusterfname");
	var vadjusterlname = document.getElementById("adjusterlname");
	var vinsadjusterfax = document.getElementById("insadjusterfax");
	var vinsadjusteremail = document.getElementById("insadjusteremail");

	var vofferdatestring = trim(vofferdate.value);
	if(vviausmail.checked) {
		var vviausmailstring = trim(vviausmail.value);
		var vinsaddressstring = trim(vinsaddress1.value) + trim(vinsaddress2.value) + trim(vinsaddress3.value);
	}
	else {
		var vviausmailstring="";
		var vinsaddressstring="";
	}
	if(vviafax.checked) {
		var vviafaxstring = trim(vviafax.value);
		var vinsadjusterfaxstring = trim(vinsadjusterfax.value);
	}
	else {
		var vviafaxstring = "";
		var vinsadjusterfaxstring = trim(vinsadjusterfax.value);
	}
	if(vviaemail.checked) {
		var vviaemailstring = trim(vviaemail.value);
		var vinsadjusteremailstring = trim(vinsadjusteremail.value);
	}
	else {
		var vviaemailstring="";
		var vinsadjusteremailstring="";
	}
	var vinsnamestring = trim(vinsname.value);
	var vinsaddressstring = trim(vinsaddress1.value) + trim(vinsaddress2.value) + trim(vinsaddress3.value);
	var vinsadjusterstring = trim(vadjusterfname.value) + trim(vadjusterlname.value);

	var vsubmitbutton = document.getElementById("CreateDemandLetter");

	if(
		vofferdatestring.length === 0 ||
		(vviausmailstring.length!==0 && vinsaddressstring.length === 0) ||
		(vviafaxstring.length !== 0 && vinsadjusterfaxstring.length===0) ||
		(vviaemailstring.length !== 0 && vinsadjusteremailstring.length===0) ||
		(vviausmailstring.length === 0 && vviafaxstring.length === 0 && vviaemailstring.length === 0 ) ||
		vinsnamestring.length === 0 ||
		vinsadjusterstring.length === 0
	)
	{
		vsubmitbutton.disabled=true;
	}
	else
		vsubmitbutton.disabled=false;
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
	$note="Demand Offer Date:$date Amount:$amount Via:$sendvia Adj:$insadjuster Ins:$insname $insaddress $inscity $insstate $inszip";
	$data="$date $amount $sendvia $insadjuster $insname $insaddress $inscity $insstate $inszip";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/notes/notesSQLFunctions.php');
	noteAddSimple($type, $app, $appid, $bnum, $pnum, $button, $note, $data);
	$_SESSION['button']='Work Account';
	$_SESSION['navigation']=$app;
	$_SESSION['id']=$appid;
	echo("<script>");
	echo("window.opener.location.href = window.opener.location.href+'?caid=$caid&pnum=$pnum&bnum=$bnum';");
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
	if(empty($_POST['offerdate']))
		$_POST['offerdate']=today();
	if(empty($_POST['insname']))
		$_POST['insname']=$_POST['payor'];
	if(empty($_POST['insaddress1']))
		$_POST['insaddress1']=$_POST['payadd1'];
	if(empty($_POST['insaddress2']))
		$_POST['insaddress2']=$_POST['payadd2'];
	if(empty($_POST['insaddress3']))
		$_POST['insaddress3']=$_POST['payadd3'];

	$padjust=trim($_POST['padjust']);
	if(empty($_POST['adjusterfname']))
		$_POST['adjusterfname']=trim(substr($padjust,0,strlen(strrchr($padjust,' '))-1));
	if(empty($_POST['insadjusterlname']))
		$_POST['adjusterlname']=strrchr($padjust,' ');

	if(empty($_POST['claimnumber'])) {
		$claim = strrchr( trim( $_POST['dx4'] ), "CLAIM #");
		if(!$claim) {
			$claim = strrchr( trim( $_POST['dx4'] ), "CLAIM#");
			if(!$claim)
				$claim = strrchr( trim( $_POST['dx4'] ), "CLAIM");
			else
				$claim = "??";
		}
		$_POST['claimnumber']=$claim;
	}

	if(empty($_POST['insadjusterfax']))
		$_POST['insadjusterfax']="";
	if(empty($_POST['insadjusteremail']))
		$_POST['insadjusteremail']="";
	if(empty($_POST['lname']))
		$_POST['lname']="Patient Last Name";
	if(empty($_POST['fname']))
		$_POST['fname']="Patient First Name";
	if(empty($_POST['ssn']))
		$_POST['ssn']="Patient SSN";
	if(empty($_POST['birth']))
		$_POST['birth']="Patient DOB";
	if(empty($_POST['injury']))
		$_POST['injury']="Patient DOI";
	if(empty($_POST['pnum']))
		$_POST['pnum']="Patient Number";
	if(empty($_POST['charges']))
		$_POST['charges']="Patient Charges";
	if(empty($_POST['payments']))
		$_POST['payments']="Patient Payments";
	if(empty($_POST['fullamount']))
		$_POST['fullbalance']=$_POST['charges']-$_POST['payments'];
	if(empty($_POST['offeramount']))
		$_POST['offeramount']=$_POST['fullbalance'];
	if(empty($_POST['fromdate']))
		$_POST['fromdate']=displayDate($_POST['fvisit']);
	if(empty($_POST['thrudate']))
		$_POST['thrudate']=displayDate($_POST['lvisit']);
	if(empty($_POST['offerdays']))
		$_POST['offerdays']='20';
	if(empty($_POST['offerexpires']))
		$_POST['offerexpires']=displayDate(date("Y-m-d",strtotime($_POST['offerdate'].' +'.$_POST['offerdays'].' days')));
	if(empty($_POST['bnum']))
		$_POST['bnum']=$_POST['bnum'];
	if(empty($_POST['collector']))
		$_POST['collector']=getuser();
	if(empty($_POST['note']))
		$_POST['note']="user note here";
$notehtmlrows=array();
$notenumrows=0;

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(!empty($noid)) {
	$notequery = "
		SELECT *
		FROM notes
		WHERE noid='$noid'
		ORDER BY crtdate desc
	";
}
if(!empty($app) && !empty($appid)) {
	$notequery = "
		SELECT *
		FROM notes
		WHERE noapp='$app' and noappid='$appid'
		ORDER BY crtdate desc
	";
}
if(!empty($bnum) && !empty($pnum)) {
	$notequery = "
		SELECT *
		FROM notes
		WHERE nobnum='$bnum' and nopnum='$pnum'
		ORDER BY crtdate desc
	";
}
if(!empty($notequery)) {
	if($noteresult = mysqli_query($dbhandle,$notequery)) {
		$notenumrows=mysqli_num_rows($noteresult);
		if($notenumrows > 0) {
			$notehtmlrows[]="<tr><th>Date Added</th><th>Button</th><th>Notes</th><th>Added by User</th></tr>";
			$thisuser=strtoupper(getuser());
			while($noterow=mysqli_fetch_assoc($noteresult)) {
				$notedate=displayDate($noterow['crtdate']) . " " . displayTime($noterow['crtdate']);
				$notebutton=$noterow['nobutton'];
				$notedescription=strtoupper($noterow['nonote']);
				$noteuser=strtoupper($noterow['crtuser']);
				if(($noteuser==$thisuser) && ($noterow['notype']=='USR'))
					$functions="Delete";
				else
					unset($functions);
				$notehtmlrows[]="<tr><td>$notedate</td><td>$notebutton</td><td>$notedescription</td><td>$noteuser</td><td>$functions</td></tr>";
			}
		}
		$notehtmlrows[]="<tr><th colspan='3'>$notenumrows notes found.</th></tr>";
	}
	else
		error("001","collectionsNotes: SELECT Error. $notequery<br>".mysqli_error($dbhandle));
	mysqli_close($dbhandle);
}
else
	error("002","Missing required identifier. (noid:$noid) (app:$app & appid:$appid) (bnum:$bnum & pnum:$pnum) button:$button notequery:$notequery");

if(count($notehtmlrows) > 0)
	$notehtml=implode("", $notehtmlrows);
$notehtml="<table>$notehtml</table>";
displaysitemessages();
?>

<div class="centerFieldset">
  <form method="post" name="noteEditForm">
    <fieldset style="text-align:center;">
      <legend> Send <?php echo $bnum; ?> Demand Offer</legend>
      <table cellpadding="5" cellspacing="0">
        <tr>
          <td nowrap="nowrap">Denial of Service Date and Amount</td>
          <td nowrap="nowrap" style="text-decoration:none"><input id="offerdate" name="offerdate" type="text" size="10" maxlength="10" value="<?php echo $_POST['offerdate']; ?>" onchange="checkinput(); validateDate(this.id);">
            <img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.noteEditForm.offerdate,'anchor1','MM/dd/yyyy'); return false;" /></td>
          <td><input name="offeramount" id="offeramount" type="text" size="15" maxlength="11" value="<?php echo $_POST['offeramount']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>Dates of Service:</td>
          <td><input name="fromdate" id="fromdate" type="text" size="12" maxlength="10" value="<?php echo $_POST['fromdate']; ?>" onchange="checkinput();  validateDate(this.id);" /></td>
          <td><input name="thrudate" id="thrudate" type="text" size="12" maxlength="10" value="<?php echo $_POST['thrudate']; ?>" onchange="checkinput(); validateDate(this.id);" /></td>
        </tr>
        <tr>
          <td>Authorized By:</td>
          <td colspan="2"><input name="authby" id="authby" type="text" size="32" maxlength="32" value="<?php echo $_POST['authby']; ?>" onchange="checkinput()" /></td>
        </tr>
        <tr>
          <td>Precertification Attached</td>
          <td colspan="2"><input name="precert" id="precert" type="text" size="32" maxlength="32" value="<?php echo $_POST['precert']; ?>" onchange="checkinput()" /></td>
        </tr>
        <tr>
          <td>Precertification Number</td>
          <td colspan="2"><input name="precertnumber" id="precertnumber" type="text" size="20" maxlength="20" value="<?php echo $_POST['precertnumber']; ?>" onchange="checkinput()" /></td>
        </tr>
        <tr>
          <td>Send Via:</td>
          <td colspan="2">

          <table>
              <tr>
                <td><label>
                    <input type="checkbox" name="viausmail" value="USMAIL" id="viausmail" onchange="checkinput();" />
                    US Mail</label></td>
              </tr>
              <tr>
                <td><label>
                    <input type="checkbox" name="viafax" value="FAX" checked="checked" id="viafax" onchange="checkinput();" />
                    Fax</label></td>
                <td><input type="text" name="insadjusterfax" id="insadjusterfax" size="20" maxlength="20" value="<?php echo $_POST['insadjusterfax']; ?>" onchange="checkinput();"></td>
              </tr>
              <tr>
                <td><label>
                    <input type="checkbox" name="viaemail" value="EMAIL" id="viaemail" onchange="checkinput();" />
                    E-mail</label></td>
                <td><input type="text" name="insadjusteremail" id="insadjusteremail" size="20" maxlength="64" value="<?php echo $_POST['insadjusteremail']; ?>" onchange="checkinput();"></td>
              </tr>
            </table>

            </td>
        </tr>
        <tr>
          <td>Claim #:</td>
          <td colspan="2"><input name="claimnumber" id="claimnumber" type="text" size="30" maxlength="30" value="<?php echo $_POST['claimnumber']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>Insurance Company</td>
          <td colspan="2"><input size="35" name="insname" id="insname" maxlength="64" type="text" value="<?php echo $_POST['insname']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td> Address</td>
          <td colspan="2"><input name="insaddress1" id="insaddress1" type="text" size="35" value="<?php echo $_POST['insaddress1']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="insaddress2" id="insaddress2" size="35" type="text" value="<?php echo $_POST['insaddress2']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input name="insaddress3" id="insaddress3" size="35" type="text" value="<?php echo $_POST['insaddress3']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>Adjuster Name:</td>
          <td><input name="adjusterfname" id="adjusterfname" type="text" size="30" maxlength="30" value="<?php echo $_POST['adjusterfname']; ?>" onchange="checkinput();" /></td>
          <td><input name="adjusterlname" id="adjusterlname" type="text" size="30" maxlength="30" value="<?php echo $_POST['adjusterlname']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>Patient # and Balance</td>
          <td><input name="pnum" id="pnum" type="text" size="7" maxlength="6" value="<?php echo $_POST['pnum']; ?>" onchange="checkinput();" /></td>
          <td><input name="fullbalance" id="fullbalance" type="text" size="15" maxlength="11" value="<?php echo $_POST['fullbalance']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>Patient Name:</td>
          <td colspan="2"><input name="fname" id="fname" type="text" size="30" maxlength="30" value="<?php echo $_POST['fname']; ?>" onchange="checkinput();" />
            <input name="lname" id="lname" type="text" size="30" maxlength="30" value="<?php echo $_POST['lname']; ?>" onchange="checkinput();" /></td>
        </tr>
        <tr>
          <td>Collector Name:</td>
          <td colspan="2"><input name="collector" id="collector" type="text" size="35" maxlength="30"value="<?php echo $_POST['collector']; ?>" onchange="checkinput();" /></td>
        </tr>
<?php
$req="app=$app&appid=$appid&bnum=$bnum&pnum=$pnum&button=$button&language=en";
foreach($_POST as $key=>$val)
	$req.="&$key=" . urlencode($val);
?>
        <tr>
          <td colspan="2"><input name="CreateReconLetter" id="CreateDemandLetter" type="button" value="Create Reconsideration Letter" onclick="window.open('/modules/collections/collectionsPrintForms.php?<?php echo $req; ?>','CreateReconLetter'); window.close();" disabled="disabled" /></td>
          <td><input name="close" type="button" value="Exit" onclick="window.close()" />
            <input name="noid" type="hidden" value="<?php echo $noid ?>" />
            <input name="app" type="hidden" value="<?php echo $app ?>" />
            <input name="appid" type="hidden" value="<?php echo $appid ?>" />
            <input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
            <input name="pnum" type="hidden" value="<?php echo $pnum ?>" /></td>
        </tr>
        <tr>
          <td colspan="3"><?php echo $notehtml; ?></td>
        </tr>
      </table>
    </fieldset>
  </form>
</div>
<?php } ?>
