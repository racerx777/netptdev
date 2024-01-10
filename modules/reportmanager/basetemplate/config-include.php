<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php'); 
securitylevel(13); 
session_set_cookie_params(120*60);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();
$dbselect = dbselect($dbhandle);

// These functions are not being used at this time

//function getReportTypesx($inactive='0') {
//	$reporttypes=array();
//	if($inactive=='1')
//		$where = "rtinactive='1'";
//	if($inactive=='0')
//		$where = "rtinactive='0'";
//	if(is_null($inactive))
//		$where="";
//	$selectquery="SELECT * FROM report_template WHERE $where ORDER BY rtdispseq, rtname, rtdescription";
//	if($selectresult=mysql_query($selectquery)) {
//		while($selectrow=mysql_fetch_assoc($selectresult)) {
//			$rtid=$selectrow['rtid']; 
//			$reporttypes["$rtid"]=$selectrow;
//		}
//	}
//	else
//		error("999","reportType:getReportTypes:SELECT report_template error.<br>$selectquery<br>".mysql_error());
//	return($reporttypes);
//}

//function getReportTypes($inactive='0') {
//	if( $result=getTableOptions($tablesarray='report_template', $keyfieldsarray='rtid', $fieldsarray='*', $wherefieldsarray="rtinactive='$inactive'", $orderfieldsarray='rtdispseq, rtname, rtdescription') ) {
//		return($result);
//	}
//	else {
//		errorclear();
//		return(array());
//	}
//}
//
//function getReportInjuryTemplateOptionsx($inactive='0', $criteria=NULL) {
//	$records=array();
//	$fields=array('*');
//	$tables=array('report_injury_template');
//	$sortfields=array('rittdispseq', 'rittname', 'rittdescription');
//	$keyfields=array('rittid');
//	$inactivefield='rittinactive';
//
//	if(empty($criteria))
//		$criteria=array();
//	else {
//		if(!is_array($criteria)) {
//			$criteria=array("$criteria");
//		}
//	}
//
//	if($inactive=='1')
//		$criteria[] = "$inactivefield='1'";
//	if($inactive=='0')
//		$criteria[] = "$inactivefield='0'";
//
//	if( count($fields)>0 ) 
//		$select="SELECT ".implode(", ", $fields);
//
//	if( count($tables)>0 ) 
//		$from="FROM ".implode(", ", $tables);
//
//	if( count($criteria)>0 ) 
//		$where="WHERE ".implode(" and ", $criteria);
//
//	if( count($sortfields)>0 ) 
//		$orderby="ORDER BY ".implode(", ", $sortfields);
//
//	$selectquery="$select $from $where $orderby";
//	if($selectresult=mysql_query($selectquery)) {
//		while($selectrow=mysql_fetch_assoc($selectresult)) {
//			$key=$selectrow["$keyfield"]; 
//			$records["$key"]=$selectrow;
//		}
//	}
//	else
//		error("999","config:getReportInjuryTemplateOptions:SELECT $table error.<br>$selectquery<br>".mysql_error());
//	return($records);
//}

//function getReportTestTemplatesOptions($inactive='0', $criteria=NULL) {
//	if( $result=getTableOptions($tablesarray='report_test_templates', $keyfieldsarray='rtnid', $fieldsarray='*', $wherefieldsarray=NULL, $orderfieldsarray='rttnseq, rtndescription') ) {
//		return($result);
//	}
//	else {
//		errorclear();
//		return(array());
//	}
//}

//function getReportTestTemplateRelationsOptions($inactive='0', $criteria=NULL) {
//	if( $result=getTableOptions($tablesarray='report_test_template_relations', $keyfieldsarray='rttrbcode,rttricd9', $fieldsarray='*', $wherefieldsarray=NULL, $orderfieldsarray='rttrbcode,rttricd9,rttrdispseq') ) {
//		return($result);
//	}
//	else {
//		errorclear();
//		return(array());
//	}
//}

