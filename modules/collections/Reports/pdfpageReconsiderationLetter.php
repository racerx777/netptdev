<?php
// required arrays of information
// $patient - name, ssn, address, sex, city, state, zip, birth, phonehome, age, phonework, email, phonecell, occupation
// $doctor - name, address, city, state, zip, phone
// $case - injurydate, postsurgical, casetype, surgerydate
// $rx - bodypart, diagnosis
// $insurance1 - name, address, city, state, zip, adjuster, phone, type, insured, policy, claim
// $insurance2 - name, address, city, state, zip, adjuster, phone, type, insured, policy, claim
// $attorney - name, address, city, state, zip, phone
// $employer - name, address, city, state, zip, phone
// $additional - injurydescription, emergencyroom, otherfacility, xrays, autodrivable, previousinjury, stilltreating
function pdfReconsiderationLetter($p, $settings, $data) {
	// Font Helv 
	$defaultfont = pdf_findfont ($p , "Helvetica-Bold" , "winansi" , 0 );
	$font=$defaultfont;

	// First Page is 8.5 x 11
	$bodyleft=32;
	$bodytop=750;

	pdf_begin_page($p, 612, 792);
//	pdf_putheader($p, 32, 700, "Reconsideration Letter");

	// Print scannable ocr indexes for this document
//	pdf_put_ocr_index_info($p, $indexarray);

	// Body 
	$newbodytop = pdf_putbusinessunitheader($p, $bodyleft, $bodytop, $data); 			//	Business Unit Information
	$newbodytop = pdf_putcollectionletterheader($p, $bodyleft, $newbodytop-10, $data);//	Collection Letter Information
	$newbodytop = pdf_putReconsiderationletterbody($p, $bodyleft, $newbodytop-10, $data);			//	Offer Body Information
	$newbodytop = pdf_putbusinessunitremitto($p, $bodyleft, $newbodytop-10, $data);		//	Remit to Information
	$newbodytop = pdf_putcollectionletterfooter($p, $bodyleft, $newbodytop-10, $data);	//	Closing Information
	pdf_end_page($p);
}

function pdf_putReconsiderationletterbody($p, $x, $y, $data) {
$text="Attn: Reconsideration Department, 

Please be advised that after an extensive review, we have found several inconsistencies on the reimbursement of the above mentioned dates of services. As a result of those inconsistencies, the services have been either under paid or not paid at all.

Attached please find the billing statement / HCFA along with the medical reports and or chart notes, these are being resubmitted due to one or more of the following reasons:

The medical treatment has been authorized by: ".$_REQUEST['authby']."

UR pre-certification letter attached ".$_REQUEST['precert']."

Pre-certification No: ".$_REQUEST['precertnumber']."

Please note that the medical treatment was authorized and more than sixty (60) days have passed since the initial submission of these bills, therefore, penalties and interests are applicable pursuant to Labor Code 4603.2(b).

If you would like to discuss via negotiation, please do not hesitate to contact the undersigned.

Sincerely,

".$_REQUEST['collector']."
Claims Representative
714-827-4822, Ext.  

cc. Claims Examiner,                     (bills and recon letter only)
cc. file.
";
//$w=576;
$w=526;
$h=300;
pdf_puttextboxed($p, $x, $y, $w, $h, $text);
return($y-(35*16));
}
?>