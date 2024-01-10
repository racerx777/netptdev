<?php
if(function_exists('ssh2_connect'))
	echo('ssh2_connect supported');
else
	echo('ssh2_connect NOT supported');

phpinfo();
exit();
function pdf_getdefaultfont($p){
	$font = PDF_findfont ($p , "Helvetica-Bold" , "winansi" , 0 );
	return($font);
}

function pdf_getdefaultfontsize(){
	return("8.0");
}

// Logo
function pdf_putimage($p, $x, $y, $image, $imagetype, $scale=1) {
	$pdfimage = pdf_open_image_file($p, $imagetype, $image);
	pdf_place_image($p, $pdfimage, $x, $y, $scale);
}

// Text
function pdf_puttext($p, $x, $y, $text, $font=NULL, $fontsize=NULL) {
	if($font==NULL)
		$font=pdf_getdefaultfont($p);
	if($fontsize==NULL)
		$fontsize=pdf_getdefaultfontsize();
	PDF_setfont($p, $font, $fontsize);
	PDF_set_text_pos($p, $x, $y);
	PDF_show($p, $text);
}

// Today's Date
function pdf_today($p, $x, $y, $font=NULL, $fontsize=NULL) {
	if($font==NULL)
		$font=pdf_getdefaultfont($p);
	if($fontsize==NULL)
		$fontsize=pdf_getdefaultfontsize();
	PDF_setfont($p, $font, $fontsize);
	PDF_set_text_pos($p, $x, $y);
	PDF_show($p, "Today's Date: ");
	PDF_set_text_pos($p, $x+75, $y);
	$today=date("m/d/Y",time());
	PDF_show($p, $today);
}

// Signature Date
function pdf_signaturedate($p, $x, $y, $font=NULL, $fontsize=NULL) {
	if($font==NULL)
		$font=pdf_getdefaultfont($p);
	if($fontsize==NULL)
		$fontsize=pdf_getdefaultfontsize();
	PDF_setfont($p, $font, $fontsize);
	PDF_set_text_pos($p, $x, $y);
	PDF_show($p, "Date Signed: ");
	PDF_set_text_pos($p, $x+75, $y);
	$today=date("m/d/Y",time());
	PDF_show($p, $today);
}

// Header
function pdf_putheader($p, $x=32, $y=720) {
	// Logo
	pdf_putimage($p, $x, $y+14, $_SERVER['DOCUMENT_ROOT']."/img/NetPTLogo.gif", "GIF", 0.4);
	// Heading
	pdf_puttext($p, $x+223, $y+36, "Patient Information and Treatment Authorization", $defaultfont, "14.0");
	// Document Title
	pdf_puttext($p, $x+286, $y+28, "Weststar Physical Therapy, Phone: 714-827-4822, Fax: 714-826-6833", $defaultfont, "8.0");
	// Today
	pdf_today($p, $x+418, $y);
}

function pdf_putinjuryinformation($p, $x, $y) {
// Injury Information
	pdf_puttext($p, $x, $y+55, "INJURY INFORMATION");
	pdf_line($p, $x, $y+51, 576, $y+51, 0, 0, 0);
	pdf_puttext($p, $x, $y+40, "Date:");
	pdf_puttext($p, $x+100, $y+40, $injury);
	pdf_puttext($p, $x, $y+30, "Type:");
	pdf_puttext($p, $x+100, $y+30, $casetype);
	pdf_puttext($p, $x+272, $y+40, "Post Surgical:");
	pdf_puttext($p, $x+372, $y+40, $postsurgical);
	pdf_puttext($p, $x+272, $y+30, "Surgery Date:");
	pdf_puttext($p, $x+372, $y+30, $surgery);

	pdf_puttext($p, $x, $y+20, "Briefly describe your injury:");
	pdf_puttext($p, $x, $y, $injurydescription);
}