//function getReportTestTemplateList($inactive='0', $criteria=NULL) {
//	if( $result=getTableOptions($tablesarray='report_bodyparts,report_test_template_relations,report_test_templates,report_test_template_tests,report_tests', $keyfieldsarray='rbbcode,rttype,rtid', $fieldsarray='rbbcode,rbsdescription,rttrrtnid,rttrbcode,rttricd9,rttrrtnid,rtnid,rtnname,rttid,rttrtnid,rtttesttype,rttrtid,rtid,rtname,rttype,rtmeasurecode', $wherefieldsarray='rbbcode=rttrbcode and rttrrtnid=rtnid and rtnid=rttrtnid and rttrtid=rtid', $orderfieldsarray='rbseq,rbbcode,rttype,rttdispseq') ) {
//	if( $result=getTableOptions($tablesarray='report_bodyparts,report_test_template_relations,report_test_templates,report_test_template_tests,report_tests', $keyfieldsarray='rbbcode,rttype,rtid', $fieldsarray='rbbcode,rbsdescription,rttricd9,rtnid,rtnname,rttid,rtid,rtname,rttype,rtmeasurecode', $wherefieldsarray='rbbcode=rttrbcode and rttrrtnid=rtnid and rtnid=rttrtnid and rttrtid=rtid', $orderfieldsarray='rbseq,rbbcode,rttype,rttdispseq') ) {

//	if( $result=getTableOptions($tablesarray='report_bodyparts,report_test_template_relations,report_test_templates,report_test_template_tests,report_tests,report_tests_measure', $keyfieldsarray='rbbcode,rttype,rtid', $fieldsarray='rbbcode,rttype,rtid,rbsdescription,rtname,rtmname', $wherefieldsarray='rbbcode=rttrbcode and rttrrtnid=rtnid and rtnid=rttrtnid and rttrtid=rtid and rtmid=rtmeasurecode', $orderfieldsarray='rbseq,rbbcode,rttype,rttdispseq') ) {
//		return($result);
//	}
//	else {
//		errorclear();
//		return(array());
//	}
//}

function getInjuryTemplates($ritid) {
// CALLED FROM THERAPIST-INFO.PHP
	$tablesarray='report_injury_templates';
	$keyfieldsarray='ritid';
	$fieldsarray='*';
	$wherefieldsarray="ritid='$ritid'";
	$orderfieldsarray='ritid';
	if( $result=getTableOptions($tablesarray, $keyfieldsarray, $fieldsarray, $wherefieldsarray, $orderfieldsarray) ) {
		return($result);
	}
	else {
		errorclear();
		return(array());
	}
}

// Used in this config-include.php file
function getBodypartTestGroups($bcode, $inactive='0', $criteria=NULL) {
	$wherefields[]="rbtgbcode='$bcode'";
	$wherefields[]="rbtgrtgid=rtgid";
	if( $result=getTableOptions($tablesarray='report_bodypart_test_groups,report_test_groups', $keyfieldsarray='rbtgbcode,rtgid', $fieldsarray='rbtgbcode,rbtgseq,rtgid,rtgname,rtgdescription', $wherefieldsarray=$wherefields, $orderfieldsarray='rbtgseq') ) {
		return($result);
	}
	else {
		errorclear();
		return(array());
	}
}

function getBodypartTestGroupTests($bcode, $groupid, $inactive='0', $criteria=NULL) {
	$wherefields[]="rbbcode='$bcode'"; // bodypart = parameter passed

	$wherefields[]="rbbcode=rbtgbcode"; // Join
	$wherefields[]="rbtgrtgid='$groupid'";

	$wherefields[]="rbtgrtgid=rtgid"; // Tests for this group only

	$wherefields[]="rbbcode=rbtbcode";
	$wherefields[]="rbtgrtgid=rbtrbtgid";

	$wherefields[]="rbtrtid=rtid";
	if( $result=getTableOptions($tablesarray='report_bodyparts,report_bodypart_test_groups,report_test_groups,report_bodypart_tests,report_tests', $keyfieldsarray='rbbcode,rtgid,rtid', $fieldsarray='rbbcode,rbbilatflag,rtgid,rtid,rbtgseq,rtgname,rtgdescription,rtname', $wherefieldsarray=$wherefields, $orderfieldsarray='rbbcode,rtgid,rbtdispseq',1) ) {
		return($result);
	}
	else {
		errorclear();
		return(array());
	}
}

//function getReportInjuryTemplateOptions($inactive='0', $criteria=NULL) {
//	if( $result=getTableOptions($tablesarray='report_injury_template', $keyfieldsarray='ritid', $fieldsarray='*', $wherefieldsarray="ritinactive='$inactive'", $orderfieldsarray='ritdispseq, ritname, ritdescription') ) {
//		return($result);
//	}
//	else {
//		errorclear();
//		return(array());
//	}
//}

