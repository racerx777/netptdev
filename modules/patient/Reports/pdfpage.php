<?php
function pdf_getdefaultfont($p){
// Courier, Courier-Bold, Courier-Oblique, Courier-BoldOblique, 
// Helvetica, Helvetica-Bold, Helvetica-Oblique, Helvetica-BoldOblique, 
// Times-Roman, Times-Bold, Times-Italic, Times-BoldItalic, 
// Symbol, ZapfDingbats
	$font = PDF_load_font ($p , "Helvetica" , "auto" , '' );
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
	$pdfimage = PDF_load_image($p, $imagetype, $image,'');
	PDF_fit_image($p, $pdfimage, $x, $y, "scale ".$scale);
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
	pdf_setcolor($p, "fill", "rgb",$fontcolor['r'],$fontcolor['g'],$fontcolor['b'],0xAA / 255);
	pdf_set_text_pos($p, $x, $y);
	pdf_show($p, $text);
}

function pdf_puttextboxed($p, $left, $top, $width, $height, $text, $mode="left", $feature="", $font=NULL, $fontsize=NULL, $fontcolor=NULL) {
	if($font==NULL)
		$font=pdf_getdefaultfont($p);

	if($fontsize==NULL)
		$fontsize=pdf_getdefaultfontsize();

	pdf_setfont($p, $font, $fontsize);

	if($fontcolor==NULL) 
		$fontcolor=pdf_getdefaultfontcolor();
	pdf_setcolor($p, "fill", "rgb",$fontcolor['r'],$fontcolor['g'],$fontcolor['b'],0xAA / 255);
	pdf_set_text_pos($p, $left, $top);
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
			//$ret = PDF_fit_textline($p, $text, $left, $y,$feature); 
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
	        if($str=="") $str=sprintf("%s",$tmptxt[$i]);
	        else    $str=sprintf("%s %s",$str,$tmptxt[$i]);
	        if((strlen($str) + strlen($tmptxt[$i+1])) > $cols)
	            {
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
	$fontcolor['r']=0;
	$fontcolor['g']=0;
	$fontcolor['b']=0;
	$width="240";
	$height="50";
	$mode="left";
	$feature="";
	pdf_puttextboxed($p, $x, $y, $width, $height, $text, $mode, "", $font, $fontsize, $fontcolor);
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
function pdf_today($p, $x, $y, $font=NULL, $fontsize=NULL, $lang='en') {
	$pdf_todayTitle['en']='Document Date:';
	$pdf_todayTitle['sp']='Fecha:';

	pdf_put_label($p, $x, $y, $pdf_todayTitle["$lang"], $font, $fontsize);
	$today=date("m/d/Y",time());
	pdf_put_value($p, $x+90, $y, $today, $font, $fontsize);
}

// Signature Date
function pdf_signaturedate($p, $x, $y, $font=NULL, $fontsize=NULL, $lang='en') {
	$pdf_signaturedateTitle['en']='Date Signed:';
	$pdf_signaturedateTitle['sp']='Fecha firmada:';

	pdf_put_label($p, $x, $y, $pdf_signaturedateTitle["$lang"], $font, $fontsize);
	$today=date("m/d/Y",time());
	pdf_put_value($p, $x+90, $y, $today, $font, $fontsize);
}

// Header
function pdf_putheader($p, $x=32, $y=720, $header="Patient Information and Treatment Authorization", $clinic=NULL, $lang='en') {
	// Logo
	pdf_putimage($p, $x, $y, $_SERVER['DOCUMENT_ROOT']."/img/NetPTLogo.gif", "GIF", 0.4);
	// Heading
	pdf_put_title($p, $x+205, $y+22, $header, $defaultfont, "16.0");
	// Document Title
	pdf_today($p, $x+395, $y, NULL, NULL, $lang);
//	pdf_put_label($p, $x+395, $y-16, "Clinic:");
	pdf_put_value($p, $x+395, $y-16, $clinic);
}

function line($y, $l, $linespacing){
	return($y-$l*$linespacing);
}

function pdf_roundrect($pdfobj, $xpos, $ypos, $xsize, $ysize, $radius)
{
 $ypos = $ypos+$ysize;
 pdf_moveto($pdfobj, $xpos, $ypos-$radius);
 pdf_lineto($pdfobj, $xpos, $ypos-$ysize+$radius);
 pdf_arc($pdfobj, $xpos+$radius, $ypos-$ysize+$radius, $radius, 180, 270);
 pdf_lineto($pdfobj, $xpos+$xsize-$radius, $ypos-$ysize);
 pdf_arc($pdfobj, $xpos+$xsize-$radius, $ypos-$ysize+$radius, $radius, 270, 360);
 pdf_lineto($pdfobj, $xpos+$xsize, $ypos-$radius);
 pdf_arc($pdfobj, $xpos+$xsize-$radius, $ypos-$radius, $radius,0,90);
 pdf_lineto($pdfobj, $xpos+$radius, $ypos);
 pdf_arc($pdfobj, $xpos+$radius, $ypos-$radius, $radius,90,180);
}

function pdf_putpatientinformation($p, $x, $y, $data, $lang='en') {
	$patient=$data['patient'];
	$case=$data['case'];
// Patient Information
	$totallines = 7.5;
	$linespacing = pdf_getdefaultlinespacing();

	$pdf_putpatientinformation_Title['en']="PATIENT INFORMATION";
	$pdf_putpatientinformation_Name['en']="Name:";
	$pdf_putpatientinformation_SSN['en']="SSN:";
	$pdf_putpatientinformation_Address['en']="Address:";
	$pdf_putpatientinformation_Sex['en']="Sex:";
	$pdf_putpatientinformation_CityStZip['en']="City,St Zip:";
	$pdf_putpatientinformation_DOB['en']="DOB:";
	$pdf_putpatientinformation_HomePhone['en']="Home Ph:";
	$pdf_putpatientinformation_Age['en']="Age:";
	$pdf_putpatientinformation_WorkPhone['en']="Work Ph:";
	$pdf_putpatientinformation_Email['en']="E-mail:";
	$pdf_putpatientinformation_CellPhone['en']="Cell Ph:";
	$pdf_putpatientinformation_Occupation['en']="Occup:";

	$pdf_putpatientinformation_Title['sp']="INFORMACION DEL PACIENTE";
	$pdf_putpatientinformation_Name['sp']="Nombre:";
	$pdf_putpatientinformation_SSN['sp']="SSN:";
	$pdf_putpatientinformation_Address['sp']="Direccion:";
	$pdf_putpatientinformation_Sex['sp']="Sexo:";
	$pdf_putpatientinformation_CityStZip['sp']="Ciudad, Zip:";
	$pdf_putpatientinformation_DOB['sp']="FDN:";
	$pdf_putpatientinformation_HomePhone['sp']="Teléfono:";
	$pdf_putpatientinformation_Age['sp']="Edad:";
	$pdf_putpatientinformation_WorkPhone['sp']="Telé trabajo:";
	$pdf_putpatientinformation_Email['sp']="Email:";
	$pdf_putpatientinformation_CellPhone['sp']="Telé celular:";
	$pdf_putpatientinformation_Occupation['sp']="Ocupación:";
	
	pdf_setcolor($p, 'stroke', 'rgb', 128/255, 128/255, 128/255,0xAA / 255);
	pdf_setlinewidth($p, 0.25);

	$newy = line($y, $totallines, $linespacing); // 770-(7.5*12)=680?
// Line 6.5
	pdf_put_label($p, $x, line($newy, 0, $linespacing), $pdf_putpatientinformation_Title["$lang"].' #'.$data['case']['crpnum']);
// Line 5.5
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
// Line 5
	pdf_put_label($p, $x, 	line($newy, 1.5, $linespacing), $pdf_putpatientinformation_Name["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 1.5, $linespacing), $patient['name']);
	pdf_roundrect($p, $x+65, $newy-(19+(0*12)), 200, 11, 2); 
	pdf_stroke($p);

//	pdf_input_area($p, $x+100, line($newy, 1.5, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_put_label($p, $x+290, line($newy, 1.5, $linespacing), $pdf_putpatientinformation_SSN["$lang"]);
	pdf_put_value($p, $x+349, line($newy, 1.5, $linespacing), $patient['ssn']);
	pdf_roundrect($p, $x+344, $newy-(19+(0*12)), 200, 11, 2); 
	pdf_stroke($p);

// Line 4

	pdf_put_label($p, $x, 	line($newy, 2.5, $linespacing), $pdf_putpatientinformation_Address["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 2.5, $linespacing), $patient['address']);
	pdf_roundrect($p, $x+65, $newy-(19+(1*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x+290, line($newy, 2.5, $linespacing), $pdf_putpatientinformation_Sex["$lang"]);
	pdf_put_value($p, $x+349, line($newy, 2.5, $linespacing), $patient['sex']);
	pdf_roundrect($p, $x+344, $newy-(19+(1*12)), 200, 11, 2); 
	pdf_stroke($p);
// Line 3

	pdf_put_label($p, $x, 	line($newy, 3.5, $linespacing), $pdf_putpatientinformation_CityStZip["$lang"]);
	pdf_put_value($p, $x+70,	line($newy, 3.5, $linespacing), $patient['city']);
//	pdf_put_value($p, $x+170, line($newy, 3.5, $linespacing), $patient['state']);
	pdf_put_value($p, $x+210, line($newy, 3.5, $linespacing), $patient['zip']);
	pdf_roundrect($p, $x+65, $newy-(19+(2*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x+290, line($newy, 3.5, $linespacing), $pdf_putpatientinformation_DOB["$lang"]);
	pdf_put_value($p, $x+349, line($newy, 3.5, $linespacing), $patient['birthdate']);
	pdf_roundrect($p, $x+344, $newy-(19+(2*12)), 200, 11, 2); 
	pdf_stroke($p);

// Line 2
	pdf_put_label($p, $x, 	line($newy, 4.5, $linespacing), $pdf_putpatientinformation_HomePhone["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 4.5, $linespacing), $patient['phonehome']);
	pdf_roundrect($p, $x+65, $newy-(19+(3*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x+290, line($newy, 4.5, $linespacing), $pdf_putpatientinformation_Age["$lang"]);
	pdf_put_value($p, $x+349, line($newy, 4.5, $linespacing), $patient['age']);
	pdf_roundrect($p, $x+344, $newy-(19+(3*12)), 200, 11, 2); 
	pdf_stroke($p);

// Line 1
	pdf_put_label($p, $x, 	line($newy, 5.5, $linespacing), $pdf_putpatientinformation_WorkPhone["$lang"]);
	pdf_put_value($p, $x+85, line($newy, 5.5, $linespacing), $patient['phonework']);
	pdf_roundrect($p, $x+65, $newy-(19+(4*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x+290, line($newy, 5.5, $linespacing), $pdf_putpatientinformation_Email["$lang"]);
	pdf_put_value($p, $x+349, line($newy, 5.5, $linespacing), $patient['email']);
	pdf_roundrect($p, $x+344, $newy-(19+(4*12)), 200, 11, 2); 
	pdf_stroke($p);

// Line 0
	pdf_put_label($p, $x, 	line($newy, 6.5, $linespacing), $pdf_putpatientinformation_CellPhone["$lang"]);
	pdf_put_value($p, $x+85, line($newy, 6.5, $linespacing), $patient['phonecell']);
	pdf_roundrect($p, $x+65, $newy-(19+(5*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x+290, line($newy, 6.5, $linespacing), $pdf_putpatientinformation_Occup["$lang"]);
	pdf_put_value($p, $x+349, line($newy, 6.5, $linespacing), $patient['occupation']);
	pdf_roundrect($p, $x+344, $newy-(19+(5*12)), 200, 11, 2); 
	pdf_stroke($p);
	
	return($newy);
}

function pdf_putpatientinformationshort($p, $x, $y, $data, $lang='en') {
	$patient=$data['patient'];
// Patient Information
	$totallines = 2.5;
	$linespacing = pdf_getdefaultlinespacing();

	$pdf_putpatientinformationshort_Title['en']="PATIENT";
	$pdf_putpatientinformationshort_Name['en']="Name:";
	$pdf_putpatientinformationshort_SSN['en']="SSN:";

	$pdf_putpatientinformationshort_Title['sp']="PACIENTE";
	$pdf_putpatientinformationshort_Name['sp']="Nombre:";
	$pdf_putpatientinformationshort_SSN['sp']="SSN:";
	
	pdf_setcolor($p, 'stroke', 'rgb', 128/255, 128/255, 128/255);
	pdf_setlinewidth($p, 0.25);

	$newy = line($y, $totallines, $linespacing); // 770-(7.5*12)=680?
// Line 6.5
	pdf_put_label($p, $x, line($newy, 0, $linespacing), $pdf_putpatientinformationshort_Title["$lang"].' #'.$data['case']['crpnum']);
// Line 5.5
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
// Line 5
	pdf_put_label($p, $x, 	line($newy, 1.5, $linespacing), $pdf_putpatientinformationshort_Name["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 1.5, $linespacing), $patient['name']);
	pdf_roundrect($p, $x+65, $newy-(19+(0*12)), 200, 11, 2); 
	pdf_stroke($p);

//	pdf_input_area($p, $x+100, line($newy, 1.5, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_put_label($p, $x+290, line($newy, 1.5, $linespacing), $pdf_putpatientinformationshort_SSN["$lang"]);
	pdf_put_value($p, $x+349, line($newy, 1.5, $linespacing), $patient['ssn']);	
	pdf_roundrect($p, $x+344, $newy-(19+(0*12)), 200, 11, 2); 
	pdf_stroke($p);
	return($newy);
}

function pdf_putcaseinjuryinformation($p, $x, $y, $data, $lang='en') {
	$case=$data['case'];
	$totallines = 3.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 
	$pdf_putcaseinjuryinformationTitle['en']="INJURY INFORMATION";
	$pdf_putcaseinjuryinformationDate['en']="Date:";
	$pdf_putcaseinjuryinformationPostSx['en']="Post Sx:";
	$pdf_putcaseinjuryinformationType['en']="Type:";
	$pdf_putcaseinjuryinformationSxDate['en']="Sx Date:";

	$pdf_putcaseinjuryinformationTitle['sp']="INFORMACIÓN DE ACCIDENTE";
	$pdf_putcaseinjuryinformationDate['sp']="FDA:";
	$pdf_putcaseinjuryinformationPostSx['sp']="Post-cirugia:";
	$pdf_putcaseinjuryinformationType['sp']="Tipo:";
	$pdf_putcaseinjuryinformationSxDate['sp']="FDC:";

// Injury Information
	pdf_put_label($p, $x, line($newy, 0, $linespacing), $pdf_putcaseinjuryinformationTitle["$lang"]);
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_put_label($p, $x, line($newy, 1.5, $linespacing), $pdf_putcaseinjuryinformationDate["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 1.5, $linespacing), $case['injurydate']);
	pdf_roundrect($p, $x+65, $newy-(19+(0*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x+290, line($newy, 1.5, $linespacing), $pdf_putcaseinjuryinformationPostSx["$lang"]);
	pdf_put_value($p, $x+349, line($newy, 1.5, $linespacing), $case['postsurgical']);
	pdf_roundrect($p, $x+344, $newy-(19+(0*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 2.5, $linespacing), $pdf_putcaseinjuryinformationType["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 2.5, $linespacing), $case['casetype']);
	pdf_roundrect($p, $x+65, $newy-(19+(1*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x+290, line($newy, 2.5, $linespacing), $pdf_putcaseinjuryinformationSxDate["$lang"]);
	pdf_put_value($p, $x+349, line($newy, 2.5, $linespacing), $case['surgerydate']);
	pdf_roundrect($p, $x+344, $newy-(19+(1*12)), 200, 11, 2); 
	pdf_stroke($p);

	return($newy);
}

function pdf_putreferralinformation($p, $x, $y, $data, $lang='en') {
	$doctor=$data['doctor'];
	$rx=$data['rx'];
// Referral Information
	$totallines = 5.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 

	$pdf_putreferralinformationTitle['en']="REFERRING DOCTOR INFORMATION";
	$pdf_putreferralinformationName['en']="Name:";
	$pdf_putreferralinformationBodyPts['en']="Body Pts:";
	$pdf_putreferralinformationAddress['en']="Address:";
	$pdf_putreferralinformationDiag['en']="Dx:";
	$pdf_putreferralinformationCityZip['en']="City, Zip:";
	$pdf_putreferralinformationPhone['en']="Phone:";

//	$pdf_putreferralinformationTitle['sp']="REFERIR AL DOCTOR INFORMACIÓN";
	$pdf_putreferralinformationTitle['sp']="DOCTOR QUE REFIRIO AL PACIENTE INFORMACIÓN";
	$pdf_putreferralinformationName['sp']="Nombre:";
	$pdf_putreferralinformationBodyPts['sp']="Partes del cuerpo:";
	$pdf_putreferralinformationAddress['sp']="Dirección:";
	$pdf_putreferralinformationDiag['sp']="Dx:";
	$pdf_putreferralinformationCityZip['sp']="Ciudad, Zip:";
	$pdf_putreferralinformationPhone['sp']="Teléfono:";

	pdf_put_label($p, $x, line($newy, 0, $linespacing), $pdf_putreferralinformationTitle["$lang"]);

	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);

	pdf_put_label($p, $x, line($newy, 1.5, $linespacing), $pdf_putreferralinformationName["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 1.5, $linespacing), $doctor['name']);
	pdf_roundrect($p, $x+65, $newy-(19+(0*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_puttextboxed($p, 0 /* $x */, line($newy, 1.5, $linespacing), 320,50, $pdf_putreferralinformationBodyPts["$lang"]);
	pdf_put_value($p, $x+349, line($newy, 1.5, $linespacing), $rx['bodypart']);
	pdf_roundrect($p, $x+344, $newy-(19+(2*12)), 200, 36, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 2.5, $linespacing), $pdf_putreferralinformationAddress["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 2.5, $linespacing), $doctor['address']);
	pdf_roundrect($p, $x+65, $newy-(19+(1*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 3.5, $linespacing), $pdf_putreferralinformationCityZip["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 3.5, $linespacing), $doctor['city']);
	pdf_put_value($p, $x+235, line($newy, 3.5, $linespacing), $doctor['zip']);
	pdf_roundrect($p, $x+65, $newy-(19+(2*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 4.5, $linespacing), $pdf_putreferralinformationPhone["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 4.5, $linespacing), $doctor['phone']);
	pdf_roundrect($p, $x+65, $newy-(19+(3*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x+290, line($newy, 4.5, $linespacing), $pdf_putreferralinformationDiag["$lang"]);
	pdf_put_value($p, $x+349, line($newy, 4.5, $linespacing), $rx['dx']);
	pdf_roundrect($p, $x+344, $newy-(19+(3*12)), 200, 11, 2); 
	pdf_stroke($p);

	return($newy);
}

function pdf_putattorneyinformation($p, $x, $y, $data, $lang='en') {
	$attorney=$data['attorney'];
//dump("attorney",$attorney);
//exit();
// Attorney Information
	$totallines = 5.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 

	$pdf_putattorneyinformationTitle['en']="ATTORNEY INFORMATION";
	$pdf_putattorneyinformationName['en']="Name:";
	$pdf_putattorneyinformationAddress['en']="Address:";
	$pdf_putattorneyinformationCityZip['en']="City, Zip:";
	$pdf_putattorneyinformationPhone['en']="Phone:";
	
	$pdf_putattorneyinformationTitle['sp']="INFORMACIÓN DEL ABOGADO";
	$pdf_putattorneyinformationName['sp']="Nombre:";
	$pdf_putattorneyinformationAddress['sp']="Dirección:";
	$pdf_putattorneyinformationCityZip['sp']="Ciudad, Zip:";
	$pdf_putattorneyinformationPhone['sp']="Teléfono:";
	
	pdf_puttext($p, $x, line($newy, 0, $linespacing), $pdf_putattorneyinformationTitle["$lang"]);

	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);

	pdf_put_label($p, $x, line($newy, 1.5, $linespacing), $pdf_putattorneyinformationName["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 1.5, $linespacing), $attorney['name']);
	pdf_roundrect($p, $x+65, $newy-(19+(0*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 2.5, $linespacing), $pdf_putattorneyinformationAddress["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 2.5, $linespacing), $attorney['address']);
	pdf_roundrect($p, $x+65, $newy-(19+(1*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 3.5, $linespacing), $pdf_putattorneyinformationCityZip["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 3.5, $linespacing), $attorney['city']);
	pdf_put_value($p, $x+250, line($newy, 3.5, $linespacing), $attorney['zip']);
	pdf_roundrect($p, $x+65, $newy-(19+(2*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 4.5, $linespacing), $pdf_putattorneyinformationPhone["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 4.5, $linespacing), $attorney['phone']);
	pdf_roundrect($p, $x+65, $newy-(19+(3*12)), 200, 11, 2); 
	pdf_stroke($p);

	return($newy);
}

function pdf_putemploymentinformation($p, $x, $y, $data, $lang='en') {
	$employer=$data['employer'];
// Employment Information
	$totallines = 5.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 

	$pdf_putemploymentinformationTitle['en']="EMPLOYMENT INFORMATION";
	$pdf_putemploymentinformationName['en']="Name:";
	$pdf_putemploymentinformationAddress['en']="Address:";
	$pdf_putemploymentinformationCityZip['en']="City, Zip:";
	$pdf_putemploymentinformationPhone['en']="Phone:";

	$pdf_putemploymentinformationTitle['sp']="INFORMACIÓN DE EMPLEO";
	$pdf_putemploymentinformationName['sp']="Nombre:";
	$pdf_putemploymentinformationAddress['sp']="Dirección:";
	$pdf_putemploymentinformationCityZip['sp']="Ciudad, Zip:";
	$pdf_putemploymentinformationPhone['sp']="Teléfono:";
	
	pdf_puttext($p, $x, line($newy, 0, $linespacing), $pdf_putemploymentinformationTitle["$lang"]);

	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);

	pdf_put_label($p, $x, line($newy, 1.5, $linespacing), $pdf_putemploymentinformationName["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 1.5, $linespacing), $employer['name']);
	pdf_roundrect($p, $x+65, $newy-(19+(0*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 2.5, $linespacing), $pdf_putemploymentinformationAddress["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 2.5, $linespacing), $employer['address']);
	pdf_roundrect($p, $x+65, $newy-(19+(1*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 3.5, $linespacing), $pdf_putemploymentinformationCityZip["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 3.5, $linespacing), $employer['city']);
	pdf_put_value($p, $x+235, line($newy, 3.5, $linespacing), $employer['zip']);
	pdf_roundrect($p, $x+65, $newy-(19+(2*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 4.5, $linespacing), $pdf_putemploymentinformationPhone["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 4.5, $linespacing), $employer['phone']);
	pdf_roundrect($p, $x+65, $newy-(19+(3*12)), 200, 11, 2); 
	pdf_stroke($p);

	return($newy);
}

function pdf_putinsurance1information($p, $x, $y, $data, $lang='en') {
	$insurance1=$data['insurance1'];
// Primary Insurance Information
	$totallines = 8.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing);

	$pdf_putinsurance1informationTitle['en']="PRIMARY INSURANCE INFORMATION";
	$pdf_putinsurance1informationName['en']="Name:";
	$pdf_putinsurance1informationAddress['en']="Address";
	$pdf_putinsurance1informationAdjPh['en']="Adj/Ph#";
	$pdf_putinsurance1informationType['en']="Type";
	$pdf_putinsurance1informationInsName['en']="Ins Name:";
	$pdf_putinsurance1informationPolClm['en']="Pol#/Clm#:";

	$pdf_putinsurance1informationTitle['sp']="INFORMACIÓN DE ASEGURANZA MEDICA";
	$pdf_putinsurance1informationName['sp']="Nombre:";
	$pdf_putinsurance1informationAddress['sp']="Dirección";
	$pdf_putinsurance1informationAdjPh['sp']="Adj/Ph#";
	$pdf_putinsurance1informationType['sp']="Tipo";
	$pdf_putinsurance1informationInsName['sp']="Asegurados:";
	$pdf_putinsurance1informationPolClm['sp']="Pol#/Clm#:";

	pdf_puttext($p, $x, line($newy, 0, $linespacing), $pdf_putinsurance1informationTitle["$lang"]);

	pdf_line($p, $x, line($newy, 0.25, $linespacing), 270, line($newy, 0.25, $linespacing), 0, 0, 0);

	pdf_put_label($p, $x, line($newy, 1.5, $linespacing), $pdf_putinsurance1informationName["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 1.5, $linespacing), $insurance1['name']);
	pdf_roundrect($p, $x+65, $newy-(19+(0*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 2.5, $linespacing), $pdf_putinsurance1informationAddress["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 2.5, $linespacing), $insurance1['address']);
	pdf_roundrect($p, $x+65, $newy-(19+(1*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_value($p, $x+70, line($newy, 3.5, $linespacing), $insurance1['city']);
	pdf_put_value($p, $x+180, line($newy, 3.5, $linespacing), $insurance1['state']);
	pdf_put_value($p, $x+260, line($newy, 3.5, $linespacing), $insurance1['zip']);
	pdf_roundrect($p, $x+65, $newy-(19+(2*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 4.5, $linespacing), $pdf_putinsurance1informationAdjPh["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 4.5, $linespacing), $insurance1['adjuster']);
	pdf_roundrect($p, $x+65, $newy-(19+(3*12)), 120, 11, 2); 
	pdf_stroke($p);

	pdf_put_value($p, $x+190, line($newy, 4.5, $linespacing), $insurance1['phone']);
	pdf_roundrect($p, $x+185, $newy-(19+(3*12)), 80, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 5.5, $linespacing), $pdf_putinsurance1informationType["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 5.5, $linespacing), $insurance1['type']);
	pdf_roundrect($p, $x+65, $newy-(19+(4*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 6.5, $linespacing), $pdf_putinsurance1informationInsName["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 6.5, $linespacing), $insurance1['insured']);
	pdf_roundrect($p, $x+65, $newy-(19+(5*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 7.5, $linespacing), $pdf_putinsurance1informationPolClm["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 7.5, $linespacing), $insurance1['policy']);
	pdf_roundrect($p, $x+65, $newy-(19+(6*12)), 120, 11, 2); 
	pdf_stroke($p);

	pdf_put_value($p, $x+190, line($newy, 7.5, $linespacing), $insurance1['claim']);
	pdf_roundrect($p, $x+185, $newy-(19+(6*12)), 80, 11, 2); 
	pdf_stroke($p);

	return($newy);
}

function pdf_putinsurance2information($p, $x, $y, $data, $lang='en') {
	$insurance2=$data['insurance2'];
// Secondary Insurance Information
	$totallines = 8.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 

	$pdf_putinsurance2informationTitle['en']="SECONDARY INSURANCE INFORMATION";
	$pdf_putinsurance2informationName['en']="Name:";
	$pdf_putinsurance2informationAddress['en']="Address";
	$pdf_putinsurance2informationAdjPh['en']="Adj/Ph#";
	$pdf_putinsurance2informationType['en']="Type";
	$pdf_putinsurance2informationInsName['en']="Ins Name:";
	$pdf_putinsurance2informationPolClm['en']="Pol#/Clm#:";

	$pdf_putinsurance2informationTitle['sp']="INFORMACIÓN SECUNDARIA DE ASEGURANZA MEDICA";
	$pdf_putinsurance2informationName['sp']="Nombre:";
	$pdf_putinsurance2informationAddress['sp']="Dirección";
	$pdf_putinsurance2informationAdjPh['sp']="Adj/Ph#";
	$pdf_putinsurance2informationType['sp']="Tipo";
	$pdf_putinsurance2informationInsName['sp']="Asegurados:";
	$pdf_putinsurance2informationPolClm['sp']="Pol#/Clm#:";

	pdf_puttext($p, $x-20, line($newy, 0, $linespacing), $pdf_putinsurance2informationTitle["$lang"]);

	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);

	pdf_put_label($p, $x, line($newy, 1.5, $linespacing), $pdf_putinsurance2informationName["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 1.5, $linespacing), $insurance2['name']);
	pdf_roundrect($p, $x+65, $newy-(19+(0*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 2.5, $linespacing), $pdf_putinsurance2informationAddress["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 2.5, $linespacing), $insurance2['address']);
	pdf_roundrect($p, $x+65, $newy-(19+(1*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_value($p, $x+70, line($newy, 3.5, $linespacing), $insurance2['city']);
	pdf_put_value($p, $x+180, line($newy, 3.5, $linespacing), $insurance2['state']);
	pdf_put_value($p, $x+260, line($newy, 3.5, $linespacing), $insurance2['zip']);
	pdf_roundrect($p, $x+65, $newy-(19+(2*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 4.5, $linespacing), $pdf_putinsurance2informationAdjPh["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 4.5, $linespacing), $insurance2['adjuster']);
	pdf_roundrect($p, $x+65, $newy-(19+(3*12)), 120, 11, 2); 
	pdf_stroke($p);

	pdf_put_value($p, $x+190, line($newy, 4.5, $linespacing), $insurance2['phone']);
	pdf_roundrect($p, $x+185, $newy-(19+(3*12)), 80, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 5.5, $linespacing), $pdf_putinsurance2informationType["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 5.5, $linespacing), $insurance2['type']);
	pdf_roundrect($p, $x+65, $newy-(19+(4*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 6.5, $linespacing), $pdf_putinsurance2informationInsName["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 6.5, $linespacing), $insurance2['insured']);
	pdf_roundrect($p, $x+65, $newy-(19+(5*12)), 200, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 7.5, $linespacing), $pdf_putinsurance2informationPolClm["$lang"]);
	pdf_put_value($p, $x+70, line($newy, 7.5, $linespacing), $insurance2['policy']);
	pdf_roundrect($p, $x+65, $newy-(19+(6*12)), 120, 11, 2); 
	pdf_stroke($p);

//	pdf_put_label($p, $x+170, line($newy, 7.5, $linespacing), "Clm#:");
	pdf_put_value($p, $x+190, line($newy, 7.5, $linespacing), $insurance2['claim']);
	pdf_roundrect($p, $x+185, $newy-(19+(6*12)), 80, 11, 2); 
	pdf_stroke($p);

	return($newy);
}

function pdf_putauthorizationinformation($p, $x, $y, $data, $lang='en') {
	$patient=$data['patient'];
// Authorization Information
	$totallines = 8;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 

	$pdf_putauthorizationinformationTitle['en']="RELEASE OF INFORMATION and ASSIGNMENT OF BENEFITS";
	$pdf_putauthorizationinformationText['en']="I hereby authorize West-Star Physical Therapy to release information requested by my insurance carrier concerning this illness upon request. I hereby authorize direct payment of my insurance benefits to West-Star Physical Therapy for services rendered.";
// I understand that I am financially responsible for non-covered services.
	$pdf_putauthorizationinformationPatient['en']=", Patient";
	$pdf_putauthorizationinformationDateSigned['en']="Date Signed";

	$pdf_putauthorizationinformationTitle['sp']="Authorizacion para Proporcionar Informacion Personal y Asignacion de Beneficios";
	$pdf_putauthorizationinformationText['sp']="Yo authorizo a West-Star Physical Therapy para que proporcione la informacion pertinente a este accidente en cuanto la aseguranza lo requiera. Yo authoizo que West-Star Physical Therapy reciba el pago directo de los beneficios de mi aseguranza por los servicios prestados.";
// Yo entiendo que soy responsible financieramente por los servicios que la aseguranza no cubra.
	$pdf_putauthorizationinformationPatient['sp']=", Paciente";
	$pdf_putauthorizationinformationDateSigned['sp']="Fecha";

	pdf_put_label($p, $x, line($newy, 0, $linespacing), $pdf_putauthorizationinformationTitle["$lang"]);
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);

//	pdf_put_value($p, $x, line($newy, 1.5, $linespacing), "I hereby authorize West-Star Physical Therapy to release information requested by my insurance ");
//	pdf_put_value($p, $x, line($newy, 2.5, $linespacing), "carrier concerning this illness upon request. I hereby authorize direct payment of my insurance ");
//	pdf_put_value($p, $x, line($newy, 3.5, $linespacing), "benefits to West-Star Physical Therapy for services rendered. I understand that I am financially ");
//	pdf_put_value($p, $x, line($newy, 4.5, $linespacing), "responsible for non-covered services.");

//	pdf_puttextboxed($p, $x, $y, $w, $h, $pdf_putauthorizationinformationText["$lang"], $mode, $feature, $font, $fontsize, $fontcolor);
	//pdf_puttextboxed($p, $x, $newy-16, 72*7.5, 12*3, $pdf_putauthorizationinformationText["$lang"], "left","", NULL,"10.0");
	pdf_puttextboxed($p, $x+90, $newy-16, 40, 12*3, $pdf_putauthorizationinformationText["$lang"], "left","", NULL,"10.0");

	$today=date("m/d/Y",time());
	pdf_put_value($p, $x+425, line($newy, 6.5, $linespacing), $today);

	pdf_line($p, $x, line($newy, 6.75, $linespacing), $x+400, line($newy, 6.75, $linespacing), 0, 0, 0);
	pdf_line($p, $x+420, line($newy, 6.75, $linespacing), $x+550, line($newy, 6.75, $linespacing), 0, 0, 0);
	pdf_put_label($p, $x+5, line($newy, 8, $linespacing), $patient['name'] . $pdf_putauthorizationinformationPatient["$lang"]);
	pdf_put_label($p, $x+425, line($newy, 8, $linespacing), $pdf_putauthorizationinformationDateSigned["$lang"]);
	return($newy);
}

function pdf_putJobInformation($p, $x, $y, $data, $lang='en') {
	$job=$data['job'];
// Job Information
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 
	$yesorno="(Yes)     (No )";

	$pdf_putJobInformationTitle['en']="JOB INFORMATION";
	$pdf_putJobInformationJobTitle['en']="Job Title:";
	$pdf_putJobInformationJobDescription['en']="Job Description:";
	$pdf_putJobInformationTitle2['en']="ADDITIONAL JOB DETAILS";
	$pdf_putJobInformationQ1['en']="During a typical 8 hour day, How many hours do you...";
	$pdf_putJobInformationQ1O1['en']="Sit:";
	$pdf_putJobInformationQ1O2['en']="Stand:";
	$pdf_putJobInformationQ1O3['en']="Walk:";
	$pdf_putJobInformationQ1O4['en']="Drive:";
	$pdf_putJobInformationHours['en']="Hours";

	$pdf_putJobInformationQ2['en']="At work, on average, how many hours do you work per...";
	$pdf_putJobInformationQ2O1['en']="Day/Shift:";
	$pdf_putJobInformationQ2O2['en']="Week:";

	$pdf_putJobInformationQ3['en']="At work, on average, how much time do you spend...";
	$pdf_putJobInformationQ3O1['en']="Squatting:";
	$pdf_putJobInformationQ3O2['en']="Stooping/bending:";
	$pdf_putJobInformationQ3O3['en']="Kneeling:";
	$pdf_putJobInformationQ3O4['en']="Reaching Up:";
	$pdf_putJobInformationQ3O5['en']="Reaching Out:";
	$pdf_putJobInformationQ3O6['en']="Twisting:";
	$pdf_putJobInformationQ3O7['en']="Crawling:";
	$pdf_putJobInformationQ3O8['en']="Stair Climbing:";
	$pdf_putJobInformationQ3O9['en']="Ladder Climbing:";
	$pdf_putJobInformationQ3O10['en']="Using a Computer:";
	$pdf_putJobInformationQ3O11['en']="Using the Telephone:";
	$pdf_putJobInformationQ3O12['en']="Pushing:";
	$pdf_putJobInformationQ3O13['en']="Pulling:";
	$pdf_putJobInformationQ3O14['en']="Lifting Overhead:";

	$pdf_putJobInformationQ4['en']="At work, my job requires that I lift...";
	$pdf_putJobInformationQ4C1['en']="Constantly";
	$pdf_putJobInformationQ4C2['en']="Often";
	$pdf_putJobInformationQ4C3['en']="Sometimes";
	$pdf_putJobInformationQ4C4['en']="Never";
	$pdf_putJobInformationQ4O1['en']="10 lbs or less:";
	$pdf_putJobInformationQ4O2['en']="11 lbs to 25 lbs:";
	$pdf_putJobInformationQ4O3['en']="26 lbs to 50 lbs:";
	$pdf_putJobInformationQ4O4['en']="51 lbs to 75 lbs:";
	$pdf_putJobInformationQ4O5['en']="76 lbs to 100 lbs:";
	$pdf_putJobInformationQ4O6['en']="over 100 lbs:";

	$pdf_putJobInformationQ5['en']="At work, my job includes...";
	$pdf_putJobInformationQ5C1['en']="Constantly";
	$pdf_putJobInformationQ5C2['en']="Often";
	$pdf_putJobInformationQ5C3['en']="Sometimes";
	$pdf_putJobInformationQ5C4['en']="Never";
	$pdf_putJobInformationQ5O1['en']="Repetitive Hand Movement";
	$pdf_putJobInformationQ5O2['en']="Repetitive Foot Movement";
	$pdf_putJobInformationQ5O3['en']="Power Gripping";
	$pdf_putJobInformationQ5O4['en']="Precision Handling";
	$pdf_putJobInformationQ5O5['en']="Balancing";
	$pdf_putJobInformationQ5O6['en']="Use of computer mouse/touch pad";
	$pdf_putJobInformationQ5O7['en']="Timed work for efficiency";
	$pdf_putJobInformationQ5O8['en']="Simultaneous computer & telephone";

	$pdf_putJobInformationTitle['sp']="INFORMACION DEL TRABAJO";

	$pdf_putJobInformationJobTitle['sp']="Cargo:";
	$pdf_putJobInformationJobDescription['sp']="Descripción de las funciones:";
	$pdf_putJobInformationTitle2['sp']="DETALLES ADICIONALES DEL TRABAJO";
	$pdf_putJobInformationQ1['sp']="Durante un día típico de 8 horas, cuántas horas le hacen...";
	$pdf_putJobInformationQ1O1['sp']="Sentarse:";
	$pdf_putJobInformationQ1O2['sp']="Pararse:";
	$pdf_putJobInformationQ1O3['sp']="Caminar:";
	$pdf_putJobInformationQ1O4['sp']="Manejar:";
	$pdf_putJobInformationHours['sp']="Horas";

//	$pdf_putJobInformationQ2['sp']="En el trabajo, sobre promedio, por cuántas horas usted trabaja...";
	$pdf_putJobInformationQ2['sp']="Cuántas horas trabaja en un ...";
	$pdf_putJobInformationQ2O1['sp']="Día Normal/Turno:";
	$pdf_putJobInformationQ2O2['sp']="Semana:";

//	$pdf_putJobInformationQ3['sp']="En el trabajo, sobre promedio, cuánto hora usted pasa...";
	$pdf_putJobInformationQ3['sp']="En un dia normal, Cuántas horas hace el o siguiente...";
	$pdf_putJobInformationQ3O1['sp']="El ponerse en cuclillas:";
	$pdf_putJobInformationQ3O2['sp']="El inclinarse/que dobla:";
	$pdf_putJobInformationQ3O3['sp']="Arrodillamiento:";
	$pdf_putJobInformationQ3O4['sp']="El alcanzar para arriba:";
	$pdf_putJobInformationQ3O5['sp']="El alcanzar hacia fuera:";
	$pdf_putJobInformationQ3O6['sp']="El torcer:";
	$pdf_putJobInformationQ3O7['sp']="Arrastre:";
	$pdf_putJobInformationQ3O8['sp']="El subir de la escalones:";
	$pdf_putJobInformationQ3O9['sp']="El subir de la escalera:";
	$pdf_putJobInformationQ3O10['sp']="Usando una computadora:";
	$pdf_putJobInformationQ3O11['sp']="Usando el teléfono:";
	$pdf_putJobInformationQ3O12['sp']="Empujando:";
	$pdf_putJobInformationQ3O13['sp']="Jalando:";
	$pdf_putJobInformationQ3O14['sp']="Cargando cosas pesadas:";

	$pdf_putJobInformationQ4['sp']="En mi trabajo se requiere que levante...";
	$pdf_putJobInformationQ4C1['sp']="Constantemente";
	$pdf_putJobInformationQ4C2['sp']="A menudo";
	$pdf_putJobInformationQ4C3['sp']="A veces";
	$pdf_putJobInformationQ4C4['sp']="Nunca";
	$pdf_putJobInformationQ4O1['sp']="10 lbs o menos:";
	$pdf_putJobInformationQ4O2['sp']="11 lbs a 25 lbs:";
	$pdf_putJobInformationQ4O3['sp']="26 lbs a 50 lbs:";
	$pdf_putJobInformationQ4O4['sp']="51 lbs a 75 lbs:";
	$pdf_putJobInformationQ4O5['sp']="76 lbs a 100 lbs:";
	$pdf_putJobInformationQ4O6['sp']="más de 100 lbs:";

	$pdf_putJobInformationQ5['sp']="Mi trabajo incluye...";
	$pdf_putJobInformationQ5C1['sp']="Constantemente";
	$pdf_putJobInformationQ5C2['sp']="A menudo";
	$pdf_putJobInformationQ5C3['sp']="A veces";
	$pdf_putJobInformationQ5C4['sp']="Nunca";
	$pdf_putJobInformationQ5O1['sp']="Movimiento repetidor de manos";
	$pdf_putJobInformationQ5O2['sp']="Movimiento repetidor de pies";
	$pdf_putJobInformationQ5O3['sp']="El agarrar de la energía";
	$pdf_putJobInformationQ5O4['sp']="Dirección de la precisión";
	$pdf_putJobInformationQ5O5['sp']="Equilibrio";
	$pdf_putJobInformationQ5O6['sp']="Uso del ratón de la computadora/del cojín de tacto";
	$pdf_putJobInformationQ5O7['sp']="Trabajo sincronizado para la eficacia";
	$pdf_putJobInformationQ5O8['sp']="Computadora simultánea y teléfono";

	pdf_puttext($p, $x, line($newy, 0, $linespacing), $pdf_putJobInformationTitle["$lang"]);

	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);

	pdf_put_label($p, $x, line($newy, 1.5, $linespacing), $pdf_putJobInformationJobTitle["$lang"]);
	pdf_roundrect($p, $x+100, $newy-(19+(0*12)), 444, 11, 2); 
	pdf_stroke($p);

//	pdf_put_label($p, $x, line($newy, 2.5, $linespacing), $pdf_putJobInformationJobDescription["$lang"]);
	pdf_puttextboxed($p, $x, line($newy, 3, $linespacing), 130, 50, $pdf_putJobInformationJobDescription["$lang"]);
	pdf_roundrect($p, $x+100, $newy-(19+(5*12)), 444, 11*5, 2); 
//	pdf_roundrect($p, $x+150, $newy-(19+(5*12)), 394, 55, 2); 
	pdf_stroke($p);

	pdf_puttext($p, $x, line($newy, 8, $linespacing), $pdf_putJobInformationTitle2["$lang"]);

	pdf_line($p, $x, line($newy, 8.25, $linespacing), 576, line($newy, 8.25, $linespacing), 0, 0, 0);

	pdf_puttext($p, $x, line($newy, 9.5, $linespacing), $pdf_putJobInformationQ1["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 10.5, $linespacing), $pdf_putJobInformationQ1O1["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 11.5, $linespacing), $pdf_putJobInformationQ1O2["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 12.5, $linespacing), $pdf_putJobInformationQ1O3["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 13.5, $linespacing), $pdf_putJobInformationQ1O4["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 10.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 11.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 12.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 13.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_roundrect($p, $x+175, $newy-(19+(9*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(10*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(11*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(12*12)), 20, 11, 2); 
	pdf_stroke($p);

	pdf_puttext($p, $x, line($newy, 15, $linespacing), $pdf_putJobInformationQ2["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 16, $linespacing), $pdf_putJobInformationQ2O1["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 17, $linespacing), $pdf_putJobInformationQ2O2["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 16, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 17, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_roundrect($p, $x+175, $newy-(19+(14.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(15.5*12)), 20, 11, 2); 
	pdf_stroke($p);

	pdf_puttext($p, $x, line($newy, 18.5, $linespacing), $pdf_putJobInformationQ3["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 19.5, $linespacing), $pdf_putJobInformationQ3O1["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 20.5, $linespacing), $pdf_putJobInformationQ3O2["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 21.5, $linespacing), $pdf_putJobInformationQ3O3["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 22.5, $linespacing), $pdf_putJobInformationQ3O4["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 23.5, $linespacing), $pdf_putJobInformationQ3O5["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 24.5, $linespacing), $pdf_putJobInformationQ3O6["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 25.5, $linespacing), $pdf_putJobInformationQ3O7["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 26.5, $linespacing), $pdf_putJobInformationQ3O8["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 27.5, $linespacing), $pdf_putJobInformationQ3O9["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 28.5, $linespacing), $pdf_putJobInformationQ3O10["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 29.5, $linespacing), $pdf_putJobInformationQ3O11["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 30.5, $linespacing), $pdf_putJobInformationQ3O12["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 31.5, $linespacing), $pdf_putJobInformationQ3O13["$lang"]);
	pdf_puttext($p, $x+20, line($newy, 32.5, $linespacing), $pdf_putJobInformationQ3O14["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 19.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 20.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 21.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 22.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 23.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 24.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 25.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 26.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 27.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 28.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 29.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 30.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 31.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_put_label($p, $x+200, line($newy, 32.5, $linespacing), $pdf_putJobInformationHours["$lang"]);
	pdf_roundrect($p, $x+175, $newy-(19+(18*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(19*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(20*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(21*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(22*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(23*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(24*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(25*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(26*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(27*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(28*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(29*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(30*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+175, $newy-(19+(31*12)), 20, 11, 2); 
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 34, $linespacing), $pdf_putJobInformationQ4["$lang"]);

	pdf_put_label($p, $x+255, line($newy, 34, $linespacing), $pdf_putJobInformationQ4C1["$lang"]);
	pdf_put_label($p, $x+350, line($newy, 34, $linespacing), $pdf_putJobInformationQ4C2["$lang"]);
	pdf_put_label($p, $x+425, line($newy, 34, $linespacing), $pdf_putJobInformationQ4C3["$lang"]);
	pdf_put_label($p, $x+500, line($newy, 34, $linespacing), $pdf_putJobInformationQ4C4["$lang"]);

	pdf_put_label($p, $x+20, line($newy, 35, $linespacing), $pdf_putJobInformationQ4O1["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 36, $linespacing), $pdf_putJobInformationQ4O2["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 37, $linespacing), $pdf_putJobInformationQ4O3["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 38, $linespacing), $pdf_putJobInformationQ4O4["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 39, $linespacing), $pdf_putJobInformationQ4O5["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 40, $linespacing), $pdf_putJobInformationQ4O6["$lang"]);

$b1=275;
$b2=360;
$b3=438;
$b4=508;
	pdf_rect($p, $x+$b1, $newy-(19+(33.5*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(33.5*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(33.5*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(33.5*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(34.5*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(34.5*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(34.5*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(34.5*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(35.5*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(35.5*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(35.5*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(35.5*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(36.5*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(36.5*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(36.5*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(36.5*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(37.5*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(37.5*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(37.5*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(37.5*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(38.5*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(38.5*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(38.5*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(38.5*12)), 20, 11); // Never
	pdf_stroke($p);

	pdf_put_label($p, $x, line($newy, 41.5, $linespacing), $pdf_putJobInformationQ5["$lang"]);

	pdf_put_label($p, $x+255, line($newy, 41.5, $linespacing), $pdf_putJobInformationQ5C1["$lang"]);
	pdf_put_label($p, $x+350, line($newy, 41.5, $linespacing), $pdf_putJobInformationQ5C2["$lang"]);
	pdf_put_label($p, $x+425, line($newy, 41.5, $linespacing), $pdf_putJobInformationQ5C3["$lang"]);
	pdf_put_label($p, $x+500, line($newy, 41.5, $linespacing), $pdf_putJobInformationQ5C4["$lang"]);

	pdf_put_label($p, $x+20, line($newy, 42.5, $linespacing), $pdf_putJobInformationQ5O1["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 43.5, $linespacing), $pdf_putJobInformationQ5O2["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 44.5, $linespacing), $pdf_putJobInformationQ5O3["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 45.5, $linespacing), $pdf_putJobInformationQ5O4["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 46.5, $linespacing), $pdf_putJobInformationQ5O5["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 47.5, $linespacing), $pdf_putJobInformationQ5O6["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 48.5, $linespacing), $pdf_putJobInformationQ5O7["$lang"]);
	pdf_put_label($p, $x+20, line($newy, 49.5, $linespacing), $pdf_putJobInformationQ5O8["$lang"]);

	pdf_rect($p, $x+$b1, $newy-(19+(41*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(41*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(41*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(41*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(42*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(42*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(42*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(42*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(43*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(43*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(43*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(43*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(44*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(44*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(44*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(44*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(45*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(45*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(45*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(45*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(46*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(46*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(46*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(46*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(47*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(47*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(47*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(47*12)), 20, 11); // Never

	pdf_rect($p, $x+$b1, $newy-(19+(48*12)), 20, 11); // Constantly
	pdf_rect($p, $x+$b2, $newy-(19+(48*12)), 20, 11); // Often
	pdf_rect($p, $x+$b3, $newy-(19+(48*12)), 20, 11); // Sometimes
	pdf_rect($p, $x+$b4, $newy-(19+(48*12)), 20, 11); // Never

	pdf_stroke($p);

}

function pdf_putinjuryinformation($p, $x, $y, $data, $lang='en') {
	$additional=$data['additional'];
// Additional Information
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 
	$yes="Yes";
	$no ="No";

	$pdf_putJobInformationTitle['en']="INJURY INFORMATION";
	$pdf_putJobInformationQ1['en']="Briefly describe your injury:";
	$pdf_putJobInformationQ2['en']="Did you go to the Emergency Room at a Hospital?";
	$pdf_putJobInformationQ3['en']="If not an Emergency Room, did you go to some other type of medical facility?";
	$pdf_putJobInformationQ4['en']="Were x-rays taken?";
	$pdf_putJobInformationQ5['en']="If an auto accident, was the vehicle drivable after the accident?";
	$pdf_putJobInformationQ6['en']="Do you have any previous injury to the same area?";
	$pdf_putJobInformationQ7['en']="Are you still being treated for this injury?";
	$pdf_putJobInformationYes['en']='Yes';
	$pdf_putJobInformationNo['en']='No';

	$pdf_putJobInformationTitle['sp']="INFORMACIÓN DE LESIÓN O ACCIDENTE";
	$pdf_putJobInformationQ1['sp']="Describa brevemente su accidente:";
	$pdf_putJobInformationQ2['sp']="Estuvo en la Sala de Emergencia?";
	$pdf_putJobInformationQ3['sp']="Si no fue a un cuarto de emergencia, se presento en algún otro tipo de centro médico?";
	$pdf_putJobInformationQ4['sp']="Se tomaron rayos x?";
	$pdf_putJobInformationQ5['sp']="Si fue un accidente de auto, pudo manejar el vehículo después del accidente?";
	$pdf_putJobInformationQ6['sp']="Ha tenido algun accidente anterior en la misma parte del cuerpo?";
	$pdf_putJobInformationQ7['sp']="Esta reciviendo tratamiento medico por la misma lesion?";
	$pdf_putJobInformationYes['sp']='Si';
	$pdf_putJobInformationNo['sp']='No';

	pdf_puttextboxed($p, $x, line($newy, 0, $linespacing), 30, 50, $pdf_putJobInformationTitle["$lang"]);

	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);

	pdf_puttextboxed($p, $x, line($newy, 1.5, $linespacing), 30, 50, $pdf_putJobInformationQ1["$lang"]);
	pdf_put_value($p, $x, line($newy, 1.5, $linespacing), $additional['injurydescription']);

	pdf_put_label($p, $x, line($newy, 8, $linespacing), $pdf_putJobInformationQ2["$lang"]);
	pdf_put_value($p, $x+420, line($newy, 8, $linespacing), $additional['emergencyroom']);

	pdf_put_label($p, $x, line($newy, 9, $linespacing), $pdf_putJobInformationQ3["$lang"]);
	pdf_put_value($p, $x+420, line($newy, 9, $linespacing), $additional['otherfacility']);

	pdf_put_label($p, $x, line($newy, 10, $linespacing), $pdf_putJobInformationQ4["$lang"]);
	pdf_put_value($p, $x+420, line($newy, 10, $linespacing), $additional['xrays']);

	pdf_put_label($p, $x, line($newy, 11, $linespacing), $pdf_putJobInformationQ5["$lang"]);
	pdf_put_value($p, $x+420, line($newy, 11, $linespacing), $additional['autodrivable']);

	pdf_put_label($p, $x, line($newy, 12, $linespacing), $pdf_putJobInformationQ6["$lang"]);
	pdf_put_value($p, $x+420, line($newy, 12, $linespacing), $additional['previousinjury']);

	pdf_put_label($p, $x, line($newy, 13, $linespacing), $pdf_putJobInformationQ7["$lang"]);
	pdf_put_value($p, $x+420, line($newy, 13, $linespacing), $additional['stilltreating']);

	pdf_put_label($p, $x+470, line($newy, 7, $linespacing), $pdf_putJobInformationYes["$lang"]);
	pdf_put_label($p, $x+500, line($newy, 7, $linespacing), $pdf_putJobInformationNo["$lang"]);

	pdf_roundrect($p, $x+195, $newy-(19+(3.5*12)), 350, 55, 2); 
	//pdf_roundrect($p, $x+100, $newy-(19+(3.5*12)), 444, 55, 2); 

	pdf_roundrect($p, $x+470, $newy-(19+(6.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+500, $newy-(19+(6.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+470, $newy-(19+(7.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+500, $newy-(19+(7.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+470, $newy-(19+(8.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+500, $newy-(19+(8.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+470, $newy-(19+(9.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+500, $newy-(19+(9.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+470, $newy-(19+(10.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+500, $newy-(19+(10.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+470, $newy-(19+(11.5*12)), 20, 11, 2); 
	pdf_roundrect($p, $x+500, $newy-(19+(11.5*12)), 20, 11, 2); 

 	pdf_stroke($p);	

	return($newy);
}

function pdf_putproviderinformation($p, $x, $y, $data, $lang='en') {
	$provider=$data['provider'];
// Provider Information
	$totallines = 5.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 

	$pdf_putproviderinformationTitle['en']="If you are still being treated for this injury, by whom?";
	$pdf_putproviderinformationName['en']="Name:";
	$pdf_putproviderinformationAddress['en']="Address:";
	$pdf_putproviderinformationCityStZip['en']="City, St Zip:";
	$pdf_putproviderinformationPhone['en']="Phone:";

	$pdf_putproviderinformationTitle['sp']="Si todavía esta reciviendo tratamiento medico por la misma lesión, favor llenar lo siguiente?";
	$pdf_putproviderinformationName['sp']="Nombre:";
	$pdf_putproviderinformationAddress['sp']="Dirección:";
	$pdf_putproviderinformationCityStZip['sp']="Ciudad, Zip:";
	$pdf_putproviderinformationPhone['sp']="Teléfono:";
	
	pdf_put_label($p, $x, line($newy, 0, $linespacing), $pdf_putproviderinformationTitle["$lang"]);

	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);

	pdf_put_label($p, $x, line($newy, 1.5, $linespacing), $pdf_putproviderinformationName["$lang"]);
	pdf_put_value($p, $x+85, line($newy, 1.5, $linespacing), $provider['name']);
	pdf_put_label($p, $x, line($newy, 2.5, $linespacing), $pdf_putproviderinformationAddress["$lang"]);
	pdf_put_value($p, $x+85, line($newy, 2.5, $linespacing), $provider['address']);
	pdf_put_label($p, $x, line($newy, 3.5, $linespacing), $pdf_putproviderinformationCityStZip["$lang"]);
	pdf_put_value($p, $x+20, line($newy, 3.5, $linespacing), $provider['city']);
	pdf_put_value($p, $x+170, line($newy, 3.5, $linespacing), $provider['state']);
	pdf_put_value($p, $x+250, line($newy, 3.5, $linespacing), $provider['zip']);
	pdf_put_label($p, $x, line($newy, 4.5, $linespacing), $pdf_putproviderinformationPhone["$lang"]);
	pdf_put_value($p, $x+85, line($newy, 4.5, $linespacing), $provider['phone']);

	pdf_roundrect($p, $x+100, $newy-(19+(0*12)), 344, 11, 2); 
	pdf_roundrect($p, $x+100, $newy-(19+(1*12)), 344, 11, 2); 
	pdf_roundrect($p, $x+100, $newy-(19+(2*12)), 344, 11, 2); 
	pdf_roundrect($p, $x+100, $newy-(19+(3*12)), 344, 11, 2); 
 	pdf_stroke($p);	

	return($newy);
}

function pdf_putpaininformation($p, $x, $y, $data, $lang='en') {
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 

	$pdf_putpaininformationTitle['en']="PAIN INFORMATION";
	$pdf_putpaininformationDirections['en']="Draw the location of your pain on the body outlines using the following markers.";
	$pdf_putpaininformationAches['en']="A = Aches";
	$pdf_putpaininformationBurning['en']="B = Burning";
	$pdf_putpaininformationNumbness['en']="N = Numbness";
	$pdf_putpaininformationPinsNeedles['en']="P = Pins & Needles";
	$pdf_putpaininformationStabbing['en']="S = Stabbing";
	$pdf_putpaininformationOther['en']="O = Other";
	
	$pdf_putpaininformationTitle['sp']="INFORMACION DEL DOLOR";
	$pdf_putpaininformationDirections['sp']="Marque la localización del dolor en los esquemas del cuerpo usando los marcadores siguientes.";
	$pdf_putpaininformationAches['sp']="A = Dolores";
	$pdf_putpaininformationBurning['sp']="B = Arder";
	$pdf_putpaininformationNumbness['sp']="N = Entumecimiento";
	$pdf_putpaininformationPinsNeedles['sp']="P = Fija el & Agujas";
	$pdf_putpaininformationStabbing['sp']="S = Punalada";
	$pdf_putpaininformationOther['sp']="O = Otro";
	
	pdf_puttext($p, $x, line($newy, 0, $linespacing), $pdf_putpaininformationTitle["$lang"]);
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_puttext($p, $x+85, line($newy, 1, $linespacing), $provider['phone']);
	pdf_putimage($p, $x+200, $newy-400, $_SERVER['DOCUMENT_ROOT']."/img/body.gif", "GIF", 0.4);
	pdf_puttext($p, $x, line($newy, 2, $linespacing), $pdf_putpaininformationDirections["$lang"]);
	pdf_puttext($p, $x, line($newy, 4, $linespacing), $pdf_putpaininformationAches["$lang"]);
	pdf_puttext($p, $x, line($newy, 5, $linespacing), $pdf_putpaininformationBurning["$lang"]);
	pdf_puttext($p, $x, line($newy, 6, $linespacing), $pdf_putpaininformationNumbness["$lang"]);
	pdf_puttext($p, $x, line($newy, 7, $linespacing), $pdf_putpaininformationPinsNeedles["$lang"]);
	pdf_puttext($p, $x, line($newy, 8, $linespacing), $pdf_putpaininformationStabbing["$lang"]);
	pdf_puttext($p, $x, line($newy, 9, $linespacing), $pdf_putpaininformationOther["$lang"]);

	pdf_rect($p, $x-2, $newy-(19+(8*12)), 200, 11*7); 
 	pdf_stroke($p);

	return($newy);
}

function pdf_putwaiver($p, $x, $y, $data, $lang='en') {
	$patient=$data['patient'];
	$totallines = 12.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 
	
	$pdf_putwaiverTitle['en']="WAIVER INFORMATION";
	$pdf_putwaiverText['en']="I, " . $patient['name'] . " AM OF LEGAL AGE AND HEREBY CERTIFY THAT I WENT TO WEST STAR \r PHYSICAL THERAPY OF MY OWN DISCRETION AND DECISION TO RECEIVE PHYSICAL THERAPY TREATMENTS. I UNDERSTAND THAT I MAY OR MAY NOT HAVE A DOCTORS' REFERRAL AND THAT GETTING PHYSICAL THERAPY IS MY TREATMENT OF CHOICE. I ALSO UNDERSTAND THAT I WILL BE EVALUATED BY A LICENSED AND CERTIFIED PHYSICAL THEREAPIST AND THAT THE THERAPIST'S EVALUATION AND RECOMMENDATION WILL BE EXPLAINED TO ME BEFORE TREATMENT. I UNDERSTAND THAT THE PHYSICAL THERAPIST WILL COMMUNICATE WITH MY MEDICAL DOCTOR TO GET AUTHORIZATION FOR MY PHYSICAL THERAPY TREATMENTS. I ALSO UNDERSTAND THAT I CANNOT RECEIVE PHYSICAL THERAPY TREATMENTS FROM WEST STAR PHYSICAL THERAPY WITHOUT SIGNED AUTHORIZATION FROM MY MEDICAL DOCTOR. FURTHERMORE, I UNDERSTAND THAT PHYSICAL THERAPY, WHILE DESIGNED TO, IS NOT GUARANTEED TO IMPROVE MY CURRENT CONDITION.";
	$pdf_putwaiverTextMinor['en']="IF MINOR:";
	$pdf_putwaiverTextParent['en']="NAME OF PARENT OF GUARDIAN:\nRELATIONSHIP:\nPATIENT SIGNATURE:\nDATE:\nWITNESSED BY:\nNAME OF STAFF MEMBER:\nSIGNATURE:\nDATE:";

	$pdf_putwaiverTitle['sp']="FORMA de PERMISO MƒDICO de TRATAMIENTO";
	$pdf_putwaiverText['sp']="YO, " . $patient['name'] . " SOY DE LA EDAD LEGAL Y POR LA PRESENTE CERTIFICO QUE FUI A ".$clinicname." POR MI PROPIA VOLUNTAD Y LA DECISION DE RECIBIR TRATAMIENTO DE FISIOTERAPIA. COMPRENDO QUE YO PODRIA O NO TENER REFERENCIA DE UN MEDICO Y QUE OBTENIENDO FISIOTERAPIA ES MI TRATAMIENTO DE ELECCION. YO TAMBIEN COMPRENDO QUE SERE EVALUADO(A) POR UN TERAPISTA FISICO CON LICENCIA VALIDA Y CERTIFICADO Y QUE LA EVALUACION Y RECOMENDACION DEL TERAPISTA SERAN EXPLICADAS A MI ANTES DEL TRATAMIENTO. COMPRENDO QUE EL ".$clinicname." SE COMUNICARA CON MI MEDICO PARA OBTENIR AUTORIZACION PARA MI TRATAMIENTO DE FISIOTERAPIA. YO TAMBIEN COMPRENDO QUE YO NO PUEDO RECIBIR TRATAMIENTO DE FISIOTERAPIA EN ".$clinicname." SIN LA AUTORIZACION FIRMADA MI MEDICO. ADEMAS, COMPRENDO QUE LA FISIOTERAPIA, PODRIA O NO GUARANTIZAR QUE MI CONDICION ACTUAL MEJORE.";
	$pdf_putwaiverTextMinor['sp']="SI MENOR:";
	$pdf_putwaiverTextParent['sp']="El Nombre de Padre O Guardian del Paciente:\nRelacion:\nFirma:\nFecha:\nTestigo(a):\nNombre de la Persona tomando los datos:\nFirma:\nFecha:";

	pdf_put_label($p, $x, line($newy, 0, $linespacing), $pdf_putwaiverTitle["$lang"]);
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_puttextboxed($p, 70, 610, 30, 25, $pdf_putwaiverText["$lang"]);
	pdf_puttextboxed($p, 70, 380, 30, 25, $pdf_putwaiverTextMinor["$lang"]);
	pdf_puttextboxed($p, 30, 360, 30, 10, $pdf_putwaiverTextParent["$lang"], "right", "", NULL, "10.0");

	$line="_____________________________________________";
	$text="$line\n$line\n$line\n$line\n$line\n$line\n$line\n$line";
	pdf_puttextboxed($p, 270, 360, 330, 10, $text, "left", "", NULL, "10.0");
	return($newy);
}

function pdf_putprivacypractices1($p, $x, $y, $data, $lang='en') {
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 

	$pdf_putprivacypractices1Title['en']="PRIVACY INFORMATION Page (1 of 3)";
	$pdf_putprivacypractices1Text1['en']="THIS NOTICE DESCRIBES HOW MEDICAL INFORMATION ABOUT YOU MAY BE USED AND DISCLOSED AND HOW YOU CAN GET ACCESS TO THIS INFORMATION. PLEASE REVIEW IT CAREFULLY";

$pdf_putprivacypractices1Text2['en']="Uses and Disclosures

Treatment: Your health information may be used by staff members or disclosed to other health care professionals for the purpose of evaluating your health, diagnosing medical conditions and providing treatment. For Example, results of laboratory tests and procedures will be available in your medical record to all health professionals who may provide treatment or who may be consulted by staff members.

Payment: Your health information may be used to seek payment from your health plan, from other sources of coverage such as an automobile insurer, or from credit card companies that you may use to pay for services. For example, your health plan may request and receive information on dates of service, services provided and the medical condition being treated.

Health Care Operations: Your health information may be used as necessary to support the day-to-day activities of and management of West Star Physical Therapy. For Example, information on the services you received may be used to support budgeting and financial reporting and activities to evaluate and promote quality.

Law Enforcement: your health information may be disclosed to law enforcement agencies to support government audits and inspections, to facilitate law enforcement investigations and to comply with government mandated reporting.

Public Health Reporting: Your health care information may be disclosed to public health agencies as required by law. For example, we are required to report certain communicable diseases to the state's public health department.

Other Uses and Disclosures That Require Your Authorization: Disclosure of your health information or its use for any purpose other than those listed above requires your specific, written authorization. However, your decision to revoke authorization will not affect of undo any use or disclosure of information that occurred before you notified us of your decision to revoke your authorization.";
	$pdf_putprivacypractices1Title['sp']="Aviso de Prácticas de Privacidad  Pagina (1 de 3)";
	$pdf_putprivacypractices1Text1['sp']="ESTA NOTA DESCRIBE INFORMACION MEDICA SOBRE USTED que PUEDE SER  UTILIZADA Y ES REVELADO Y COMO USTED PUEDE CONSEGUIR ACCESO A ESTA INFORMACION. REVISELO POR FAVOR CON CUIDADO";
	
$pdf_putprivacypractices1Text2['sp'] = "Usos y Revelaciones

El tratamiento: Su información de la salud puede ser utilizada por empleados de la clinica o compartida con otros profesionales de la asistencia médica para el propósito de evaluar su salud, diagnosticando condiciones médicas y proporcionar tratamiento. Por ejemplo, los resultados de examenes de laboratorio y procedimientos estarán disponibles en su historial médico para todos los profesionales de medicina que puedan proporcionar tratamiento o que pueden ser consultados por los empleados de la clinica.

El pago: Su información de la salud puede ser utilizada para obtener es tu pagos de su plan de salud medico, de otras fuentes de alcance como una aseguranza de automóvil, o de las compañías de tarjeta de crédito que usted puede utilizar para pagar por servicios. Por ejemplo, su plan de salud puede solicitar y recibir información de  fechas de servicio, servicios proveidos y la condición médica tratada.

Las Operaciones de Asistencia médica: Su información de la salud puede ser utilizada para apoyar como sea necesario las actividades diarias y de la gestión de Weststar Physical Therapy. Por ejemplo, la información en los servicios que usted reciba pueden ser utilizados para mantener, presupuestar y cubrir actividades financieras para evaluar y promover calidad medica.

La Aplicación de ley: su información de la salud puede ser enviada a agencias legales para mantener auditorías de gobierno e inspecciones, para facilitar investigaciones legales y para cumplir con el mandato de covertura del gobierno.

Anuncio De Salud Publica: Su información de la asistencia médica puede ser revelada a agencias sanitarias según exige la ley. Por ejemplo, nosotros somos requeridos a informar ciertas enfermedades transmisibles al departamento sanitarias del estado.

Otros Usos y las Revelaciones Que Requieren Su Autorización: La distribucion de su información de salud o su uso para cualquier propósito ademas de los mentionados arriba requieren su autorización específica y en escrito. Sin embargo, su decisión de revocar autorización no afectará ningún uso ni la distribucion de información que ocurrió antes que usted nos notificara de su decisión de revocar su autorización.
";
	pdf_put_label($p, $x, line($newy, 0, $linespacing), $pdf_putprivacypractices1Title["$lang"]);
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_puttextboxed($p, 70, 622, 30, 10, $pdf_putprivacypractices1Text1["$lang"]);
	pdf_puttextboxed($p, 100, 572, 30, 10, $pdf_putprivacypractices1Text2["$lang"]);
	return($newy);
}

function pdf_putprivacypractices2($p, $x, $y, $data, $lang='en') {
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 

	$pdf_putprivacypractices2Title['en']="PRIVACY INFORMATION Page (2 of 3)";
	$pdf_putprivacypractices2Text['en']= "Appointment Reminders: Your health information will be used by our staff to send you appointment reminders.

Information About Treatments: Your health information may be used to send you information that you may find interesting on the treatment and management of your medical condition. From our database, we may also send you information describing only West Star related information that may be of interest to you**



                Please do not use my health information for the above-mentioned services.

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

	$pdf_putprivacypractices2Title['sp']="Nota de Prácticas de Intimidad Paga (2 de 3)";
	$pdf_putprivacypractices2Text['sp']="Los Usos adicionales de Información

Los Recordatorios de la cita: Su información de la salud será utilizada por nuestro personal para enviarle recordatorios de cita.

La información sobre Tratamientos: Su información de la salud puede ser utilizada para enviarle información que usted puede encontrar interesante en el tratamiento y la gestión de su condición médica. De nuestra base de datos, nosotros también le podemos enviar información que describe Estrella sólo Occidental información relacionada que puede ser de interés a usted**

               Por Favor no utiliza mi información de la salud para los servicios sobredichos.

Los Derechos individuales: Usted tiene ciertos derechos bajo los estándares federales de intimidad. Estos incluyen: 
• El derecho de solicitar restricciones en el uso y la revelación de su información protegida de asistencia médica; 
• El derecho de recibir comunicaciones confidenciales con respecto a su condición y el tratamiento médicos; 
• El derecho de inspeccionar y copiar su información protegida de la salud; 
• El derecho de enmendar o someterse correcciones a su información protegida de la asistencia médica; 
• El derecho de recibir una contabilidad de cómo y a quien su información protegida de salud tiene Fue revelado; • El derecho de recibir una copia impresa de esta nota. 

Los Deberes occidentales de WestStar: Somos requeridos por la ley a mantener que la intimidad de su información protegida de la salud y para proporcionarle con esta nota de prácticas de intimidad.

Nosotros también somos requeridos a respetar las políticas de intimidad y prácticas que son resumidas en esta nota.

Correcto Revisar las Prácticas de Intimidad: Como permitido por la ley, nosotros reservamos el derecho de enmendar para modificar nuestras políticas de intimidad y prácticas. Estos cambios en nuestras políticas y prácticas pueden ser requeridos por cambios en federal y las leyes de estado y regulaciones. Sobre la petición, nosotros le proporcionaremos con la nota más recientemente revisada en cualquier visita de la oficina. El revisa políticas y prácticas serán aplicadas a toda información protegida de la salud que mantenemos.";

	pdf_put_label($p, $x, line($newy, 0, $linespacing), $pdf_putprivacypractices2Title["$lang"]);
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_puttextboxed($p, 100, 622, 30, 10, $pdf_putprivacypractices2Text["$lang"]);
	pdf_roundrect($p, $x, $newy-(38*12+10), 45, 16, 2); 
 	pdf_stroke($p);	

	return($newy);
}

function pdf_putprivacypractices3($p, $x, $y, $data, $lang='en') {
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 

	$pdf_putprivacypractices3Title['en']="PRIVACY INFORMATION Page (3 of 3)";
	$pdf_putprivacypractices3Text['en']="Requests to Inspect Protected Health Information: You may generally inspect or copy the protected health information we maintain. As permitted by Federal Regulations we require that requests to inspect or copy protected health information be submitted in writing. You may obtain a form to request access to your records by contacting our corporate office. Your request will be reviewed and will generally be approved unless there are legal or medical reasons to deny the request.

Complaints: If you would like to submit a comment or complaint about our privacy practices, you can do so by sending a letter outlining your concerns to:

West Star Physical Therapy
5400 Orange Street
Suite #215
Cypress, CA 90630

If you believe that your privacy rights have been violated, you should call the matter to our attention by sending a letter describing the cause of concern to the same address. You will not be penalized or otherwise retaliated against for filing a complaint.

Contact Person: The address of the person you may contact for further information concerning privacy practices is:

West Star Physical Therapy
5400 Orange Street
Suite #215
Cypress, CA 90630

Effective Date: This notice is effective as of May 18, 2012
";
	$pdf_putprivacypractices3Title['sp']="Nota de Prácticas de Intimidad Paga (3 de 3)";
	$pdf_putprivacypractices3Text['sp']="Las peticiones para Inspeccionar Información Protegida de Salud: Usted puede inspeccionar generalmente o puede copiar la información protegida de la salud que mantenemos. Como permitido por Regulaciones Federales nosotros requerimos que peticiones a inspeccionar o copiar información protegida de salud es sometida en la escritura. Usted puede obtener una forma para solicitar acceso a sus registros contactando nuestra oficina corporativa. Su petición será revisada y será aprobada generalmente a menos que hay razones legales o médicas de negar la petición.

Las quejas: Si usted querría someterse un comentario o la queja acerca de nuestras prácticas de intimidad, usted puede hacer así enviando una carta que resume sus preocupaciones a:

Connie Medeiros
West Star Physical Therapy
5400 Orange Street
Suite #215
Cypress, CA 90630

Si usted cree que sus derechos de intimidad han sido violados, usted debe llamar el asunto a nuestra atención enviando una carta que describe la causa de preocupación a la misma dirección. Usted no será penalizado ni de otro modo será vengado en contra para archivar una queja.

Contacte a Persona: El nombre y la dirección de la persona usted puede contactar para la información adicional con respecto a prácticas de intimidad es:

Connie Medeiros
West Star Physical Therapy
5400 Orange Street
Suite #215
Cypress, CA 90630

La Fecha de vigencia: Esta nota es efectiva al el 7 de mayo de 2003";


	pdf_puttext($p, $x, line($newy, 0, $linespacing), $pdf_putprivacypractices3Title["$lang"]);
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
	pdf_puttextboxed($p, 100, 622, 30, 10, $pdf_putprivacypractices3Text["$lang"]);
	return($newy);
}

function pdf_putprivacyacknowledgement($p, $x, $y, $data, $lang='en') {
	$patient=$data['patient'];
	$totallines = 10.5;
	$linespacing = pdf_getdefaultlinespacing();
	$newy = line($y, $totallines, $linespacing); 

	$pdf_putprivacyacknowledgementTitle['en']="PRIVACY ACKNOWLEDGMENT INFORMATION";
	$pdf_putprivacyacknowledgementTitle2['en']="Acknowledgement of Receipt of Notice of Privacy Practices";
	$pdf_putprivacyacknowledgementText['en']="I " . $patient['name'] . ", have received, read and fully understand the Notice of Privacy Practices for West Star Physical therapy and acknowledge and understand that West Stat Physical therapy reserves the right to modify or amend the privacy practices outlined in the notice.";
	$pdf_putprivacyacknowledgementSignature['en']="Patient:\nSignature:\nDate:";
	$line="_____________________________________________";
	$pdf_putprivacyacknowledgementSignature2['en']=$patient['name']."\n$line\n$line";
	$pdf_putprivacyacknowledgementText2['en']="Patient Representative is required if the patient is a minor or patient is an adult who is unable to sign this form.";
	$pdf_putprivacyacknowledgementSignature3['en']="Name of Patient Representative:\nRelationship to Patient:\nSignature:\nDate:";
	$pdf_putprivacyacknowledgementSignature4['en']="$line\n$line\n$line\n$line";



	$pdf_putprivacyacknowledgementTitle['sp']="PRIVACY ACKNOWLEDGMENT INFORMACION";
	$pdf_putprivacyacknowledgementTitle2['sp']="Reconocimiento de Recibo de Nota de Prácticas de Intimidad";
	$pdf_putprivacyacknowledgementText['sp']="He recibido y comprendido completamente que la nota de Practica de Intimidad para la fisioterapia de West Star. Reconozcoy comprendo que West Star reserve el derecho de modificar o enmendar las practicas de intimidad resumidas en la nota.";
	$pdf_putprivacyacknowledgementSignature['sp']="Paciente:\nFirma:\nFecha:";
	$pdf_putprivacyacknowledgementSignature2['sp']=$patient['name']."\n$line\n$line";
	$pdf_putprivacyacknowledgementText2['sp']="Patient Representative is required if the patient is a minor or patient is an adult who is unable to sign this form.";
	$pdf_putprivacyacknowledgementSignature3['sp']="Nombre del representante paciente:\nRelacion a paciente:\nFirma:\nFecha:";
	$pdf_putprivacyacknowledgementSignature4['sp']="$line\n$line\n$line\n$line";

	pdf_puttext($p, $x, line($newy, 0, $linespacing), $pdf_putprivacyacknowledgementTitle["$lang"]);
	pdf_line($p, $x, line($newy, 0.25, $linespacing), 576, line($newy, 0.25, $linespacing), 0, 0, 0);
//	pdf_puttext($p, $x, line($newy, 1.5, $linespacing), "West Star Physical Therapy
	pdf_puttextboxed($p, 100, 600, 30, 10, $pdf_putprivacyacknowledgementTitle2["$lang"], 'center');
	pdf_puttextboxed($p, 100, 580, 30, 10, $pdf_putprivacyacknowledgementText["$lang"]);
	pdf_puttextboxed($p, 100, 480, 30, 10, $pdf_putprivacyacknowledgementSignature["$lang"], 'right');
	pdf_puttextboxed($p, 100, 480, 330, 10, $pdf_putprivacyacknowledgementSignature2["$lang"]);
	pdf_puttextboxed($p, 100, 400, 30, 10, $pdf_putprivacyacknowledgementText2["$lang"]);
	pdf_puttextboxed($p, 100, 360, 30, 10, $pdf_putprivacyacknowledgementSignature3["$lang"], 'right');
	pdf_puttextboxed($p, 100, 360, 330, 10, $pdf_putprivacyacknowledgementSignature4["$lang"]);

//	$text="
//
//_______________________________________________
//Patient Name (Print or Type)
//
//_______________________________________________
//Signature of Patient
//
//
//_______________________________________________
//Date
//
//
//_______________________________________________
//Signature of Patient Representative
//(Required if the patient is a minor or an adult who is unable to sign this form)
//
//
//_______________________________________________
//Relationship of Patient Representative to Patient
//";
//	pdf_puttextboxed($p, 30, 410, 550, 10, $text);
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