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
function pdfDemandOffer($p, $settings, $data) {
	// Font Helv 
	$defaultfont =  PDF_load_font ($p , "Helvetica" , "auto" ,false );
	$font=$defaultfont;

	// First Page is 8.5 x 11
	$bodyleft=32;
	$bodytop=750;

	PDF_begin_page_ext($p, 612, 792,'');
//	pdf_putheader($p, 32, 700, "Demand Letter");

	// Print scannable ocr indexes for this document
//	pdf_put_ocr_index_info($p, $indexarray);

	// Body 
	$newbodytop = pdf_putbusinessunitheader($p, $bodyleft, $bodytop, $data); 			//	Business Unit Information
	$newbodytop = pdf_putcollectionletterheader($p, $bodyleft, $newbodytop+50, $data);//	Collection Letter Information
	$newbodytop = pdf_putdemandofferbody($p, $bodyleft+70, $newbodytop-60, $data);			//	Offer Body Information
	$newbodytop = pdf_putbusinessunitremitto($p, $bodyleft, $newbodytop+25, $data);		//	Remit to Information
	$newbodytop = pdf_putcollectionletterfooter($p, $bodyleft, $newbodytop-20, $data);	//	Closing Information
	PDF_end_page_ext($p,'');
}

function pdf_putdemandofferbody($p, $x, $y, $data) {

$numberofdays = $data['offerdays']; 
if(empty($numberofdays))
	$penaltiesandinteresttext="";
else {
	$wordofdays = numberinwords($numberofdays);
	$penaltiesandinteresttext="This offer is valid for $wordofdays ($numberofdays) days only. ";
}

$text="Dear ".properCase($data['adjusterfname']).", 

Our office has a bill/lien for medical treatment in the amount of ".displayCurrency($data['fullbalance'],",","$").".  Attached please find the itemized statement for your review.  

In order to avoid the expense and uncertainty of litigation, we hereby extend our most reasonable demand in the amount of  ".displayCurrency($data['offeramount'],",","$")." as full and final satisfaction for all dates of service from ".displayDate($data['fromdate'])." through ".displayDate($data['thrudate']).". This will include any and all penalties as well as interests that may be accrued pursuant to Labor Code 4603.2(b).

$penaltiesandinteresttext

";
//$w=576;
$w=40;
$h=400;
pdf_puttextboxed($p, $x, $y, $w, $h, $text);
return($y-(11*16));
}
?>