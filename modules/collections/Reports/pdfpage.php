<?php
function pdf_getdefaultfont($p){
// Courier, Courier-Bold, Courier-Oblique, Courier-BoldOblique,
// Helvetica, Helvetica-Bold, Helvetica-Oblique, Helvetica-BoldOblique,
// Times-Roman, Times-Bold, Times-Italic, Times-BoldItalic,
// Symbol, ZapfDingbats
	$font = PDF_load_font ($p , "Helvetica" , "auto" ,'' );
	return($font);
}

function pdf_getdefaultfontsize(){
	return("12.0");
}

function pdf_getdefaultlinespacing(){
	return("12.0");
}

function pdf_getdefaultfontcolor(){
	$defaultfontcolor = array();
	$defaultfontcolor['r']=0.0;
	$defaultfontcolor['g']=0.0;
	$defaultfontcolor['b']=0.0;
	return($defaultfontcolor);
}

// Logo
function pdf_putimage($p, $x, $y, $image, $imagetype, $scale=1) {
	$pdfimage = pdf_open_image_file($p, $imagetype, $image);
	pdf_place_image($p, $pdfimage, $x, $y, $scale);
}

// Text
function pdf_puttext($p, $x, $y, $text, $font=NULL, $fontsize=NULL, $fontcolor=NULL) {
	if($font==NULL)
		$font=pdf_getdefaultfont($p);

	if($fontsize==NULL)
		$fontsize=pdf_getdefaultfontsize();

	pdf_setfont($p, $font,$fontsize);

	if($fontcolor==NULL)
		$fontcolor=pdf_getdefaultfontcolor();
	pdf_setcolor($p, "fill", "rgb",$fontcolor['r'],$fontcolor['g'],$fontcolor['b'],$fontsize);
	pdf_set_text_pos($p, $x, $y);
	pdf_show($p, $text);
}

function pdf_puttextboxed($p, $left, $top, $width, $height, $text, $mode="left", $feature="", $font=NULL, $fontsize=NULL, $fontcolor=NULL) {
	if($font==NULL)
		$font=pdf_getdefaultfont($p);

	if($fontsize==NULL)
		$fontsize=pdf_getdefaultfontsize();

	pdf_setfont($p, $font,$fontsize);

	if($fontcolor==NULL)
		$fontcolor=pdf_getdefaultfontcolor();
	pdf_setcolor($p, "fill", "rgb",$fontcolor['r'],$fontcolor['g'],$fontcolor['b'],$fontsize);
	//pdf_set_text_pos($p, $left, $top);
	$y=$top;
	$lineHeight=$fontsize;
	$lineSpace=5;
//$ret = pdf_show_boxed($p, $text, $left, $y, $width, $lineHeight, $mode, $feature);
$ret = text_block($p, $text,$left ,$width, $y); 
$lineSpace = 5;
$loopcount=155;
while($ret>0 and $loopcount > 0)  //write the whole text into the pdf line by line including the new line character
{
    $y = $y - $lineHeight-$lineSpace;
    $textleft = substr($text,-$ret);
    if($textleft[0] == "\n" or $textleft[0] == "\r") $textleft = substr($textleft,1); //this line decrements the $ret by 1.
    //$ret = pdf_show_boxed($p, $textleft, $left, $y, $width, $lineHeight, $mode, $feature);
	$loopcount--;

} //			pdf_show_boxed($p, $text,  $left,  $top,  $width,  $height, $mode, $feature );
}

function text_block($p,$text,$cols,$xcrd,$ycrd)
{
	$font_size=12;  //font size, used to space lines on y axis
	$tmplines = explode("\n",$text);
	for($j=0;$j<count($tmplines);$j++)
	    {
	    $tmptxt = explode(" ",$tmplines[$j]);
	    $str="";
	    for($i=0;$i<count($tmptxt);$i++)
	        {
	        if($str=="") $str=sprintf("%s",isset($tmptxt[$i])?$tmptxt[$i]:'');
	        else    $str=sprintf("%s %s",$str,isset($tmptxt[$i])?$tmptxt[$i]:'');
	        $tmt = isset($tmptxt[$i+1])?$tmptxt[$i]:'';
	        if((strlen($str) + strlen($tmt)) > $cols)
	            {
	            	//position=center
	            pdf_fit_textline($p,$str,$xcrd,$ycrd,"");
	            $str="";
	            $ycrd-=$font_size;
	            }
	        }
	    pdf_fit_textline($p,$str,$xcrd,$ycrd,"");
	    $ycrd-=$font_size;
	    }
	return $ycrd;
}

function pdf_put_title($p, $x, $y, $text, $font=NULL, $fontsize=NULL, $fontcolor=NULL) {
	$fontcolor = array();
	$fontcolor['r']=0.0;
	$fontcolor['g']=0.0;
	$fontcolor['b']=0.0;
	pdf_puttext($p, $x, $y, $text, $font, $fontsize, $fontcolor);
}

function pdf_put_value($p, $x, $y, $text, $font=NULL, $fontsize=NULL, $fontcolor=NULL) {
	$fontcolor = array();
	$fontcolor['r']=0.0;
	$fontcolor['g']=0.0;
	$fontcolor['b']=0.0;
	pdf_puttext($p, $x, $y, $text, $font, $fontsize, $fontcolor);
}

function pdf_put_label($p, $x, $y, $text, $font=NULL, $fontsize=NULL, $fontcolor=NULL) {
	$fontcolor = array();
	$fontcolor['r']=0.0;
	$fontcolor['g']=0.0;
	$fontcolor['b']=0.0;
	pdf_puttext($p, $x, $y, $text, $font, $fontsize, $fontcolor);
}

// Today's Date
function pdf_today($p, $x, $y, $font=NULL, $fontsize=NULL) {
	pdf_put_label($p, $x, $y, 'Document Date:', $font, $fontsize);
	$today=date("m/d/Y",time());
	pdf_put_value($p, $x+90, $y, $today, $font, $fontsize);
}

// Signature Date
function pdf_signaturedate($p, $x, $y, $font=NULL, $fontsize=NULL) {
	pdf_put_label($p, $x, $y, 'Date Signed:', $font, $fontsize);
	$today=date("m/d/Y",time());
	pdf_put_value($p, $x+90, $y, $today, $font, $fontsize);
}