function pdf_putpatientinformation($p, $x, $y) {
// Patient Information
	pdf_puttext($p, $x, $y+65, "PATIENT INFORMATION");
	pdf_line($p, $x, $y+61, 576, $y+61, 0, 0, 0);
	pdf_puttext($p, $x, $y+50, "Name:");
	pdf_puttext($p, $x+100, $y+50, $patientname);
	pdf_puttext($p, $x, $y+40, "Address:");
	pdf_puttext($p, $x+100, $y+40, $patientaddress);
	pdf_puttext($p, $x, $y+30, "City:");
	pdf_puttext($p, $x+20, $y+30, $patientcity);
	pdf_puttext($p, $x+150, $y+30, "State:");
	pdf_puttext($p, $x+170, $y+30, $patientstate);
	pdf_puttext($p, $x+200, $y+30, "Zip:");
	pdf_puttext($p, $x+250, $y+30, $patientzip);
	pdf_puttext($p, $x, $y+20, "Phone (Home):");
	pdf_puttext($p, $x+100, $y+20, $patientphonehome);
	pdf_puttext($p, $x, $y+10, "Phone (Work):");
	pdf_puttext($p, $x+100, $y+10, $patientphonework);
	pdf_puttext($p, $x, $y, "Phone (Cell):");
	pdf_puttext($p, $x+100, $y, $patientphonecell);
	pdf_puttext($p, $x+272, $y+50, "Social Security Number:");
	pdf_puttext($p, $x+372, $y+50, $patientssn);
	pdf_puttext($p, $x+272, $y+40, "Sex:");
	pdf_puttext($p, $x+372, $y+40, $patientsex);
	pdf_puttext($p, $x+272, $y+30, "Date of Birth:");
	pdf_puttext($p, $x+372, $y+30, $patientbirth);
	pdf_puttext($p, $x+272, $y+20, "Age:");
	pdf_puttext($p, $x+372, $y+20, $patientage);
	pdf_puttext($p, $x+272, $y+10, "E-mail:");
	pdf_puttext($p, $x+372, $y+10, $patientemail);
	pdf_puttext($p, $x+272, $y, "Occupation:");
	pdf_puttext($p, $x+372, $y, $patientoccupation);
}

function pdf_putreferralinformation($p, $x, $y) {
// Referral Information
	pdf_puttext($p, $x, $y+45, "REFERRING DOCTOR INFORMATION");
	pdf_line($p, $x, $y+41, 576, $y+41, 0, 0, 0);
	pdf_puttext($p, $x, $y+30, "Name:");
	pdf_puttext($p, $x+100, $y+30, $doctorname);
	pdf_puttext($p, $x, $y+20, "Address:");
	pdf_puttext($p, $x+100, $y+20, $doctoraddress);
	pdf_puttext($p, $x, $y+10, "City:");
	pdf_puttext($p, $x+20, $y+10, $doctorcity);
	pdf_puttext($p, $x+150, $y+10, "State:");
	pdf_puttext($p, $x+170, $y+10, $doctorstate);
	pdf_puttext($p, $x+200, $y+10, "Zip:");
	pdf_puttext($p, $x+250, $y+10, $doctorzip);
	pdf_puttext($p, $x, $y, "Phone:");
	pdf_puttext($p, $x+100, $y, $doctorphone);

	pdf_puttext($p, $x+272, $y+30, "Body Part(s):");
	pdf_puttext($p, $x+372, $y+30, $doctorbodypart);
	pdf_puttext($p, $x+272, $y+20, "Diagnosis:");
	pdf_puttext($p, $x+372, $y+20, $doctordiagnosis);
}

function pdf_putemploymentinformation($p, $x, $y) {
// Employment Information
	pdf_puttext($p, $x, $y+45, "EMPLOYMENT INFORMATION");
	pdf_line($p, $x, $y+41, 576, $y+41, 0, 0, 0);
	pdf_puttext($p, $x, $y+30, "Name:");
	pdf_puttext($p, $x+100, $y+30, $employername);
	pdf_puttext($p, $x, $y+20, "Address:");
	pdf_puttext($p, $x+100, $y+20, $employeraddress);
	pdf_puttext($p, $x, $y+10, "City:");
	pdf_puttext($p, $x+20, $y+10, $employercity);
	pdf_puttext($p, $x+150, $y+10, "State:");
	pdf_puttext($p, $x+170, $y+10, $employerstate);
	pdf_puttext($p, $x+200, $y+10, "Zip:");
	pdf_puttext($p, $x+250, $y+10, $employerzip);
	pdf_puttext($p, $x, $y, "Phone:");
	pdf_puttext($p, $x+100, $y, $employerphone);
}

function pdf_putattorneyinformation($p, $x, $y) {
// Attorney Information
	pdf_puttext($p, $x, $y+45, "ATTORNEY INFORMATION");
	pdf_line($p, $x, $y+41, 576, $y+41, 0, 0, 0);
	pdf_puttext($p, $x, $y+30, "Name:");
	pdf_puttext($p, $x+100, $y+30, $attorneyname);
	pdf_puttext($p, $x, $y+20, "Address:");
	pdf_puttext($p, $x+100, $y+20, $attorneyaddress);
	pdf_puttext($p, $x, $y+10, "City:");
	pdf_puttext($p, $x+20, $y+10, $attorneycity);
	pdf_puttext($p, $x+150, $y+10, "State:");
	pdf_puttext($p, $x+170, $y+10, $attorneystate);
	pdf_puttext($p, $x+200, $y+10, "Zip:");
	pdf_puttext($p, $x+250, $y+10, $attorneyzip);
	pdf_puttext($p, $x, $y, "Phone:");
	pdf_puttext($p, $x+100, $y, $attorneyphone);
}

