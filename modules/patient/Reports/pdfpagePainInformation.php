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
function pdfPainInformation($p, $settings, $data, $lang='en') {
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

	$bodyleft=32;
	$bodytop=710;

	$pdfPainInformationTitle['en']="Pain Information";
	$pdfPainInformationTitle['sp']="Pain Informacion";
	
	pdf_begin_page($p, 612, 792);
	pdf_putheader($p, 32, 700, $pdfPainInformationTitle["$lang"], $case['clinic'], $lang);
	$newbodytop = pdf_putpatientinformationshort($p, $bodyleft, $bodytop, $data, $lang); 			//		Patient Information
	$newbodytop = pdf_putpaininformation($p, $bodyleft, $newbodytop-(12*(-6.5)), $data, $lang);	//		Current Pain Information
	pdf_end_page($p);
}
?>