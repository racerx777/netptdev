<?php
// Therapist Access Level 13
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/reportmanager/config.php'); 
securitylevel(13); 

$TEST_GROUP='MOBILITY';
$test_group='mobility';
$test_group_title="Joint";

// Load options and option value arrays
$options['rdbtrtname']=getSelectOptions($arrayofarrayitems=$validbodyparttestlist["$bp"]["$gp"], $optionvaluefield='*simple', $arrayofoptionfields=array('0'), $defaultoption=NULL, $addblankoption=FALSE, $arraykey=NULL, $arrayofmatchvalues=array());

$options['m1']=getSelectOptions($arrayofarrayitems=$valid_mobility_test_measure_forcedir, $optionvaluefield='value', $arrayofoptionfields=array('value'=>''), $defaultoption=NULL, $addblankoption=FALSE, $arraykey=NULL, $arrayofmatchvalues=array());

$options['m2']=getSelectOptions($arrayofarrayitems=$valid_mobility_test_measure_grade, $optionvaluefield='value', $arrayofoptionfields=array('value'=>''), $defaultoption=NULL, $addblankoption=FALSE, $arraykey=NULL, $arrayofmatchvalues=array());

$options['m3']=getSelectOptions($arrayofarrayitems=$valid_mobility_test_measure_endfeel, $optionvaluefield='value', $arrayofoptionfields=array('value'=>''), $defaultoption=NULL, $addblankoption=FALSE, $arraykey=NULL, $arrayofmatchvalues=array());

$options['m4']=getSelectOptions($arrayofarrayitems=$valid_mobility_test_measure_symptoms, $optionvaluefield='value', $arrayofoptionfields=array('value'=>''), $defaultoption=NULL, $addblankoption=FALSE, $arraykey=NULL, $arrayofmatchvalues=array());

$t[0]['title']='Joint';
$t[0]['descrip']='Joint Name';
$t[0]['field']='rdbtrtname';
$t[0]['size']='24';
$t[0]['maximum']='30';
$t[0]['valid_mobility_test_m1'];
$t[0]['options']=$options['rdbtrtname'];

$m[0]['title']='Dir';
$m[0]['descrip']='Force/Direction';
$m[0]['field']='m1';
$m[0]['size']='27';
$m[0]['maximum']='30';
$m[0]['valid_mobility_test_force'];
$m[0]['options']=$options['m1'];

$m[1]['title']='Grd';
$m[1]['descript']='Grade';
$m[1]['field']='m2';
$m[1]['size']='1';
$m[1]['maximum']='2';
$m[1]['valid_mobility_test_grade'];
$m[1]['options']=$options['m2'];

$m[2]['title']='Feel';
$m[2]['descrip']='End-Feel';
$m[2]['field']='m3';
$m[2]['size']='12';
$m[2]['maximum']='20';
$m[2]['valid_mobility_test_endfeel'];
$m[2]['options']=$options['m3'];

$m[3]['title']='Sym';
$m[3]['descrip']='Symptoms';
$m[3]['field']='m4';
$m[3]['size']='9';
$m[3]['maximum']='20';
$m[3]['valid_mobility_test_symptoms'];
$m[3]['options']=$options['m4'];

if(empty($bp))
	$bp='bodypart';

if(empty($gp))
	$gp='testgroup';

$testcolspan="4";

// ROM Test Type
// $bp=body part code (ie. 10)
// $bd=body part description (ie. C-SPINE)
// $gp=test group id (ie. 1)
// $gd=test group description (ie. PROM)
// $bg=body part code colon test group id (ie. 10:1)

$tableid="OBJ_".$bp."_".$gp;
$tableclass="OBJ_".$TEST_GROUP;

if($requirecompreportdate) {
	$tablewidth='width="650px"';
	$compstyle='style="font-size:smaller;"';
}
else {
	$tablewidth='width="470px"';
	$compstyle='style="display:none;"';
}

if($bilateral) {
	$bilateralstyle='';
	$rowspan="3";
	$colspan="8";
}
else {
	$bilateralstyle='style="display:none;"';
	$rowspan="2";
	$colspan="4";
}

$row=array();
$index=0;