function pdf_putadditionalinformation($p, $x, $y) {
// Additional Information
	$yesorno="(Yes)     (No )";
	pdf_puttext($p, $x, $y+65, "ADDITIONAL INFORMATION");
	pdf_line($p, $x, $y+61, 576, $y+61, 0, 0, 0);
	pdf_puttext($p, $x, $y+50, "Did you go to the Emergency Room at a Hospital?");
	pdf_puttext($p, $x+400, $y+50, $yesorno);
	pdf_puttext($p, $x, $y+40, "If not an Emergency Room, did you go to some other type of medical facility?");
	pdf_puttext($p, $x+400, $y+40, $yesorno);
	pdf_puttext($p, $x, $y+30, "Were x-rays taken?");
	pdf_puttext($p, $x+400, $y+30, $yesorno);
	pdf_puttext($p, $x, $y+20, "If an auto accident, was the vehicle drivable after the accident?");
	pdf_puttext($p, $x+400, $y+20, $yesorno);
	pdf_puttext($p, $x, $y+10, "Do you have any previous injury to the same area?");
	pdf_puttext($p, $x+400, $y+10, $yesorno);
	pdf_puttext($p, $x, $y, "Are you still being treated for this injury?");
	pdf_puttext($p, $x+400, $y, $yesorno);
}

function pdf_putproviderinformation($p, $x, $y) {
// Provider Information
	pdf_puttext($p, $x, $y+45, "If you are still being treated for this injury, by whom?");
	pdf_line($p, $x, $y+41, 576, $y+41, 0, 0, 0);
	pdf_puttext($p, $x, $y+30, "Name:");
	pdf_puttext($p, $x+100, $y+30, $providername);
	pdf_puttext($p, $x, $y+20, "Address:");
	pdf_puttext($p, $x+100, $y+20, $provideraddress);
	pdf_puttext($p, $x, $y+10, "City:");
	pdf_puttext($p, $x+20, $y+10, $providercity);
	pdf_puttext($p, $x+150, $y+10, "State:");
	pdf_puttext($p, $x+170, $y+10, $providerstate);
	pdf_puttext($p, $x+200, $y+10, "Zip:");
	pdf_puttext($p, $x+250, $y+10, $providerzip);
	pdf_puttext($p, $x, $y, "Phone:");
	pdf_puttext($p, $x+100, $y, $providerphone);
}

function pdf_putinsurance1information($p, $x, $y) {
// Primary Insurance Information
	pdf_puttext($p, $x, $y+75, "PRIMARY INSURANCE INFORMATION");
	pdf_line($p, $x, $y+71, 270, $y+71, 0, 0, 0);
	pdf_puttext($p, $x, $y+60, "Company Name:");
	pdf_puttext($p, $x+100, $y+60, $providername);
	pdf_puttext($p, $x, $y+50, "Address:");
	pdf_puttext($p, $x+100, $y+50, $provideraddress);
	pdf_puttext($p, $x, $y+40, "City:");
	pdf_puttext($p, $x+20, $y+40, $providercity);
	pdf_puttext($p, $x+150, $y+40, "State:");
	pdf_puttext($p, $x+170, $y+40, $providerstate);
	pdf_puttext($p, $x+200, $y+40, "Zip:");
	pdf_puttext($p, $x+250, $y+40, $providerzip);

	pdf_puttext($p, $x, $y+30, "Adjuster:");
	pdf_puttext($p, $x+100, $y+30, $providerphone);
	pdf_puttext($p, $x+172, $y+30, "Phone:");
	pdf_puttext($p, $x+222, $y+30, $providerphone);

	pdf_puttext($p, $x, $y+20, "Type:");
	pdf_puttext($p, $x+100, $y+20, $providerphone);

	pdf_puttext($p, $x, $y+10, "Insured Name:");
	pdf_puttext($p, $x+100, $y+10, $providerphone);

	pdf_puttext($p, $x, $y, "Policy/Group #:");
	pdf_puttext($p, $x+100, $y, $providerphone);
	pdf_puttext($p, $x+172, $y, "Claim #:");
	pdf_puttext($p, $x+222, $y, $providerphone);
}

