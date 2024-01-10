<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script>
var cal = new CalendarPopup();
cal.setDisabledWeekDays(0,6);

document.title="Collection Notes"

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
	var offerdate = document.getElementById("offerdate");
	var viausmail = document.getElementById("viausmail");
	var viafax = document.getElementById("viafax");
	var viaemail = document.getElementById("viaemail");
	var insname = document.getElementById("insname");
	var insaddress = document.getElementById("insaddress");
	var inscity = document.getElementById("inscity");
	var insstate = document.getElementById("insstate");
	var inszip = document.getElementById("inszip");
	var insadjuster = document.getElementById("insadjuster");

	var offerdatestring = trim(offerdate.value);
	if(viausmail.checked) 
		var viausmailstring = trim(viausmail.value);
	else
		var viausmailstring = "";
	if(viafax.checked) 
		var viafaxstring = trim(viafax.value);
	else
		var viafaxstring = "";
	if(viaemail.checked) 
		var viaemailstring = trim(viaemail.value);
	else
		var viaemailstring = "";
	var insnamestring = trim(insname.value);
	var insaddressstring = trim(insaddress.value);
	var inscitystring = trim(inscity.value);
	var insstatestring = trim(insstate.value);
	var inszipstring = trim(inszip.value);
	var insadjusterstring = trim(insadjuster.value);

	var submitbutton = document.getElementById("submitbutton");

	if(
		offerdatestring.length === 0 || 
		( viausmailstring.length === 0 && viafaxstring.length === 0 && viaemailstring.length === 0 ) || 
		insnamestring.length === 0 || 
		insaddressstring.length === 0 || 
		inscitystring.length === 0 || 
		insstatestring.length === 0 || 
		inszipstring.length === 0 || 
		insadjusterstring.length === 0) 
	{
		submitbutton.disabled=true;
	}
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
// Actions should be taken here.
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
      <legend>Display/Update Notes</legend>
      <table cellpadding="5" cellspacing="0">
        <tr>
          <th colspan="3">Send <?php echo $bnum; ?> Demand Offer</th>
        </tr>
        <tr>
          <td>Demand Offer Date </td>
          <td colspan="2" nowrap="nowrap" style="text-decoration:none"><input id="offerdate" name="offerdate" type="text" size="10" maxlength="10" value="<?php echo $offerdate; ?>" onchange="noteChangeCase(event, this); validateDate(this.id);">
            <img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.noteEditForm.offerdate,'anchor1','MM/dd/yyyy'); return false;" /></td>
        </tr>
        <tr>
          <td>Send Via:</td>
          <td colspan="2"><table>
            <tr>
              <td><label>
                <input type="checkbox" name="viausmail" value="USMAIL" id="viausmail" onchange="noteChangeCase(event, this);" />
                US Mail</label></td>
            </tr>
            <tr>
              <td><label>
                <input type="checkbox" name="viafax" value="FAX" checked="checked" id="viafax" onchange="noteChangeCase(event, this);" />
                Fax</label></td>
            </tr>
            <tr>
              <td><label>
                <input type="checkbox" name="viaemail" value="EMAIL" id="viaemail" onchange="noteChangeCase(event, this);" />
                E-mail</label></td>
            </tr>
          </table></td>
        </tr>
        <tr>
        	<td>Insurance Carrier</td>
            <td colspan="2"><input size="32" name="insname" id="insname" maxlength="64" type="text" value="<?php echo $_POST['insname']; ?>" onchange="noteChangeCase(event, this);" /></td>
        </tr>
		<tr>
        	<td> Address</td>
            <td colspan="2"><input name="insaddress" id="insaddress" type="text" value="<?php echo $_POST['insaddress']; ?>" onchange="noteChangeCase(event, this);" /></td>
		</tr>
		<tr>
        	<td>City, State, Zip</td>
            <td colspan="2">
            <input name="inscity" id="inscity" size="22" maxlength="64" type="text" value="<?php echo $_POST['inscity']; ?>" onchange="noteChangeCase(event, this);" />
            <input name="insstate" id="insstate" size="2" maxlength="3" type="text" value="<?php echo $_POST['insstate']; ?>" onchange="noteChangeCase(event, this);" />
            <input name="inszip" id="inszip" size="11" maxlength="11" type="text" value="<?php echo $_POST['inszip']; ?>" onchange="noteChangeCase(event, this);" /></td>
		</tr>
		<tr>
        	<td>Attn To (Adjuster):</td>
            <td colspan="2"><input name="insadjuster" id="insadjuster" type="text" value="<?php echo $_POST['insadjuster']; ?>" onchange="noteChangeCase(event, this);" /></td>
		</tr>
        <tr>
          <td colspan="3">
            <input id="submitbutton" name="submitbutton" type="submit" value="Create Demand Letter" disabled="disabled" />
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
