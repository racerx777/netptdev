<?php
function noteAddSimple($type, $app, $appid, $bnum, $pnum, $button, $note, $data) {
	$requirefile=$_SERVER['DOCUMENT_ROOT'] . '/common/session.php';
	require_once($requirefile);
	securitylevel(12);
	if(!empty($type) && !empty($app) && !empty($appid) && !empty($bnum) && !empty($pnum) && !empty($button) && !empty($note) ) {
		$requirefile=$_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php';
		require_once($requirefile);
		$dbhandle = dbconnect();
		
		$auditfields = getauditfields();
		$crtdate = $auditfields['date'];
		$crtuser = $auditfields['user'];
		$crtprog = 'noteAdd';
		// echo $insertquery = 'insert into notes set notype="'.$type.'", noapp="'.$app.'", noappid="'.$appid.'", nobnum="'.$bnum.'", nopnum="'.$pnum.'", nobutton="'.$button.'", nonote="'.$note'", nodata="'.$data.'", crtdate="'.$crtdate.'", crtuser="'.$crtuser.'", crtprog="'.$crtprog.'"';
		$insertquery = 'INSERT INTO notes (`notype`,`noapp`,`noappid`,`nobnum`,`nopnum`,`nobutton`,`nonote`,`nodata`,`crtdate`,`crtuser`,`crtprog`) values("'.$type.'","'.$app.'","'.$appid.'","'.$bnum.'","'.$pnum.'","'.$button.'","'.$note.'","'.$data.'","'.$crtdate.'","'.$crtuser.'","'.$crtprog.'")';
		if($insertresult = mysqli_query($dbhandle,$insertquery)) 	
			notify("000","Note added at $crtdate by $crtuser.");
		else 
			error("001","noteAdd:INSERT QUERY:$insertquery<br>".mysqli_error($dbhandle));
	}
	else {
		error("002","notesSQLFunctions.noteAddSimple:Missing required field. type:$type app:$app appid:$appid bnum:$bnum pnum:$pnum button:$button note:$note data:$data");
	}
}
?>