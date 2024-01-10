<?php
function pdfGenerateReport($p, $settings, $data) {
//  Font Helv 
	$defaultfont = pdf_findfont($p , "Helvetica-Bold" , "winansi" , 0 );
	$font=$defaultfont;

	// First Page is 8.5 x 11
	$pagewidth=pdf_pagewidth();
	$pageheight=pdf_pageheight();
	
	$topmargin=36;
	$leftmargin=32;
	
	$bodytop=$pageheight-$topmargin;
	$bodyleft=$leftmargin;

// Run through the entire report to determine the height of each section in each group without printing
	$pagination=array();

	$print=false; // cannot print anyway because pdf not in page scope / not started

	$sections=array();
// Returns an array of the height of each section in report header : $section['logo'], $section['address'], $section['line'], $section['title']
	$sections=pdf_putreportheader($p, $bodyleft, 0, $data, $print);
	$pagination=array_merge($pagination, $sections);

	$sections=pdf_putreportreferral($p, $bodyleft, 0, $data, $print);
	$pagination=array_merge($pagination, $sections);

	$sections=pdf_putreportsubjective($p, $bodyleft, 0, $data, $print);
	$pagination=array_merge($pagination, $sections);

	$sections=pdf_putreportobjective($p, $bodyleft, 0, $data, $print);
	$pagination=array_merge($pagination, $sections);

	$sections=pdf_putreportassessment($p, $bodyleft, 0, $data, $print);
	$pagination=array_merge($pagination, $sections);

	$sections=pdf_putreportplan($p, $bodyleft, 0, $data, $print);
	$pagination=array_merge($pagination, $sections);

	$sections=pdf_putreportsignature($p, $bodyleft, 0, $data, $print);
	$pagination=array_merge($pagination, $sections);

	$totalheight=array_sum($pagination);

//dump("pagination",$pagination);
//dump("totalheight",$totalheight);
// Now Pagination should have a list of all sections and the $height should be the final length of the document
// Now Create an Array to tell each section that needs to paginate to do so.

$pagelimit=756;
$smallheaderheight=100;

$pagebreaks=array();
$pagetotal=0;
$currentpage=1;
foreach($pagination as $section=>$height) {
	list($mainsection, $subsection)=split("_", $section, 2);
	$pagetotal=$pagetotal+$height;
	$pagebreak=false;
	if($pagetotal >= $pagelimit) {
		$currentpage++;
		$pagebreak=true;
		$pagetotal=$smallheaderheight+$height;
	}
	$pagebreaks["$mainsection"]["$subsection"]['currentpage']=$currentpage;
	$pagebreaks["$mainsection"]["$subsection"]['pagebreak']=$pagebreak;
	$pagebreaks["$mainsection"]["$subsection"]['height']=$height;
	$pagebreaks["$mainsection"]["$subsection"]['position']=$pagetotal;
}

$mainsectionsoftbreaks=array();
foreach($pagebreaks as $mainsection=>$subsectionarray) {
	foreach($subsectionarray as $subsection=>$subsectiondata) {
		if($subsectiondata['pagebreak'])
			$mainsectionsoftbreaks["$mainsection"]=true;
	}
}

// dump('pagebreaks',$pagebreaks);

$repaginating=false;
foreach($pagination as $section=>$height) {
	list($mainsection, $subsection)=split("_", $section, 2);
	if($mainsectionsoftbreaks["$mainsection"] || $repaginating) {
		if($repaginating==false) {
			$pagetotal=0;
			$currentpage=$pagebreaks["$mainsection"]["$subsection"]['currentpage']+1;
			$pagebreak=true;
			$pagetotal=$smallheaderheight+$height;
			unset($mainsectionsoftbreaks["$mainsection"]);
			$repaginating=true;
		}
		else {
			$pagetotal=$pagetotal+$height;
			$pagebreak=false;
			if($pagetotal >= $pagelimit) {
				$currentpage++;
				$pagebreak=true;
				$pagetotal=$smallheaderheight+$height;
			}
		}
		$pagebreaks["$mainsection"]["$subsection"]['currentpage']=$currentpage;
		$pagebreaks["$mainsection"]["$subsection"]['pagebreak']=$pagebreak;
		$pagebreaks["$mainsection"]["$subsection"]['height']=$height;
		$pagebreaks["$mainsection"]["$subsection"]['position']=$pagetotal;
	}
}


$data['pagebreaks']=$pagebreaks;
//dump('after mainsectionsoftbreaks pagebreaks', $pagebreaks);
//exit();
	pdf_begin_page($p, $pagewidth, $pageheight);

// Print scannable ocr indexes for this document
//	pdf_put_ocr_index_info($p, $indexarray);

// Body 
	$print=true;
// Page One Header never breaks
	$sections = pdf_putreportheader($p, $bodyleft, $bodytop, $data, $print); 		//Clinic Unit Information all static always fits

// Referral never breaks
	$newbodytop=$bodytop-$data['pagebreaks']['reportheader']['titlearea']['position'];
	$sections = pdf_putreportreferral($p, $bodyleft, $newbodytop, $data, $print);	//Report Referral Information fairly statid always fits

// Print Subjective Until a break, then break on the title+data where the break occurs
	$newbodytop=$bodytop-$data['pagebreaks']['referral']['area']['position'];
	$sections = pdf_putreportsubjective($p, $bodyleft, $newbodytop, $data, $print);	//Report Subjective Information can grow/shrink each section needs to check before, then page when needed

// Print Objective Until a break, then break on the bodypart+data where the break occurs
	$newbodytop=$bodytop-$data['pagebreaks']['Objective']['Title']['position'];
	$sections = pdf_putreportobjective($p, $bodyleft, $newbodytop, $data, $print);	//Report Subjective Information can grow/shrink each section needs to check before, then page when needed

// ***** THIS MAY NEED WORK HERE *****

// Print Assessment Until a break, then break on the title+data where the break occurs
	$newbodytop=$bodytop-$data['pagebreaks']['Assessment']['Title']['position'];
	$sections = pdf_putreportassessment($p, $bodyleft, $newbodytop, $data, $print);	//	Report Assessment Information

// Print Plan Until a break, then break on the title+data where the break occurs
	$newbodytop=$bodytop-$data['pagebreaks']['Plan']['Title']['position'];
	$sections = pdf_putreportplan($p, $bodyleft, $newbodytop, $data, $print);			//	Report Plan Information

// Print Signature Until a break, then break on the title+data where the break occurs
	$newbodytop=$bodytop-$data['pagebreaks']['Signature']['License']['position'];
	$sections = pdf_putreportsignature($p, $bodyleft, $newbodytop, $data, $print);		//	Closing Information
	$newbodytop=$newbodytop-array_sum($sections);
	pdf_end_page($p);
}

