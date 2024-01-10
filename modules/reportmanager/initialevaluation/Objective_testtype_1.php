<?php
// Therapist Access Level 13
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/reportmanager/config.php'); 
securitylevel(13); 

$TEST_GROUP='AROM';
$test_group='arom';
$test_group_title="Active Motion";

// Load options and option value arrays
$options['rdbtrtname']=getSelectOptions($arrayofarrayitems=$validbodyparttestlist["$bp"]["$gp"], $optionvaluefield='*simple', $arrayofoptionfields=array('0'), $defaultoption=NULL, $addblankoption=FALSE, $arraykey=NULL, $arrayofmatchvalues=array(), FALSE);

$options['m1']=getSelectOptions($arrayofarrayitems=$valid_rom_test_measure_0_90, $optionvaluefield='value', $arrayofoptionfields=array('value'=>''), $defaultoption=NULL, $addblankoption=FALSE, $arraykey=NULL, $arrayofmatchvalues=array());

$options['m2']=getSelectOptions($arrayofarrayitems=$valid_mmt_test_measure, $optionvaluefield='value', $arrayofoptionfields=array('value'=>''), $defaultoption=NULL, $addblankoption=FALSE, $arraykey=NULL, $arrayofmatchvalues=array());

$options['valid_rom_units']=getSelectOptions($arrayofarrayitems=$valid_rom_units, $optionvaluefield='value', $arrayofoptionfields=array('value'=>''), $defaultoption=NULL, $addblankoption=FALSE, $arraykey=NULL, $arrayofmatchvalues=array());

if(empty($bp))
	$bp='bodypart';

if(empty($gp))
	$gp='testgroup';

$testcolspan="2";

// ROM Test Type
// $bp=body part code (ie. 10)
// $bd=body part description (ie. C-SPINE)
// $gp=test group id (ie. 1)
// $gd=test group description (ie. AROM)
// $bg=body part code colon test group id (ie. 10:1)

$tableid="OBJ_".$bp."_".$gp;
$tableclass="OBJ_".$TEST_GROUP;

if($requirecompreportdate) {
	$tablewidth='width="650px"';
	$compstyle='';
}
else {
	$tablewidth='width="470px"';
	$compstyle='style="display:none;"';
}

if($bilateral) {
	$bilateralstyle='';
	$rowspan="3";
	$colspan="4";
}
else {
	$bilateralstyle='style="display:none;"';
	$rowspan="2";
	$colspan="2";
}

$row=array();
$index=0;

// Header
$rowid=$tableid."_row_th";//.testrow;
$rowclass=$tableid."_row_th";
$row[]='
	<tr id="'.$rowid.'" class="'.$rowclass.'" >
		<th rowspan="'.$rowspan.'" width="200px">'.$test_group_title.'</th>
		<th colspan="'.$colspan.'" width="160px">Current Status:'. displayDate($report['header']['rhvisitdate']).'</th>
		<th colspan="'.$colspan.'" width="160px" '.$compstyle.'>Previous Status:'. displayDate($report['header']['rhcompreportdate']).'</th>
		<th rowspan="'.$rowspan.'" width="60px">Units</th>
		<th rowspan="'.$rowspan.'" width="40px">&nbsp;</th>
	</tr>
	<tr '.$bilateralstyle.'>
		<th colspan="'.$testcolspan.'" width="80px">Right</th>
		<th colspan="'.$testcolspan.'" width="80px">Left</th>
		<th colspan="'.$testcolspan.'" width="80px" '.$compstyle.'>Right</th>
		<th colspan="'.$testcolspan.'" width="80px" '.$compstyle.'>Left</th>
	</tr>
	<tr>
		<th width="40px">ROM</th>
		<th width="40px">MMT</th>
		<th width="40px" '.$bilateralstyle.'>ROM</th>
		<th width="40px" '.$bilateralstyle.'>MMT</th>
		<th width="40px" '.$compstyle.'>ROM</th>
		<th width="40px" '.$compstyle.'>MMT</th>
		<th width="40px" '.$compstyle.$bilateralstyle.'>ROM</th>
		<th width="40px" '.$compstyle.$bilateralstyle.'>MMT</th>
	</tr>
';

// List of tests available for a bodypart and test group
// ie. 10-1 = C-SPINE and AROM
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
		$rowid=$array['rdbtid'];
// Rows from database
// Current Values from database
		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtrtname", '', "valid_tests_".$bp."_".$gp, 'report[detail_bodypart_test]['.$rdbtid.'][rdbtrtname]', $array['rdbtrtname'], 30, 64 ,$options['rdbtrtname']);
		
		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[m1][right]", $rdbtid, "valid_rom_test_measure_0_90", 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult1][m1][right]', $array['rdbtresult1']['m1']['right'], 1, 3, $options['m1']);
		
		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[m2][right]", $rdbtid, "valid_mmt_test_measure", 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult1][m2][right]', $array['rdbtresult1']['m2']['right'], 1, 3, $options['m2']);


		if($bilateral) {
			$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[m1][left]", $rdbtid, "valid_rom_test_measure_0_90", 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult1][m1][left]', $array['rdbtresult1']['m1']['left'], 1, 3, $options['m1']);
			$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[m2][left]", $rdbtid, "valid_mmt_test_measure", 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult1][m2][left]', $array['rdbtresult1']['m2']['left'], 1, 3, $options['m2']);
		}


