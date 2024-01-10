<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
require('fpdf/fpdf.php');

securitylevel(12); 
$id=$_REQUEST['id'];
$lang=$_REQUEST['lang'];
require_once('patientPrintFormsFunctions.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$data = array();
if(!empty($id)) {	
	if($id!='walk-in')
		$data = patientPrintIntakeForms($id, $casetype='WC', $lang);		
if(errorcount()==0) {
	
	// $p=new FPDF();
	$p = pdf_new();

	PDF_begin_document($p,'',''); 
	pdf_set_info($p, "Creator", "patientintakepackage.php");
	pdf_set_info($p, "Author", "NetPT");
	pdf_set_info($p, "Title", "Patient Intake Package");

	require_once('Reports/pdfpage.php');
	require_once('Reports/pdfpagePatientInformation.php');
	require_once('Reports/pdfpageJobInformation.php');
	require_once('Reports/pdfpageInjuryInformation.php');
	require_once('Reports/pdfpagePainInformation.php');
	require_once('Reports/pdfpageWaiver.php');
	require_once('Reports/pdfpagePrivacyPractices.php');
	require_once('Reports/pdfpagePrivacyAcknowledgement.php');

	pdfPatientInformation($p, $settings, $data, $lang);
	pdfJobInformation($p, $settings, $data, $lang);
	pdfInjuryInformation($p, $settings, $data, $lang);
	pdfPainInformation($p, $settings, $data, $lang);
	pdfWaiver($p, $settings, $data, $lang);
	pdfPrivacyPractices($p, $settings, $data, $lang);
	pdfPrivacyAcknowledgement($p, $settings, $data, $lang);
	
	PDF_end_document($p,'');
	$buf = pdf_get_buffer($p);
	$len = strlen($buf);
	header("Content-type: application/pdf");
	header("Content-Length: $len");
	header("Content-Disposition: inline; filename=patientintakepackage.pdf");
	print $buf;
	pdf_delete($p);
}
}
if(errorcount()!=0) {
// Window Close
?>

<script type="text/javascript" language="javascript">
//	parentURL = window.opener.location.href;
//	if (parentURL && parentURL != �undefined�) {
	window.opener.location.reload(); //this also we can add to reload the parent window.
//	}
	window.close();

</script>


<?php
}
?>