// Header
function pdf_putheader($p, $x=32, $y=720, $header="Collection Letter") {
	// Logo
	pdf_putimage($p, $x, $y, $_SERVER['DOCUMENT_ROOT']."/img/NetPTLogo.gif", "GIF", 0.4);
	// Heading
	pdf_put_title($p, $x+205, $y+22, $header, $defaultfont, "16.0");
	// Document Title
	pdf_today($p, $x+395, $y);
}

function line($y, $l, $linespacing){
	return($y-$l*$linespacing);
}

function pdf_putbusinessunitheader($p, $x, $y, $data) {
	$remitaddressline1=$data['bulmaddress0'];
	$remitaddressline2=$data['bulmaddress1'].", ".$data['bulmcity'].", ".$data['bulmstate']." ".$data['bulmzip'];
	$remitaddressline3="TEL:".$data['bulmphone']."    FAX:".$data['bulmfax'];
	$text=" $remitaddressline1";
//   $remitaddressline2 
// $remitaddressline3
// ";
$w=210;
$h=300;
$linespacing=12;
pdf_puttextboxed($p, $x, $y, $w, $h, $remitaddressline1, "center");
pdf_puttextboxed($p, $x, $y-15, $w-24, $h, $remitaddressline2, "center");
pdf_puttextboxed($p, $x, $y-30, $w-14, $h, $remitaddressline3, "center");
//pdf_line($p, $x, line($y-3*12, 0.25, $linespacing), 576, line($y-3*12, 0.25, $linespacing), 0, 0, 0);
return($y-(3*16));
}

function pdf_putbusinessunitremitto($p, $x, $y, $data) {
	$text1="Please sign this letter; fax it back and make check payable to: ";
	$remitaddressline1=$data['bulmaddress0'];
	$remitaddressline2=$data['bulmaddress1'];
	$remitaddressline3=$data['bulmcity'].", ".$data['bulmstate']." ".$data['bulmzip'];
	$remitaddressline4="TAX ID#:".$data['bumtaxid'];
	$text="$remitaddressline1
$remitaddressline2
$remitaddressline3
$remitaddressline4";
$w=40;
$h=300;
pdf_puttext($p, $x+8, $y, $text1);
pdf_puttextboxed($p, 150, $y-40, $w, $h, $text);
return($y-16-(6*16));
}

function pdf_putcourtletterheader($p, $x, $y, $data) {
$line=array();

	$line[]="Date:".displayDate(today());
if(!empty($data['venuename']))
	$line[]=strtoupper($data['venuename']);
if(!empty($data['venueaddress1']))
	$line[]=strtoupper($data['venueaddress1']);
if(!empty($data['venueaddress2']))
	$line[]=strtoupper($data['venueaddress2']);
if(!empty($data['venueaddress3']))
	$line[]=strtoupper($data['venueaddress3']);
pdf_putwindowenvelopeaddress($p, $line);

$text="Action Date: ". $data['scheduledactiondate'] ."
Re: ".$data['first']." ".$data['last']."
WCAB No.: ".$data['wcab']."
Our File #: ".$data['pnum']."
Lien Amount: ".displayCurrency($data['lienamount'],",","$")."
CIC Status: ".$data['cicstatus'];
$w=261;
$h=16*4;
pdf_puttextboxed($p, 375, 585+18+(3*12), $w, $h, $text);
return($y-(15*12));
}

function pdf_putwindowenvelopeaddress($p, $addressarray) {
$line1=$addressarray[0];
$line2=$addressarray[1];
$line3=$addressarray[2];
$line4=$addressarray[3];
$line5=$addressarray[4];
$line6=$addressarray[5];
$line7=$addressarray[6];

$text="$line1
$line2
$line3
$line4
$line5
$line6
$line7
";
pdf_puttextboxed($p, 54+18, 610+18+(4*12), 450, 90-18-18, $text);
}

function pdf_putcollectionletterheader($p, $x, $y, $data) {
$line=array();

if(!empty( $data['offerdate'] ))
	$line[]="Date: ".displayDate($data['offerdate']);
if(!empty($data['insname']))
	$line[]=strtoupper($data['insname']);
if(!empty($data['insaddress1']))
	$line[]=strtoupper($data['insaddress1']);
if(!empty($data['insaddress2']))
	$line[]=strtoupper($data['insaddress2']);
if(!empty($data['insaddress3']))
	$line[]=strtoupper($data['insaddress3']);
if(!empty($data['adjusterfname']))
	$line[]=strtoupper("ATTN: " . $data['adjusterfname']." " . $data['adjusterlname']);

if($data['viausmail']=='USMAIL')
	$sentvia[]="Sent via United States Mail.";
if($data['viafax']=='FAX')
	$sentvia[]="Sent via facsimile:". displayPhone($data['insadjusterfax']);
if($data['viaemail']=='EMAIL')
	$sentvia[]="Sent via e-mail:". $data['insadjusteremail'];

if (is_array($sentvia)) {
    $text1=implode(" ", $sentvia);
} else {
    $text1="";
}

if(!empty($text1))
	$line[]=strtoupper($text1);
pdf_putwindowenvelopeaddress($p, $line);

$text="Re: ".$data['fname']." ".$data['lname']."
Claim No.: ".$data['claimnumber']."
Our File #: ".$data['pnum'];
$w=40;
$h=100;
pdf_puttextboxed($p, 375, 610+18+(3*12), $w, $h, $text);
return($y-(9*16));
}

function pdf_putcollectionletterfooter($p, $x, $y, $data) {
$text="Thank you in advance for your professional courtesy in the resolution to this matter.";

$text1="Sincerely,



".$data['collector']."
Lien Claimant Representative for
".$data['bulmaddress0'];

$text2="Accepted and agreed upon by (Sign and Date) _______________________ _________";

$text3 = $data['adjusterfname']." ".$data['adjusterlname'];
$w=40;
$h=300;
pdf_puttextboxed($p, $x+150, $y+20, $w, $h, $text);
pdf_puttextboxed($p, $x+150, $y-12, $w, $h, $text1);
pdf_puttextboxed($p, $x+900, $y-132, $w, $h, $text2);
pdf_puttextboxed($p, $x+900, $y-162, $w, $h, $text3);
return($y-(10*16));
}

function pdf_putpatientinformation($p, $x, $y, $data) {
	$patient=$data['patient'];
// Patient Information
	$totallines = 7.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); // 770-(7.5*12)=680?
// Line 6.5
	pdf_put_label($p, $x, line($newy, 0, $linespacing), "PATIENT INFORMATION");
