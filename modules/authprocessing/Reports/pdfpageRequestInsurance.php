<?php
// required arrays of information in $data
// $today - today's date
// $injurydate - Injury Date
// $fvisit - first visit
// $lvisit - last visit
// $patient - name, address, city, state, zip
// $employer - name, address, city, state, zip
// $attorney - name, address, city, state, zip
// $signedname -  name for signature
// $signedtitle - title for signature
function pdfRequestInsurance($p, $settings, $data) {
	// Font Helv 
	$defaultfont = pdf_findfont ($p , "Helvetica-Bold" , "winansi" , 0 );
	$font=$defaultfont;

	// First Page is 8.5 x 11
	$bodyleft=32;
	$bodytop=750;

	pdf_begin_page($p, 612, 792);
	// Print scannable ocr indexes for this document
//	pdf_put_ocr_index_info($p, $indexarray);

	// Body Page 1 is Patient
	$newbodytop = pdf_putbusinessunitheader($p, $bodyleft, $bodytop, $data); 			//	Business Unit Information
	$newbodytop = pdf_putstandardletterheader($p, $bodyleft, $newbodytop-10, $data, 'patient');//	Patient Letter Information
	$newbodytop = pdf_putRequestInsuranceletterbody($p, $bodyleft, $newbodytop, $data);			//	Offer Body Information
//	$newbodytop = pdf_putbusinessunitremitto($p, $bodyleft, $newbodytop-10, $data);		//	Remit to Information
//	$newbodytop = pdf_putcollectionletterfooter($p, $bodyleft, $newbodytop-10, $data);	//	Closing Information
	pdf_end_page($p);
	pdf_begin_page($p, 612, 792);
	// Print scannable ocr indexes for this document
//	pdf_put_ocr_index_info($p, $indexarray);

	// Body Page 1 is Patient
	$newbodytop = pdf_putbusinessunitheader($p, $bodyleft, $bodytop, $data); 			//	Business Unit Information
	$newbodytop = pdf_putstandardletterheader($p, $bodyleft, $newbodytop, $data, 'employer');//	Employer Letter Information
	$newbodytop = pdf_putRequestInsuranceletterbody($p, $bodyleft, $newbodytop, $data);			//	Offer Body Information
//	$newbodytop = pdf_putbusinessunitremitto($p, $bodyleft, $newbodytop-10, $data);		//	Remit to Information
//	$newbodytop = pdf_putcollectionletterfooter($p, $bodyleft, $newbodytop-10, $data);	//	Closing Information
	pdf_end_page($p);
	pdf_begin_page($p, 612, 792);
	// Print scannable ocr indexes for this document
//	pdf_put_ocr_index_info($p, $indexarray);

	// Body Page 1 is Patient
	$newbodytop = pdf_putbusinessunitheader($p, $bodyleft, $bodytop, $data); 			//	Business Unit Information
	$newbodytop = pdf_putstandardletterheader($p, $bodyleft, $newbodytop, $data, 'attorney');//	Attorney Letter Information
	$newbodytop = pdf_putRequestInsuranceletterbody($p, $bodyleft, $newbodytop, $data);			//	Offer Body Information
//	$newbodytop = pdf_putbusinessunitremitto($p, $bodyleft, $newbodytop-10, $data);		//	Remit to Information
//	$newbodytop = pdf_putcollectionletterfooter($p, $bodyleft, $newbodytop-10, $data);	//	Closing Information
	pdf_end_page($p);
}

function pdf_putRequestInsuranceletterbody($p, $x, $y, $data) {
	$patient=$data['patient'];
	$clinic=$data['clinic'];
	$case=$data['case'];
	$linespacing = pdf_getdefaultlinespacing();
// Regarding, Account, Dates etc.
	pdf_put_title($p, $x, line($y, 0, $linespacing), "Regarding:"); 
	pdf_put_value($p, $x+100, line($y, 0, $linespacing), $patient['name']); 

	pdf_put_title($p, $x, line($y, 1.5, $linespacing), "Account:"); 
	if($data['pnum']!='N/A') 
		pdf_put_value($p, $x+100, line($y, 1.5, $linespacing), $data['pnum']); 

	pdf_put_title($p, $x, line($y, 3, $linespacing), "Injury Date:"); 
	pdf_put_value($p, $x+100, line($y, 3, $linespacing), displayDate($data['injurydate'])); 

	pdf_put_title($p, $x, line($y, 4.5, $linespacing), "Service Date:"); 
	pdf_put_value($p, $x+100, line($y, 4.5, $linespacing), displayDate($data['fvisit'])." to ". displayDate($data['lvisit']) ); 
	$totallines = 7;
	$newy = line($y, $totallines, $linespacing); 

	$w=520;
	$h=300;
	$text=properCase($data['patient']['name'])." was referred to our ". properCase($data['clinic']['name'])." clinic for ".properCase($data['treatmenttype'])." services in reference to a ". properCase($data['casetype']) ." injury. 

We need to bill for these services; however, have been unable to do so as we have yet to be provided with the correct insurance information. At this time we are asking that you provide us with the correct insurance  information:";
pdf_puttextboxed($p, $x, $newy, $w, $h, $text);
	$totallines = 9.5;
	$newy = line($newy, $totallines, $linespacing); 

// Primary Insurance Information
	pdf_put_title($p, $x, line($newy, 0, $linespacing), "Employer's Insurance Company Information:"); 
	pdf_put_title($p, $x+35, line($newy, 1.5, $linespacing), "Company Name:"); 
	pdf_roundrect($p, $x+150, line($newy, 1.5, $linespacing)-4, 300, 14, 2);
	pdf_stroke($p);

	pdf_put_title($p, $x+35, line($newy, 3, $linespacing), "Address:"); 
	pdf_roundrect($p, $x+150, line($newy, 3, $linespacing)-4, 300, 14, 2);
	pdf_stroke($p);

	pdf_put_title($p, $x+35, line($newy, 4.5, $linespacing), " "); 
	pdf_roundrect($p, $x+150, line($newy, 4.5, $linespacing)-4, 300, 14, 2);
	pdf_stroke($p);

	pdf_put_title($p, $x+35, line($newy, 6, $linespacing), "City, State, Zip:"); 
	pdf_roundrect($p, $x+150, line($newy, 6, $linespacing)-4, 300, 14, 2);
	pdf_stroke($p);

	pdf_put_title($p, $x+35, line($newy, 7.5, $linespacing), "Claim/Policy No.:"); 
	pdf_roundrect($p, $x+150, line($newy, 7.5, $linespacing)-4, 300, 14, 2);
	pdf_stroke($p);

	pdf_put_title($p, $x+35, line($newy, 9, $linespacing), "Adjuster Name:"); 
	pdf_roundrect($p, $x+150, line($newy, 9, $linespacing)-4, 300, 14, 2);
	pdf_stroke($p);

	pdf_put_title($p, $x+35, line($newy, 10.5, $linespacing), "Telephone Number:"); 
	pdf_roundrect($p, $x+150, line($newy, 10.5, $linespacing)-4, 300, 14, 2);
	pdf_stroke($p);

	pdf_put_title($p, $x+35, line($newy, 12, $linespacing), "Fax Number:"); 
	pdf_roundrect($p, $x+150, line($newy, 12, $linespacing)-4, 300, 14, 2);
	pdf_stroke($p);
	$totallines = 14.5;
	$newy = line($newy, $totallines, $linespacing); 

$text2="Sincerely,

".properCase($data['signedname'])."
".properCase($data['signedtitle']);

pdf_puttextboxed($p, $x, $newy, $w, $h, $text2);

return($y-(35*16));
}
?>