function getReportBodypartsOptions($inactive='0', $criteria=NULL) {
	if( $result=getTableOptions($tablesarray='report_bodyparts', $keyfieldsarray='rbbcode', $fieldsarray='*', $wherefieldsarray="rbinactive='$inactive'", $orderfieldsarray='rbseq, rbsdescription') ) {
		return($result);
	}
	else {
		errorclear();
		return(array());
	}
}

function getReportTestTemplateList1($inactive='0', $criteria=NULL) {
	$tablesarray='report_bodyparts, report_test_template_relations, report_test_templates, report_test_template_tests, report_tests, report_tests_measure';
	$keyfieldsarray='rbbcode,rttype,rtid';
	$fieldsarray='*';
	if(empty($criteria))
		$wherefieldsarray='rbbcode=rttrbcode and rttrrtnid=rtnid and rtnid=rttrtnid and rttrtid=rtid and rtmid=rtmeasurecode';
	else
		$wherefieldsarray='(rbbcode=rttrbcode and rttrrtnid=rtnid and rtnid=rttrtnid and rttrtid=rtid and rtmid=rtmeasurecode) and '.$criteria;
	$orderfieldsarray='rbseq,rbbcode,rttype,rttdispseq';
	if( $result=getTableOptions($tablesarray, $keyfieldsarray, $fieldsarray, $wherefieldsarray, $orderfieldsarray) ) {
		return($result);
	}
	else {
		errorclear();
		return(array());
	}
}

function getBodyparts($bodypart) {
	$tests=getReportTestTemplateList1('0', 'rbsdescription="'.$bodypart.'"');
	foreach($tests as $key=>$val) {
		$results[]=strtoupper($val['rbsdescription']);
	}
	return($results);
}

function getTests($bodypart, $type) {
	$tests=getReportTestTemplateList1('0','rbsdescription="'.$bodypart.'" and rttype="'.$type.'"');
	foreach($tests as $key=>$val) {
		$results[]=strtoupper($val['rtname']);
	}
	return($results);
}

function geticd9codes() {
	$tablesarray='master_ICD9';
	$keyfieldsarray='imicd9';
	$fieldsarray='imicd9,imdx,imncode,imbcode';
	$wherefieldsarray='iminactive="0"';
	$orderfieldsarray='imicd9';
	if( $result=getTableOptions($tablesarray, $keyfieldsarray, $fieldsarray, $wherefieldsarray, $orderfieldsarray) ) {
		return($result);
	}
	else {
		errorclear();
		return(array());
	}
}

function getinjuries() {
	$tablesarray='master_ICD9,master_injury_nature';
	$keyfieldsarray='imnsdescription';
	$fieldsarray='imnsdescription';
	$wherefieldsarray='master_ICD9.imncode=master_injury_nature.imncode and iminactive="0" and imninactive="0"';
	$orderfieldsarray='imnsdescription';
	if( $result=getTableOptions($tablesarray, $keyfieldsarray, $fieldsarray, $wherefieldsarray, $orderfieldsarray) ) {
		foreach($result as $key=>$value) {
			if(is_array($value)) {
				foreach($value as $k=>$v) 
					$value["$k"]=strtoupper($v);
				$result["$key"]=$value;
			}
			else
				$result["$key"]=strtoupper($value);
		}
		return($result);
	}
	else {
		errorclear();
		return(array());
	}
}

function p2j($p,$j,$a=NULL) {
	$s=array();
	if(is_array($p)) {
		$c=count($p);
		$s=array();
		$s[]='<script type="text/javascript" language="javascript" >';
		$s[]='var '.$j.' = new Array('.$c.'); ';
		$i=0;
		foreach($p as $k=>$v) {
			if(is_array($v)) {
				if(empty($a)) 
					$z=$v[0];
				else
					$z=$v["$a"];
			}
			else 
				$z=$v;
			$z=addslashes($z);
			$s[]=$j.'['.$i."]='".$z."'; ";
			$i++;
		}
		$s[]='</script>
';
	}
	return(implode('',$s));
}



// Perform initialization of some arrays for application wide usage