// Line 5.5
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
// Line 5
	pdf_put_label($p, $x, 	line($newy, 1.5, $linespacing), "Name:");
	pdf_put_value($p, $x+85, line($newy, 1.5, $linespacing), $patient['name']);
//	pdf_input_area($p, $x+100, line($newy, 1.5, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_put_label($p, $x+272, line($newy, 1.5, $linespacing), "Social Security Number:");
	pdf_put_value($p, $x+410, line($newy, 1.5, $linespacing), $patient['ssn']);
// Line 4
	pdf_put_label($p, $x, 	line($newy, 2.5, $linespacing), "Address:");
	pdf_put_value($p, $x+85, line($newy, 2.5, $linespacing), $patient['address']);
	pdf_put_label($p, $x+272, line($newy, 2.5, $linespacing), "Sex:");
	pdf_put_value($p, $x+410, line($newy, 2.5, $linespacing), $patient['sex']);
// Line 3
	pdf_put_label($p, $x, 	line($newy, 3.5, $linespacing), "City, State Zip:");
	pdf_put_value($p, $x+85,	line($newy, 3.5, $linespacing), $patient['city']);
//	pdf_puttext($p, $x+150, line($newy, 3.5, $linespacing), "State:");
	pdf_put_value($p, $x+170, line($newy, 3.5, $linespacing), $patient['state']);
//	pdf_puttext($p, $x+200, line($newy, 3.5, $linespacing), "Zip:");
	pdf_put_value($p, $x+200, line($newy, 3.5, $linespacing), $patient['zip']);
	pdf_put_label($p, $x+272, line($newy, 3.5, $linespacing), "Date of Birth:");
	pdf_put_value($p, $x+410, line($newy, 3.5, $linespacing), $patient['birthdate']);
// Line 2
	pdf_put_label($p, $x, 	line($newy, 4.5, $linespacing), "Phone (Home):");
	pdf_put_value($p, $x+85, line($newy, 4.5, $linespacing), $patient['phonehome']);
	pdf_put_label($p, $x+272, line($newy, 4.5, $linespacing), "Age:");
	pdf_put_value($p, $x+410, line($newy, 4.5, $linespacing), $patient['age']);
// Line 1
	pdf_put_label($p, $x, 	line($newy, 5.5, $linespacing), "Phone (Work):");
	pdf_put_value($p, $x+85, line($newy, 5.5, $linespacing), $patient['phonework']);
	pdf_put_label($p, $x+272, line($newy, 5.5, $linespacing), "E-mail:");
	pdf_put_value($p, $x+410, line($newy, 5.5, $linespacing), $patient['email']);
// Line 0
	pdf_put_label($p, $x, 	line($newy, 6.5, $linespacing), "Phone (Cell):");
	pdf_put_value($p, $x+85, line($newy, 6.5, $linespacing), $patient['phonecell']);
	pdf_put_label($p, $x+272, line($newy, 6.5, $linespacing), "Occupation:");
	pdf_put_value($p, $x+410, line($newy, 6.5, $linespacing), $patient['occupation']);

	return($newy);
}

function pdf_putpatientinformationshort($p, $x, $y, $data) {
	$patient=$data['patient'];
// Patient Information
	$totallines = 2.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); // 770-(7.5*12)=680?
// Line 6.5
	pdf_put_label($p, $x, line($newy, 0, $linespacing), "PATIENT");
// Line 5.5
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
// Line 5
	pdf_put_label($p, $x, 	line($newy, 1.5, $linespacing), "Name:");
	pdf_put_value($p, $x+85, line($newy, 1.5, $linespacing), $patient['name']);
//	pdf_input_area($p, $x+100, line($newy, 1.5, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_put_label($p, $x+272, line($newy, 1.5, $linespacing), "Social Security Number:");
	pdf_put_value($p, $x+410, line($newy, 1.5, $linespacing), $patient['ssn']);
	return($newy);
}

function pdf_putcaseinjuryinformation($p, $x, $y, $data) {
	$case=$data['case'];
	$totallines = 3.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
// Injury Information
	pdf_put_label($p, $x, line($newy, 0, $linespacing), "INJURY INFORMATION");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_put_label($p, $x, line($newy, 1.5, $linespacing), "Date:");
	pdf_put_value($p, $x+85, line($newy, 1.5, $linespacing), $case['injurydate']);
	pdf_put_label($p, $x+272, line($newy, 1.5, $linespacing), "Post Surgical:");
	pdf_put_value($p, $x+410, line($newy, 1.5, $linespacing), $case['postsurgical']);

	pdf_put_label($p, $x, line($newy, 2.5, $linespacing), "Type:");
	pdf_put_value($p, $x+85, line($newy, 2.5, $linespacing), $case['casetype']);
	pdf_put_label($p, $x+272, line($newy, 2.5, $linespacing), "Surgery Date:");
	pdf_put_value($p, $x+410, line($newy, 2.5, $linespacing), $case['surgerydate']);

	return($newy);
}

function pdf_putreferralinformation($p, $x, $y, $data) {
	$doctor=$data['doctor'];
// Referral Information
	$totallines = 5.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);

	pdf_put_label($p, $x, line($newy, 0, $linespacing), "REFERRING DOCTOR INFORMATION");

	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);

	pdf_put_label($p, $x, line($newy, 1.5, $linespacing), "Name:");
	pdf_put_value($p, $x+85, line($newy, 1.5, $linespacing), $doctor['name']);
	pdf_put_label($p, $x+272, line($newy, 1.5, $linespacing), "Body Part(s):");
	pdf_put_value($p, $x+410, line($newy, 1.5, $linespacing), $rx['bodypart']);

	pdf_put_label($p, $x, line($newy, 2.5, $linespacing), "Address:");
	pdf_put_value($p, $x+85, line($newy, 2.5, $linespacing), $doctor['address']);
	pdf_put_label($p, $x+272, line($newy, 2.5, $linespacing), "Diagnosis:");
	pdf_put_value($p, $x+410, line($newy, 2.5, $linespacing), $rx['diagnosis']);

	pdf_put_label($p, $x, line($newy, 3.5, $linespacing), "City, State Zip:");
	pdf_put_value($p, $x+20, line($newy, 3.5, $linespacing), $doctor['city']);
//	pdf_puttext($p, $x+150, line($newy, 3.5, $linespacing), "State:");
	pdf_put_value($p, $x+170, line($newy, 3.5, $linespacing), $doctor['state']);
