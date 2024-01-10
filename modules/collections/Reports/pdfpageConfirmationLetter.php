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

function pdfConfirmationLetter($p, $settings, $data) {
	// Font Helv 
	$defaultfont = pdf_findfont ($p , "Helvetica-Bold" , "winansi" , 0 );
	$font=$defaultfont;

	// First Page is 8.5 x 11
	$bodyleft=32;
	$bodytop=750;

	pdf_begin_page($p, 612, 792);
//	pdf_putheader($p, 32, 700, "Confirmation Letter");

	// Print scannable ocr indexes for this document
//	pdf_put_ocr_index_info($p, $indexarray);

	// Body 
	$newbodytop = pdf_putbusinessunitheader($p, $bodyleft, $bodytop, $data); 			//	Business Unit Information
	$newbodytop = pdf_putcollectionletterheader($p, $bodyleft, $newbodytop-10, $data);//	Collection Letter Information
	$newbodytop = pdf_putconfirmationletterbody($p, $bodyleft, $newbodytop-10, $data);			//	Offer Body Information
	$newbodytop = pdf_putbusinessunitremitto($p, $bodyleft, $newbodytop-20, $data);		//	Remit to Information
	$newbodytop = pdf_putcollectionletterfooter($p, $bodyleft, $newbodytop-20, $data);	//	Closing Information
	pdf_end_page($p);
}

function pdf_putconfirmationletterbody($p, $x, $y, $data) {

$numberofdays = $data['offerdays']; 
if(empty($numberofdays))
	$penaltiesandinteresttext="";
else {
	$wordofdays = numberinwords($numberofdays);
	$penaltiesandinteresttext="Penalties and interest waved if paid within $wordofdays ($numberofdays) days from the date of this letter. ";
}

$text="Dear ".properCase($data['adjusterfname']).", 

Pursuant to our telephone conversation today, wherein we have agreed to settle the bill/lien of ". $data['bulmaddress0'] .", in the amount of ".displayCurrency($data['fullbalance'],",","$")." for a total of ".displayCurrency($data['offeramount'],",","$")." as full and final satisfaction for all dates of service from ".displayDate($data['fromdate'])." through ".displayDate($data['thrudate']).".		  

Please sign below and return to my attention via-fax at (714)826-6833 as soon as possible, in order to withdraw from any proceedings from the WCAB. 

$penaltiesandinteresttext";
//$w=576;
$w=526;
$h=300;
pdf_puttextboxed($p, $x, $y, $w, $h, $text);
return($y-(10*16));
}
?>