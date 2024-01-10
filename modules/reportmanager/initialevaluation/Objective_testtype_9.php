<?php
// Therapist Access Level 13
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/reportmanager/config.php'); 
securitylevel(13); 

$TEST_GROUP='REFLEXES';
$test_group='reflexes';
$test_group_title="Reflexes Title";

// Load options and option value arrays
$options["$test_group"]=getSelectOptions($arrayofarrayitems=$validbodyparttestlist["$bp"]["$gp"], $optionvaluefield='*simple', $arrayofoptionfields=array('0'), $defaultoption=NULL, $addblankoption=FALSE, $arraykey=NULL, $arrayofmatchvalues=array());

$optionvalues["$test_group"]=getSelectOptions($arrayofarrayitems=$valid_test_values["$test_group"], $optionvaluefield='value', $arrayofoptionfields=array('value'=>''), $defaultoption=NULL, $addblankoption=FALSE, $arraykey=NULL, $arrayofmatchvalues=array());

if(empty($bp))
	$bp='bodypart';

if(empty($gp))
	$gp='testgroup';

$testcolspan="1";

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
	$compstyle='';
}
else {
	$tablewidth='width="470px"';
	$compstyle='style="display:none;"';
}

if($bilateral) {
	$bilateralstyle='';
	$rowspan="2";
	$colspan="2";
}
else {
	$bilateralstyle='style="display:none;"';
	$rowspan="1";
	$colspan="1";
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
		<th rowspan="'.$rowspan.'" width="40px">&nbsp;</th>
	</tr>
	<tr '.$bilateralstyle.'>
		<th colspan="'.$testcolspan.'" width="80px">Right</th>
		<th colspan="'.$testcolspan.'" width="80px">Left</th>
		<th colspan="'.$testcolspan.'" width="80px" '.$compstyle.'>Right</th>
		<th colspan="'.$testcolspan.'" width="80px" '.$compstyle.'>Left</th>
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
		$rowid=$array['rdbtid'];
// Rows from database
// Current Values from database

// function getDropDownHTML($tableid, $rowid, $field, $num, $inputarrayname, $formfieldname, $formfieldvalue, $formfieldsize, $formfieldmaxsize, $options)
		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtrtname", NULL, "valid_tests_".$bp."_".$gp, 'report[detail_bodypart_test]['.$rdbtid.'][rdbtrtname]', $array['rdbtrtname'], 30, 64 , $options['rdbtrtname']);

		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[right]", NULL, NULL, 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult1][right]', $array['rdbtresult1']['right'], 8, 20, $options['rdbtresult']);

		if($bilateral) 
			$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[left]", NULL, NULL, 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult1][left]', $array['rdbtresult1']['left'], 8, 20, $options['rdbtresult']);

// Comparison Values from database
if($requirecompreportdate) {
	$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[right]", NULL, NULL, 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult2][right]', $array['rdbtresult2']['right'], 8, 20, $options['rdbtresult']);

	if($bilateral) {
		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[left]", NULL, NULL, 'report[detail_bodypart_test]['.$rdbtid.'][rdbtresult2][left]', $array['rdbtresult2']['left'], 8, 20, $options['rdbtresult']);
	}
}

//$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[units]", '', "valid_test_values['$test_group']", 'report[detail_bodypart_test][add][rdbtresult1][units]', NULL, 8, 20, $options['rdbtresult1[units]']);

// Row Buttons
$cell[]='<td id="rowbuttons" class="rowadddelbuttons" ><a class="plusminuslink" style="display:none; text-decoration:none; float:left;" onClick="javascript:'.$TEST_GROUP.'TableRowAdd(this);" >&nbsp;&#43;&nbsp;</a><a class="plusminuslink" style="display:block; text-decoration:none; float:right;" onClick="javascript:'.$TEST_GROUP.'TableRowDel(this);" >&nbsp;&minus;&nbsp;</a><input id="'.$tableid.'_'.$rowid.'_bcode" name="report[detail_bodypart_test]['.$rdbtid.'][rdbtbcode]" type="hidden" value="'.$bp.'" /><input id="'.$tableid.'_'.$rowid.'_rtgid" name="report[detail_bodypart_test]['.$rdbtid.'][rdbtrtgid]" type="hidden" value="'.$gp.'" /></td>';
$cells=implode('',$cell);
$row[]='<tr class="'.$TEST_GROUP.'" id="'.$tableid.'_'.$rdbtid.'">'.$cells.'</tr>';
	}
}

$nameprefix='addbodyparttest['.$bp.']['.$gp.']';
$value=$_POST['addbodyparttest'];

//$rowid="row_add";
$rowid='add';

$cell=array();
$cell[]='<tr class="'.$TEST_GROUP.'" id="'.$tableid.'_'.$rowid.'">';










// Add Row
// Current ADD values
$cell[]=getDropDownHTML($tableid, $rowid, "rdbtrtname", '', "valid_tests_".$bp."_".$gp, 'report[detail_bodypart_test][add][rdbtrtname]['.$tableid.']', NULL, 30, 64 ,$options['rdbtrtname']);
$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[right]", NULL, NULL, 'report[detail_bodypart_test][add][rdbtresult1][right]['.$tableid.']', NULL, 8, 20, $options['rdbtresult']);
if($bilateral) {
	$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[left]", NULL, NULL, 'report[detail_bodypart_test][add][rdbtresult1][left]['.$tableid.']', NULL, 8, 20, $options['rdbtresult']);
}
// Comparison ADD values
if($requirecompreportdate) {
	$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[right]", NULL, NULL, 'report[detail_bodypart_test][add][rdbtresult2][right]['.$tableid.']', NULL, 8, 20, $options['rdbtresult']);
	if($bilateral) {
		$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult2[left]", NULL, NULL, 'report[detail_bodypart_test][add][rdbtresult2][left]['.$tableid.']', NULL, 8, 20, $options['rdbtresult']);
	}
}
//$cell[]=getDropDownHTML($tableid, $rowid, "rdbtresult1[units]", NULL, NULL, 'report[detail_bodypart_test][add][rdbtresult1][units]', NULL, 8, 20, $options['rdbtresult1[units]']);

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