//	pdf_puttext($p, $x+200, line($newy, 3.5, $linespacing), "Zip:");
	pdf_put_value($p, $x+250, line($newy, 3.5, $linespacing), $doctor['zip']);

	pdf_put_label($p, $x, line($newy, 4.5, $linespacing), "Phone:");
	pdf_put_value($p, $x+85, line($newy, 4.5, $linespacing), $doctor['phone']);

	return($newy);
}

function pdf_putattorneyinformation($p, $x, $y, $data) {
	$attorney=$data['attorney'];
// Attorney Information
	$totallines = 5.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);

	pdf_puttext($p, $x, line($newy, 0, $linespacing), "ATTORNEY INFORMATION");

	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);

	pdf_puttext($p, $x, line($newy, 1.5, $linespacing), "Name:");
	pdf_puttext($p, $x+85, line($newy, 1.5, $linespacing), $attorney['name']);

	pdf_puttext($p, $x, line($newy, 2.5, $linespacing), "Address:");
	pdf_puttext($p, $x+85, line($newy, 2.5, $linespacing), $attorney['address']);

	pdf_puttext($p, $x, line($newy, 3.5, $linespacing), "City, State Zip:");
	pdf_puttext($p, $x+20, line($newy, 3.5, $linespacing), $attorney['city']);
//	pdf_puttext($p, $x+150, line($newy, 3.5, $linespacing), "State:");
	pdf_puttext($p, $x+170, line($newy, 3.5, $linespacing), $attorney['state']);
//	pdf_puttext($p, $x+200, line($newy, 3.5, $linespacing), "Zip:");
	pdf_puttext($p, $x+250, line($newy, 3.5, $linespacing), $attorney['zip']);

	pdf_puttext($p, $x, line($newy, 4.5, $linespacing), "Phone:");
	pdf_puttext($p, $x+85, line($newy, 4.5, $linespacing), $attorney['phone']);
	return($newy);
}

function pdf_putemploymentinformation($p, $x, $y, $data) {
	$employer=$data['employer'];
// Employment Information
	$totallines = 5.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);

	pdf_puttext($p, $x, line($newy, 0, $linespacing), "EMPLOYMENT INFORMATION");

	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);

	pdf_puttext($p, $x, line($newy, 1.5, $linespacing), "Name:");
	pdf_puttext($p, $x+85, line($newy, 1.5, $linespacing), $employer['name']);

	pdf_puttext($p, $x, line($newy, 2.5, $linespacing), "Address:");
	pdf_puttext($p, $x+85, line($newy, 2.5, $linespacing), $employer['address']);

	pdf_puttext($p, $x, line($newy, 3.5, $linespacing), "City, State Zip:");
	pdf_puttext($p, $x+20, line($newy, 3.5, $linespacing), $employer['city']);
//	pdf_puttext($p, $x+150, line($newy, 3.5, $linespacing), "State:");
	pdf_puttext($p, $x+170, line($newy, 3.5, $linespacing), $employer['state']);
//	pdf_puttext($p, $x+200, line($newy, 3.5, $linespacing), "Zip:");
	pdf_puttext($p, $x+250, line($newy, 3.5, $linespacing), $employer['zip']);

	pdf_puttext($p, $x, line($newy, 4.5, $linespacing), "Phone:");
	pdf_puttext($p, $x+85, line($newy, 4.5, $linespacing), $employer['phone']);
	return($newy);
}

function pdf_putinsurance1information($p, $x, $y, $data) {
	$insurance1=$data['insurance1'];
// Primary Insurance Information
	$totallines = 8.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "PRIMARY INSURANCE INFORMATION");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 270, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_puttext($p, $x, line($newy, 1.5, $linespacing), "Company Name:");
	pdf_puttext($p, $x+85, line($newy, 1.5, $linespacing), $insurance1['name']);
	pdf_puttext($p, $x, line($newy, 2.5, $linespacing), "Address:");
	pdf_puttext($p, $x+85, line($newy, 2.5, $linespacing), $insurance1['address']);
	pdf_puttext($p, $x, line($newy, 3.5, $linespacing), "City, State Zip:");
	pdf_puttext($p, $x+20, line($newy, 3.5, $linespacing), $insurance1['city']);
//	pdf_puttext($p, $x+150, line($newy, 3.5, $linespacing), "State:");
	pdf_puttext($p, $x+170, line($newy, 3.5, $linespacing), $insurance1['state']);
//	pdf_puttext($p, $x+200, line($newy, 3.5, $linespacing), "Zip:");
	pdf_puttext($p, $x+250, line($newy, 3.5, $linespacing), $insurance1['zip']);

	pdf_puttext($p, $x, line($newy, 4.5, $linespacing), "Adjuster:");
	pdf_puttext($p, $x+85, line($newy, 4.5, $linespacing), $insurance1['adjuster']);
	pdf_puttext($p, $x+172, line($newy, 4.5, $linespacing), "Phone:");
	pdf_puttext($p, $x+222, line($newy, 4.5, $linespacing), $insurance1['phone']);

	pdf_puttext($p, $x, line($newy, 5.5, $linespacing), "Type:");
	pdf_puttext($p, $x+85, line($newy, 5.5, $linespacing), $insurance1['type']);

	pdf_puttext($p, $x, line($newy, 6.5, $linespacing), "Insured Name:");
	pdf_puttext($p, $x+85, line($newy, 6.5, $linespacing), $insurance1['insured']);

	pdf_puttext($p, $x, line($newy, 7.5, $linespacing), "Policy/Group #:");
	pdf_puttext($p, $x+85, line($newy, 7.5, $linespacing), $insurance1['policy']);
	pdf_puttext($p, $x+172, line($newy, 7.5, $linespacing), "Claim #:");
	pdf_puttext($p, $x+222, line($newy, 7.5, $linespacing), $insurance1['claim']);
	return($newy);
}

function pdf_putinsurance2information($p, $x, $y, $data) {
	$insurance2=$data['insurance2'];
// Secondary Insurance Information
	$totallines = 8.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "SECONDARY INSURANCE INFORMATION");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_puttext($p, $x, line($newy, 1.5, $linespacing), "Company Name:");
	pdf_puttext($p, $x+85, line($newy, 1.5, $linespacing), $insurance2['name']);
	pdf_puttext($p, $x, line($newy, 2.5, $linespacing), "Address:");
	pdf_puttext($p, $x+85, line($newy, 2.5, $linespacing), $insurance2['address']);
	pdf_puttext($p, $x, line($newy, 3.5, $linespacing), "City State Zip:");
	pdf_puttext($p, $x+20, line($newy, 3.5, $linespacing), $insurance2['city']);
