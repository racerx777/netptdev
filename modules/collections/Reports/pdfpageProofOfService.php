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
function pdfProofOfService($p, $settings, $data) {
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
//	$newbodytop = pdf_putbusinessunitheader($p, $bodyleft, $bodytop, $data); 			//	Business Unit Information
//	$newbodytop = pdf_putcollectionletterheader($p, $bodyleft, $newbodytop-10, $data);//	Collection Letter Information
	$newbodytop = pdf_putProofOfServicebody($p, $bodyleft, $bodytop, $data);			//	Offer Body Information
//	$newbodytop = pdf_putbusinessunitremitto($p, $bodyleft, $newbodytop-10, $data);		//	Remit to Information
//	$newbodytop = pdf_putcollectionletterfooter($p, $bodyleft, $newbodytop-10, $data);	//	Closing Information
	pdf_end_page($p);
}

function trimupper($s) {
	return(strtoupper(trim($s)));
}

function formatAddress($a1, $a2, $a3, $a4) {
	$a1=trimupper($a1);
	$a2=trimupper($a2);
	$a3=trimupper($a3);
	$a4=trimupper($a4);
	if(!empty($a1))
		$a[]="   ".$a1;
	if(!empty($a2))
		$a[]="   ".$a2;
	if(!empty($a3))
		$a[]="   ".$a3;
	if(!empty($a4))
		$a[]="   ".$a4;
	if(count($a) > 0)
		$address=implode("\n",$a);
	else
		$address=false;
	return($address);
}

function pdf_putProofOfServicebody($p, $x, $y, $data) {
	$a1=formatAddress($_REQUEST['toaddress01'], $_REQUEST['toaddress11'], $_REQUEST['toaddress21'], $_REQUEST['toaddress31']);
	$a2=formatAddress($_REQUEST['toaddress02'], $_REQUEST['toaddress12'], $_REQUEST['toaddress22'], $_REQUEST['toaddress32']);
	$a3=formatAddress($_REQUEST['toaddress03'], $_REQUEST['toaddress13'], $_REQUEST['toaddress23'], $_REQUEST['toaddress33']);
	$a4=formatAddress($_REQUEST['toaddress04'], $_REQUEST['toaddress14'], $_REQUEST['toaddress24'], $_REQUEST['toaddress34']);
	$a5=formatAddress($_REQUEST['toaddress05'], $_REQUEST['toaddress15'], $_REQUEST['toaddress25'], $_REQUEST['toaddress35']);
	$address=array();
	if($a1)
		$address[]=$a1;
	if($a2)
		$address[]=$a2;
	if($a3)
		$address[]=$a3;
	if($a4)
		$address[]=$a4;
	if($a5)
		$address[]=$a5;
	if(count($address)>0) {
		$addresses=implode("\n----------------------------\n",$address);
	}

$text="PROOF OF SERVICE
1013 a (3) C.C.P. .2015.5 C.C.P.
STATE OF CALIFORNIA, COUNTY OF ORANGE";
$w=576;
$h=3*12;
pdf_puttextboxed($p, $x, $y, $w, $h, $text, "center");

$text="
I am employed in the County of Orange, State of California.  I am over the age of 18 and not a party to the within the entitled action.  My business address is ".$data['bulmaddress1'].", ".$data['bulmaddress2'].", ".$data['bulmcity'].", ".$data['bulmstate']." ".$data['bulmzip'].".

On ".$_REQUEST['posdate'].", I served the foregoing documents described; '".$_REQUEST['documents']."' on the parties in this action. By placing a true copy there of enclosed is sealed envelope(s) addressed below as follows:

".$addresses."

BY FIRST CLASS MAIL

I declare under penalty of perjury under the laws of the State of California that the foregoing is true and correct. I am readily familiar with this office's practice of collection and processing correspondence for mailing.  Under that practice, it would be deposited with the U.S. Postal Service on that same day with postage thereon fully prepaid at Orange County, California in the ordinary course of business.  I am aware that on motion of the party served, service is presumed invalid if postal cancellatite or postage meter date is more than one day after date of deposit of mailing in affidavit.
 
I declare under penalty of perjury that the foregoing is true and correct. Executed on ".$_REQUEST['posdate'].", at ".$data['bulmcity'].", ".$data['bulmstate'].".


____________________________________
".$_REQUEST['collector'];
//$w=576;
$w=526;
$h=300;
pdf_puttextboxed($p, $x, $y-(4*12), $w, $h, $text, "left", "", NULL, 10);
return($y-(35*16));
}
?>