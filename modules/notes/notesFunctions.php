<?php
function noteAdd($type, $app, $appid, $bnum, $pnum, $button, $note, $data) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(12);
	if(!empty($type) && !empty($app) && !empty($appid) && !empty($bnum) && !empty($pnum) && !empty($button) && !empty($note) && !empty($data)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$crtdate = date("Y-m-d H:i:s", time());
		$crtuser = getuser();
		$crtprog = 'noteAdd';
		$insertquery = "
		insert into notes
		set notype='$type', noapp='$app', noappid='$appid', nobnum='$bnum', nopnum='$pnum', nobutton='$button', nonote='$note', nodata='$data'
		";
		if($insertresult = mysqli_query($dbhandle,$insertquery)) 	
			notify("000","Note added at $crtdate by $crtuser.");
		else 
			error("002","noteAdd:INSERT QUERY:$insertquery<br>".mysqli_error($dbhandle));
	}
	else
		error("002","noteAdd:Missing required field. type:$type app:$app appid:$appid bnum:$bnum pnum:$pnum button:$button note:$note data:$data");
}
?>