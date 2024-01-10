<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
?>
<script>
document.title="Collection Edex Requested"
</script>
<?php
$collector=getuser();
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

// Validation
//if(isset($_POST['submitbutton'])) {
//	$callbackdatetime = dbDate($_POST['caqschcalldate']['date']." ".$_POST['caqschcalldate']['time']);
//	$now = dbDate(date("Y-m-d H:i:s", time()));
//	if($callbackdatetime<=$now)
//		error("","Callback date cannot be before current date/time. $callbackdatetime < $now");
//}
if(errorcount()==0 && isset($_POST['submitbutton']) && !empty($_POST['nonote'])) {
// Format message fields and use notes system to insert note
	$type='SYS';
	$app='collections';
	$button='EdexRequest';
	$today=date("m/d/Y H:i:s", time());
	$tomorrow=date("m/d/Y 00:00:01", strtotime($today . " +1 days"));
	$note = 'Edex ' . strtoupper($_POST['edexrequesttype']) . ' Request:' . strtoupper($_POST['nonote']);

	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/notes/notesSQLFunctions.php');
	noteAddSimple($type, $app, $appid, $bnum, $pnum, $button, $note, $data);

	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsQueueFunctions.php');
//	$datetime="$tomorrow";
	collectionsQueueUpdate($appid, $button);

	unset($_POST['nonote']);
//	$_SESSION['button']='Work Account';
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
displaysitemessages();
// retrieve basic account information
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
	if($numrows == 1)
//		echo("One Record Found for $bnum $pnum. Row Fetched.");
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
// default date to next week
$ssn=$_POST['ssn'];
$today=date("m/d/Y H:i:s", time());
$yyyymmdd=date("Ymd", $today);
$tomorrow=date("m/d/Y", strtotime($today . " +1 days"));
$caqschcalldate['date']=$tomorrow;
?>
<div class="centerFieldset">
<?php
if(1==2) {

$ckfile = tempnam ("/", "EDEXSESSIONID");

$url1="https://secure.edexis.com";
$ch = curl_init ($url1);
curl_setopt($ch, CURLOPT_URL, $url1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
$castring=getcwd() . "/BuiltinObjectToken-VerisignClass3PublicPrimaryCertificationAuthority-G2.crt";
curl_setopt($ch, CURLOPT_CAINFO, $castring);
curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPGET, true);
$output = (curl_exec ($ch));

$url2="https://secure.edexis.com/members/login1.wcs?username";
$ch = curl_init ($url2);
curl_setopt($ch, CURLOPT_URL, $url2);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//$castring=getcwd() . "/BuiltinObjectToken-VerisignClass3PublicPrimaryCertificationAuthority-G2.crt";
curl_setopt($ch, CURLOPT_CAINFO, $castring);
curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPGET, true);
$output = (curl_exec ($ch));

$url2="https://secure.edexis.com/members/ssnmultiinqy.wcs?action=verify";
$ch = curl_init ($url2);
$fields = array(
				"SSN"=>$ssn,
				"EMPLOYMENT"=>"OFF",
				"PREAPP"=>"OFF",
				"searchtype"=>"SSN",
				"user_ref1" => $pnum,
				"user_ref2" => $today,
        		"user_ref3" => $user,
        		"B2" => "Submit Request"
);

//url-ify the data for the POST
foreach($fields as $key=>$value) {
	$fields_string .= $key.'='.urlencode($value).'&';
}
$fields_string=rtrim($fields_string,'&');

// Initialize session and set URL.
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Initialize session and set URL.
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
$output = (curl_exec ($ch));
//curl_close($ch);
// scrape output
echo($output);
//$chunk1=strstr($output,"N2201");
//$chunk1=strstr($chunk1,"<tbody>");
//$strlen=strlen($chunk1);
//$str=strpos($chunk1,'<tbody>');
//$end=strpos($chunk1,'</tbody>')+8;
//$len = $end-$str;
//$tr = substr($chunk1, $str, $len);
}
?>
	<form method="post" name="noteEditForm">
		<fieldset style="text-align:center;">
		<legend>Display/Update Notes for <?php echo "$bnum $pnum - $patientname" ?></legend>
		<table cellpadding="5" cellspacing="0">
			<tr>
				<th colspan="3">Edex Request</th>
			</tr>
			<tr>
				<td>Request Type:<br />
					<label>
					<input type="radio" name="edexrequesttype" value="ssn" id="edexrequesttype_0" checked="checked" />
					SS Number <?php echo $ssn;?></label>
					<br />
					<label>
					<input type="radio" name="edexrequesttype" value="adj" id="edexrequesttype_1" />
					Adj Number</label>
					<br />
					<label>
					<input type="radio" name="edexrequesttype" value="wcab" id="edexrequesttype_2" />
					WCAB Number</label>
					<br />
					<label>
					<input type="radio" name="edexrequesttype" value="name" id="edexrequesttype_3" />
					Name</label>
					<br />
				</td>
			</tr>
			<tr>
				<td nowrap="nowrap">Note:<br />
					<textarea wrap="soft" cols="115" rows="15" id="nonote" name="nonote" onchange="noteChange();" onkeypress="noteKeypress();"></textarea></td>
			</tr>
			<tr>
				<td><input style="float:left;" id="submitbutton" name="submitbutton" type="submit" value="Confirm Edex Requested" />
					<input style="float:right;" name="close" type="button" value="Exit" onclick="window.close()" />
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
<?php } ?>
