<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16);
// parameters are passed as REQUEST variables urlencoded
foreach($_REQUEST as $key=>$val) {
	$stripped=stripslashes($val);
	$data["$key"]=urldecode($stripped);
}
if(!empty($data['arraylist'])) {
	$stripped=stripslashes($data['arraylist']);
	$arraylist=unserialize($stripped);
	if( count($arraylist) >0) {
		foreach($arraylist as $key=>$array)
			$data["$key"]=$array;
	}
}
unset($app);
unset($appid);
unset($bnum);
unset($pnum);
unset($button);

$app=$data['app'];
$appid=$data['appid'];
$bnum=$data['bnum'];
$pnum=$data['pnum'];
$button=$data['button'];

if	(
	empty($bnum) ||
	empty($button) || (  
		( empty($app) || empty($appid) ) && 
		( empty($bnum) || empty($pnum) ) 
		) 
	) {
	error("001","Missing required value/identifier. (app:$app appid:$appid) (bnum:$bnum pnum:$pnum) button:$button");
	displaysitemessages(); 
	echo '<input name="cancel" type="submit" value="Cancel" onclick="javascript:window.close()" />';
	exit();
}
else {
//	require_once('authprocessingPrintFormsFunctions.php');
	if(!empty($bnum)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$buquery = "
				select bumtaxid, bulmaddress0, bulmaddress1, bulmaddress2, bulmcity, bulmstate, bulmzip, bulmphone, bulmfax
				from master_business_units b
				LEFT JOIN master_business_units_locations l
				ON bumcode=bulmcode
				where bumcode='$bnum' and bulmname='POBOX'
				";
		if($buresult = mysqli_query($dbhandle,$buquery)) {
			if($burow=mysqli_fetch_assoc($buresult)) {
				foreach($burow as $field=>$value) 
					$data[$field]=$value;
			}
		}
	}
	$p = pdf_new();
	pdf_open_file($p); 
	pdf_set_info($p, "Creator", "authprocessing.php");
	pdf_set_info($p, "Author", "NetPT");
	pdf_set_info($p, "Title", "Authorizations Document");
	require_once('Reports/pdfpage.php'); // functions to print on pdf
	if($button=='RequestInsurance') {
		require_once('Reports/pdfpageRequestInsurance.php');
		pdfRequestInsurance($p, $settings, $data); // Output Data Function
	}
	pdf_close($p);
	$buf = pdf_get_buffer($p);
	$len = strlen($buf);
	header("Content-type: application/pdf");
	header("Content-Length: $len");
	$filename=$bnum."_".$pnum."_".$button."(".$app."_".$appid.").pdf";
	header("Content-Disposition: inline; filename=$filename");
	print $buf;
	pdf_delete($p);
}
?>