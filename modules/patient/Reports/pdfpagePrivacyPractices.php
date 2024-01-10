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
function pdfPrivacyPractices($p, $settings, $data, $lang='en') {
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

	$bodyleft=30;
	$bodytop=710;

	$pdfPrivacyPracticesTitle['en']="Notice of Privacy Practices";
	$pdfPrivacyPracticesTitle['sp']="Notice of Privacy Practices";

	pdf_begin_page($p, 612, 792);
	pdf_putheader($p, 30, 700, $pdfPrivacyPracticesTitle["$lang"], $case['clinic'], $lang);
	$newbodytop = pdf_putpatientinformationshort($p, $bodyleft, $bodytop, $data, $lang); 	//		Patient Information
	$newbodytop = pdf_putprivacypractices1($p, $bodyleft, $newbodytop+84, $data, $lang);	//		Current Privacy Practices Information
	pdf_end_page($p);
	pdf_begin_page($p, 612, 792);
	pdf_putheader($p, 30, 700, $pdfPrivacyPracticesTitle["$lang"], $case['clinic'], $lang);
	$newbodytop = pdf_putpatientinformationshort($p, $bodyleft, $bodytop, $data, $lang); 	//		Patient Information
	$newbodytop = pdf_putprivacypractices2($p, $bodyleft, $newbodytop+84, $data, $lang);	//		Current Privacy Practices Information
	pdf_end_page($p);
	pdf_begin_page($p, 612, 792);
	pdf_putheader($p, 30, 700, $pdfPrivacyPracticesTitle["$lang"], $case['clinic'], $lang);
	$newbodytop = pdf_putpatientinformationshort($p, $bodyleft, $bodytop, $data, $lang); 	//		Patient Information
	$newbodytop = pdf_putprivacypractices3($p, $bodyleft, $newbodytop+84, $data, $lang);//		Current Privacy Practices Information
	pdf_end_page($p);
}
?>