// Load javascript arrays for autocomplete values
// For Each Valid Body Part and Each Valid Bodypart Test Group Load Array.
$validbodypartlist=array();
$validbodyparttestgrouplist=array();
$validbodyparttestlist=array();
$bodyparts=getReportBodypartsOptions(); // Get all Bodyparts
if(is_array($bodyparts) && count($bodyparts)>0 ) {
	foreach($bodyparts as $key=>$bodypartarray) {
		$bcode=$bodypartarray['rbbcode'];
		$validbodypartlist["$bcode"]=$bodypartarray['rbsdescription'];

		$bodyparttestgroups=getBodypartTestGroups($bcode); // Get all Bodypart Test Groups - keys rbtgbcode,rtgid
		if(is_array($bodyparttestgroups) && count($bodyparttestgroups)>0 ) {
			foreach($bodyparttestgroups as $bcodegroup=>$bodyparttestgrouparray) {
				$groupid=$bodyparttestgrouparray['rtgid'];
				$validbodyparttestgrouplist["$bcode"]["$groupid"]=$bodyparttestgrouparray['rtgname'];

				$bodyparttestgrouptests=getBodypartTestGroupTests($bcode, $groupid); // Get all Bodypart Tests - keys rbbcode:rtgid:rtid

//if($bcode=='10' && $groupid==4) {
//dump("bodyparttestgrouptests",$bodyparttestgrouptests);
//}

				if(is_array($bodyparttestgrouptests) && count($bodyparttestgrouptests)>0 ) {
					foreach($bodyparttestgrouptests as $bcodegroupidtestid=>$bodyparttestarray) {
						$rtid=$bodyparttestarray['rtid'];
						$validbodyparttestlist["$bcode"]["$groupid"]["$rtid"]=$bodyparttestarray['rtname'];
					}
				}
			}
		}
	}
}

$prognosis=array(
1=>array("value"=>"EXCELLENT"),
2=>array("value"=>"GOOD"),
3=>array("value"=>"FAIR"),
4=>array("value"=>"POOR"),
5=>array("value"=>"GUARDED")
);

$descriptors=array(
1=>array("value"=>"LEFT"),
2=>array("value"=>"RIGHT"),
3=>array("value"=>"BILATERAL"),
4=>array("value"=>"UPPER"),
5=>array("value"=>"LOWER")
);

$valid_rom_units=array(
1=>array("value"=>"DEGREES"),
2=>array("value"=>"PERCENT")
);

$valid_rom_test_measure_0_90=array(
0=>array("value"=>"0"),
1=>array("value"=>"5"),
2=>array("value"=>"10"),
3=>array("value"=>"15"),
4=>array("value"=>"20"),
5=>array("value"=>"25"),
6=>array("value"=>"30"),
7=>array("value"=>"35"),
8=>array("value"=>"40"),
9=>array("value"=>"45"),
10=>array("value"=>"50"),
11=>array("value"=>"55"),
12=>array("value"=>"60"),
13=>array("value"=>"65"),
14=>array("value"=>"70"),
15=>array("value"=>"75"),
16=>array("value"=>"80"),
17=>array("value"=>"85"),
18=>array("value"=>"90")
);

$valid_rom_test_measure_0_180=array(
0=>array("value"=>"0"),
1=>array("value"=>"5"),
2=>array("value"=>"10"),
3=>array("value"=>"15"),
4=>array("value"=>"20"),
5=>array("value"=>"25"),
6=>array("value"=>"30"),
7=>array("value"=>"35"),
8=>array("value"=>"40"),
9=>array("value"=>"45"),
10=>array("value"=>"50"),
11=>array("value"=>"55"),
12=>array("value"=>"60"),
13=>array("value"=>"65"),
14=>array("value"=>"70"),
15=>array("value"=>"75"),
16=>array("value"=>"80"),
17=>array("value"=>"85"),
18=>array("value"=>"90"),
19=>array("value"=>"95"),
20=>array("value"=>"100"),
21=>array("value"=>"105"),
22=>array("value"=>"110"),
23=>array("value"=>"115"),
24=>array("value"=>"120"),
25=>array("value"=>"125"),
26=>array("value"=>"130"),
27=>array("value"=>"135"),
28=>array("value"=>"140"),
29=>array("value"=>"145"),
30=>array("value"=>"150"),
31=>array("value"=>"155"),
32=>array("value"=>"160"),
33=>array("value"=>"165"),
34=>array("value"=>"170"),
35=>array("value"=>"175"),
36=>array("value"=>"180")
);