// Header
$rowid=$tableid."_row_th";//.testrow;
$rowclass=$tableid."_row_th";
$row[]='
	<tr id="'.$rowid.'" class="'.$rowclass.'" >
		<th rowspan="'.$rowspan.'" width="200px">'.$test_group_title.'</th>
		<th colspan="'.$colspan.'" width="160px" style="font-size:smaller;" >Current Status:'. displayDate($report['header']['rhvisitdate']).'</th>
		<th colspan="'.$colspan.'" width="160px" '.$compstyle.'>Previous Status:'. displayDate($report['header']['rhcompreportdate']).'</th>
		<th rowspan="'.$rowspan.'" width="40px">&nbsp;</th>
	</tr>
	<tr '.$bilateralstyle.'>
		<th colspan="'.$testcolspan.'" width="80px">Right</th>
		<th colspan="'.$testcolspan.'" width="80px">Left</th>
		<th colspan="'.$testcolspan.'" width="80px" '.$compstyle.'>Right</th>
		<th colspan="'.$testcolspan.'" width="80px" '.$compstyle.'>Left</th>
	</tr>
	<tr>
		<th width="80px" style="font-size:smaller;" >Dir</th>
		<th width="80px" style="font-size:smaller;" >Grd</th>
		<th width="80px" style="font-size:smaller;" >Feel</th>
		<th width="80px" style="font-size:smaller;" >Sym</th>
		<th width="80px" '.$bilateralstyle.'>Dir</th>
		<th width="80px" '.$bilateralstyle.'>Grd</th>
		<th width="80px" '.$bilateralstyle.'>Feel</th>
		<th width="80px" '.$bilateralstyle.'>Sym</th>
		<th width="80px" '.$compstyle.'>Dir</th>
		<th width="80px" '.$compstyle.'>Grd</th>
		<th width="80px" '.$compstyle.'>Feel</th>
		<th width="80px" '.$compstyle.'>Sym</th>
		<th width="80px" '.$compstyle.$bilateralstyle.'>Dir</th>
		<th width="80px" '.$compstyle.$bilateralstyle.'>Grd</th>
		<th width="80px" '.$compstyle.$bilateralstyle.'>Feel</th>
		<th width="80px" '.$compstyle.$bilateralstyle.'>Sym</th>
	</tr>
';

// List of tests available for a bodypart and test group
// ie. 10-1 = C-SPINE and PROM
// 		What tests should popup in the list?
// The tests are setup in javascript.php

// This is one group of records from current report data
$rdbtids=$testindex["$bp"]["$gp"];
if(is_array($rdbtids) && count($rdbtids)>0) {
	foreach($rdbtids as $arrayelement=>$rdbtid) {

// report values
		$array=$report['detail_bodypart_test']["$rdbtid"];
		$cell=array();
		if($lasttestnumber <= $rdbtid)
			$lasttestnumber=$rdbtid;
		$rowid='row_'.$rdbtid;
// Rows from Database
// Current Values from database
		$measure=$t[0];
		$cell[]=getDropDownHTML($tableid, $rowid, $measure['field'], NULL, "valid_tests_".$bp."_".$gp, 'report[detail_bodypart_test]['.$rdbtid.']['.$measure['field'].']', $array[$measure['field']], $measure['size'], $measure['maximum'], $measure['options']);
		foreach($m as $i=>$measure) {
			$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[".$measure['field']."][right]", NULL, "valid_mobility_test_measure_".$measure['field'], 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult1]['.$measure['field'].'][right]', $array['rdbtresult1'][$measure['field']]['right'], $measure['size'], $measure['maximum'], $measure['options']);
		}
		if($bilateral) {
			foreach($m as $i=>$measure) {
				$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[".$measure['field']."][left]", NULL, "valid_mobility_test_measure_".$measure['field'], 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult1]['.$measure['field'].'][left]', $array['rdbtresult1'][$measure['field']]['left'], $measure['size'], $measure['maximum'], $measure['options']);
			}
		}
		// Comparison Values from database
		if($requirecompreportdate) {
			foreach($m as $i=>$measure) {
				$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[".$measure['field']."][right]", NULL, "valid_mobility_test_measure_".$measure['field'], 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult2]['.$measure['field'].'][right]', $array['rdbtresult2'][$measure['field']]['right'], $measure['size'], $measure['maximum'], $measure['options']);
			}
			if($bilateral) {
				foreach($m as $i=>$measure) {
					$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[".$measure['field']."][left]", NULL, "valid_mobility_test_measure_".$measure['field'], 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult2]['.$measure['field'].'][left]', $array['rdbtresult2'][$measure['field']]['left'], $measure['size'], $measure['maximum'], $measure['options']);
				}
			}
		}
// Row Buttons
		$cell[]='<td id="rowbuttons" class="rowadddelbuttons" ><a class="plusminuslink" style="display:none; text-decoration:none; float:left;" onClick="javascript:'.$TEST_GROUP.'TableRowAdd(this);" >&nbsp;&#43;&nbsp;</a><a class="plusminuslink" style="display:block; text-decoration:none; float:right;" onClick="javascript:'.$TEST_GROUP.'TableRowDel(this);" >&nbsp;&minus;&nbsp;</a><input id="'.$tableid.'_'.$rowid.'_bcode" name="report[detail_bodypart_test]['.$rdbtid.'][rdbtbcode]" type="hidden" value="'.$bp.'" /><input id="'.$tableid.'_'.$rowid.'_rtgid" name="report[detail_bodypart_test]['.$rdbtid.'][rdbtrtgid]" type="hidden" value="'.$gp.'" /></td>';
		$cells=implode('',$cell);
		$row[]='<tr class="ROM" id="'.$rdbtid.'">'.$cells.'</tr>';
	}
}

