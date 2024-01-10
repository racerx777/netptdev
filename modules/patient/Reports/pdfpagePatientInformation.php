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
function pdfPatientInformation($p, $settings, $data, $lang="en") {
	$patient=$data['patient'];
	$doctor=$data['doctor'];
	$case=$data['case'];
	$rx=$data['rx'];
	$insurance1=$data['insurance1'];
	$insurance2=$data['insurance2'];
	$attorney=$data['attorney'];
	$employer=$data['employer'];
	// Font Helv 
	$defaultfont = PDF_load_font ($p , "Helvetica-Bold" , "winansi" , '' );
	$font=$defaultfont;

	$pdfPatientInformation_Title['en']="Patient Information and Treatment Authorization";
//	$pdfPatientInformation_Title['sp']="Informacion Del Paciente Y Authorization\npara El Tratamiento";
	$pdfPatientInformation_Title['sp']="Informacion y Autorizacion Para El Tratamiento\nDel Paciente";
	
	// First Page is 8.5 x 11
	$bodyleft=32;
	$bodytop=770;

	PDF_begin_page_ext($p, 612, 792,'');
//	pdf_putheader($p, 32, 700, "Patient Information and Treatment Authorization", $case['clinic']);
	pdf_putheader($p, 32, 700, $pdfPatientInformation_Title["$lang"], $case['clinic'], $lang);

	// Print scannable ocr indexes for this document
//	pdf_put_ocr_index_info($p, $indexarray);

	// Body 
	$newbodytop = pdf_putpatientinformation($p, $bodyleft, $bodytop, $data, $lang); 			//Patient Info
	$newbodytop = pdf_putcaseinjuryinformation($p, $bodyleft, $newbodytop-(12*5.5), $data, $lang);//Injury Info
	$newbodytop = pdf_putreferralinformation($p, $bodyleft, $newbodytop-(12*0), $data, $lang); //Referral Info
	$newbodytop = pdf_putattorneyinformation($p, $bodyleft, $newbodytop-(12*1), $data, $lang);	//Attorney Info
	$newbodytop = pdf_putemploymentinformation($p, $bodyleft, $newbodytop-(12*1), $data, $lang); //Employment Info
	pdf_putinsurance1information($p, $bodyleft, $newbodytop-(12*-2), $data, $lang); 	// Primary Insurance Info
	$newbodytop = pdf_putinsurance2information($p, $bodyleft+272, $newbodytop-(12*-2), $data, $lang);//Secondary Insurance Info
	$newbodytop = pdf_putauthorizationinformation($p, $bodyleft, $newbodytop-(16) ,$data, $lang);//Authorization Infor
	PDF_end_page_ext($p,'');
}
?>