// Comparison Values from database
		if($requirecompreportdate) {
			$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[m1][right]", $rdbtid, "valid_rom_test_measure_0_90", 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult2][m1][right]', $array['rdbtresult2']['m1']['right'], 1, 3, $options['m1']);
			$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[m2][right]", $rdbtid, "valid_mmt_test_measure", 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult2][m2][right]', $array['rdbtresult2']['m2']['right'], 1, 3, $options['m2']);
		
			if($bilateral) {
				$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[m1][left]", $rdbtid, "valid_rom_test_measure_0_90", 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult2][m1][left]', $array['rdbtresult2']['m1']['left'], 1, 3, $options['m1']);
				$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[m2][left]", $rdbtid, "valid_mmt_test_measure", 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult2][m1][left]', $array['rdbtresult2']['m2']['left'], 1, 3, $options['m2']);
			}
		}

$cell[]=getDropDownHTML($tableid, $rowid, "rdbtrtmname", $rdbtid, "valid_rom_units", 'report[detail_bodypart_test]['.$rdbtid.'][rdbtrtmname]', $array['rdbtrtmname'], 6, 10, $options['valid_rom_units']);

// Row Buttons
$cell[]='<td id="rowbuttons" class="rowadddelbuttons" ><a class="plusminuslink" style="display:none; text-decoration:none; float:left;" onClick="javascript:'.$TEST_GROUP.'TableRowAdd(this);" >&nbsp;&#43;&nbsp;</a><a class="plusminuslink" style="display:block; text-decoration:none; float:right;" onClick="javascript:'.$TEST_GROUP.'TableRowDel(this);" >&nbsp;&minus;&nbsp;</a><input id="'.$tableid.'_'.$rowid.'_bcode" name="report[detail_bodypart_test]['.$rdbtid.'][rdbtbcode]" type="hidden" value="'.$bp.'" /><input id="'.$tableid.'_'.$rowid.'_rtgid" name="report[detail_bodypart_test]['.$rdbtid.'][rdbtrtgid]" type="hidden" value="'.$gp.'" /></td>';
$cells=implode('',$cell);
$row[]='<tr class="'.$TEST_GROUP.'" id="'.$tableid.'_'.$rdbtid.'">'.$cells.'</tr>';
	}
}

$nameprefix='addbodyparttest['.$bp.']['.$gp.']';
$value=$_POST['addbodyparttest'];

$rowid='add';

$cell=array();
$cell[]='<tr class="'.$TEST_GROUP.'" id="'.$tableid.'_'.$rowid.'">';

// Add Row
// Current ADD values
		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtrtname", '', "valid_tests_".$bp."_".$gp, 'report[detail_bodypart_test][add][rdbtrtname]['.$tableid.']', NULL, 30, 64 ,$options['rdbtrtname']);
		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[m1][right]", '', "valid_rom_test_measure_0_90", 'report[detail_bodypart_test][add][rdbtresult1][m1][right]['.$tableid.']', NULL, 1, 3, $options['m1']);
		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[m2][right]", '', "valid_mmt_test_measure", 'report[detail_bodypart_test][add][rdbtresult1][m2][right]['.$tableid.']', NULL, 1, 3, $options['m2']);

		if($bilateral) {
			$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[m1][left]", '', "valid_rom_test_measure_0_90", 'report[detail_bodypart_test][add][rdbtresult1][m1][left]['.$tableid.']', NULL, 1, 3, $options['m1']);
			$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[m2][left]", '', "valid_mmt_test_measure", 'report[detail_bodypart_test][add][rdbtresult1][m2][left]['.$tableid.']', NULL, 1, 3, $options['m2']);
		}

// Comparison ADD values
		if($requirecompreportdate) {
			$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[m1][right]", '', "valid_rom_test_measure_0_90", 'report[detail_bodypart_test][add][rdbtresult2][m1][right]['.$tableid.']', NULL, 1, 3, $options['m1']);
			$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[m2][right]", '', "valid_mmt_test_measure", 'report[detail_bodypart_test][add][rdbtresult2][m2][right]['.$tableid.']', NULL, 1, 3, $options['m2']);
			if($bilateral) {
				$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[m1][left]", '', "valid_rom_test_measure_0_90", 'report[detail_bodypart_test][add][rdbtresult2][m1][left]['.$tableid.']', NULL, 1, 3, $options['m1']);
				$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[m2][left]", '', "valid_mmt_test_measure", 'report[detail_bodypart_test][add][rdbtresult2][m2][left]['.$tableid.']', NULL, 1, 3, $options['m2']);
			}
		}

if(empty($report[detail_bodypart_test][add][rdbtrtmname]['.$tableid.']))
	$report[detail_bodypart_test][add][rdbtrtmname]['.$tableid.']='DEGREES';

$cell[]=getDropDownHTML($tableid, $rowid, "rdbtrtmname", '', "valid_rom_units", 'report[detail_bodypart_test][add][rdbtrtmname]['.$tableid.']', $report[detail_bodypart_test][add][rdbtrtmname]['.$tableid.'], 6, 10, $options['valid_rom_units']);

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