function calculatePrintSpace($p, $x, $y, $w, $fontsize, $lines, $drawsamplebox=false) {
	$fontheight=$fontsize*72/72; // changing denominator bigger makes the font spacing tighter, smaller makes font spacing more loose

	$toppad=$fontheight*0.3;
	$botpad=$fontheight*0.3;

	$singlelineheight=($toppad+$fontheight+$botpad);

	$totalheight=$singlelineheight*$lines;

	$toppos=array();
	$midpos=array();
	$botpos=array();
	$prtpos=array();
	if($drawsamplebox) {
	// draw overall box
		$radius=1;
		pdf_roundrect($p, $x, $y-$totalheight, $w, $totalheight, $radius);
		pdf_stroke($p);
	}
	for($i=0; $i<$lines; $i++) {
//	if(is_array($y))
//		dump("debug", debug_backtrace());
	
		$toppos["$i"]=$y-( ($singlelineheight)*$i);

		$midpos["$i"]=$y -( ($toppad+$fontheight)*($i+1) ) + (($fontheight/2)) + ($botpad*$i);

		$prtpos["$i"]=$y -( ($toppad+$fontheight)*($i+1)) + ($botpad*$i);

		$botpos["$i"]=$y-( $singlelineheight*($i+1) );
		$lineheight["$i"]=$singlelineheight;
		if($drawsamplebox) {
			pdf_roundrect($p, $x, $prtpos["$i"], $w*.3, $fontheight, $radius);
			pdf_stroke($p);
			pdf_line($p, $x, $toppos["$i"], $x+$w, $toppos["$i"], 0, 0, 0);
			pdf_line($p, $x, $botpos["$i"], $x+$w, $botpos["$i"], 0, 0, 0);
			pdf_line($p, $x, $midpos["$i"], $x+$w, $midpos["$i"], 0, 0, 0);
		}
	}
	$printspace=array();
	$printspace['height']=$totalheight;
	$printspace['lineheight']=$lineheight;
	$printspace['top']=$toppos;
	$printspace['middle']=$midpos;
	$printspace['bottom']=$botpos;
	$printspace['print']=$prtpos;
	return($printspace);
}

function widthForStringUsingFontSize($string, $font, $fontSize) {

// string iconv ( string $in_charset , string $out_charset , string $str )
// Performs a character set conversion on the string str from in_charset to out_charset.

     $drawingString = iconv('UTF-8', 'UTF-16BE//IGNORE', $string);

     $characters = array();
     for ($i = 0; $i < strlen($drawingString); $i++) {
         $characters[] = (ord($drawingString[$i++]) << 8 ) | ord($drawingString[$i]);
     }
     $glyphs = $font->glyphNumbersForCharacters($characters);
     $widths = $font->widthsForGlyphs($glyphs);
     $stringWidth = (array_sum($widths) / $font->getUnitsPerEm()) * $fontSize;
     return $stringWidth;
 }

