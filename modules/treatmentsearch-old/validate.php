<?php
// Validate form fields
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(10);

function ArraySearchRecursive($Needle, $Haystack, $NeedleKey="", $Strict=false, $Path=array()) {
	if(!is_array($Haystack))
		return false;
	foreach($Haystack as $Key => $Val) {
		if(is_array($Val) && $SubPath=ArraySearchRecursive($Needle, $Val, $NeedleKey, $Strict, $Path)) {
			$Path=array_merge($Path,Array($Key),$SubPath);
			return $Path;
		}
		elseif((!$Strict&&$Val==$Needle && $Key==(strlen($NeedleKey)>0?$NeedleKey:$Key)) || ($Strict&&$Val===$Needle&&$Key==(strlen($NeedleKey)>0?$NeedleKey:$Key))) {
			$Path[]=$Key;
			return $Path;
		}
	}
	return false;
}

//if(!isset($_SESSION['user']['umclinic']) || empty($_SESSION['user']['umclinic']))
//	error('001', 'Clinic must be set. Please set your clinic before adding or editing treatments.');
if(!isset($_POST['thcnum']) || empty($_POST['thcnum']))
	error('001', 'Clinic must be specified.');

if(!isset($_POST['thdate']) || empty($_POST['thdate']))
	error('001', 'Date cannot be blank.');

$treatmentdate=date('m/d/Y', strtotime($_POST['thdate']));
$today=date('m/d/Y', time());
//if( $treatmentdate > $today )
//	error('001', 'Date cannot be in the future.');
if($_POST['thpnum']=='++++++')
	unset($_POST['thpnum']);

if(isset($_POST['thpnum']) && !empty($_POST['thpnum'])) {
	$existingpatient==TRUE;
	$patients = $_SESSION['useraccess']['patients'];
	$thispatient = $patients[$_POST['thpnum']];
	$cnum = $thispatient['cnum'];
	$lname = $thispatient['lname'];
	$fname = $thispatient['fname'];
	if(!empty($cnum) && !empty($lname) && !empty($fname)) {
//		$_POST['thcnum'] = $cnum;
		$_POST['thlname'] = $lname;
		$_POST['thfname'] = $fname;
	}
}
else {
	$existingpatient==FALSE;
//	if( (isset($_POST['thlname']) && !empty($_POST['thlname'])) && (isset($_POST['thfname']) && !empty($_POST['thfname'])) ) {
//		$searchname = strtoupper(trim($_POST['thlname']) . trim($_POST['thfname']));
//		$patients = $_SESSION['useraccess']['patients'];
//		$foundarray=array();
//		foreach($patients as $pnum=>$patient) {
//			if($patient['searchname'] == $searchname) {
//				$foundarray[] = $pnum;
//			}
//		}
//		if( count($foundarray) == 1 ) {
//			$_POST['thpnum'] = $foundarray[0];
//		}
//		else {
//			if( count($foundarray) > 1 ) {
//				$_POST['thpnum'] = "++++++";
//			}
//		}
//	}
	$_POST['thlname']=strtoupper($_POST['thlname']);
	$_POST['thfname']=strtoupper($_POST['thfname']);
	$_POST['thcnum']= strtoupper($_POST['thcnum']);
	$lname=$_POST['thlname'];
	$fname=$_POST['thfname'];
	$cnum= $_POST['thcnum'];
	if(!empty($lname) && !empty($fname) && !empty($cnum)) {
// Look in ptos_pnums table
		$pnumquery="
			SELECT pnum
			FROM ptos_pnums
			WHERE lname='$lname' and fname='$fname' and cnum='$cnum'
		";
		if($pnumresult=mysqli_query($dbhandle,$pnumquery)) {
			if(mysqli_num_rows($pnumresult)==1) {
				if($pnumrow=mysqli_fetch_assoc($pnumresult))
					$_POST['thpnum']=$pnumrow['pnum'];
			}
			else
				if(mysqli_num_rows($pnumresult)>1)
					$_POST['thpnum']="++++++";
		}
	}
}

if(!isset($_POST['thlname']) || empty($_POST['thlname']))
	error('002', 'Patient last name cannot be blank.');
else {
}

if(!isset($_POST['thfname']) || empty($_POST['thfname']))
	error('003', 'Patient first name cannot be blank.');
else {
}