//	pdf_puttext($p, $x+150, line($newy, 3.5, $linespacing), "State:");
	pdf_puttext($p, $x+170, line($newy, 3.5, $linespacing), $insurance2['state']);
//	pdf_puttext($p, $x+200, line($newy, 3.5, $linespacing), "Zip:");
	pdf_puttext($p, $x+250, line($newy, 3.5, $linespacing), $insurance2['zip']);

	pdf_puttext($p, $x, line($newy, 4.5, $linespacing), "Adjuster:");
	pdf_puttext($p, $x+85, line($newy, 4.5, $linespacing), $insurance2['adjuster']);
	pdf_puttext($p, $x+172, line($newy, 4.5, $linespacing), "Phone:");
	pdf_puttext($p, $x+222, line($newy, 4.5, $linespacing), $insurance2['phone']);

	pdf_puttext($p, $x, line($newy, 5.5, $linespacing), "Type:");
	pdf_puttext($p, $x+85, line($newy, 5.5, $linespacing), $insurance2['type']);

	pdf_puttext($p, $x, line($newy, 6.5, $linespacing), "Insured Name:");
	pdf_puttext($p, $x+85, line($newy, 6.5, $linespacing), $insurance2['insured']);

	pdf_puttext($p, $x, line($newy, 7.5, $linespacing), "Policy/Group #:");
	pdf_puttext($p, $x+85, line($newy, 7.5, $linespacing), $insurance2['policy']);
	pdf_puttext($p, $x+172, line($newy, 7.5, $linespacing), "Claim #:");
	pdf_puttext($p, $x+222, line($newy, 7.5, $linespacing), $insurance2['claim']);
	return($newy);
}

function pdf_putauthorizationinformation($p, $x, $y, $data) {
	$patient=$data['patient'];
// Authorization Information
	$totallines = 9;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	pdf_put_label($p, $x, line($newy, 0, $linespacing), "RELEASE OF INFORMATION and ASSIGNMENT OF BENEFITS");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_put_value($p, $x, line($newy, 1.5, $linespacing), "I hereby authorize West-Star Physical Therapy to release information requested by my insurance ");
	pdf_put_value($p, $x, line($newy, 2.5, $linespacing), "carrier concerning this illness upon request. I hereby authorize direct payment of my insurance ");
	pdf_put_value($p, $x, line($newy, 3.5, $linespacing), "benefits to West-Star Physical Therapy for services rendered. I understand that I am financially ");
	pdf_put_value($p, $x, line($newy, 4.5, $linespacing), "responsible for non-covered services.");
	$today=date("m/d/Y",time());
	pdf_put_value($p, $x+425, line($newy, 6.5, $linespacing), $today);
	pdf_line($p, $x, line($newy, 6.75, $linespacing), $x+400, line($newy, 6.75, $linespacing), 0, 0, 0);
	pdf_line($p, $x+420, line($newy, 6.75, $linespacing), $x+550, line($newy, 6.75, $linespacing), 0, 0, 0);
	pdf_put_label($p, $x+5, line($newy, 8, $linespacing), $patient['name'] . ", Patient");
	pdf_put_label($p, $x+425, line($newy, 8, $linespacing), "Date Signed");
	return($newy);
}

function pdf_putJobInformation($p, $x, $y, $data) {
	$job=$data['job'];
// Job Information
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	$yesorno="(Yes)     (No )";
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "JOB INFORMATION");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_puttext($p, $x, line($newy, 1.5, $linespacing), "Job Title:");
	pdf_puttext($p, $x, line($newy, 2.5, $linespacing), "Description of Job Duties:");
	pdf_puttext($p, $x, line($newy, 8, $linespacing), "ADDITIONAL JOB DETAILS");
	pdf_line($p, $x, line($newy, 8.25, $linespacing), 576, line($newy, 8.25, $linespacing), 0, 0, 0);
	pdf_puttext($p, $x, line($newy, 9.5, $linespacing), "During a typical 8 hour day, How many hours do you...");
	pdf_puttext($p, $x+20, line($newy, 10.5, $linespacing), "Sit: ");
	pdf_puttext($p, $x+20, line($newy, 11.5, $linespacing), "Stand: ");
	pdf_puttext($p, $x+20, line($newy, 12.5, $linespacing), "Walk: ");
	pdf_puttext($p, $x+20, line($newy, 13.5, $linespacing), "Drive: ");

	pdf_puttext($p, $x, line($newy, 15, $linespacing), "At work, on average, how many hours do you work per...");
	pdf_puttext($p, $x+20, line($newy, 16, $linespacing), "Day/Shift: ");
	pdf_puttext($p, $x+20, line($newy, 17, $linespacing), "Week: ");

	pdf_puttext($p, $x, line($newy, 18.5, $linespacing), "At work, on average, how much time do you spend...");
	pdf_puttext($p, $x+20, line($newy, 19.5, $linespacing), "Squatting:");
	pdf_puttext($p, $x+20, line($newy, 20.5, $linespacing), "Stooping/bending:");
	pdf_puttext($p, $x+20, line($newy, 21.5, $linespacing), "Kneeling:");
	pdf_puttext($p, $x+20, line($newy, 22.5, $linespacing), "Reaching Up:");
	pdf_puttext($p, $x+20, line($newy, 23.5, $linespacing), "Reaching Out:");
	pdf_puttext($p, $x+20, line($newy, 24.5, $linespacing), "Twisting:");
	pdf_puttext($p, $x+20, line($newy, 25.5, $linespacing), "Crawling:");
	pdf_puttext($p, $x+20, line($newy, 26.5, $linespacing), "Stair Climbing:");
	pdf_puttext($p, $x+20, line($newy, 27.5, $linespacing), "Ladder Climbing:");
	pdf_puttext($p, $x+20, line($newy, 28.5, $linespacing), "Using a Computer:");
	pdf_puttext($p, $x+20, line($newy, 29.5, $linespacing), "Using the Telephone:");
	pdf_puttext($p, $x+20, line($newy, 30.5, $linespacing), "Pushing:");
	pdf_puttext($p, $x+20, line($newy, 31.5, $linespacing), "Pulling:");
	pdf_puttext($p, $x+20, line($newy, 32.5, $linespacing), "Lifting Overhead:");

	pdf_puttext($p, $x, line($newy, 34, $linespacing), "At work, my job requires that I lift...");
	pdf_puttext($p, $x+20, line($newy, 35, $linespacing), "10 lbs or less: Constantly, Often, Sometimes, Never");
	pdf_puttext($p, $x+20, line($newy, 36, $linespacing), "11 lbs to 24 lbs: Constantly, Often, Sometimes, Never");
	pdf_puttext($p, $x+20, line($newy, 37, $linespacing), "25 lbs to 34 lbs: Constantly, Often, Sometimes, Never");
	pdf_puttext($p, $x+20, line($newy, 38, $linespacing), "35 lbs to 50 lbs: Constantly, Often, Sometimes, Never");
	pdf_puttext($p, $x+20, line($newy, 39, $linespacing), "51 lbs to 74 lbs: Constantly, Often, Sometimes, Never");
	pdf_puttext($p, $x+20, line($newy, 40, $linespacing), "75 lbs to 100 lbs: Constantly, Often, Sometimes, Never");
	pdf_puttext($p, $x+20, line($newy, 41, $linespacing), "over 100 lbs: Constantly, Often, Sometimes, Never");

	pdf_puttext($p, $x, line($newy, 42.5, $linespacing), "At work, my job includes...");
	pdf_puttext($p, $x+20, line($newy, 43.5, $linespacing), "Repetitive Hand Movement");
	pdf_puttext($p, $x+20, line($newy, 44.5, $linespacing), "Repetitive Foot Movement");
	pdf_puttext($p, $x+20, line($newy, 45.5, $linespacing), "Power Gripping");
	pdf_puttext($p, $x+20, line($newy, 46.5, $linespacing), "Precision Handling");
	pdf_puttext($p, $x+20, line($newy, 47.5, $linespacing), "Balancing");
	pdf_puttext($p, $x+20, line($newy, 48.5, $linespacing), "Use of computer mouse/touch pad");
	pdf_puttext($p, $x+20, line($newy, 49.5, $linespacing), "Timed work for efficiency");
	pdf_puttext($p, $x+20, line($newy, 50.5, $linespacing), "Simultaneous use of computer and telephone");
}