function pdf_putReporttitle($p, $x, $y, $text, $lines, $print=false) {
	$fontsize="16.0";
	$w=pdf_pagewidth()-$x;
	$printspace=calculatePrintSpace($p, $x, $y, $w, $fontsize, $lines);
	if($print)
		pdf_puttextboxed($p, $x, $printspace['print'][0], $w, $printspace['lineheight'][0], $text, $mode="center", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
	return($printspace['height']);
}

function pdf_putSOAPtitle($p, $x, $y, $text, $lines, $print=false) {
// Add line under subjective
	$fontsize="14.0";
	$w=pdf_pagewidth()-$x;
	$printspace=calculatePrintSpace($p, $x, $y, $w, $fontsize, $lines);
	if($print) {
		pdf_puttextboxed($p, $x, $printspace['print'][0], $w, $printspace['lineheight'][0], $text, $mode="center", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
		pdf_line($p, $x, $y-22, 576, $y-22, $dashed=0, $headangle=0, $arrowlength=0);
	}
	return($printspace['height']);
}

function pdf_putSOAPsubtitle($p, $x, $y, $text, $lines, $print=false) {
	$fontsize="10.0";
	$w=pdf_pagewidth()-$x;
	$printspace=calculatePrintSpace($p, $x, $y, $w, $fontsize, $lines);
	if($print)
		pdf_puttextboxed($p, $x, $printspace['print'][0], $w, $printspace['lineheight'][0], $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
	return($printspace['height']);
}

function pdf_putSOAPsubtitlegroup($p, $x, $y, $text, $lines, $print=false) {
	$fontsize="10.0";
	$w=pdf_pagewidth()-$x;
	$printspace=calculatePrintSpace($p, $x, $y, $w, $fontsize, $lines);
	if($print)
		pdf_puttextboxed($p, $x, $printspace['print'][0], $w, $printspace['lineheight'][0], $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
	return($printspace['height']);
}

function pdf_putSOAPsubtitlegrouptopic($p, $x, $y, $text, $lines, $print=false) {
	$fontsize="8.0";
	$w=pdf_pagewidth()-$x;
	$printspace=calculatePrintSpace($p, $x, $y, $w, $fontsize, $lines);
	if($print)
		pdf_puttextboxed($p, $x, $printspace['print'][0], $w, $printspace['lineheight'][0], $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
	return($printspace['height']);
}

function pdf_putSOAPsubtitlegrouptopicdetail($p, $x, $y, $text, $lines, $print=false) {
	$fontsize="8.0";
	$w=pdf_pagewidth()-$x;
	$printspace=calculatePrintSpace($p, $x, $y, $w, $fontsize, $lines);
	if($print)
		pdf_puttextboxed($p, $x, $printspace['print'][0], $w, $printspace['lineheight'][0], $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
	return($printspace['height']);
}

function pdf_putSOAPdetails($p, $x, $y, $textarray, $print=false, $fontsize="8.0", $mode="left", $w=NULL, $font=NULL, $fontcolor=NULL, $feature="") {
	if(empty($w))
		$w=pdf_pagewidth()-$x;
	$lines=count($textarray);
	$printspace=calculatePrintSpace($p, $x, $y, $w, $fontsize, $lines);
	if($print) {
		foreach($textarray as $index=>$text) {
			pdf_puttextboxed($p, $x, $printspace['print']["$index"], $w, $printspace['lineheight']["$index"], $text, $mode, $feature, $font, $fontsize, $fontcolor);
		}
	}
	return($printspace['height']);
}

function put_pdfreportobjectiveNote($p, $x, $y, $data, $print=false) {
	$wrap=pdf_reportwrap();
	$newy=$y;

	$heights['Note']=put_pdfSubTitleAndTextArea($p, $x, $newy, "Note:", 1, $data['header']['rhobjectivenote'], $wrap, $print);
	$newy=$newy-$heights['Note'];

	return(array_sum($heights));
}

function put_pdfreportassessmentNote($p, $x, $y, $data, $print=false) {
	$wrap=pdf_reportwrap();
	$newy=$y;

	$heights['Note']=put_pdfSubTitleAndTextArea($p, $x, $newy, "Note:", 1, $data['header']['rhassessmentnote'], $wrap, $print);
	$newy=$newy-$heights['Note'];

	return(array_sum($heights));
}

function put_pdfreportplanNote($p, $x, $y, $data, $print=false) {
	$wrap=pdf_reportwrap();
	$newy=$y;

	$heights['Note']=put_pdfSubTitleAndTextArea($p, $x, $newy, "Note:", 1, $data['header']['rhtreatmentplannote'], $wrap, $print);
	$newy=$newy-$heights['Note'];

	return(array_sum($heights));
}


function pagebreak($p) {
	pdf_end_page($p);
	pdf_begin_page($p, 612, 792);
}

function pdf_putreportheader($p, $x, $y, $data, $print=false) {

// y for this section
	$thisy=$y;

	if($data['pagebreaks']['reportheader']['logo']['pagebreak']) {
		pagebreak($p);
		$thisy=756;
	}

// Logo
	if(!empty($data['header']['rhlogo']))
		$image=$data['header']['rhlogo'];
	else 
		$image='/img/wsptn logo bw outline.jpg';

	$scaledimageheight=0;
	$heights['reportheader_logo']=0;
	if(file_exists($image)) {
		list($imagedirname, $imagebasename, $imageextension) = pathinfo($image);
		list($imagewidth, $imageheight, $imagetype, $imagetagstring, $imagechannels, $imagebits, $imagemime) = getimagesize($image,$info);

		switch ($imageextension) {
			case 'gif' :
				$imagetype='GIF';
				break;
			case 'jpg' :
				$imagetype='jpeg';
				break;
			case 'png' :
				$imagetype='png';
				break;
			case 'tif' :
			case 'tiff' :
				$imagetype='tiff';
				break;
			default :
				$imagetype='jpeg';
				break;
		}
	
		$scale=round(30/$imageheight,3); 
		$scaledimagewidth=round($imagewidth*$scale,0);
		$scaledimageheight=round($imageheight*$scale,0);
		$imageoffsetx=round($scaledimagewidth/2,0);
		$imageoffsety=round($scaledimageheight/2,0);
		$centerx=((612-$x)/2)-$imageoffsetx;
		$centery=$thisy-$scaledimageheight;
		if($print) 
			pdf_putimage($p, $centerx, $centery, $image, $imagetype, $scale);
		$heights['reportheader_logo']=$scaledimageheight;
	}
	$thisy=$thisy-$heights['reportheader_logo'];

// Address for each used address line
	if(!empty($data['header']['rhcmaddress1'])) $addressline[]=propercase($data['header']['rhcmaddress1']);
	if(!empty($data['header']['rhcmaddress2'])) $addressline[]=propercase($data['header']['rhcmaddress2']);
	$addressline[]=propercase($data['header']['rhcmcity']).", ".strtoupper($data['header']['rhcmstate'])." ".$data['header']['rhcmzip'];
	if(!empty($data['header']['rhcmphone'])) $addressline[]="T:".displayPhone($data['header']['rhcmphone'])."    F:".displayPhone($data['header']['rhcmfax']);

	if($data['pagebreaks']['reportheader']['address']['pagebreak']) {
		pagebreak($p);
		$thisy=756;
	}

	$heights['reportheader_address'] = pdf_putSOAPdetails($p, $x, $thisy, $addressline, $print, $fontsize="9.0", "center");
//	$lines=count($addressline);
//	$fontsize="9.0";
//	$w=pdf_pagewidth()-$x;
//	$printspace=calculatePrintSpace($p, $x, $thisy, $w, $fontsize, $lines, false);
//	if($print) {
//		foreach($addressline as $index=>$text) {
//			$text=propercase($text);
//			pdf_puttextboxed($p, $x, $printspace['print']["$index"], $w, $printspace['height']["$index"], $text, $mode="center", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//		}
//	}
//	$heights['reportheader_address']=$printspace['height'];
	$thisy=$thisy-$heights['reportheader_address'];

// Line
	if($data['pagebreaks']['reportheader']['line']['pagebreak']) {
		pagebreak($p);
		$thisy=756;
	}

	$lines=0.5;
	$fontsize="4.0";
	$w=pdf_pagewidth()-$x;
	$printspace=calculatePrintSpace($p, $x, $thisy, $w, $fontsize, $lines);
	if($print)
		pdf_line($p, $x, $printspace['top'][0]-28, $w, $printspace['top'][0]-28, 0, 0, 0);
	$heights['reportheader_line']=$printspace['height'];
	$thisy=$thisy-$heights['reportheader_line'];

// Title Area Contains Two Data Groups return height of the taller one.
// Report Title
	if($data['pagebreaks']['reportheader']['titlearea']['pagebreak']) {
		pagebreak($p);
		$thisy=756;
	}

	$titleheight=pdf_putReporttitle($p, $x, $thisy, "Initial Evaluation", 1, $print);
//	$text="Initial Evaluation";
//	$lines=1;
//	$fontsize="16.0";
//	$w=pdf_pagewidth()-$x;
//	$printspace=calculatePrintSpace($p, $x, $thisy, $w, $fontsize, $lines);
//	if($print)
//		pdf_puttextboxed($p, $x, $printspace['print'][0], $w, $printspace['height'][0], $text, $mode="center", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$titleheight=$printspace['height'];

// Evaluation/Report Date - evalgendate
	if(!empty($data['header']['rhdate'])) 
		$evaluationdate=displayDate($data['header']['rhdate']);
	else 
		$evaluationdate='____/____/________';

	$generateddate=today();
	$generateddate=displayDate($generateddate);


//	$fontsize="8.0";
//	$w=pdf_pagewidth()-$x;

	$dates=array();
	$dates[]="Evaluation Date:".$evaluationdate;
	$dates[]="Generated Date:".$generateddate;

// x can be just about anywhere depending on what w is.
// if x is 0 then the text prints starting at 0 and the width needs to be the right edge 
// if x is pagewidth-margin-length of text field then the w is the length of the text feld this one is probably most right
	$w=100;
	$thisx=pdf_pagewidth()-pdf_pagemarginright()-$w;
	$datesheight = pdf_putSOAPdetails($p, $thisx, $thisy, $dates, $print, "8.0", "right", $w);


//	$lines=count($dates);
//	$printspace=calculatePrintSpace($p, $x, $thisy, $w, $fontsize, $lines);
//	if($print) {
//		foreach($dates as $index=>$text) {
//			if($print) 
//				pdf_puttextboxed($p, 0, $printspace['print'][$index], $w, $printspace['height']["$index"], $text, $mode="right", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//		}
//	}
//	$datesheight=$printspace['height'];

// ONLY MOVING DOWN THE HEIGHT OF THE larger, compare the two lines of Dates Above with the Title, they are off to the right
	if($titleheight>$datesheight) 
		$heights['reportheader_titlearea']=$titleheight;
	else 
		$heights['reportheader_titlearea']=$datesheight;

	$thisy=$thisy-$heights['reportheader_titlearea'];

	return($heights);
}

function pdf_putreportreferral($p, $x, $y, $data, $print=false) {
//	$thisy=$y;
// format data
// Patient Name - patientname
	if(!empty($data['header']['rhfname'])) {
		if(!empty($data['header']['rhlname']))
			$patientname=$data['header']['rhlname'].', '.$data['header']['rhfname'];
		else
			$patientname='_________________________, '.$data['header']['rhfname'];
	}
	else {
		if(!empty($data['header']['rhlname']))
			$patientname=$data['header']['rhlname'].', _________________________';

		else
			$patientname='_________________________, _________________________';
	}

// Patient Gender - gender
	if(!empty($data['header']['rhsex'])) {
		$gender=$data['header']['rhsex'];
	}
	else {
		$gender='M / F';
	}

// Patient Number - pnum
	if(!empty($data['header']['rhpnum'])) {
		$pnum=$data['header']['rhpnum'];
	}
	else {
		$pnum='____________';
	}

// Date First Seen - firstseendate
	if(!empty($data['header']['rhcrdate'])) {
		$firstseendate=displayDate($data['header']['rhcrdate']);
	}
	else {
		$firstseendate='____/____/________';
	}

// Report ID - reportid
	$reportid=$data['header']['rhid'];

// Date of Birth - birthdate
	if(!empty($data['header']['rhdob'])) {
		$birthdate=displayDate($data['header']['rhdob']);
	}
	else {
		$birthdate='____/____/________';
	}

// Referring Doctor - doctorname
	if(!empty($data['header']['rhdmfname'])) {
		if(!empty($data['header']['rhdmlname']))
			$doctorname=$data['header']['rhdmlname'].', '.$data['header']['rhdmfname'];
		else
			$doctorname='_________________, '.$data['header']['rhdmfname'];
	}
	else {
		if(!empty($data['header']['rhdmlname']))
			$doctorname=$data['header']['rhdmlname'].', _________________';

		else
			$doctorname='_________________, _________________';
	}

// Diagnosis - icd9                rhicd9dxnature1 rhicd9dxbodypart1 rhicd9dxbodydescriptor1
	$icd9codes=icd9CodeOptions();
	$icd9=array();
	if(!empty($data['header']['rhicd9code1'])) 
		$icd9[]=$icd9codes[$data['header']['rhicd9code1']]['description'];

	if(!empty($data['header']['rhicd9code2'])) 
		$icd9[]=$icd9codes[$data['header']['rhicd9code2']]['description'];

	if(!empty($data['header']['rhicd9code3'])) 
		$icd9[]=$icd9codes[$data['header']['rhicd9code3']]['description'];

	if(!empty($data['header']['rhicd9code4'])) 
		$icd9[]=$icd9codes[$data['header']['rhicd9code4']]['description'];

// This Section has four columns Title: Data Title: Data ( up to 7 rows as ICD9 can grow/shrink)
// Data is all prepared above, so we know what we have.
// We'll determine printspace using the second data column because it can have the most elements
	if($data['pagebreaks']['referral']['area']['pagebreak']) {
		pagebreak($p);
		$y=756;
	}

	$referral1=array();
	$referral1[]="Name:";
	$referral1[]="Gender:";
	$referral1[]="Referring Doctor:";
	$referral1[]="Diagnosis:";
	
	$referral2=array();
	$referral2[]=$patientname;
	$referral2[]=$gender;
	$referral2[]=$doctorname;
	if(is_array($icd9) && count($icd9)>0 ) {
		foreach($icd9 as $icd9dx=>$icd9description) 
			$referral2[]=$icd9description;
	}
	else {
		$referral2[]='(Not Specified)';
	}
	$referral3=array();
	$referral3[]="Date of Birth:";
	$referral3[]="Account Number:";
	$referral3[]="First Seen:";

	$referral4=array();
	$referral4[]=$birthdate;
	$referral4[]=$pnum;
	$referral4[]=$firstseendate;

	$fontsize="8.0";
	$w=pdf_pagewidth()-$x;
	$lines=count($referral2);
	$printspace=calculatePrintSpace($p, $x, $y, $w, $fontsize, $lines);

	$referral1width=80;
	$referral2width=350-85-$x;
	$referral3width=80;
	$referral4width=80;
	
	foreach($referral1 as $index=>$text) {
		if($print) 
			pdf_puttextboxed($p, $x, $printspace['print'][$index], $referral1width, $printspace['height']["$index"], $text, $mode="right", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
	}
	foreach($referral2 as $index=>$text) {
		if($print)
			pdf_puttextboxed($p, $x+85, $printspace['print'][$index], $referral2width, $printspace['height']["$index"], $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
	}
	foreach($referral3 as $index=>$text) {
		if($print)
			pdf_puttextboxed($p, $x+350, $printspace['print'][$index], $referral3width, $printspace['height']["$index"], $text, $mode="right", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
	}
	foreach($referral4 as $index=>$text) {
		if($print)
			pdf_puttextboxed($p, $x+435, $printspace['print'][$index], $referral4width, $printspace['height']["$index"], $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
	}

	$heights['referral_area']=$printspace['height'];

//	$lines=1;
//	$fontsize="8.0";
//	$fontheight=$fontsize/72*72;
//	$linespace=round(3/$fontsize * 3, 3); 
//	$lineheight=round($fontheight+$linespace,0); 
//	$toppadding=$fontheight;
//	$w=612-$x;
//	$h=$lineheight*$lines;
//
//	$column1title=$x;
//	$column1data=$x+80;
//
//	$column2title=$x+350;
//	$column2data=$x+430;

// Line 1
//	$linenumber=1;
//	$text="Name:";
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column1title, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['titlename']=$h+$toppadding;
//
//	$text=$patientname;
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column1data, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['dataname']=$h+$toppadding;
//
//	$text="Date of Birth:";
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column2title, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['titlebirthdate']=$h+$toppadding;
//
//	$text=$birthdate;
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column2data, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['databirthdate']=$h+$toppadding;

// Line 2
	//$linenumber=2;
//	$text="Gender:";
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column1title, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['titlegender']=$h+$toppadding;
//
//	$text=$gender;
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column1data, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['datagender']=$h+$toppadding;
//
//	$text="Account#:";
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column2title, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['titleaccount']=$h+$toppadding;
//
//	$text=$pnum;
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column2data, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['dataaccount']=$h+$toppadding;

//Line 3
//	$linenumber=3;
//	$text="Referring Physician:";
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column1title, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['titledoctorname']=$h+$toppadding;
//
//	$text=$doctorname;
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column1data, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['datadoctorname']=$h+$toppadding;
//
//	$text="First Seen:";
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column2title, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['titlefirstseendate']=$h+$toppadding;
//
//	$text=$firstseendate;
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column2data, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['datafirstseen']=$h+$toppadding;

// Line 4
//	$linenumber=4;
//	$text="Diagnosis:";
//	$verticalposition=$y-$heights['title']-$lineheight*$linenumber;
//	pdf_puttextboxed($p, $column1title, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//	$heights['titlediagnosis']=$h+$toppadding;
//
//	foreach($icd9 as $index=>$value) {
//		$temporaryheights=0;
//		if(!empty($value)) {
//			$text=$value;
//			$verticalposition=$y-$heights['title']-$lineheight*($linenumber+$index);
//			pdf_puttextboxed($p, $column1data, $verticalposition, $w, $h, $text, $mode="left", $feature="", $font=NULL, $fontsize, $fontcolor=NULL);
//			$temporaryheights=$temporaryheights+$h+$toppadding;
//		}
//		$heights['datadiagnosis']=temporaryheights;
//	}
	
//	$used=
//		$heights['title']+
//		$heights['titlename']+
////		$heights['dataname']+
////		$heights['titlebirthdate']+
////		$heights['databirthdate']+
//		$heights['titlegender']+
////		$heights['datagender']+
////		$heights['titlepnum']+
////		$heights['datapnum']+
//		$heights['titledoctorname']+
////		$heights['datadoctorname']+
////		$heights['titlefirstseendate']+
////		$heights['datafirstseendate']+
////		$heights['titlediagnosis']+
//		$heights['datadiagnosis'];
//	return($y-$used);

	return($heights);
}

function textareawordwrap($textarea, $wrap) {
	$ww = wordwrap($textarea, $wrap, "\n", false);
	$textarray = explode("\n",$ww);
	return($textarray);
}

function put_pdfSubTitleAndTextArea($p, $x, $y, $title, $titlelines, $textarea, $wrap, $print) {
	$cc1=pdf_putSOAPsubtitlegroup($p, $x, $y, $title, $titlelines, $print);
	$textarray=textareawordwrap($textarea, $wrap);
	$cc2=pdf_putSOAPdetails($p, $x+100, $y, $textarray, $print, "8.0");
	if($cc1>$cc2) 
		$cc=$cc1;
	else
		$cc=$cc2;
	return($cc);
}







function put_pdfreportsubjectiveNote($p, $x, $y, $data, $print=false) {
	$wrap=pdf_reportwrap();
	$newy=$y;

	$heights['Note']=put_pdfSubTitleAndTextArea($p, $x, $newy, "Note:", 1, $data['header']['rhsubjectivenote'], $wrap, $print);
	$newy=$newy-$heights['Note'];

	return(array_sum($heights));
}

function put_pdfreportsubjectiveWork($p, $x, $y, $data, $print=false) {
	$wrap=pdf_reportwrap();
	$newy=$y;

	$heights['Subjective_CurrentWork_Title']=pdf_putSOAPsubtitle($p, $x, $newy, 'Work Details:', 1, $print);
	$newy=$newy-$heights['Subjective_CurrentWork_Title'];
	
	$indent=$x+10;
	if($data['header']['rhworking']) $status="Currently Working"; else $status="Currently Not Working";

	$heights['WorkStatus']=put_pdfSubTitleAndTextArea($p, $indent, $newy, "Status:", 1, $status, $wrap, $print);
	$newy=$newy-$heights['WorkStatus'];

	$heights['WorkOccupation']=put_pdfSubTitleAndTextArea($p, $indent, $newy, "Occupation:", 1, $data['header']['rhoccup'], $wrap, $print);
	$newy=$newy-$heights['WorkOccupation'];

	$heights['WorkJobRequirement']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Job Requirement:', 1, $data['header']['rhjobrequirement'], $wrap, $print);
	$newy=$newy-$heights['WorkJobRequirement'];

	return(array_sum($heights));
}

function put_pdfreportsubjectiveMedicalHistory($p, $x, $y, $data, $print=false) {
	$wrap=pdf_reportwrap();
	$newy=$y;

	$heights['MedicalHistory_Title']=pdf_putSOAPsubtitle($p, $x, $newy, 'Medical History:', 1, $print);
	$newy=$newy-$heights['MedicalHistory_Title'];

	$indent=$x+10;
	$heights['MedicalHistory_Details']=put_pdfSubTitleAndTextArea($p, $indent, $newy, "Medical History:", 1, $data['header']['rhmedicalhistory'], $wrap, $print);
	$newy=$newy-$heights['MedicalHistory_Details'];

	$heights['MedicalHistory_Surgeries']=put_pdfSubTitleAndTextArea($p, $indent, $newy, "Surgeries:", 1, $data['header']['rhsurgeries'], $wrap, $print);
	$newy=$newy-$heights['MedicalHistory_Surgeries'];

	$heights['MedicalHistory_DiagnosticTests']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Diagnostic Tests:', 1, $data['header']['rhdiagnostictests'], $wrap, $print);
	$newy=$newy-$heights['MedicalHistory_DiagnosticTests'];

	$heights['MedicalHistory_Medications']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Medications:', 1, $data['header']['rhmedications'], $wrap, $print);
	$newy=$newy-$heights['MedicalHistory_Medications'];

	return(array_sum($heights));
}

function put_pdfreportsubjectiveCurrentCondition($p, $x, $y, $data, $print=false) {
	$wrap=pdf_reportwrap();
	$newy=$y;

	$heights['CurrentCondition_Title']=pdf_putSOAPsubtitle($p, $x, $newy, 'Current Condition:', 1, $print);
	$newy=$newy-$heights['CurrentCondition_Title'];

// Details
	$indent=$x+10;
	$heights['CurrentCondition_Detail_Title']=pdf_putSOAPsubtitle($p, $indent, $newy, 'Details:', 1, $print);
	$newy=$newy-$heights['CurrentCondition_Detail_Title'];
	
	$heights['CurrentCondition_Detail_ChiefComplaint']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Chief Complaint:', 1, $data['header']['rhchiefcomplaint'], $wrap, $print);
	$newy=$newy-$heights['CurrentCondition_Detail_ChiefComplaint'];

	$heights['CurrentCondition_Detail_OnsetDate']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Onset Date:', 1, displayDate($data['header']['rhinjurydate']), $wrap, $print);
	$newy=$newy-$heights['CurrentCondition_Detail_OnsetDate'];

	$heights['CurrentCondition_Detail_TypeOfInjury']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Type of Injury:', 1, $data['header']['rhcasetypecode'], $wrap, $print);
	$newy=$newy-$heights['CurrentCondition_Detail_TypeOfInjury'];

	$heights['CurrentCondition_Detail_SpecificInjury']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Specific Injury:', 1, $data['header']['rhspecificinjury'], $wrap, $print);
	$newy=$newy-$heights['CurrentCondition_Detail_SpecificInjury'];

// Treatments
	$indent=$x+10;
	$heights['CurrentCondition_Treatments_Title']=pdf_putSOAPsubtitle($p, $indent, $newy, 'Treatments:', 1, $print);
	$newy=$newy-$heights['CurrentCondition_Treatments_Title'];
	
	$heights['CurrentCondition_Treatments_Detail']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Treatment Detail:', 1, $data['header']['rhtreatments'], $wrap, $print);
	$newy=$newy-$heights['CurrentCondition_Treatments_Detail'];

	return(array_sum($heights));
}

function put_pdfreportsubjectivePainHistory($p, $x, $y, $data, $print=false) {
	$wrap=pdf_reportwrap();
	$newy=$y;

	$heights['PainHistory_Title']=pdf_putSOAPsubtitle($p, $x, $newy, 'Pain History:', 1, $print);
	$newy=$newy-$heights['PainHistory_Title'];

// Area
	$indent=$x+10;
	$heights['PainHistory_PainArea_Title']=pdf_putSOAPsubtitle($p, $indent, $newy, 'Pain Area:', 1, $print);
	$newy=$newy-$heights['PainHistory_PainArea_Title'];
	
	$heights['PainHistory_PainArea_Detail']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Area:', 1, $data['header']['rhpainarea'], $wrap, $print);
	$newy=$newy-$heights['PainHistory_PainArea_Detail'];

// Description
	$heights['PainHistory_PainDescription_Title']=pdf_putSOAPsubtitle($p, $indent, $newy, 'Pain Description:', 1, $print);
	$newy=$newy-$heights['PainHistory_PainDescription_Title'];
	
	$heights['PainHistory_PainDescription_Detail']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Area:', 1, $data['header']['rhpaindescription'], $wrap, $print);
	$newy=$newy-$heights['PainHistory_PainDescription_Detail'];

	return(array_sum($heights));
}

function put_pdfreportsubjectiveFunctionalStatus($p, $x, $y, $data, $print=false) {
	$wrap=pdf_reportwrap();
	$newy=$y;

	$heights['FunctionalStatus_Title']=pdf_putSOAPsubtitle($p, $x, $newy, 'Functional Status:', 1, $print);
	$newy=$newy-$heights['FunctionalStatus_Title'];

// Functional Activity
	$indent=$x+10;
	$heights['FunctionalStatus_FunctionalActivity']=pdf_putSOAPsubtitle($p, $indent, $newy, 'Functional Activity:', 1, $print);
	$newy=$newy-$heights['FunctionalStatus_FunctionalActivity'];
	
	$heights['FunctionalStatus_FunctionalActivity_Detail']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Activity:', 1, $data['header']['rhfunctionalactivity'], $wrap, $print);
	$newy=$newy-$heights['FunctionalStatus_FunctionalActivity_Detail'];

	return(array_sum($heights));
}

function pdf_putreportsubjective($p, $x, $y, $data, $print=false) {
	$newy=$y;

	if($data['pagebreaks']['Subjective']['Title']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$heights['Subjective_Title']=pdf_putSOAPtitle($p, $x, $newy, 'Subjective', 1, $print);
	$newy=$newy-$heights['Subjective_Title'];

	if($data['pagebreaks']['Subjective']['Note']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	if( !empty($data['header']['rhsubjectivenoteprint']) && !empty($data['header']['rhsubjectivenote'])) {
		$heights['Subjective_Note']=put_pdfreportsubjectiveNote($p, $x, $newy, $data, $print);
		$newy=$newy-$heights['Subjective_Note'];
	}

	$indent=$x+10;
// Current Condition
	if($data['pagebreaks']['Subjective']['CurrentCondition']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$heights['Subjective_CurrentCondition']=put_pdfreportsubjectiveCurrentCondition($p, $indent, $newy, $data, $print);
	$newy=$newy-$heights['Subjective_CurrentCondition'];

// Pain History
//	if($data['pagebreaks']['Subjective']['PainHistory']['pagebreak']) {
//		pagebreak($p);
//		$newy=756;
//	}

//	$heights['Subjective_PainHistory']=put_pdfreportsubjectivePainHistory($p, $indent, $newy, $data, $print);
//	$newy=$newy-$heights['Subjective_PainHistory'];

// Functional Status
	if($data['pagebreaks']['Subjective']['FunctionalStatus']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$heights['Subjective_FunctionalStatus']=put_pdfreportsubjectiveFunctionalStatus($p, $indent, $newy, $data, $print);
	$newy=$newy-$heights['Subjective_FunctionalStatus'];

// Medical History
	if($data['pagebreaks']['Subjective']['MedicalHistory']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$heights['Subjective_MedicalHistory'] = put_pdfreportsubjectiveMedicalHistory($p, $indent, $newy, $data, $print);
	$newy=$newy-$heights['Subjective_MedicalHistory'];

// Work/Job Details
	if($data['pagebreaks']['Subjective']['Work']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}
	$heights['Subjective_Work']=put_pdfreportsubjectiveWork($p, $indent, $newy, $data, $print);
	$newy=$newy-$heights['Subjective_Work'];

	return($heights);
}

function put_pdfreportobjectiveBodypartTest($p, $x, $y, $data, $testrecordid, $test, $print=NULL) {
	$wrap=pdf_reportwrap();
	$newy=$y;

// Put the Bodypart Test Title 
	$arrayelement='BodypartTest_Title'.$testrecordid;
	$heights["$arrayelement"]=pdf_putSOAPsubtitle($p, $x, $newy, properCase($test['rtname']).' '.$test['rdbtresult1'].' '.$test['rdbtmeasure'], 1, $print);
	$newy=$newy-$heights["$arrayelement"];

// Put Test
// Return Length 
	return(array_sum($heights));
}

function put_pdfreportobjectiveBodypart($p, $x, $y, $data, $bodypartrecordid, $bodypart, $print=NULL) {
	$wrap=pdf_reportwrap();
	$newy=$y;

// Put the Bodypart Title
	$heights['Bodypart_Title']=pdf_putSOAPsubtitle($p, $x, $newy, $bodypart['imbsdescription'], 1, $print);
	$newy=$newy-$heights['Bodypart_Title'];

// Put Each ROM Test
	$indent=$x+10;
	$testarray=array();
	$tests=$data['detail_bodypart_test']['record'];
	if(is_array($tests)) {
		foreach($data['detail_bodypart_test']['record'] as $testrecordid=>$test) {
			if($test['rdbtrdbid']==$bodypartrecordid) {
				if($test['rttype']=='ROM') {
//				$arrayelement='Objective_ROM_Bodypart_Test_'.$testrecordid;
//				$heights["$arrayelement"]=put_pdfreportobjectiveBodypartTest($p, $indent, $newy, $data, $testrecordid, $test, $print);
//				$newy=$newy-$heights["$arrayelement"];			
					$testarray[]=$test['rttype'].' '.properCase($test['rtname']).' '.$test['rdbtresult1'].' '.$test['rtmname'];
				}
			}
		}
	}
//	if(count($testarray)>0) {
//		$heights['Bodypart_ROM'.$bodypartrecordid] = pdf_putSOAPdetails($p, $indent, $newy, $testarray, $print);
//		$newy=$newy-$heights['Bodypart_ROM'.$bodypartrecordid];
//	}

// Put Each SPECIAL Test
	$indent=$x+10;
//	$testarray=array();
	if(is_array($tests)) {
		foreach($data['detail_bodypart_test']['record'] as $testrecordid=>$test) {
			if($test['rdbtrdbid']==$bodypartrecordid) {
				if($test['rttype']=='SPECIAL') {
//					$arrayelement='Objective_SPECIAL_Bodypart_Test_'.$testrecordid;
//					$heights["$arrayelement"]=put_pdfreportobjectiveBodypartTest($p, $indent, $newy, $data, $testrecordid, $test, $print);
//					$newy=$newy-$heights["$arrayelement"];
					$testarray[]=$test['rttype'].' '.properCase($test['rtname']).' '.$test['rdbtresult1'].' '.$test['rtmname'];
				}
			}
		}
	}
	if(count($testarray)>0) {
		$heights['Bodypart_'.bodypartrecordid.'_Tests'] = pdf_putSOAPdetails($p, $indent, $newy, $testarray, $print);
		$newy=$newy-$heights['Bodypart_'.bodypartrecordid.'_Tests'];
	}
//dump("put_pdfreportobjectiveBodypart heights",$heights);
// Return Length of all
	return(array_sum($heights));
}

function pdf_putreportobjective($p, $x, $y, $data, $print=false) {
	$newy=$y;

	if($data['pagebreaks']['Objective']['Title']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$heights['Objective_Title']=pdf_putSOAPtitle($p, $x, $newy, 'Objective', 1, $print);
	$newy=$newy-$heights['Objective_Title'];

	if($data['pagebreaks']['Objective']['Note']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	if( !empty($data['header']['rhobjectivenoteprint']) && !empty($data['header']['rhobjectivenote'])) {
		$heights['Objective_Note']=put_pdfreportobjectiveNote($p, $x, $newy, $data, $print);
		$newy=$newy-$heights['Objective_Note'];
	}

	$indent=$x+10;

	$bodyparts=$data['detail_bodypart']['record'];
	if(is_array($bodyparts)) {
		foreach($bodyparts as $bodypartrecordid=>$bodypart) {

			$arrayelement='Objective_Bodypart_'.$bodypartrecordid;

			if($data['pagebreaks']['Objective']["$arrayelement"]['pagebreak']) {
				pagebreak($p);
				$newy=756;
			}

			$heights["$arrayelement"]=put_pdfreportobjectiveBodypart($p, $indent, $newy, $data, $bodypartrecordid, $bodypart, $print);
			$newy=$newy-$heights["$arrayelement"];
	
		}
	}
	return($heights);
}

function pdf_putreportobjective1($p, $x, $y, $data) {
	$indent=10;
// Output
	$newy=$y;
	$newy-=pdf_putSOAPtitle($p, $x, $newy, 'Objective:', 1);
		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'L-SPINE (Body Part) Active Range Of Motion:', 1);
			$newy-=pdf_putSOAPsubtitlegroup($p, $x+$indent*2, $newy, 'Motion...Range Of Motion', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Flexion...100 Percent', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Extension...100 Percent', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Sidebending Right...100 Percent', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Sidebending Left...100 Percent', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Rotation Right...100 Percent', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Rotation Left...100 Percent', 1);

		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'L-SPINE (Body Part) Passive Range Of Motion:', 1);
			$newy-=pdf_putSOAPsubtitlegroup($p, $x+$indent*2, $newy, 'Motion...Range Of Motion', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Flexion...100 Percent', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Extension...100 Percent', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Sidebending Right...100 Percent', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Sidebending Left...100 Percent', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Rotation Right...100 Percent', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Rotation Left...100 Percent', 1);

		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'Muscle Testing:', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopic($p, $x+$indent*2, $newy, 'Measurement...Right Strength...Left Strength', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Rectus Abdominus...5/5...5/5', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Hip Abductors...5/5...5/5', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Hip Adductors...5/5...5/5', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Back Extensors...5/5...5/5', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'External Oblique...5/5...5/5', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Internal Oblique...5/5...5/5', 1);

			$newy-=pdf_putSOAPsubtitlegroup($p, $x+$indent*2, $newy, 'Pain Description:', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopic($p, $x+$indent*2, $newy, 'Area...Activity/Time...Symptoms...Descriptions', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'L-SPINE...Morning...Improved...Dull', 1);

		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'Special Tests:', 1);
			$newy-=pdf_putSOAPsubtitlegroup($p, $x+$indent*2, $newy, 'Special Test...Right...Left', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Slump Test (Lumbar)...Negative...Negative', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Straight Leg Raise...Negative...Negative', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Quadrant Test (Lumbar)...Negative...Negative', 1);

		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'Functional Tests:', 1);
			$newy-=pdf_putSOAPsubtitlegroup($p, $x+$indent*2, $newy, 'Functional Test...Right...Left', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Test1...Negative...Negative', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Test2...Negative...Negative', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Test3...Negative...Negative', 1);

		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'Joint Mobiliy:', 1);
			$newy-=pdf_putSOAPsubtitlegroup($p, $x+$indent*2, $newy, 'Joint', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Gross Lumbar', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Sacroilliac Joint', 1);

	return($newy);
}

function pdf_putreportobjective2($p, $x, $y, $data) {
	$indent=10;
// Output
	$newy=$y;
		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'Myotomes:', 1);
			$newy-=pdf_putSOAPsubtitlegroup($p, $x+$indent*2, $newy, 'Myotomes...Right...Left', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'L1,2-Psoas...5/5...5/5', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'L3-Quadricep...5/5...5/5', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'L5-EHL/Peroneals...5/5...5/5', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'S1,2-Gastroc/Hams...5/5...5/5', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'S1,2-FHL...5/5...5/5', 1);

		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'Dermatomes:', 1);
			$newy-=pdf_putSOAPsubtitlegroup($p, $x+$indent*2, $newy, 'Dermatome...Right...Left', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'L3...Increased...Increased', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'L4...Increased...Increased', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'L5...Increased...Increased', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'S1...Increased...Increased', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'S2...Increased...Increased', 1);

		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'Reflexes:', 1);
			$newy-=pdf_putSOAPsubtitlegroup($p, $x+$indent*2, $newy, 'Reflex...Right...Left', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'L3-Patelar...2+...2+', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'S1-Achilles...2+...2+', 1);

		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'Palpation:', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Patient tender to calves +/+++', 1);

	return($newy);
}

function pdf_putreportassessment($p, $x, $y, $data, $print=NULL) {
	$newy=$y;
	$wrap=pdf_reportwrap();

	if($data['pagebreaks']['Assessment']['Title']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}
//$heights['Assessment_Pagebreak']=-756+$data['pagebreaks']['Assessment_Title']['height']+$data['pagebreaks']['Assessment_Note']['height']+$data['pagebreaks']['Assessment_Details']['height'];

	$heights['Assessment_Title']=pdf_putSOAPtitle($p, $x, $newy, 'Assessment', 1, $print);
	$newy=$newy-$heights['Assessment_Title'];

	if($data['pagebreaks']['Assessment']['Note']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	if( !empty($data['header']['rhassessmentnoteprint']) && !empty($data['header']['rhassessmentnote'])) {
		$heights['Assessment_Note']=put_pdfreportassessmentNote($p, $x, $newy, $data, $print);
		$newy=$newy-$heights['Assessment_Note'];
	}

	if($data['pagebreaks']['Assessment']['Details']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$indent=$x+10;
	$heights['Assessment_Details']=put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Details:', 1, $data['header']['rhassessment'], $wrap, $print);
	$newy=$newy-$heights['Assessment_Details'];
	return($heights);
}

function pdf_putreportassessment1($p, $x, $y, $data ) {
	$indent=10;
// Output
	$newy=$y;
	$newy-=pdf_putSOAPtitle($p, $x, $newy, 'Assessment:', 1);
//		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'L-SPINE (Body Part) Active Range Of Motion:', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopic($p, $x+$indent*2, $newy, 'Description', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Evaluation has determined decrease in the functional status for this patient.', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Evaluation has found subjective and objective deficits that demonstrates amenability to physical therapy interventions.', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Patient/family are involved in the development of these goals.', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Patient/family are educated about current injury and teeatment.', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopic($p, $x+$indent*2, $newy, 'Potential to reach goals: Good', 1);
	return($newy);
}

function pdf_putreportplan($p, $x, $y, $data, $print=NULL) {
	$newy=$y;
	$wrap=pdf_reportwrap();

	if($data['pagebreaks']['Plan']['Title']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$heights['Plan_Title']=pdf_putSOAPtitle($p, $x, $newy, 'Plan', 1, $print);
	$newy=$newy-$heights['Plan_Title'];

	if($data['pagebreaks']['Plan']['Note']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	if( !empty($data['header']['rhtreatmentplannoteprint']) && !empty($data['header']['rhtreatmentplannote'])) {
		$heights['Plan_Note']=put_pdfreportplanNote($p, $x, $newy, $data, $print);
		$newy=$newy-$heights['Plan_Note'];
	}

	if($data['pagebreaks']['Plan']['Short']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$indent=$x+10;
	$heights['Plan_Short']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Short Term Goals', 1, $data['header']['rhshortgoals'], $wrap, $print);
	$newy=$newy-$heights['Plan_Short'];

	if($data['pagebreaks']['Plan']['Long']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$heights['Plan_Long']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Long Term Goals', 1, $data['header']['rhlonggoals'], $wrap, $print);
	$newy=$newy-$heights['Plan_Long'];

	if($data['pagebreaks']['Plan']['Details']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$heights['Plan_Details']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Treatment Plan', 1, $data['header']['rhtreatmentplan'], $wrap, $print);
	$newy=$newy-$heights['Plan_Details'];

	if($data['pagebreaks']['Plan']['Order']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$heights['Plan_Order']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Doctor\'s Order', 1, $data['header']['rhfrequency']." time(s) per week, for ".$data['header']['rhduration']." week(s).", $wrap, $print);
	$newy=$newy-$heights['Plan_Order'];

	return($heights);
}


function pdf_putreportplan1($p, $x, $y, $data) {
	$indent=10;
// Output
	$newy=$y;
	$newy-=pdf_putSOAPtitle($p, $x, $newy, 'Plan:', 1);
		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'Goals:', 1);
			$newy-=pdf_putSOAPsubtitlegrouptopic($p, $x+$indent*2, $newy, 'Length...Status...Goal', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Short Term...Not Met...1. Independent with home exercise program in 3 visits.', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Long Term...Not Met... 2. Patient able to participate in full recreational activities in 6 weeks.', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Short Term...Not Met...2a. Increase ROM to WNL.', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Short Term...Not Met...2b. Increase strength to WNL.', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Long Term...Not Met...3. Patient to report decreased pain during functional activities in 6 weeks.', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Short Term...Not Met...4. Patient to report decreased pain mesured by visual analog scale.', 1);
		$newy-=pdf_putSOAPsubtitle($p, $x+$indent, $newy, 'Treatment:', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Recommend Physical Therapy for 3 times a week for 4 weeks with treatment to consist of:', 1);
				$newy-=pdf_putSOAPsubtitlegrouptopicdetail($p, $x+$indent*2, $newy, 'Bio/Iso/Myo/etc...', 1);
	return($newy);
}

function pdf_putreportsignature($p, $x, $y, $data, $print=NULL) {
	$newy=$y;
	$wrap=pdf_reportwrap();

	if($data['pagebreaks']['Signature']['Title']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$heights['Signature_Title']=pdf_putSOAPsubtitle($p, $x, $newy, 'Evaluation Performed By:', 1, $print);
	$newy=$newy-$heights['Signature_Title'];

	if($data['pagebreaks']['Signature']['Therapist']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$indent=$x+10;
	$heights['Signature_Therapist']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'Therapist:', 1, $data['header']['rhtherapname'], $wrap, $print);
	$newy=$newy-$heights['Signature_Therapist'];

	if($data['pagebreaks']['Signature']['License']['pagebreak']) {
		pagebreak($p);
		$newy=756;
	}

	$heights['Signature_License']= put_pdfSubTitleAndTextArea($p, $indent, $newy, 'License:', 1, $data['header']['rhtheraplic'], $wrap, $print);
	$newy=$newy-$heights['Signature_License'];
	return($heights);
}

function pdf_putreportsignature1($p, $x, $y, $data) {
	$header=$data['header'];
	$indent=10;
// Output
	$newy=$y;
	$newy-=pdf_putSOAPsubtitlegrouptopic($p, $x+$indent, $newy, $header['rhtherapname'], 1);
	$newy-=pdf_putSOAPsubtitlegrouptopic($p, $x+$indent, $newy, $header['rhtheraplic'], 1);
	return($newy);
}

?>