//if(!isset($_POST['thctmcode']) || empty($_POST['thctmcode']))
//	error('004', 'Case type must be either Work Comp or Personal Injury.');
//else {
//}
function getAcctType($pnum) {
	unset($acctype);
	$select = "SELECT acctype from PTOS_Patients WHERE pnum='$pnum'";
	if($result=mysqli_query($dbhandle,$select)) {
		if($row=mysqli_fetch_assoc($result)) {
			$acctype=$row['acctype'];
			if($acctype=='15') $acctype='5';
			else if($acctype=='16') $acctype='6';
				else if($acctype=='17') $acctype='6';
					else if($acctype=='18') $acctype='6';
						else if($acctype=='19') $acctype='6';
							else if($acctype=='61') $acctype='61';
								else if($acctype=='62') $acctype='61';
									else if(substr($acctype,0,1)=='2') $acctype='2';
										else if(substr($acctype,0,1)=='3') $acctype='3';
											else if(substr($acctype,0,1)=='4') $acctype='5';
												else if(substr($acctype,0,1)=='5') $acctype='5';
													else if(substr($acctype,0,1)=='6') $acctype='6';
														else if(substr($acctype,0,1)=='7') $acctype='6';
															else if(substr($acctype,0,1)=='8') $acctype='8';
																else if(substr($acctype,0,1)=='9') $acctype='9';
																	else $acctype='??';
		}
	}
	return($acctype);
}

// case types
if(!empty($_POST['thpnum']))
	$_POST['thctmcode']=getAcctType($_POST['thpnum']);

if(!isset($_POST['thvtmcode']) || empty($_POST['thvtmcode']))
	error('005', 'Visit Type must be specified.');

if(!isset($_POST['thttmcode']) || empty($_POST['thttmcode'])) {
	if($_POST['thvtmcode']!= 'DC' && $_POST['thvtmcode']!= 'DCW' && $_POST['thvtmcode']!= 'NPE') {
		error('006', 'Treatment Type must be specified.');
	}
}
else {
	if($_POST['thttmcode'] == 'A' || $_POST['thttmcode'] == 'P') {
// DIRTY LITTLE FIX FOR ACPUNCTURE AND POOL
// IF ACUPUNCTURE OR POOL THEN MAKE THE PROCEDURE A/P
		$_POST['procedure'][$_POST['thttmcode']] = $_POST['thttmcode'];
// IF POOL ADD MODALITY FOR ADDITIONAL 15 MIN
		if($_POST['thttmcode'] == 'P') {
			$_POST['modalities'][$_POST['thttmcode']]=array('15P'=>'15P');
		}
	}
	else {
		if(!isset($_POST['procedure'][$_POST['thttmcode']]) || empty($_POST['procedure'][$_POST['thttmcode']]))
			if(!isuserlevel(23)) {
				if($_POST['thvtmcode']!= 'DC' && $_POST['thvtmcode']!= 'DCW' && $_POST['thvtmcode']!= 'NPE')
					error('007', 'Procedures must be specified.');
			}
	}
}
// If inserting/adding
if($_SESSION['button']=='Update' || $_SESSION['button']=='Add') {
	$thisdate=date('Y-m-d', strtotime($_POST['thdate']));
	$thiscnum=$_POST['thcnum'];
	$thispnum=$_POST['thpnum'];
	if(empty($_POST['thpnum']))
		$pnumwhere="";
	else
		$pnumwhere = "thpnum='$thispnum' or ";

	$thislname=$_POST['thlname'];
	$thisfname=$_POST['thfname'];
	$dupquery="
		SELECT thid from treatment_header
		WHERE thcnum = '$thiscnum' and thdate = '$thisdate' and thsbmstatus > '199' and thsbmstatus < '900' and ($pnumwhere(thlname='$thislname' and thfname='$thisfname'))
	";
	if($dupresult=mysqli_query($dbhandle,$dupquery)) {
		$numrows=mysqli_num_rows($dupresult);
		if(($_SESSION['button']=='Update' and $numrows>1) || ($_SESSION['button']=='Add' and $numrows>0)) {
			error('008', "Duplicate treatment found at Clinic $thiscnum, for Date:$thisdate, for Patient:$thispnum $thislname, $thisfname. Cannot accept two treatments on the same day for the same patient.");
			dump("dupquery",$dupquery);
		}
	}
}

if($thisuser!='NancyVilla') {
//dump("patients",$_SESSION['useraccess']['patients']);
	if($_POST['thvtmcode']!= 'DC' && $_POST['thvtmcode']!= 'DCW' ) {

		if(empty($_POST['thnadate']))
			error('009', "Next Action Date must be specified.");
		else {
			$todays_date = strtotime(date("Y-m-d"));
			if(strtotime($_POST['thnadate']) <= $todays_date )
				error('010', "Next Action Date must be greater than today's date.");
			else {
				if(strtotime($_POST['thnadate']) <= strtotime($_POST['thdate']) )
					error('010', "Next Action Date must be greater than treatment date.");
			}
		}
	}
}

?>