function pdf_putinjuryinformation($p, $x, $y, $data) {
	$additional=$data['additional'];
// Additional Information
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	$yesorno="(Yes)     (No )";
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "INJURY INFORMATION");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_puttext($p, $x, line($newy, 1.5, $linespacing), "Briefly describe your injury:");
	pdf_puttext($p, $x, line($newy, 1.5, $linespacing), $additional['injurydescription']);
	pdf_puttext($p, $x, line($newy, 4.5, $linespacing), "Did you go to the Emergency Room at a Hospital?");
	pdf_puttext($p, $x+420, line($newy, 4.5, $linespacing), $additional['emergencyroom']);
	pdf_puttext($p, $x, line($newy, 5.5, $linespacing), "If not an Emergency Room, did you go to some other type of medical facility?");
	pdf_puttext($p, $x+420, line($newy, 5.5, $linespacing), $additional['otherfacility']);
	pdf_puttext($p, $x, line($newy, 6.5, $linespacing), "Were x-rays taken?");
	pdf_puttext($p, $x+420, line($newy, 6.5, $linespacing), $additional['xrays']);
	pdf_puttext($p, $x, line($newy, 7.5, $linespacing), "If an auto accident, was the vehicle drivable after the accident?");
	pdf_puttext($p, $x+420, line($newy, 7.5, $linespacing), $additional['autodrivable']);
	pdf_puttext($p, $x, line($newy, 8.5, $linespacing), "Do you have any previous injury to the same area?");
	pdf_puttext($p, $x+420, line($newy, 8.5, $linespacing), $additional['previousinjury']);
	pdf_puttext($p, $x, line($newy, 9.5, $linespacing), "Are you still being treated for this injury?");
	pdf_puttext($p, $x+420, line($newy, 9.5, $linespacing), $additional['stilltreating']);
	return($newy);
}

function pdf_putproviderinformation($p, $x, $y, $data) {
	$provider=$data['provider'];
// Provider Information
	$totallines = 5.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "If you are still being treated for this injury, by whom?");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_puttext($p, $x, line($newy, 1.5, $linespacing), "Name:");
	pdf_puttext($p, $x+85, line($newy, 1.5, $linespacing), $provider['name']);
	pdf_puttext($p, $x, line($newy, 2.5, $linespacing), "Address:");
	pdf_puttext($p, $x+85, line($newy, 2.5, $linespacing), $provider['address']);
	pdf_puttext($p, $x, line($newy, 3.5, $linespacing), "City, State Zip:");
	pdf_puttext($p, $x+20, line($newy, 3.5, $linespacing), $provider['city']);
//	pdf_puttext($p, $x+150, line($newy, 3.5, $linespacing), "State:");
	pdf_puttext($p, $x+170, line($newy, 3.5, $linespacing), $provider['state']);
//	pdf_puttext($p, $x+200, line($newy, 3.5, $linespacing), "Zip:");
	pdf_puttext($p, $x+250, line($newy, 3.5, $linespacing), $provider['zip']);
	pdf_puttext($p, $x, line($newy, 4.5, $linespacing), "Phone:");
	pdf_puttext($p, $x+85, line($newy, 4.5, $linespacing), $provider['phone']);
	return($newy);
}

function pdf_putpaininformation($p, $x, $y, $data) {
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "PAIN INFORMATION");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_puttext($p, $x+85, line($newy, 1, $linespacing), $provider['phone']);
	pdf_putimage($p, $x+200, $newy-400, $_SERVER['DOCUMENT_ROOT']."/img/body.gif", "GIF", 0.4);
	pdf_puttext($p, $x, line($newy, 2, $linespacing), "Draw the location of your pain on the body outlines.");
	pdf_puttext($p, $x, line($newy, 3, $linespacing), "Aches");
	pdf_puttext($p, $x, line($newy, 4, $linespacing), "Burning");
	pdf_puttext($p, $x, line($newy, 5, $linespacing), "Numbness");
	pdf_puttext($p, $x, line($newy, 6, $linespacing), "Pins & Needles");
	pdf_puttext($p, $x, line($newy, 7, $linespacing), "Stabbing");
	pdf_puttext($p, $x, line($newy, 8, $linespacing), "Other");
	return($newy);
}

