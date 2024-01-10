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

function pdf_getdefaultfont($p){
// Courier, Courier-Bold, Courier-Oblique, Courier-BoldOblique, 
// Helvetica, Helvetica-Bold, Helvetica-Oblique, Helvetica-BoldOblique, 
// Times-Roman, Times-Bold, Times-Italic, Times-BoldItalic, 
// Symbol, ZapfDingbats
	$font = PDF_findfont ($p , "Helvetica" , "auto" , 0 );
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
	$defaultfontcolor['r']=.5;
	$defaultfontcolor['g']=.5;
	$defaultfontcolor['b']=.5;	
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

	pdf_setfont($p, $font, $fontsize);

	if($fontcolor==NULL) 
		$fontcolor=pdf_getdefaultfontcolor();
	pdf_setcolor($p, "fill", "rgb",$fontcolor['r'],$fontcolor['g'],$fontcolor['b']);
	pdf_set_text_pos($p, $x, $y);
	pdf_show($p, $text);
}

function pdf_put_title($p, $x, $y, $text, $font=NULL, $fontsize=NULL, $fontcolor=NULL) {
	$fontcolor = array();
	$fontcolor['r']=0;
	$fontcolor['g']=0;
	$fontcolor['b']=0;
	pdf_puttext($p, $x, $y, $text, $font, $fontsize, $fontcolor);
}

function pdf_put_value($p, $x, $y, $text, $font=NULL, $fontsize=NULL, $fontcolor=NULL) {
	$fontcolor = array();
	$fontcolor['r']=0;
	$fontcolor['g']=0;
	$fontcolor['b']=0;
	pdf_puttext($p, $x, $y, $text, $font, $fontsize, $fontcolor);
}

function pdf_put_label($p, $x, $y, $text, $font=NULL, $fontsize=NULL, $fontcolor=NULL) {
	$fontcolor = array();
	$fontcolor['r']=.5;
	$fontcolor['g']=.5;
	$fontcolor['b']=.5;
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
//	if($font==NULL)
//		$font=pdf_getdefaultfont($p);
//	if($fontsize==NULL)
//		$fontsize=pdf_getdefaultfontsize();
//	pdf_setfont($p, $font, $fontsize);
//	pdf_set_text_pos($p, $x, $y);
//	pdf_show($p, "Date Signed: ");
//	pdf_set_text_pos($p, $x+75, $y);
//	$today=date("m/d/Y",time());
//	pdf_show($p, $today);
	pdf_put_label($p, $x, $y, 'Date Signed:', $font, $fontsize);
	$today=date("m/d/Y",time());
	pdf_put_value($p, $x+90, $y, $today, $font, $fontsize);
}

// Header
function pdf_putheader($p, $x=32, $y=720) {
	// Logo
	pdf_putimage($p, $x, $y, $_SERVER['DOCUMENT_ROOT']."/img/NetPTLogo.gif", "GIF", 0.4);
	// Heading
	pdf_put_title($p, $x+205, $y+22, "Patient Information and Treatment Authorization", $defaultfont, "16.0");
	// Document Title
//	pdf_puttext($p, $x+265, $y+12, "Weststar Physical Therapy, Phone: 714-827-4822, Fax: 714-826-6833", $defaultfont, "9.0");
	// Today
	pdf_today($p, $x+395, $y);
}

function line($y, $l, $linespacing){
	return($y-$l*$linespacing);
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
	pdf_put_value($p, $x+410, line($newy, 3.5, $linespacing), $patient['birth']);
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
	pdf_put_label($p, $x, line($newy, 0, $linespacing), "PATIENT INFORMATION");
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

function pdf_putinjuryinformation($p, $x, $y, $data) {
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

function pdf_putadditionalinformation($p, $x, $y, $data) {
	$additional=$data['additional'];
// Additional Information
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 
	$yesorno="(Yes)     (No )";
	pdf_puttext($p, $x, line($newy, 0, $linespacing), "ADDITIONAL INFORMATION");
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
	$totallines = 12.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 
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

function pdfPatientInformation($p, $settings, $data) {
	$patient=$data['patient'];
	$doctor=$data['doctor'];
	$case=$data['case'];
	$rx=$data['rx'];
	$insurance1=$data['insurance1'];
	$insurance2=$data['insurance2'];
	$attorney=$data['attorney'];
	$employer=$data['employer'];
	// Font Helv 
	$defaultfont = pdf_findfont ($p , "Helvetica-Bold" , "winansi" , 0 );
	$font=$defaultfont;

	// First Page is 8.5 x 11
	$bodyleft=32;
	$bodytop=770;

	pdf_begin_page($p, 612, 792);
	pdf_putheader($p, 32, 700);

	// Print scannable ocr indexes for this document
//	pdf_put_ocr_index_info($p, $indexarray);

	// Body 
	$newbodytop = pdf_putpatientinformation($p, $bodyleft, $bodytop, $data); 						//		Patient Information
	$newbodytop = pdf_putinjuryinformation($p, $bodyleft, $newbodytop-(12*5.5), $data);			//		Injury Information
	$newbodytop = pdf_putreferralinformation($p, $bodyleft, $newbodytop-(12*0), $data);			//		Referral Information
	$newbodytop = pdf_putattorneyinformation($p, $bodyleft, $newbodytop-(12*1), $data);			//		Attorney Information
	$newbodytop = pdf_putemploymentinformation($p, $bodyleft, $newbodytop-(12*1), $data);			//		Employment Information
	pdf_putinsurance1information($p, $bodyleft, $newbodytop-(12*-2), $data);						//		Primary Insurance Information
	$newbodytop = pdf_putinsurance2information($p, $bodyleft+272, $newbodytop-(12*-2), $data);		//		Secondary Insurance Information
	$newbodytop = pdf_putauthorizationinformation($p, $bodyleft, $newbodytop-(12*2) ,$data);		//		Authorization Information
	pdf_end_page($p);
	
	// Second Page is 8.5 x 11
	$bodyleft=32;
	$bodytop=710;

	pdf_begin_page($p, 612, 792);
	pdf_putheader($p, 32, 700);
	$newbodytop = pdf_putpatientinformationshort($p, $bodyleft, $bodytop, $data); 						//		Patient Information
	$newbodytop = pdf_putadditionalinformation($p, $bodyleft, $newbodytop+(12*6.5), $data);					//		Additional Information
	$newbodytop = pdf_putproviderinformation($p, $bodyleft+20, $newbodytop-(12*5.5), $data);		//		Current Provider Information
	$newbodytop = pdf_putpaininformation($p, $bodyleft, $newbodytop-(12*(-6.5)), $data);		//		Current Provider Information
	pdf_end_page($p);
}
?>