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
	var note = document.getElementById("nonote");
	var maildate = document.getElementById("maildate");
	var mailitem = document.getElementById("mailitem");
	var submitbutton = document.getElementById("submitbutton");
	var notestring = trim(note.value);
	var datestring = trim(maildate.value);
	var itemstring = trim(mailitem.value);
	if( datestring.length === 0 || (itemstring.length === 0 && notestring.length === 0) ) {
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

if(isset($_POST['submitbutton']) && !empty($nonote)) {
// Format message fields and use notes system to insert note
	$app="collections";
	$type='SYS';
	$date=displayDate($_POST['maildate']);
	if(!empty($_POST['mailitem']))
		$mail=strtoupper($_POST['mailitem']);
	else 
		$mail="OTHER";
	$note="Received:$date Item:$mail " . strtoupper($_POST['nonote']);
	$data="$date $mail";
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
// Actions should be taken here.
?>

<div class="centerFieldset">
  <form method="post" name="noteEditForm">
    <fieldset style="text-align:center;">
      <legend>Display/Update Notes for <?php echo "$bnum $pnum - $patientname" ?></legend>
      <table cellpadding="5" cellspacing="0">
        <tr>
          <th colspan="3">Add Received Mail Item </th>
        </tr>
        <tr>
          <td>Mail Received Date </td>
          <td nowrap="nowrap" style="text-decoration:none"><input id="maildate" name="maildate" type="text" size="10" maxlength="10" value="<?php echo $maildate; ?>" onchange="checkinput(); validateDate(this.id);"> <img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.noteEditForm.maildate,'anchor1','MM/dd/yyyy'); return false;" /></td><td>&nbsp;</td>
        </tr>
        <tr>
          <td>Mail Item</td>
          <td><select name="mailitem" id="mailitem" style="width:10em;" onchange="checkinput();">
              <?php echo getSelectOptions($arrayofarrayitems = getMailTypeCodes($app, 'incoming'), $optionvaluefield='code', $arrayofoptionfields=array('description'=>' (', 'code'=>')'), $defaultoption=$default['caaccttype'], $addblankoption=TRUE, $arraykey='', $arrayofmatchvalues=array(), $sortoptions=FALSE); ?>
            </select></td><td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" nowrap="nowrap">Additional Description <input id="nonote" name="nonote" type="text" size="64" maxlength="255" value="<?php echo $_POST['nonote']; ?>" onkeypress="noteChangeCase(event, this);" />
		  </td><td>
            <input id="submitbutton" name="submitbutton" type="submit" value="Add Note" disabled="disabled" />
            <input name="close" type="button" value="Exit" onclick="window.close()" />
            <input name="noid" type="hidden" value="<?php echo $noid ?>" />
            <input name="app" type="hidden" value="<?php echo $app ?>" />
            <input name="appid" type="hidden" value="<?php echo $appid ?>" />
            <input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
            <input name="pnum" type="hidden" value="<?php echo $pnum ?>" /></td>
        </tr>
        <tr>
          <td colspan="3"><?php require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsNotesList.php'); ?>
          </td>
        </tr>
      </table>
    </fieldset>
  </form>
</div>
<?php } ?>
