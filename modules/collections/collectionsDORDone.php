<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
?>
<script>
document.title="Collection Notes"
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
	$app="collections";
	$type='SYS';
	$date=displayDate(today());
	$datetime=dbDate($date);
	$charges=$_POST['charges'];
	$payments=$_POST['payments'];
	$doramount=$_POST['lienamount'];
	$doruser=getuser();
	$caid=$appid;
	$note="DOR Done Date:$date Charges:$charges Payments:$payments Lien Amount:$doramount ";
	$data="$date $charges $payments $doramount";
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		UPDATE collection_accounts
		SET cadorstatus='D', cadordate='$datetime', cadoruser='$doruser'
		WHERE caid='$caid'
	";
	if(mysqli_query($dbhandle,$query)) {
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
		error("999","Error Updating Collection Account DOR Status.<br>$query<br>".mysqli_error($dbhandle));
		displaysitemessages();
		exit();
	}
}
else {
// Actions should be taken here.
	$charges=0;
	$payments=0;
	$lientamount=0;
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		SELECT charges, payments
		FROM PTOS_Patients p
		WHERE pnum='$pnum'
	";
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$numrows=mysqli_num_rows($result);
		if($numrows==1) {
	// ok
			if($row=mysqli_fetch_assoc($result)) {
				$charges=displayCurrency($row['charges']);
				$payments=displayCurrency($row['payments']);
				$lienamount=displayCurrency($charges-$payments);
?>

<div class="centerFieldset">
  <form method="post" name="noteEditForm">
    <fieldset style="text-align:center;">
      <legend>Display/Update Notes</legend>
      <table cellpadding="5" cellspacing="0">
        <tr>
          <th colspan="3"><?php echo $bnum; ?> DOR Done</th>
        </tr>
        <tr>
          <td> Charges </td>
          <td colspan="2"><input style="text-align:right" name="charges" type="text" readonly="readonly" value="<?php echo($charges); ?>"  /></td>
        </tr>
        <tr>
          <td> Payments </td>
          <td colspan="2"><input style="text-align:right" name="payments" type="text" readonly="readonly" value="<?php echo($payments); ?>"  /></td>
        </tr>
        <tr>
          <td>Lien Amount </td>
          <td colspan="2"><input style="text-align:right" name="lienamount" type="text" readonly="readonly" value="<?php echo($lienamount); ?>"  /></td>
        </tr>
        <tr>
          <td colspan="3"><input id="submitbutton" name="submitbutton" type="submit" value="DOR Done" />
            <input name="close" type="button" value="Exit" onclick="window.close()" />
            <input name="noid" type="hidden" value="<?php echo $noid ?>" />
            <input name="app" type="hidden" value="<?php echo $app ?>" />
            <input name="appid" type="hidden" value="<?php echo $appid ?>" />
            <input name="bnum" type="hidden" value="<?php echo $bnum ?>" />
            <input name="pnum" type="hidden" value="<?php echo $pnum ?>" /></td>
        </tr>
      </table>
    </fieldset>
  </form>
</div>
<?php
			}
			else {
// error on fetch
			}
		}
		else {
// error not == 1 row
		}
	}
	else {
// error result
	}
displaysitemessages();
}
?>