$nameprefix='addbodyparttest['.$bp.']['.$gp.']';
$value=$_POST['addbodyparttest'];
$rowid="row_add";

$cell=array();
$cell[]='<tr class="'.$TEST_GROUP.'" id="'.$TEST_GROUP.'rowadd">';

// Add Row
// Current ADD values
$measure=$t[0];
$cell[]=getDropDownHTML($tableid, $rowid, $measure['field'], NULL, "valid_tests_".$bp."_".$gp, 'report[detail_bodypart_test][add]['.$measure['field'].']', NULL, $measure['size'], $measure['maximum'], $measure['options']);

foreach($m as $i=>$measure) {
	$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[".$measure['field']."][right]", NULL, "valid_mobility_test_measure_".$measure['field'], 'report[detail_bodypart_test][add][rdbtresult1]['.$measure['field'].'][right]', NULL, $measure['size'], $measure['maximum'], $measure['options']);
}

if($bilateral) {
	foreach($m as $i=>$measure) {
		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[".$measure['field']."][left]", NULL, "valid_mobility_test_measure_".$measure['field'], 'report[detail_bodypart_test][add][rdbtresult1]['.$measure['field'].'][left]', NULL, $measure['size'], $measure['maximum'], $measure['options']);
	}
}
// Comparison ADD Values
if($requirecompreportdate) {
	foreach($m as $i=>$measure) {
		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[".$measure['field']."][right]", NULL, "valid_mobility_test_measure_".$measure['field'], 'report[detail_bodypart_test][add][rdbtresult2]['.$measure['field'].'][right]', NULL, $measure['size'], $measure['maximum'], $measure['options']);
	}

	if($bilateral) {
		foreach($m as $i=>$measure) {
			$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[".$measure['field']."][left]", NULL, "valid_mobility_test_measure_".$measure['field'], 'report[detail_bodypart_test][add][rdbtresult2]['.$measure['field'].'][left]', NULL, $measure['size'], $measure['maximum'], $measure['options']);
		}
	}
}

$cell[]='<td id="rowbuttons" class="rowadddelbuttons" >
<a class="plusminuslink" style="display:block; text-decoration:none; float:left;" onClick="javascript:'.$TEST_GROUP.'TableRowAdd(this);" >&nbsp;&#43;&nbsp;</a>
<a class="plusminuslink" style="display:none; text-decoration:none; float:right;" onClick="javascript:'.$TEST_GROUP.'TableRowDel(this);" >&nbsp;&minus;&nbsp;</a>
<input id="'.$tableid.'_'.$rowid.'_bcode" name="report[detail_bodypart_test][add][rdbtbcode]" type="hidden" value="'.$bp.'" />
<input id="'.$tableid.'_'.$rowid.'_rtgid" name="report[detail_bodypart_test][add][rdbtrtgid]" type="hidden" value="'.$gp.'" /></td>';
$cell[]='</tr>
<!-- END OF LIST '.$bp.' add/del -->';
$row[]=implode("\n",$cell);

$table=array();
$table[]='<table id="'.$tableid.'" class="'.$tableclass.'" border="1" cellpadding="2" cellspacing="0" '.$tablewidth.' >';
$table[]=implode("\n",$row);
$table[]='</table>';
$form=implode("\n",$table);
echo($form);
?>
