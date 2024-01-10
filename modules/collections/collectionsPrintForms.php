<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
//require_once($_SERVER['DOCUMENT_ROOT'] . '/common/javascript.js');
foreach($_REQUEST as $key=>$val) 
	$data[$key]=urldecode($val);
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
//Sdump("va1",stripslashes($_REQUEST['venueaddress1']));
if	(
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
	require_once('collectionsPrintFormsFunctions.php');

	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$buquery = "
			select bumtaxid, bulmaddress0, bulmaddress1, bulmaddress2, bulmcity, bulmstate, bulmzip, bulmphone, bulmfax
			from master_business_units b
			LEFT JOIN master_business_units_locations l
			ON bumcode=bulmcode
			where bumcode='$bnum' and bulmname='COLLECTIONS'
			";
	if($buresult = mysqli_query($dbhandle,$buquery)) {
		if($burow=mysqli_fetch_assoc($buresult)) {
			foreach($burow as $field=>$value) 
				$data[$field]=$value;
		}
	}
	$p = pdf_new();
	PDF_begin_document($p,'',''); 
	pdf_set_info($p, "Creator", "collections.php");
	pdf_set_info($p, "Author", "NetPT");
	pdf_set_info($p, "Title", "Collections Document");

	require_once('Reports/pdfpage.php'); // functions to print on pdf
	if($button=='DemandOffer') {
		require_once('Reports/pdfpageDemandOffer.php');
		pdfDemandOffer($p, $settings, $data); // Output Data Function
	}
    if($button=='AppealLetter') {
        require_once('Reports/pdfpageAppealLetter.php');
        pdfAppealLetter($p, $settings, $data);
    }
	if($button=='ConfirmationLetter') {
		require_once('Reports/pdfpageConfirmationLetter.php');
		pdfConfirmationLetter($p, $settings, $data); // Output Data Function
	}
	if($button=='ReconsiderationLetter') {
		require_once('Reports/pdfpageReconsiderationLetter.php');
		pdfReconsiderationLetter($p, $settings, $data); // Output Data Function
	}
	if($button=='ProofOfService') {
		require_once('Reports/pdfpageProofOfService.php');
		pdfProofOfService($p, $settings, $data); // Output Data Function
	}
	if($button=='NonAppearanceLetter') {
		require_once('Reports/pdfpageNonAppearanceLetter.php');
		pdfNonAppearanceLetter($p, $settings, $data); // Output Data Function
	}
	PDF_end_document($p,'');
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