function pdf_putwaiver($p, $x, $y, $data) {
	$patient=$data['patient'];
	$totallines = 12.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "WAIVER INFORMATION");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	$text="I, " . $patient['name'] . " AM OF LEGAL AGE AND HEREBY CERTIFY THAT I WENT TO WEST STAR PHYSICAL THERAPY OF MY OWN DISCRETION AND DECISION TO RECEIVE PHYSICAL THERAPY TREATMENTS. I UNDERSTAND THAT I MAY OR MAY NOT HAVE A DOCTORS� REFERRAL AND THAT GETTING PHYSICAL THERAPY IS MY TREATMENT OF CHOICE. I ALSO UNDERSTAND THAT I WILL BE EVALUATED BY A LICENSED AND CERTIFIED PHYSICAL THEREAPIST AND THAT THE THERAPIST�S EVALUATION AND RECOMMENDATION WILL BE EXPLAINED TO ME BEFORE TREATMENT. I UNDERSTAND THAT THE PHYSICAL THERAPIST WILL COMMUNICATE WITH MY MEDICAL DOCTOR TO GET AUTHORIZATION FOR MY PHYSICAL THERAPY TREATMENTS. I ALSO UNDERSTAND THAT I CANNOT RECEIVE PHYSICAL THERAPY TREATMENTS FROM WEST STAR PHYSICAL THERAPY WITHOUT SIGNED AUTHORIZATION FROM MY MEDICAL DOCTOR. FURTHERMORE, I UNDERSTAND THAT PHYSICAL THERAPY, WHILE DESIGNED TO, IS NOT GUARENTEED TO IMPROVE MY CURRENT CONDITION.";
	pdf_puttextboxed($p, 30, 610, 550, 10, $text);

	$text="IF MINOR:";
	pdf_puttextboxed($p, 35, 380, 550, 10, $text);

	$text="NAME OF PARENT OF GUARDIAN:\rRELATIONSHIP:\rPATIENT SIGNATURE:\rDATE:\n\nWITNESSED BY:\rNAME OF STAFF MEMBER:\rSIGNATURE:\rDATE:";
	pdf_puttextboxed($p, 45, 360, 200, 10, $text, 'right');

	$line="_____________________________________________";
	$text="$line\r$line\r$line\r$line\r\r$line\r$line\r$line\r$line";
	pdf_puttextboxed($p, 250, 360, 330, 10, $text);
	return($newy);
}

function pdf_putprivacypractices1($p, $x, $y, $data) {
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "PRIVACY INFORMATION Page (1 of 3)");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	$text="THIS NOTICE DESCRIBES HOW MEDICAL INFORMATION ABOUT YOU MAY BE USED AND DISCLOSED AND HOW YOU CAN GET ACCESS TO THIS INFORMATION. PLEASE REVIEW IT CAREFULLY

Uses and Disclosures

Treatment: Your health information may be used by staff members or disclosed to other health care professionals for the purpose of evaluating your health, diagnosing medical conditions and providing treatment. For Example, results of laboratory tests and procedures will be available in your medical record to all health professionals who may provide treatment or who may be consulted by staff members.

Payment: Your health information may be used to seek payment from your health plan, from other sources of coverage such as an automobile insurer, or from credit card companies that you may use to pay for services. For example, your health plan may request and receive information on dates of service, services provided and the medical condition being treated.

Health Care Operations: Your health information may be used as necessary to support the day-to-day activities of and management of West Star Physical Therapy. For Example, information on the services you received may be used to support budgeting and financial reporting and activities to evaluate and promote quality.

Law Enforcement: your health information may be disclosed to law enforcement agencies to support government audits and inspections, to facilitate law enforcement investigations and to comply with government mandated reporting.

Public Health Reporting: Your health care information may be disclosed to public health agencies as required by law. For example, we are required to report certain communicable diseases to the state�s public health department.

Other Uses and Disclosures That Require Your Authorization: Disclosure of your health information or its use for any purpose other than those listed above requires your specific, written authorization. However, your decision to revoke authorization will not affect of undo any use or disclosure of information that occurred before you notified us of your decision to revoke your authorization.
";
	pdf_puttextboxed($p, 30, 610, 550, 10, $text);
	return($newy);
}

function pdf_putprivacypractices2($p, $x, $y, $data) {
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "PRIVACY INFORMATION Page (2 of 3)");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	$text="Appointment Reminders: Your health information will be used by our staff to send you appointment reminders.

Information About Treatments: Your health information may be used to send you information that you may find interesting on the treatment and management of your medical condition. From our database, we may also send you information describing only West Star related information that may be of interest to you**

**______Please do not use my health information for the above-mentioned services.

Individual Rights: You have certain rights under the federal privacy standards. These include:

The right to request restrictions on the use and disclosure of your protected health care information;
The right to receive confidential communications concerning your medical condition and treatment;
The right to inspect and copy your protected health information;
The right to amend or submit corrections to your protected health care information;
The right to receive an accounting of how and to whom your protected health information has been disclosed;
The right to receive a printed copy of this notice.

West Star Physical Therapy Duties: We are required by law to maintain the privacy of your protected health information and to provide you with this notice of privacy practices.

We are also required to abide by the privacy policies and practices that are outlined in this notice.

Right to Revise Privacy Practices: As permitted by law, we reserve the right to amend to modify our privacy policies and practices. These changes in our policies and practices may be required by changes in federal and state laws and regulations. Upon request, we will provide you with the most recently revised notice on any office visits. The revise policies and practices will be applied to all protected health information we maintain.";
	pdf_puttextboxed($p, 30, 610, 550, 10, $text);
	return($newy);
}

function pdf_putprivacypractices3($p, $x, $y, $data) {
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "PRIVACY INFORMATION Page (3 of 3)");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	$text="Requests to Inspect Protected Health Information: You may generally inspect or copy the protected health information we maintain. As permitted by Federal Regulations we require that requests to inspect or copy protected health information be submitted in writing. You may obtain a form to request access to your records by contacting our corporate office. Your request will be reviewed and will generally be approved unless there are legal or medical reasons to deny the request.

Complaints: If you would like to submit a comment or complaint about our privacy practices, you can do so by sending a letter outlining your concerns to:

Connie Medeiros
West Star Physical Therapy
5400 Orange Street
Suite #215
Cypress, CA 90630

If you believe that your privacy rights have been violated, you should call the matter to our attention by sending a letter describing the cause of concern to the same address. You will not be penalized or otherwise retaliated against for filing a complaint.

Contact Person: The name and address of the person you may contact for further information concerning privacy practices is:

Connie Medeiros
West Star Physical Therapy
5400 Orange Street
Suite #215
Cypress, CA 90630

Effective Date: This notice is effective as of May 7, 2003
";
	pdf_puttextboxed($p, 30, 610, 550, 10, $text);
	return($newy);
}

function pdf_putprivacyacknowledgement($p, $x, $y, $data) {
	$patient=$data['patient'];
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "PRIVACY ACKNOWLEDGMENT INFORMATION");
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	$text="Acknowledgement of Receipt of Notice of Privacy Practices";
	pdf_puttextboxed($p, 30, 600, 550, 10, $text, 'center');

	$text="I " . $patient['name'] . ", have received, read and fully understand the Notice of Privacy Practices for West Star Physical therapy and acknowledge and understand that West Stat Physical therapy reserves the right to modify or amend the privacy practices outlined in the notice.";
	pdf_puttextboxed($p, 30, 580, 550, 10, $text);

	$text="Patient:\rSignature:\rDate:";
	pdf_puttextboxed($p, 45, 480, 200, 10, $text, 'right');

	$line="_____________________________________________";
	$text=$patient['name']."\r$line\r$line";
	pdf_puttextboxed($p, 250, 480, 330, 10, $text);

	$text="Patient Representative is required if the patient is a minor or patient is an adult who is unable to sign this form.";
	pdf_puttextboxed($p, 30, 400, 550, 10, $text);

	$text="Name of Patient Representative:\rRelationship to Patient:\rSignature:\rDate:";
	pdf_puttextboxed($p, 45, 360, 200, 10, $text, 'right');

	$text="$line\r$line\r$line\r$line";
	pdf_puttextboxed($p, 250, 360, 330, 10, $text);
	return($newy);
}

function find_angle ($x1, $y1, $x2, $y2) {
// This function takes two points (x1,y1) and (x2,y2)
// as inputs and finds the slope and angle of a line
// between those two points.  It returns the angle
// and slope in an array. I can't figure out how to
// return a NULL value, so if the two input points
// are in a vertical line, the function returns
// $angle = 90 and $slope = 0. I know this is wrong.
        if (($x2-$x1) != 0) {
                $slope = ($y2-$y1)/($x2-$x1);
                // Get rotation angle by finding the arctangent of the slope
                $angle = rad2deg(atan($slope));
                if ($x1 > $x2) {
                        $angle = 180+$angle;
                } elseif ($y1 > $y2) {
                        $angle = 360+$angle;
                }
        } else {
                // Vertical line has no slope, 90deg angle
                $angle = 90;
#               unset ($slope);
                $slope = 0;
        }
        return array ($angle, $slope);
}

function pdf_arrow ($pdfobj, $x1, $y1, $x2, $y2, $dashed) {
// This function will draw, stroke, and fill a line
// from (x1,y1) to (x2,y2) with an arrowhead defined
// by $headangle (in degrees) and $arrowlength.
// If $dashed is nonzero, a dashed line is drawn.
// REQUIRES: find_angle
        $headangle = 20;
        $arrowlength = 20;

        list ($angle, $slope) = find_angle($x1, $y1, $x2, $y2);

        pdf_moveto($pdfobj, $x2, $y2);

        // Find the two other points of the arrowhead
        // using $headangle and $arrowlength.
        $xarrow1 = $x2+cos(deg2rad(180+$angle+$headangle/2))*$arrowlength;
        $yarrow1 = $y2+sin(deg2rad(180+$angle+$headangle/2))*$arrowlength;
        $xarrow2 = $x2+cos(deg2rad(180+$angle-$headangle/2))*$arrowlength;
        $yarrow2 = $y2+sin(deg2rad(180+$angle-$headangle/2))*$arrowlength;
        // Draw two legs of the arrowhead, close and fill
        pdf_lineto($pdfobj, $xarrow1, $yarrow1);
        pdf_lineto($pdfobj, $xarrow2, $yarrow2);
        pdf_closepath($pdfobj);
        pdf_fill($pdfobj);

        // Find the point bisecting the short side
        // of the arrowhead. This is necessary so
        // the end of the line doesn't poke out the
        // beyond the arrow point.
        $x2line = ($xarrow1+$xarrow2)/2;
        $y2line = ($yarrow1+$yarrow2)/2;

        // Now draw the "body" line of the arrow
        if ($dashed != 0) {
                pdf_setdash($pdfobj,5,5);
        }
        pdf_moveto($pdfobj, $x1, $y1);
        pdf_lineto($pdfobj, $x2line, $y2line);
        pdf_stroke($pdfobj);
}

function pdf_line ($pdfobj, $x1, $y1, $x2, $y2, $dashed, $headangle=20, $arrowlength=20) {
// This function will draw, stroke, and fill a line
// from (x1,y1) to (x2,y2) with an arrowhead defined
// by $headangle (in degrees) and $arrowlength.
// If $dashed is nonzero, a dashed line is drawn.
// REQUIRES: find_angle

        list ($angle, $slope) = find_angle($x1, $y1, $x2, $y2);

        pdf_moveto($pdfobj, $x2, $y2);

        // Find the two other points of the arrowhead
        // using $headangle and $arrowlength.
        $xarrow1 = $x2+cos(deg2rad(180+$angle+$headangle/2))*$arrowlength;
        $yarrow1 = $y2+sin(deg2rad(180+$angle+$headangle/2))*$arrowlength;
        $xarrow2 = $x2+cos(deg2rad(180+$angle-$headangle/2))*$arrowlength;
        $yarrow2 = $y2+sin(deg2rad(180+$angle-$headangle/2))*$arrowlength;
        // Draw two legs of the arrowhead, close and fill
        pdf_lineto($pdfobj, $xarrow1, $yarrow1);
        pdf_lineto($pdfobj, $xarrow2, $yarrow2);
        pdf_closepath($pdfobj);
        pdf_fill($pdfobj);

        // Find the point bisecting the short side
        // of the arrowhead. This is necessary so
        // the end of the line doesn't poke out the
        // beyond the arrow point.
        $x2line = ($xarrow1+$xarrow2)/2;
        $y2line = ($yarrow1+$yarrow2)/2;

        // Now draw the "body" line of the arrow
        if ($dashed != 0) {
                pdf_setdash($pdfobj,5,5);
        }
        pdf_moveto($pdfobj, $x1, $y1);
        pdf_lineto($pdfobj, $x2line, $y2line);
        pdf_stroke($pdfobj);
}
?>