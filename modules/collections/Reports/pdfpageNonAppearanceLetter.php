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
function pdfNonAppearanceLetter($p, $settings, $data) {
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
	$newbodytop = pdf_putcourtletterheader($p, $bodyleft, $newbodytop-10, $data);//	Collection Letter Information
	$newbodytop = pdf_putNonAppearanceLetterbody($p, $bodyleft, $newbodytop-10, $data);			//	Offer Body Information
//	$newbodytop = pdf_putbusinessunitremitto($p, $bodyleft, $newbodytop-10, $data);		//	Remit to Information
//	$newbodytop = pdf_putcourtletterfooter($p, $bodyleft, $newbodytop-10, $data);	//	Closing Information
	pdf_end_page($p);
}

function pdf_putNonAppearanceLetterbody($p, $x, $y, $data) {

// Old Text
$text="Dear ".$_REQUEST['venuename'].", 

Please be advised that the above captioned matter is currently scheduled for a ".$_REQUEST['scheduledaction']." on ".$_REQUEST['scheduledactiondate'].".

It is hereby kindly requested that our absense be excused, as we will not be able to appear at the currently scheduled hearing due to a calendar scheduling conflict.

However and pursuant to the Board's Policies and Procedures Manual/Uniform Lien Procedures Section 8:171, our office will have a representative with settlement authority available via telephone. [title 8, California Code of Regulations (WCAB Rules of Practice and Procedures) S10563.]
Our office has previously served all interested parties with our lien as well as all supporting documentation and a written request for service of medical reports.

By copy of this letter to the parties, request is made for service of all medical evidence not previously served. Request is also made that our office is served with any and all Minutes and Hearing, Conference Statements and/or Settlement Documents.

Thank you in advance for your courtesy and cooperation. If you have any questions, please do not hesitate to contact the undersigned at the number listed below.

Very truly yours,


".$data['collector']."
Lien Claimant Representative for
".$data['bulmaddress0'];

$text="Dear Mr./Mrs. ".$_REQUEST['venuename'].": 

This matter is currently set for hearing and our office has an outstanding lien in the amount listed above.

Please be advised that our office will not be making an appearance for the scheduled ".$_REQUEST['scheduledaction']." on ".$_REQUEST['scheduledactiondate'].".

Rule 10240(3) states that “Lien Claimants not defined as a party under subdivision 10210(y)(3) with liens of less than $25,000.00 shall be available by telephone with settlement authority and shall notify defendant(s) of the telephone number at which the defendant may reach the lien claimant during the MSC or lien conference”.

Lien claimant kindly requests service of all medical evidence and/or any other documents including minutes of hearings, conference statements and settlement documents.  

Please note your file, excuse our absence for the upcoming hearing and if you have authority to resolve this matter prior to the scheduled hearing, please contact the undersigned at 714-827-4822 or via mail to the address listed above.     

Thank you in advance for your professional courtesy in resolving this matter.

Sincerely,



".$data['collector']."
Lien Claimant Representative for
".$data['bulmaddress0'];

//$w=550;
$w=526;
$h=576;
pdf_puttextboxed($p, $x, $y, $w, $h, $text);
return($y-(23*16));
}
?>