$valid_mmt_test_measure=array(
0=>array("value"=>"0/5"),
1=>array("value"=>"1/5"),
2=>array("value"=>"2/5"),
3=>array("value"=>"3/5"),
4=>array("value"=>"4/5"),
5=>array("value"=>"5/5")
);

$valid_special_test_measure=array(
0=>array("value"=>"Positive"),
1=>array("value"=>"Negative"),
2=>array("value"=>"Positive w/pain")
);

$valid_myotomes_test_measure=array(
0=>array("value"=>"0/5"),
1=>array("value"=>"1/5"),
2=>array("value"=>"2/5"),
3=>array("value"=>"3/5"),
4=>array("value"=>"4/5"),
5=>array("value"=>"5/5")
);

$valid_dermatomes_test_measure=array(
0=>array("value"=>"Absent"),
1=>array("value"=>"Impaired"),
2=>array("value"=>"Normal"),
3=>array("value"=>"Hypersensitive")
);


$valid_girth_units=array(
0=>array("value"=>"mm"),
1=>array("value"=>"cm"),
2=>array("value"=>"In.")
);

$valid_mobility_test_measure_forcedir=array(
0=>array("value"=>"P-A Central"),
1=>array("value"=>"P-A Right Transvere Process"),
2=>array("value"=>"P-A Left Transverse Process"),
3=>array("value"=>"Right Rotation"),
4=>array("value"=>"Left Rotation")
);

$valid_mobility_test_measure_grade=array(
0=>array("value"=>"I"),
1=>array("value"=>"II"),
2=>array("value"=>"III"),
3=>array("value"=>"IV"),
4=>array("value"=>"V"),
5=>array("value"=>"VI")
);

$valid_mobility_test_measure_endfeel=array(
0=>array("value"=>"Normal"),
1=>array("value"=>"Soft"),
2=>array("value"=>"Firm"),
3=>array("value"=>"Hard"),
4=>array("value"=>"Muscle Spasm"),
5=>array("value"=>"Boggy"),
6=>array("value"=>"Springy"),
7=>array("value"=>"Empty")
);

$valid_mobility_test_measure_symptoms=array(
0=>array("value"=>"Increased"),
1=>array("value"=>"Decreased"),
2=>array("value"=>"No Change")
);

// arrays used are created in config.php
$configjs[]=p2j($validbodypartlist,'valid_bodyparts');
foreach($validbodyparttestgrouplist as $bcode=>$idname) {
	$configjs[]=p2j($idname,'valid_groups_'.$bcode);
}
foreach($validbodyparttestlist as $bcode=>$grouptestname) {
	foreach($grouptestname as $groupid=>$idname) {
		$configjs[]=p2j($idname,'valid_tests_'.$bcode.'_'.$groupid);
	}
}
$configjs[]=p2j($prognosis,'prognosis','value'); 
$configjs[]=p2j($descriptors,'descriptors','value'); 
//$configjs[]=p2j($mnames, 'mnames', 'value'); 
$configjs[]=p2j($valid_rom_units, 'valid_rom_units', 'value'); 
$configjs[]=p2j($valid_rom_test_measure_0_90, 'valid_rom_test_measure_0_90', 'value'); 
$configjs[]=p2j($valid_rom_test_measure_0_90, 'valid_rom_test_measure_0_90', 'value'); 
$configjs[]=p2j($valid_rom_test_measure_0_180, 'valid_rom_test_measure_0_180', 'value'); 
$configjs[]=p2j($valid_mmt_test_measure, 'valid_mmt_test_measure', 'value'); 
$configjs[]=p2j($valid_special_test_measure, 'valid_special_test_measure', 'value'); 
$configjs[]=p2j($valid_myotomes_test_measure, "valid_myotomes_test_measure", 'value'); 
$configjs[]=p2j($valid_dermatomes_test_measure, "valid_dermatomes_test_measure", 'value'); 
$configjs[]=p2j($valid_girth_units, "valid_girth_units", 'value'); 

$configjs[]=p2j($bodyparts,'bodyparts','rbsdescription'); 
$configjs[]=p2j($icd9codes = geticd9codes(),'icd9codes','imdx'); 
$configjs[]=p2j($injuries = getinjuries(),'injuries','imnsdescription'); 



?>