function pdf_putinsurance2information($p, $x, $y) {
// Secondary Insurance Information
	pdf_puttext($p, $x, $y+75, "SECONDARY INSURANCE INFORMATION");
	pdf_line($p, $x, $y+71, 576, $y+71, 0, 0, 0);
	pdf_puttext($p, $x, $y+60, "Company Name:");
	pdf_puttext($p, $x+100, $y+60, $providername);
	pdf_puttext($p, $x, $y+50, "Address:");
	pdf_puttext($p, $x+100, $y+50, $provideraddress);
	pdf_puttext($p, $x, $y+40, "City:");
	pdf_puttext($p, $x+20, $y+40, $providercity);
	pdf_puttext($p, $x+150, $y+40, "State:");
	pdf_puttext($p, $x+170, $y+40, $providerstate);
	pdf_puttext($p, $x+200, $y+40, "Zip:");
	pdf_puttext($p, $x+250, $y+40, $providerzip);

	pdf_puttext($p, $x, $y+30, "Adjuster:");
	pdf_puttext($p, $x+100, $y+30, $providerphone);
	pdf_puttext($p, $x+172, $y+30, "Phone:");
	pdf_puttext($p, $x+222, $y+30, $providerphone);

	pdf_puttext($p, $x, $y+20, "Type:");
	pdf_puttext($p, $x+100, $y+20, $providerphone);

	pdf_puttext($p, $x, $y+10, "Insured Name:");
	pdf_puttext($p, $x+100, $y+10, $providerphone);

	pdf_puttext($p, $x, $y, "Policy/Group #:");
	pdf_puttext($p, $x+100, $y, $providerphone);
	pdf_puttext($p, $x+172, $y, "Claim #:");
	pdf_puttext($p, $x+222, $y, $providerphone);
}

function pdf_putauthorizationinformation($p, $x, $y) {
// Authorization Information
	pdf_puttext($p, $x, $y+65, "RELEASE OF INFORMATION and ASSIGNMENT OF BENEFITS");
	pdf_line($p, $x, $y+61, 576, $y+61, 0, 0, 0);
	pdf_puttext($p, $x, $y+50, "I hereby authorize West-Star Physical Therapy to release information requested by my insurance carrier concerning this illness upon request. I ");
	pdf_puttext($p, $x, $y+40, "hereby authorize direct payment of my insurance benefits to West-Star Physical Therapy for services rendered. I understand that I am financially");
	pdf_puttext($p, $x, $y+30, "responsible for non-covered services.");

	$today=date("m/d/Y",time());
	pdf_puttext($p, $x+418,$y+11, $today);

	pdf_line($p, $x, $y+8, $x+400, $y+8, 0, 0, 0);
	pdf_line($p, $x+418, $y+8, $x+562, $y+8, 0, 0, 0);

	pdf_puttext($p, $x, $y, "[PATIENT NAME], Patient");
	pdf_puttext($p, $x+418, $y, "Date Signed");
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


//phpinfo(); LOOK INTO fpdf class for creating pdf files without pdflib
$p = PDF_new();
pdf_open_file($p); 
/*  open new PDF file; insert a file name to create the PDF on disk */
PDF_set_info($p, "Creator", "patientintakepackage.php");
PDF_set_info($p, "Author", "NetPT");
PDF_set_info($p, "Title", "Patient Intake Package");

// Page is 8.5 x 11
PDF_begin_page($p, 612, 792);

// Font Helv 
$defaultfont = PDF_findfont ($p , "Helvetica-Bold" , "winansi" , 0 );
$font=$defaultfont;

pdf_putheader($p, 32, 720);

// Body 
$bodyleft=32;
$bodytop=635;

//		Patient Information
pdf_putpatientinformation($p, $bodyleft, $bodytop);

//		Injury Information
pdf_putinjuryinformation($p, $bodyleft, $bodytop-70);

//		Referral Information
pdf_putreferralinformation($p, $bodyleft, $bodytop-130);

//		Attorney Information
pdf_putattorneyinformation($p, $bodyleft, $bodytop-190);

//		Employment Information
pdf_putemploymentinformation($p, $bodyleft, $bodytop-250);

//		Additional Information
pdf_putadditionalinformation($p, $bodyleft, $bodytop-330);

//		Current Provider Information
pdf_putproviderinformation($p, $bodyleft+20, $bodytop-390);

//		Primary Insurance Information
pdf_putinsurance1information($p, $bodyleft, $bodytop-480);

//		Secondary Insurance Information
pdf_putinsurance2information($p, $bodyleft+272, $bodytop-480);

//		Authorization Information
pdf_putauthorizationinformation($p, $bodyleft, $bodytop-560);

PDF_end_page($p);
PDF_close($p);
$buf = PDF_get_buffer($p);
$len = strlen($buf);
header("Content-type: application/pdf");
header("Content-Length: $len");
header("Content-Disposition: inline; filename=patientintakepackage.pdf");
print $buf;
PDF_delete($p);
?>