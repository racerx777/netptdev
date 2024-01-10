<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 
if(!$popup)
	require_once('reportGeneratorFunctions.js');
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function getreportuseraccess() {
	$result=array();
	$bumcode=array();
	$pgmbumcode=array();
	$cmcnum=array();

	$useraccess=$_SESSION['useraccess'];
	foreach($useraccess['businessunits'] as $key=>$businessunit) 
		$bumcode[$businessunit['bumcode']]=$businessunit['bumcode'];
	foreach($useraccess['providergroups'] as $key=>$providergroup) 
		$pgmbumcode[$providergroup['pgmbumcode']]=$providergroup['pgmbumcode'];
	foreach($useraccess['clinics'] as $key=>$clinic) 
		$cmcnum[$clinic['cmcnum']]=$clinic['cmcnum'];

	$result=array('bumcode'=>$bumcode,'pgmcode'=>$pgmbumcode,'clinic'=>$cmcnum);
	return($result);
}

function repostPostArray($source, $id=NULL, $name=NULL) {
	if(is_array($source)) {
		foreach($source as $key=>$val) { // tracker, report
			if(is_array($val)) {
				if(empty($id))
					$newid = "$key";
				else
					$newid = $id."_".$key;
				if(empty($name))
					$newname ="$key";
				else
					$newname = $name . "[$key]";
				repostPostArray($val, $newid, $newname);
			}
			else {
				if(empty($id))
					$newid=$key;
				else
					$newid="_$key";
				if(empty($name))
					$newname=$key;
				else
					$newname="[$key]";
				$hidden='<input type="hidden" id="'.$id.$newid.'" name="'.$name.$newname.'" value="'.$val.'" />';
				echo "$hidden";
			}
		}
		unset($id);
		unset($name);
	}
}

function getReportTemplateDefaults($rtid) {
// Report Template ID is required
// Success/Fail: Returns array containing all report template detaults (if found)
	$array=array();
// Get values from report template default table
// Select all header defaults from Report Template Defaults Table by Business Unit, Provider, Clinic, User, Report
	$tablesarray='report_template_defaults';
	$keyfieldsarray='rtdid';
	$fieldsarray='*';
	$wherefieldsarray="rtdrtid='$rtid'";
	$orderfieldsarray='rtdsection,rtdfield';
	if( $result=getTableOptions($tablesarray, $keyfieldsarray, $fieldsarray, $wherefieldsarray, $orderfieldsarray) ) {
		foreach($result as $section=>$data) {
			$section=$data['rtdsection'];
			$field=$data['rtdfield'];
			if( !empty($section) && !empty($field) ) {
				if(!$value=unserialize($data['rtdvalue'])) {
					$value=$data['rtdvalue'];
				}
				$array["$section"]["$field"]=$value;
			}
		}
	}
	return($array);
}

function getInjuryTemplateDefaults($ritid) {
	$array=array();
// Get values from report injury templates default table
// Select all header defaults from Report Template Defaults Table by Business Unit, Provider, Clinic, User, Report
	$tablesarray='report_injury_templates_defaults';
	$keyfieldsarray='ritdid';
	$fieldsarray='*';
	$wherefieldsarray="ritdritid='$ritid'";
	$orderfieldsarray='ritdsection,ritdfield';
	if( $result=getTableOptions($tablesarray, $keyfieldsarray, $fieldsarray, $wherefieldsarray, $orderfieldsarray) ) {
		foreach($result as $section=>$data) {
			$section=$data['ritdsection'];
			$field=$data['ritdfield'];
			if( !empty($section) && !empty($field) ) {
				if(!$value=unserialize($data['ritdvalue'])) {
					$value=stripslashes($data['ritdvalue']);
				}
				$array["$section"]["$field"]=$value;
			}
		}
	}
//	else 
//		info('000','reportGeneratorFunctions:getInjuryTemplateDefaults:FYI no injury defaults');
//	dump("injury default array",$array);
//	exit();
	return($array);
}

function getInjuryTemplateTypes($bumcode, $pgmcode, $cnum, $user=NULL, $rtid=NULL, $inactive='0') {
	$wherefields=array();
	$wherefields[]='rait_ritid=ritid';
	$wherefields[]="ritinactive='$inactive'";

// Available Templates will contain all of the Clinic Level "Base Templates" and also include "Custom" user templates
	if( is_array($bumcode) && count($bumcode) > 0 ) 
		$wherefields[]="rait_bumcode IN ('".implode("','",$bumcode)."')";
	else 
		error("999","getInjuryTemplateTypes:Business Unit Must Be Specified");
	if(is_array($pgmcode) && count($pgmcode)>0) 
		$wherefields[]="rait_pgmcode IN ('".implode("','",$pgmcode)."')";
	else 
		error("999","getInjuryTemplateTypes:Provider Group Must Be Specified");

	if( is_array($cnum) && count($cnum) > 0) 
		$wherefields[]="rait_cnum IN ('".implode("','",$cnum)."')";
		if(!empty($user))
			$wherefields[]="rait_user IN ('$user','')";
	else 
		error("999","getInjuryTemplateTypes:Clinic Must Be Specified");	

	if( !empty($rtid) ) 
		$wherefields[]="rait_rtid='$rtid'";

//dump("wherefields",$wherefields);

	if( $result=getTableOptions($tablesarray='report_access_injury_template,report_injury_templates', $keyfieldsarray='rait_rtid,ritid', $fieldsarray='*', $wherefieldsarray=$wherefields, $orderfieldsarray='rait_dispseq,ritdispseq,ritdescription') ) {
//		dump("result",$result);
		return($result);
	}
	else {
//		dump("result",$result);
		error("999","Error getInjuryTemplateTypes <br>".mysqli_error($dbhandle));
		errorclear();
		return(array());
	}
}

function selectInjuryTemplateType($crid, $rtid=NULL,  $rhid=NULL, $report=NULL) {
// Case ID is required
// Report Template access defines which report templates are available to the current user
// Displays dropdown of available injury templates for current user
// REPOSTS $_POST values to form then performs exit() on script
// If user does not have permission to any injury templates an error message is displayed, actions are not reset, returns to processing 

// Retrieve array of injury template types, false if no reports
	$reportuseraccess=getreportuseraccess();
	$bumcode=$reportuseraccess['bumcode'];
	$pgmbumcode=$reportuseraccess['pgmcode'];
	$cmcnum=$reportuseraccess['clinic'];
	$user=getuser();
	$injurytemplatetypes=array();
//dump("crid"  , $crid);
//dump("rtid"  , $rtid);
//dump("rhid"  , $rhid);
//dump("report", $report);
//dump("bumcode"  , $bumcode);
//dump("pgmbumcode"  , $pgmbumcode);
//dump("cmcnum"  , $cmcnum);
//dump("user"  , $user);
	$injurytemplatetypes=getInjuryTemplateTypes($bumcode, $pgmbumcode, $cmcnum, $user, $rtid);
	if(count($injurytemplatetypes) > 0) {
// Add each Injury Template as an option
		$optionshtml=array();
		$thisoptionreportemplates=array();
		foreach($injurytemplatetypes as $index=>$thisoption) {
			$thisoptionrtid=$thisoption['rait_rtid'];
			$thisoptionreportemplates["$thisoptionrtid"]=$thisoptionrtid;
			$selectvalue=$thisoption['ritid'];
			if($thisoption['rait_user']=='')
				$templatedescription='';
			else
				$templatedescription=' ('.strtoupper($thisoption['rait_user']).' USER TEMPLATE)';
			$selectdescription=$thisoption['ritdescription'].$templatedescription;
			if($thisoption['ritid']==$ritid)
				$selected=' selected="selected"';
			else
				unset($selected);
			$optionshtml[]='<option value="'.$selectvalue.'" '.$selected.'>'.$selectdescription.'</option>';
		} // foreach
		$optionshtml=implode("
", $optionshtml);
		unset($reporttemplatename);
		foreach($thisoptionreportemplates as $index=>$thisoptionreportemplate) {
			$reporttemplatename["$thisoptionreportemplate"]=getReportDescription($thisoptionreportemplate);
		}
		if(is_array($reporttemplatename) && count($reporttemplatename)>0)
			$reporttemplatename=implode(",",$reporttemplatename);
?>
<div style="width:auto;" class="containedBox">
	<fieldset>
	<legend style="font-size:large">Select <?php echo $reporttemplatename; ?> template:</legend>
	<form name="injurytemplatetype" method="post">
		<div>
			<select name="ritid">
				<?php echo $optionshtml; ?>
			</select>
		</div>
		<div style="float:left">
			<input name="button['0']" type="submit" value="Cancel">
		</div>
		<div style="float:left;">
			<input name="button[<?php echo $crid; ?>]" type="submit" value="Set Injury Template Type">
		</div>
		<input name="caller" type="hidden" value="<?php echo $_SESSION['button']; ?>">
		<input name="crid" type="hidden" value="<?php echo $crid; ?>">
		<input name="bumcode" type="hidden" value="<?php echo $_POST['bumcode']; ?>">
		<input name="pgmcode" type="hidden" value="<?php echo $_POST['pgmcode']; ?>">
		<input name="crcnum" type="hidden" value="<?php echo $_POST['crcnum']; ?>">
<?php
		repostPostArray($_POST);
?>
	</form>
	</fieldset>
</div>
<?php
		exit();
	} // if
	else  {
		error("999", "selectInjuryTemplateType:No Injury Templates Available");
		unset($_SESSION['id']);
		unset($_SESSION['button']);
	}
}

function getDoctor($dmid, $dlid, $dlsid) {
// Requires Doctor ID, Doctor Location ID, Doctor Location Staff ID
// Success: Returns array of data for that doctor, location and contact from respective tables
// Fail: Returns error message and FALSE
	$data=array();
	$selectquery = "
		SELECT *
		FROM doctors
		LEFT JOIN doctor_locations
		ON dlid='$dlid'
		LEFT JOIN doctor_locations_contacts
		ON dlsid='$dlsid'
		WHERE dmid = '$dmid'
	";
	if($selectresult=mysqli_query($dbhandle,$selectquery)) {
		if($selectrow=mysqli_fetch_assoc($selectresult)) {
			foreach($selectrow as $field=>$value)
				$data["$field"]=$value;
			return($data);
		}
	}
	error("999","Error retrieving doctor from doctors, locations, contacts table. doctor:$dmid location:$dlid staff/contact:$dlsid.");
	return(FALSE);
}

function getPatient($paid) {
// Requires Patient ID
// Success: Returns array of data for that patient from patients table
// Fail: Returns error message and FALSE
	$data=array();
	$selectquery = "
		SELECT *
		FROM patients
		WHERE paid = '$paid'
	";
	if($selectresult=mysqli_query($dbhandle,$selectquery)) {
		if($selectrow=mysqli_fetch_assoc($selectresult)) {
			foreach($selectrow as $field=>$value)
				$data["$field"]=$value;
			return($data);
		}
	}
	error("999","Error retrieving patient from patients table. patient:$paid.");
	return(FALSE);
}

function getCase($crid) {
// Requires Case ID
// Success: Returns array of data for that case from cases table
// Fail: Returns error message and FALSE
	$data=array();
	$selectquery = "
		SELECT *
		FROM cases
		WHERE crid = '$crid'
	";
	if($selectresult=mysqli_query($dbhandle,$selectquery)) {
		if($selectrow=mysqli_fetch_assoc($selectresult)) {
			foreach($selectrow as $field=>$value)
				$data["$field"]=$value;
			return($data);
		}
	}
	error("999","Error retrieving case from cases table. case:$crid.");
	return(FALSE);
}

function getReportDefaults($crid, $rtid, $ritid, $rhid) {
// populates array containing 'header' 'detail_bodypart' and 'detail_bodypart_test' indexes
	$defaults=array();
	if(empty($ritid))
		$ritid=selectInjuryTemplateType($crid, $rtid, $rhid, $report);

	if(errorcount()==0) {
	// Get Report and Injury Defaults for the template ids specified
		unset($header);
		unset($detail_bodypart);
		unset($detail_bodypart_test_group);
		unset($detail_bodypart_test);
	
	// Get default values for this report template
		$reportdefaults=array();
		$reportdefaults=getReportTemplateDefaults($rtid); // returns array['section']['field']=value
		if(is_array($reportdefaults) && count($reportdefaults)>0) {
			if(is_array($reportdefaults['header']) && count($reportdefaults['header'])>0) {
				foreach($reportdefaults['header'] as $key=>$value) 
					$header["$key"]=$value;
			}
			if(is_array($reportdefaults['detail_bodypart']) && count($reportdefaults['detail_bodypart'])>0) {
				foreach($reportdefaults['detail_bodypart'] as $key=>$value) 
					$detail_bodypart["$key"]=$value;
			}
			if(is_array($reportdefaults['detail_bodypart_test_group']) && count($reportdefaults['detail_bodypart_test_group'])>0) {
				foreach($reportdefaults['detail_bodypart_test_group'] as $key=>$value) 
					$detail_bodypart_test_group["$key"]=$value;
			}
			if(is_array($reportdefaults['detail_bodypart_test']) && count($reportdefaults['detail_bodypart_test'])>0) {
				foreach($reportdefaults['detail_bodypart_test'] as $key=>$value) 
					$detail_bodypart_test["$key"]=$value;
			}
			unset($reportdefaults['header']);
			unset($reportdefaults['detail_bodypart']);
			unset($reportdefaults['detail_bodypart_test_group']);
			unset($reportdefaults['detail_bodypart_test']);
		}
		unset($reportdefaults);

	// Get default values for this injury template
		$injurydefaults=array();
		$injurydefaults=getInjuryTemplateDefaults($ritid);
		if(is_array($injurydefaults) && count($injurydefaults)>0) {
			if(is_array($injurydefaults['header']) && count($injurydefaults['header'])>0) {
				foreach($injurydefaults['header'] as $key=>$value) 
					$header["$key"]=$value;
			}
// keyed by rrn
			if(is_array($injurydefaults['detail_bodypart']) && count($injurydefaults['detail_bodypart'])>0) {
				foreach($injurydefaults['detail_bodypart'] as $key=>$value) {
					$detail_bodypart["$key"]=$value;
				}
			}
			if(is_array($injurydefaults['detail_bodypart_test_group']) && count($injurydefaults['detail_bodypart_test_group'])>0) {
				foreach($injurydefaults['detail_bodypart_test_group'] as $key=>$value) 
					$detail_bodypart_test_group["$key"]=$value;
			}
// keyed by rrn
			if(is_array($injurydefaults['detail_bodypart_test']) && count($injurydefaults['detail_bodypart_test'])>0) {
				foreach($injurydefaults['detail_bodypart_test'] as $key=>$value) 
					$detail_bodypart_test["$key"]=$value;
			}
			unset($injurydefaults['header']);
			unset($injurydefaults['detail_bodypart']);
			unset($injurydefaults['detail_bodypart_test_group']);
			unset($injurydefaults['detail_bodypart_test']);
		}
		unset($injurydefaults);
		$header['rhrtid']=$rtid;
		$header['rhritid']=$ritid;

		$defaults['header']=$header;
		$defaults['detail_bodypart']=$detail_bodypart;
		$defaults['detail_bodypart_test_group']=$detail_bodypart_test_group;
		$defaults['detail_bodypart_test']=$detail_bodypart_test;
	
		if($crid!='WalkIn') {
			$defaults['case']=getCase($crid);
			$defaults['patient']=getPatient($defaults['case']['crpaid']);
			$defaults['doctor']=getDoctor($defaults['case']['crrefdmid'], $defaults['case']['crrefdlid'], $defaults['case']['crrefdlsid']);
		}
		return($defaults);
	}
	return(FALSE);
}

function getProgressReportDefaults($crid, $rtid, $ritid, $rhid) {
// populates array containing 'header' 'detail_bodypart' and 'detail_bodypart_test' indexes
	$defaults=array();
	if(empty($_POST['report']['header']['rhcompreportdate']) & empty($_POST['report']['header']['rhcompreportid']) ) { // Comparison Date is not set
// select previously filed report or enter report date
		displayCasePreviousReports($crid);
	}
	else {
		$compreportid=$_POST['report']['header']['rhcompreportid'];
		if(!empty($compreportid)) {
			$comp=getReport($compreportid);
// Move default values to $default array
			$defaults['header']['rhbumcode']=$comp['header']['rhbumcode'];
			$defaults['header']['rhpgmcode']=$comp['header']['rhpgmcode'];
			$defaults['header']['rhcnum']=$comp['header']['rhcnum'];

// Need to assign a new id
//			$defaults['header']['rhid']=$comp['header']['rhid'];

			$defaults['header']['rhstatus']='NEW';
			$defaults['header']['rhbumcode']=dbDate(today());
			$defaults['header']['rhgenerateddate']=NULL;
			$defaults['header']['rhfileddate']=NULL;
			$defaults['header']['rhreportname']=NULL;
			$defaults['header']['rhrtid']=$rtid;
			$defaults['header']['rhritid']=$comp['header']['rhritid'];
			$defaults['header']['rhritname']=$comp['header']['rhritname'];
			$defaults['header']['rhritdescription']=$comp['header']['rhritdescription'];
			$defaults['header']['rhlastreportdate']=$comp['header']['rhdate'];
			$defaults['header']['rhnextdoctorvisit']=$comp['header']['rhnextdoctorvisit'];
			$defaults['header']['rhnextreportdate']=$comp['header']['rh']; // 30 days past this report date
			$defaults['header']['rhcompreportdate']=$comp['header']['rhdate'];
			$defaults['header']['rharchdate']=NULL;
			$defaults['header']['rharchstatus']=NULL;
			$defaults['header']['rhcrid']=$crid;
			$defaults['header']['rhcrdate']=$comp['header']['rhcrdate'];
			$defaults['header']['rhinjurydate']=$comp['header']['rhinjurydate'];
			$defaults['header']['rhinjurytypecode']=$comp['header']['rhinjurytypecode'];
			$defaults['header']['rhfrequency']=$comp['header']['rhfrequency'];
			$defaults['header']['rhduration']=$comp['header']['rhduration'];
			$defaults['header']['rhtotalvisits']=$comp['header']['rhtotalvisits'];
			$defaults['header']['rhcasetypecode']=$comp['header']['rhcasetypecode'];
			$defaults['header']['rhtherapytypecode']=$comp['header']['rhtherapytypecode'];
			$defaults['header']['rhlogo']=$comp['header']['rhlogo'];
			$defaults['header']['rhcmaddress1']=$comp['header']['rhcmaddress1'];
			$defaults['header']['rhcmaddress2']=$comp['header']['rhcmaddress2'];
			$defaults['header']['rhcmcity']=$comp['header']['rhcmcity'];
			$defaults['header']['rhcmstate']=$comp['header']['rhcmstate'];
			$defaults['header']['rhcmzip']=$comp['header']['rhcmzip'];
			$defaults['header']['rhcmphone']=$comp['header']['rhcmphone'];
			$defaults['header']['rhcmfax']=$comp['header']['rhcmfax'];
			$defaults['header']['rhvisitdate']=today();
			$defaults['header']['rhlvisitdate']=NULL; // need last visit date
			$defaults['header']['rhvisitsauthorized']=$comp['header']['rhvisitsauthorized'];
			$defaults['header']['rhvisitsused']=$comp['header']['rhvisitsused'];
			$defaults['header']['rhvisitenddate']=$comp['header']['rhvisitenddate'];
			$defaults['header']['rhicd9code1']=$comp['header']['rhicd9code1'];
			$defaults['header']['rhicd9desc1']=$comp['header']['rhicd9desc1'];
			$defaults['header']['rhicd9dxnature1']=$comp['header']['rhicd9dxnature1'];
			$defaults['header']['rhicd9dxbodypart1']=$comp['header']['rhicd9dxbodypart1'];
			$defaults['header']['rhicd9dxbodypartdesc1']=$comp['header']['rhicd9dxbodypartdesc1'];
			$defaults['header']['rhicd9dxbodydescriptor1']=$comp['header']['rhicd9dxbodydescriptor1'];
			$defaults['header']['rhicd9dxbodydescriptordesc1']=$comp['header']['rhicd9dxbodydescriptordesc1'];
			$defaults['header']['rhicd9code2']=$comp['header']['rhicd9code2'];
			$defaults['header']['rhicd9desc2']=$comp['header']['rhicd9desc2'];
			$defaults['header']['rhicd9dxnature2']=$comp['header']['rhicd9dxnature2'];
			$defaults['header']['rhicd9dxbodypart2']=$comp['header']['rhicd9dxbodypart2'];
			$defaults['header']['rhicd9dxbodypartdesc2']=$comp['header']['rhicd9dxbodypartdesc2'];
			$defaults['header']['rhicd9dxbodydescriptor2']=$comp['header']['rhicd9dxbodydescriptor2'];
			$defaults['header']['rhicd9dxbodydescriptordesc2']=$comp['header']['rhicd9dxbodydescriptordesc2'];
			$defaults['header']['rhicd9code3']=$comp['header']['rhicd9code3'];
			$defaults['header']['rhicd9desc3']=$comp['header']['rhicd9desc3'];
			$defaults['header']['rhicd9dxnature3']=$comp['header']['rhicd9dxnature3'];
			$defaults['header']['rhicd9dxbodypart3']=$comp['header']['rhicd9dxbodypart3'];
			$defaults['header']['rhicd9dxbodypartdesc3']=$comp['header']['rhicd9dxbodypartdesc3'];
			$defaults['header']['rhicd9dxbodydescriptor3']=$comp['header']['rhicd9dxbodydescriptor3'];
			$defaults['header']['rhicd9dxbodydescriptordesc3']=$comp['header']['rhicd9dxbodydescriptordesc3'];
			$defaults['header']['rhicd9code4']=$comp['header']['rhicd9code4'];
			$defaults['header']['rhicd9desc4']=$comp['header']['rhicd9desc4'];
			$defaults['header']['rhicd9dxnature4']=$comp['header']['rhicd9dxnature4'];
			$defaults['header']['rhicd9dxbodypart4']=$comp['header']['rhicd9dxbodypart4'];
			$defaults['header']['rhicd9dxbodypartdesc4']=$comp['header']['rhicd9dxbodypartdesc4'];
			$defaults['header']['rhicd9dxbodydescriptor4']=$comp['header']['rhicd9dxbodydescriptor4'];
			$defaults['header']['rhicd9dxbodydescriptordesc4']=$comp['header']['rhicd9dxbodydescriptordesc4'];
			$defaults['header']['rhinjuriesarray']=$comp['header']['rhinjuriesarray'];
			$defaults['header']['rhpaid']=$comp['header']['rhpaid'];
			$defaults['header']['rhpnum']=$comp['header']['rhpnum'];
			$defaults['header']['rhfname']=$comp['header']['rhfname'];
			$defaults['header']['rhlname']=$comp['header']['rhlname'];
			$defaults['header']['rhdob']=$comp['header']['rhdob'];
			$defaults['header']['rhsex']=$comp['header']['rhsex'];
			$defaults['header']['rhworking']=$comp['header']['rhworking'];
			$defaults['header']['rhoccup']=$comp['header']['rhoccup'];
			$defaults['header']['rhjobrequirement']=$comp['header']['rhjobrequirement'];
			$defaults['header']['rhmedicalhistory']=$comp['header']['rhmedicalhistory'];
			$defaults['header']['rhsurgeries']=$comp['header']['rhsurgeries'];
			$defaults['header']['rhdiagnostictests']=$comp['header']['rhdiagnostictests'];
			$defaults['header']['rhmedications']=$comp['header']['rhmedications'];
			$defaults['header']['rhchiefcomplaint']=$comp['header']['rhchiefcomplaint'];
			$defaults['header']['rhpainrating']=$comp['header']['rhpainrating'];
			$defaults['header']['rhspecificinjury']=$comp['header']['rhspecificinjury'];
			$defaults['header']['rhfunctionalactivity']=$comp['header']['rhfunctionalactivity'];
			$defaults['header']['rhsubjectivenote']=$comp['header']['rhsubjectivenote'];
			$defaults['header']['rhsubjectivenoteprint']=$comp['header']['rhsubjectivenoteprint'];
			$defaults['header']['rhobjectivenote']=$comp['header']['rhobjectivenote'];
			$defaults['header']['rhobjectivenoteprint']=$comp['header']['rhobjectivenoteprint'];
			$defaults['header']['rhbloodpressure']=$comp['header']['rhbloodpressure'];
			$defaults['header']['rhheartrate']=$comp['header']['rhheartrate'];
			$defaults['header']['rhassessmentnote']=$comp['header']['rhassessmentnote'];
			$defaults['header']['rhassessmentnoteprint']=$comp['header']['rhassessmentnoteprint'];
			$defaults['header']['rhtreatmentplannote']=$comp['header']['rhtreatmentplannote'];
			$defaults['header']['rhtreatmentplannoteprint']=$comp['header']['rhtreatmentplannoteprint'];
			$defaults['header']['rhassessment']=$comp['header']['rhassessment'];
			$defaults['header']['rhprognosis']=$comp['header']['rhprognosis'];
			$defaults['header']['rhshortgoals']=$comp['header']['rhshortgoals'];
			$defaults['header']['rhlonggoals']=$comp['header']['rhlonggoals'];
			$defaults['header']['rhtreatmentplan']=$comp['header']['rhtreatmentplan'];
			$defaults['header']['rhdmid']=$comp['header']['rhdmid'];
			$defaults['header']['rhdmlname']=$comp['header']['rhdmlname'];
			$defaults['header']['rhdmfname']=$comp['header']['rhdmfname'];
			$defaults['header']['rhdocupin']=$comp['header']['rhdocupin'];
			$defaults['header']['rhdocnpi']=$comp['header']['rhdocnpi'];
			$defaults['header']['rhdlid']=$comp['header']['rhdlid'];
			$defaults['header']['rhdlcity']=$comp['header']['rhdlcity'];
			$defaults['header']['rhdlsid']=$comp['header']['rhdlsid'];
			$defaults['header']['rhdlstitle']=$comp['header']['rhdlstitle'];
			$defaults['header']['rhdlsname']=$comp['header']['rhdlsname'];
			$defaults['header']['rhdlsphone']=$comp['header']['rhdlsphone'];
			$defaults['header']['rhdlsfax']=$comp['header']['rhdlsfax'];
			$defaults['header']['rhpostsurgical']=$comp['header']['rhpostsurgical'];
			$defaults['header']['rhsurgerydate']=$comp['header']['rhsurgerydate'];
			$defaults['header']['rhtherapcode']=$comp['header']['rhtherapcode'];
			$defaults['header']['rhtherapname']=$comp['header']['rhtherapname'];
			$defaults['header']['rhtheraplic']=$comp['header']['rhtheraplic'];
			$defaults['header']['rhtherapnpi']=$comp['header']['rhtherapnpi'];
			$defaults['header']['rhcheckedarray']=$comp['header']['rhcheckedarray'];
			$defaults['header']['rhexpandarray']=$comp['header']['rhexpandarray'];
			$defaults['detail_bodypart']=$comp['detail_bodypart'];
			$defaults['detail_bodypart_test_group']=$comp['detail_bodypart_test_group'];
			$defaults['detail_bodypart_test']=$comp['detail_bodypart_test'];
			foreach($defaults['detail_bodypart_test'] as $rrn=>$record) {
				unset($defaults['detail_bodypart_test']["$rrn"]['rdbtid']);
				unset($defaults['detail_bodypart_test']["$rrn"]['rdbtrhid']); // should be set to new rhid
				$defaults['detail_bodypart_test']["$rrn"]['rdbtresult2']=$defaults['detail_bodypart_test']["$rrn"]['rdbtresult1'];
				$defaults['detail_bodypart_test']["$rrn"]['rdbtresult1']=NULL;
				$defaults['detail_bodypart_test']["$rrn"]['rdbtresult2date']=$defaults['detail_bodypart_test']["$rrn"]['rdbtresult1date'];
				$defaults['detail_bodypart_test']["$rrn"]['rdbtresult1date']=NULL;
			}
		}
		else {
			if($crid!='WalkIn') {
				$defaults['case']=getCase($crid);
				$defaults['patient']=getPatient($defaults['case']['crpaid']);
				$defaults['doctor']=getDoctor($defaults['case']['crrefdmid'], $defaults['case']['crrefdlid'], $defaults['case']['crrefdlsid']);
			}
		}
		return($defaults);
	}
}

function displayCasePreviousReports($rhcrid) {
	$reports=array();
	$selectable=false;
	if(!empty($rhcrid)) {
		$select="SELECT * FROM report_header WHERE rhcrid='$rhcrid' ORDER BY rhlname, rhfname, rhdate DESC";
		if($result=mysqli_query($dbhandle,$select)) {
			while($row=mysqli_fetch_assoc($result)) {
				$rhid=$row['rhid'];
				$reports["$rhid"]=$row;
				if($reports["$rhid"]['rhstatus']=='FILED') {
					$reports["$rhid"]['_disabled']='';
					$reports["$rhid"]['_style']='';
					$reports["$rhid"]['_msg']='';
					$selectable=true;
				}
				else {
					$reports["$rhid"]['_disabled']='disabled="disabled"';
					$reports["$rhid"]['_style']='style="color:#999999;"';
					$reports["$rhid"]['_msg']='<span style="color:red; white-space:nowrap;">***Report must be FILED/ARCHIVED to select.***</span>';
				}
			}
		}
	}
	echo('<div class="containedBox" id="compdateselect">');
	echo('<fieldset>');
	echo('<legend>Progress Report Comparison Date</legend>');
	echo('<form id="selectcompreport" name="selectcompreport" method="post">');
	repostPostArray($_POST);
	echo("<table width='100%'>");
	if(is_array($reports) && count($reports)>0) {
		foreach($reports as $rhid=>$reportrow) {
			echo("<tr>");
			echo("<td>");
			echo('<div '.$reportrow['_style'].'>');
			echo('<input type=radio id="rhcompreportid'.$rhid.'" name="report[header][rhcompreportid]" value="'.$rhid.'" '.$reportrow['_disabled'].'/>');
			echo displayDate($reportrow['rhvisitdate']) . " " .getReportDescription($reportrow['rhrtid']) . " (" . $reportrow['rhstatus'] . "/" . $reportrow['rharchstatus'] . ")".$reportrow['_msg'];
			echo('</div>');
			echo("</td>");
			echo("</tr>");
		}
	}
	echo("<tr>");
	echo("<td>");
	echo('<div>');
	echo('Specify this date: <input type="text" id="rhcompreportdate" name="report[header][rhcompreportdate]" value="" />');
	echo('</div>');
	echo("</td>");
	echo("</tr>");
	echo("</table>");
	echo('<input type="button" id="Cancel" name="Cancel" value="Cancel" onclick="javascript:submit();" />');
	echo('<input type="submit" id="ConfirmCompReportDate" name="button['.$report['header']['rhid'].']" value="Confirm comparison report date" />
');
	echo('</form>');
	echo('</fieldset>');
	echo('</div>');
	exit();
}

function displayPreviousReports($report) {
//dump("report previous",$report);
//dumppost();
	$rhcrid=$report['header']['rhcrid'];
	$rhpnum=$report['header']['rhpnum'];
	$rhid=$report['header']['rhid'];
	$reports=array();
	$selectable=false;
	if(!empty($rhcrid) && !empty($rhid)) {
		$select="SELECT * FROM report_header WHERE rhcrid='$rhcrid' and rhid<>'$rhid' ORDER BY rhlname, rhfname, rhdate DESC";
		if($result=mysqli_query($dbhandle,$select)) {
			while($row=mysqli_fetch_assoc($result)) {
				$rhid=$row['rhid'];
				$reports["$rhid"]=$row;
				if($reports["$rhid"]['rhstatus']=='FILED') {
					$reports["$rhid"]['_disabled']='';
					$reports["$rhid"]['_style']='';
					$reports["$rhid"]['_msg']='';
					$selectable=true;
				}
				else {
					$reports["$rhid"]['_disabled']='disabled="disabled"';
					$reports["$rhid"]['_style']='style="color:#999999;"';
					$reports["$rhid"]['_msg']='<span style="color:red;">*** Report must be FILED/ARCHIVED in order to use comparison test data. ***</span>';
				}
			}
		}
	}
	echo('<div class="containedBox" id="compdateselect">');
	echo('<fieldset>');
	echo('<legend>Progress Report Comparison Date</legend>');
	echo('<form id="selectcompreport" name="selectcompreport" method="post">');
	repostPostArray($_POST);
	echo("<table>");
	if(is_array($reports) && count($reports)>0) {
		foreach($reports as $rhid=>$reportrow) {
			echo("<tr>");
			echo("<td>");
			echo('<div '.$reportrow['_style'].'>');
			echo('<input type=radio id="rhcompreportid'.$rhid.'" name="report[header][rhcompreportid]" value="'.$rhid.'" '.$reportrow['_disabled'].'/>');
			echo displayDate($reportrow['rhvisitdate']) . " " .getReportDescription($reportrow['rhrtid']) . " (" . $reportrow['rhstatus'] . "/" . $reportrow['rharchstatus'] . ")".$reportrow['_msg'];
			echo('</div>');
			echo("</td>");
			echo("</tr>");
		}
	}
	echo("<tr>");
	echo("<td>");
	echo('<div>');
	echo('Use this date: <input type="text" id="rhcompreportdate" name="report[header][rhcompreportdate]" value="" />');
	echo('</div>');
	echo("</td>");
	echo("</tr>");
	echo("</table>");
	echo('<input type="button" id="Cancel" name="Cancel" value="Cancel" onclick="javascript:submit();" />');
	echo('<input type="submit" id="ConfirmCompReportDate" name="button['.$report['header']['rhid'].']" value="Confirm comparison report date" />
');
	echo('</form>');
	echo('</fieldset>');
	echo('</div>');
	exit();
}

function confirmComparisonReportDate($button, $id) { // contains the button and the id of the current report
	$crpnum=$_POST['crpnum'];
	$compreportdate=dbDate($_POST['report']['header']['rhcompreportdate']);
	$compreportid=$_POST['report']['header']['rhcompreportid'];
	if(empty($compreportid)) {
		if(empty($compreportdate)) 
			error("999","You must select an existing report or enter a valid report date.");
		if(dbDate($compreportdate)=="1969-12-31 15:59:59") 
			error("999","Invalid Date $compreportdate");
	}
	if(errorcount()!=0) {
		displaysitemessages();
		unset($_POST['report']['header']['rhcompreportdate']);
	}
	else {
		displaysitemessages();
		if(!empty($compreportdate) || !empty($compreportid) ) {
			if(!empty($compreportdate)) 
				$where = " and rhdate='$compreportdate'";
			if(!empty($compreportid)) 
				$where = " and rhid='$compreportid'";
// Get Report ID for this Date if it exists
			$rhpnum=$_POST['report']['header']['rhpnum'];
			$select="
				SELECT rhid, rhdate, rhstatus, rharchstatus, rhpnum, rhvisitdate
				FROM report_header 
				WHERE rhpnum='$crpnum' $where
			";
			if($result=mysqli_query($dbhandle,$select)) {
				if(mysqli_num_rows($result)==1) {
					if($row=mysqli_fetch_assoc($result)) {
						$_POST['report']['header']['rhcompreportdate']=$row['rhvisitdate'];
						$_POST['report']['header']['rhcompreportid']=$row['rhid'];
						addCompTestResultsToPOST($id, $row);
//						notify("000","Comparison Report Date:".displayDate($_POST['report']['header']['rhcompreportdate']));
					}
					else
						error("999","Error fetching comparison report information.<br>$select<br>".mysqli_error($dbhandle));
				}
				else {
					if(mysqli_num_rows($result)>1)
						error("999","Mulltiple reports exist for given date. You must select report from list.");
//					else {
//						info("000","No report found for specified date.");
//						$_POST['report']['header']['rhcompreportdate']=$_POST['compreportdate'];
//					}
				}
			}
			else
				error("999", "Error selecting comparison report information.<br>$select<br>".mysqli_error($dbhandle));
		}
	}
}

function addCompTestResultsToPOST($id, $compreport) {
	$rdbrhid=$compreport['rhid'];
	$rhvisitdate=$compreport['rhvisitdate'];
	$select="
		SELECT rdbid, rdbbcode 
		FROM report_detail_bodypart 
		WHERE rdbrhid='$rdbrhid'
		ORDER BY rdbdispseq
	";
	if($result=mysqli_query($dbhandle,$select)) {
		$bodypartCodeOptions=bodypartCodeOptions();
		$now=time();
		while($row=mysqli_fetch_assoc($result)) {
			$rdbid=$row['rdbid'];
			$rhid=$compreport['rhid'];
			$bodypartcode=$row['rdbbcode'];
			$now++;
			$bodypartrecordid="New_$now";
			addBodypartToPOST($id, $bodypartcode, $bodypartrecordid, $bodypartCodeOptions);

			$selecttest="
				SELECT * 
				FROM report_detail_bodypart_test 
				LEFT JOIN report_tests 
				ON rdbtrtid=rtid 
				WHERE rdbtrhid='$rhid' and rdbtrdbid='$rdbid' 
				ORDER BY rttype, rdbtdispseq 
			";
			if($resulttest=mysqli_query($dbhandle,$selecttest)) {
				$testCodeOptions=testCodeOptions();
				$nowtest=time();
				while($rowtest=mysqli_fetch_assoc($resulttest)) {
					$rdbtid=$rowtest['rdbtid'];
					$testid=$rowtest['rdbtrtid'];
					$result2=$rowtest['rdbtresult1'];
					if(empty($rowtest['rdbtresult1date']) || $rowtest['rdbtresult1date']=='0000-00-00')
						$result2date=$rhvisitdate;
					else
						$result2date=$rowtest['rdbtresult1date'];
					$result2date=dbDate($result2date);
					$nowtest++;
					$testrecordid="New_".$bodypartrecordid.$rdbtid.$testid.$nowtest;
					addBodypartTest2ToPOST($id, $bodypartrecordid, $testid, $testrecordid, $result2, $result2date, $testCodeOptions);
				}
			}
		}
	}
}

function addBodypartToPOST($rhid, $bodypartcode, $bodypartrecordid, $bodypartCodeOptions=NULL) {
	if(empty($bodypartCodeOptions))
		$bodypartCodeOptions=bodypartCodeOptions();
	$imbinactive=$bodypartCodeOptions["$bodypartcode"]["inactive"];
	$imbparent=$bodypartCodeOptions["$bodypartcode"]["parent"];
	$imbdescription=$bodypartCodeOptions["$bodypartcode"]["description"];
	$imbsdescription=$bodypartCodeOptions["$bodypartcode"]["shortdescription"];
	$_POST['report']['detail_bodypart']['record']["$bodypartrecordid"] = array("rdbid"=>$bodypartrecordid, "rdbrhid"=>$rhid, "rdbdispseq"=>999, "rdbbcode"=>$bodypartcode, "imbinactive"=>$imbinactive, "imbparent"=>$imbparent, "imbdescription"=>$imbdescription, "imbsdescription"=>$imbsdescription);	
}

function fileReport($rhid) {
	$report=getReport($rhid);
	$currentreportstatus=$report['header']['rhstatus'];
	if( updateReportStatus('FILED',$report) ) {
		if(errorcount()==0) {
			$entity='Evaluation Report';
			$lname=properCase($report['header']['rhlname']);
			$fname=properCase($report['header']['rhfname']);
			$pnum=properCase($report['header']['rhpnum']);

			require_once($_SERVER['DOCUMENT_ROOT'].'/common/clinic.options.php');
			$clinicInformation = getClinicInformation($report['header']['rhcnum'], 1);
			if($clinicInformation['cmpgmcode']=='WS')
				$clinic=$clinicInformation['cmshortname']; 
			else
				$clinic=$report['header']['rhcnum']; 

			$appname=$_SESSION['application'];
			$reporttypes=getReportTypes();
			$docname=$reporttypes[$report['header']['rhrtid']]['rtdescription']; 
			$docdate=$report['header']['rhdate'];
			$docid=$rhid;
			$attach='1';
			$eachword=array();
			$word=array();
			foreach($report['header'] as $field=>$value) {
				if(!is_array($value)) {
					if(!empty($value)) {
						$eachword[]=mysqli_real_escape_string($dbhandle,"$field:$value");
					}
				}
			}
			if(count($eachword)>0)
				$words=implode(" ", $eachword);
			$filename1=$_SERVER['DOCUMENT_ROOT']."/modules/documentmanager/documents/Report_$rhid.pdf";
			$filepath=dirname($filename1);
			$filename=basename($filename1);
			$filetype='pdf';
			require_once($_SERVER['DOCUMENT_ROOT'].'/modules/documentmanager/documentManagerFunctions.php');
			if(requestArchive($entity, $lname, $pnum, $fname, $clinic, $docdate, $filename, $docref, $notes, $appname, $docname, $docid, $attach, $words, $filepath, $filetype)) {
				if(updateReportArchiveStatus('PENDING ARCHIVE',$report))
					notify("000","Report $rhid Filed. Archive requested.");
				else
					error("999","Error updating Report Archive Status to PENDING ARCHIVE. Please contact NetPT support. FILED OK, requestArchive() OK, updateReportArchiveStatus(PENDING ARCHIVE) function FAILED.");
			}
			else
				error("000","Report $rhid could not complete the requestArchive() function. Please contact NetPT support. FILED OK, requestArchive() function FAILED.");
		}
	}
	else
		updateReportStatus($currentreportstatus,$report);
}

function updateReportStatus($status, $report) {
	$rhid=$report['header']['rhid'];
	if($status=='GENERATED') {
		if($report['header']['rhstatus']=='FILED') 
			error("999","Report cannot be generated because it was already been filed.");
		else {
			$generateddate=dbDate(today());
			$udpate="UPDATE report_header SET rhstatus='GENERATED', rhgenerateddate='$generateddate' WHERE rhid='$rhid'";
		}
	}
	if($status=='FILED') {
		if($report['header']['rhstatus']=='NEW') 
			error("999","Report cannot be filed because it was not generated yet (Click the generate button to generate report).");
		if($report['header']['rhstatus']=='FILED') 
			error("999","Report cannot be filed because it was already filed.");
		if($report['header']['rhstatus']=='GENERATED') {
			$fileddate=dbDate(today());
			$udpate="UPDATE report_header SET rhstatus='FILED', rhfileddate='$fileddate' WHERE rhid='$rhid'";
		}
	}
	if(errorcount()==0) {
		if($result=mysqli_query($dbhandle,$udpate)) {
			notify("000","Report status updated from ".$report['header']['rhstatus']. " to $status.");
			return(true);
		}
		else
			error("999","Error updating report from ".$report['header']['rhstatus']." to $status<br />$update<br />".mysqli_error($dbhandle));
	}
	return(false);
}

function getReportTypes($inactive='0') {
	if( $result=getTableOptions($tablesarray='report_template', $keyfieldsarray='rtid', $fieldsarray='*', $wherefieldsarray="rtinactive='$inactive'", $orderfieldsarray='rtdispseq, rtname, rtdescription') ) {
		return($result);
	}
	else {
		errorclear();
		return(array());
	}
}

function updateReportArchiveStatus($status, $report) {
	$rhid=$report['header']['rhid'];
	unset($update);
	$actiondate=dbDate(today());
	if($status=='NOT ARCHIVED') {
		if(empty($report['header']['rharchstatus']) || $userissunni ) 
			$udpate="UPDATE report_header SET rharchstatus='$status', rharchdate='$actiondate' WHERE rhid='$rhid'";
		else 
			error("999","Report archive status cannot be changed to NOT ARCHIVED because it already has an archive status.");
	}
	if($status=='PENDING ARCHIVE') {
		if($report['header']['rharchstatus']=='NOT ARCHIVED' || $userissunni ) 
			$udpate="UPDATE report_header SET rharchstatus='$status', rharchdate='$actiondate' WHERE rhid='$rhid'";
		else
			error("999","Report archive status cannot be changed to PENDING ARCHIVE because it is not in the NOT ARCHIVED status.");
	}
	if($status=='ARCHIVED') {
		if($report['header']['rharchstatus']=='PENDING ARCHIVE' || $userissunni ) 
			$udpate="UPDATE report_header SET rharchstatus='$status', rharchdate='$actiondate' WHERE rhid='$rhid'";
		else
			error("999","Report archive status cannot be changed to ARCHIVED because it is not in the PENDING ARCHIVE status.");
	}

	if(errorcount()==0) {
		if($result=mysqli_query($dbhandle,$udpate)) {
			notify("000","Report archive status updated from previous status ".$report['header']['rharchstatus']. " to $status.");
			return(true);
		}
		else
			error("999","Error updating report from previous status ".$report['header']['rharchstatus']." to $status<br />$update<br />".mysqli_error($dbhandle));
	}
	return(false);
}

function simplearraycheck($input, $isrequired=false, $delimiter=NULL, $trimelements=false, $removeblankelements=false, $removezeroelements=false) {
// Function to analyze input
// if input is an array return array
// if input is string and is not delimited return array with string as element
// if input is string and is delimited return array with string exploded by delimiter as elements
	$result=false;
	if(is_array($input)) 
		$result=$input;
	else {
		if(is_string($input)) {

			if(empty($delimiter)) 
				$result=array("$input");
			else 
				$result=explode($delimiter,$input);
		}
	}

	if(is_array($result)) {
		if($trimelements) {
			foreach($result as $key=>$val)
				$result[$key]=trim($val);
		}
	
		if($removeblankelements) {
			foreach($result as $key=>$val) {
				if($val!='') 
					$newresult[$key]=trim($val);
			}
			$result=$newresult;
		}
	
		if($removezeroelements) {
			if( is_array($result) && count($result) > 0 ) {
				foreach($result as $key=>$val) {
					if($val!='0') 
						$newresult[$key]=trim($val);
				}
				$result=$newresult;
			}
		}
	}

	if($isrequired) {
		if(!is_array($result) || count($result)==0)
			$result=false;
	}

	return($result);
}

function getTableOptions($tablesarray, $keyfieldsarray, $fieldsarray=NULL, $wherefieldsarray=NULL, $orderfieldsarray=NULL, $dumpquery=NULL) {
// Function returns array for SELECT OPTIONS processing, returns array or error messages and false
	$records=array();
	unset($from);
	unset($keys);
	unset($select);
	unset($where);
	unset($orderby);

// Handle the Table List
	if( $tablesarray=simplearraycheck($tablesarray, $isrequired=true, $delimiter=',', $trimelements=true, $removeblankelements=true, $removezeroelements=true) ) 
		$from="FROM ".implode(",", $tablesarray);
	else
		error("999","No table(s) specified.");

// Handle the Key Field List - separate by colons for key
	if( $keyfieldsarray=simplearraycheck($keyfieldsarray, $isrequired=true, $delimiter=',', $trimelements=true, $removeblankelements=true, $removezeroelements=true) ) 
		$keys="key:".implode(":", $keyfieldsarray);
	else 
		error("999","No keys(s) specified.");

// Handle the Field Select List
	if( $fieldsarray=simplearraycheck($fieldsarray, $isrequired=true, $delimiter=',', $trimelements=true, $removeblankelements=true, $removezeroelements=true) ) 
		$select='SELECT '.implode(",", $fieldsarray);
	else
		$select='SELECT *';

// Handle the Where Field List
	if( $wherefieldsarray=simplearraycheck($wherefieldsarray, $isrequired=false, $delimiter=',', $trimelements=true, $removeblankelements=true, $removezeroelements=true) ) 
		$where='WHERE '.implode(" AND ", $wherefieldsarray);
	else
		unset($where);

// Handle the Order By Field Select List
	if( $orderfieldsarray=simplearraycheck($orderfieldsarray, $isrequired=false, $delimiter=',', $trimelements=true, $removeblankelements=true, $removezeroelements=true) ) 
		$orderby='ORDER BY '.implode(",", $orderfieldsarray);
	else
		unset($orderby);

	if(errorcount()==0) {
		$selectquery="$select $from $where $orderby";
//if($dumpquery) {
//	dump('selectquery',$selectquery);
//}
		if($selectresult=mysqli_query($dbhandle,$selectquery)) {
			while($selectrow=mysqli_fetch_assoc($selectresult)) {
				$keyvalues=array();
				foreach($keyfieldsarray as $index=>$keyfield) {
					if(!empty($selectrow["$keyfield"]))
						$keyvalues[]=$selectrow["$keyfield"]; 
				}
				$key=implode(":",$keyvalues);
				$records["$key"]=$selectrow;
			}
			return($records);
		}
		else
			error("999","config:getReportInjuryTemplateOptions:SELECT $table error.<br>$selectquery<br>".mysqli_error($dbhandle));
	}
	return(false);
}

function getReportAccessTemplate($bumcodearray, $pgmcodearray, $cnumarray, $user=NULL, $inactive='0') {
	$wherefields=array();
	$wherefields[]='rat_rtid=rtid';
	$wherefields[]="rtinactive='$inactive'";

// Available Templates will contain all of the Clinic Level "Base Templates" and also include "Custom" user templates
	if( is_array($bumcodearray) && count($bumcodearray) > 0 ) 
		$wherefields[]="rat_bumcode IN ('".implode("','",$bumcodearray)."')";
	else 
		error("999","getReportAccessTemplate:Business Unit Array Must Be Specified");

	if(is_array($pgmcodearray) && count($pgmcodearray)>0) 
		$wherefields[]="rat_pgmcode IN ('".implode("','",$pgmcodearray)."')";
	else 
		error("999","getReportAccessTemplate:Provider Group Array Must Be Specified");

	if( is_array($cnumarray) && count($cnumarray) > 0) 
		$wherefields[]="rat_cnum IN ('".implode("','",$cnumarray)."')";
		if(!empty($user))
			$wherefields[]="(rat_user IS NULL OR rat_user='$user')";
	else 
		error("999","getReportAccessTemplate:Clinic Array Must Be Specified");	

	if( $result=getTableOptions($tablesarray='report_access_template,report_template', $keyfieldsarray='rat_rtid', $fieldsarray='*', $wherefieldsarray=$wherefields, $orderfieldsarray='rat_dispseq, rtname, rtdescription') ) {
		return($result);
	}
	else {
		errorclear();
		return(array());
	}
}

function selectReportType($crid=NULL, $rtid=NULL,  $rhid=NULL, $report=NULL) {
// No parameters are required
// Report Template access defines which report templates are available to the current user
// Displays dropdown of available report types for current user
// REPOSTS $_POST values to form then performs exit() on script
// If user does not have permission to any report templates an error message is displayed, actions reset, returns to processing 
// Retrieve array of report types, false if no reports
	$reportuseraccess=getreportuseraccess();
	$bumcode=$reportuseraccess['bumcode'];
	$pgmbumcode=$reportuseraccess['pgmcode'];
	$cmcnum=$reportuseraccess['clinic'];
	$user=getuser();
	$reporttypes=array();
	$reporttypes=getReportAccessTemplate($bumcode,$pgmbumcode,$cmcnum,$user);

	if(count($reporttypes) > 0) {
// Add each Report as an option
		foreach($reporttypes as $reportrtid=>$reportarray) {
			$thisoption=$reportarray;
			$selectvalue=$thisoption['rtid'];
			$selectdescription=$thisoption['rtname'].'-'.$thisoption['rtdescription'];
			if($thisoption['rtid']==$rtid)
				$selected=' selected="selected"';
			else
				unset($selected);
			$optionshtml[]='<option value="'.$selectvalue.'" '.$selected.'>'.$selectdescription.'</option>';
		} // foreach
		$optionshtml=implode("
", $optionshtml);
?>
<div style="width:auto;" class="containedBox">
	<fieldset>
	<legend style="font-size:large">Select report type:</legend>
	<form name="reporttype" method="post">
		<div>
			<select name="rtid">
				<?php echo $optionshtml; ?>
			</select>
		</div>
		<div style="float:left">
			<input name="button['0']" type="submit" value="Cancel">
		</div>
		<div style="float:left;">
			<input name="button[<?php echo $crid; ?>]" type="submit" value="Set Report Type">
		</div>
		<input name="caller" type="hidden" value="<?php echo $_SESSION['button']; ?>">
		<input name="crid" type="hidden" value="<?php echo $crid; ?>">
		<input name="bumcode" type="hidden" value="<?php echo $_POST['bumcode']; ?>">
		<input name="pgmcode" type="hidden" value="<?php echo $_POST['pgmcode']; ?>">
		<input name="crcnum" type="hidden" value="<?php echo $_POST['crcnum']; ?>">
<?php
		repostPostArray($_POST);
?>
	</form>
	</fieldset>
</div>
<?php
		exit();
	} // if
	else {
		error("999", "selectReportType:No Reports Available");
		unset($_SESSION['button']);
		unset($_SESSION['id']);
	}
}

function getReportIdfromInjuryTemplateId($ritid) {
// required: report injury template id
// returns:
//		Success:Report Template ID as specified in the report injury template definition
//		Fail: error message and empty array
	if( $result=getTableOptions($tablesarray='report_injury_templates', $keyfieldsarray='ritrtid', $fieldsarray='ritrtid', $wherefieldsarray="ritid='$ritid'", $orderfieldsarray='ritrtid') ) {
		if(count($result)==1) { // Must only find one record
			if($current=current($result)) // save array to $current
				return($current['ritrtid']); // return current array Report Template ID
		}
	}

// On any failure return error message and FALSE
	error("999","Error getReportIdfromInjuryTemplateId <br>".mysqli_error($dbhandle));
	errorclear();
	return(FALSE);
}

function addReport($crid, $rtid=NULL, $ritid=NULL, $rhid=NULL) {
	$report=array();
	$initialids=array('1','4');
	$progressids=array('2');
	$dischargeids=array('3');

// Handle required parameters, Report Type and Injury Type Templates
// The handler forms must repost values?
//dump("Add $crid:$rtid:$ritid",$rhid);
	if(empty($rtid) && !empty($ritid)) 
		$rtid=getReportIdfromInjuryTemplateId($ritid);

	if(empty($rtid))
		$rtid=selectReportType($crid, $rtid, $rhid, $report);

// If progress report *** special processing
	if(in_array($rtid,$progressids)) { // Progress Report
// Have Case and Report Template 2
// List previously filed cases, allow selection of comparison report
		$report=getProgressReportDefaults($crid, $rtid, $ritid, $rhid);
	}
	else {
		$report=getReportDefaults($crid, $rtid, $ritid, $rhid);
	}
dump("report",$report);
exit();
	if(errorcount()==0) {
	// returns $report array to be written to new report
		// Create Report Header Record with known field values
		unset($header);
		unset($bodyparts);
		unset($bodypart_tests);

		$header=$report['header'];
// Assign header values unique to all NEW reports
		$header['rhstatus']='NEW';
		$header['rhdate']=dbDate(today());
		$header['rhrtid']=$report['header']['rhrtid'];
		$header['rhritid']=$report['header']['rhritid'];
	//	$header['rhlastreportdate']=NULL; // last report date
	//	$header['rhnextdoctorvisit']=NULL; // next Md Visit
	// Default to 30 Days
		$header['rhnextreportdate']=strtotime($header['rhdate'] .' + 30 days'); // next report date
	//	$header['rharchdate']=NULL; // archive report date
		$header['rharchstatus']='NOT ARCHIVED'; // archive report status
		
		$header['rhcrid']=$report['case']['crid'];
		$header['rhcrdate']=$report['case']['crdate'];
		$header['rhinjurydate']=$report['case']['crinjurydate'];
		$header['rhinjurytypecode']=$report['case']['crinjurytypecode'];
		$header['rhfrequency']=$report['case']['crfrequency'];
		$header['rhduration']=$report['case']['crduration'];
		$header['rhtotalvisits']=$report['case']['crtotalvisits'];
	
		$header['rhcasetypecode']=$report['case']['crcasetypecode'];
		$header['rhtherapytypecode']=$report['case']['crtherapytypecode'];
	
		$header['rhcnum']=$report['case']['crcnum'];
	
	// This should be entered or default to today
	//	$header['rhvisitdate']=$report['case']['crapptdate'];
		$header['rhvisitdate']=dbDate(today());
		
		$header['rhvisitsauthorized']=$report['case']['crvisitsauthorized'];
		$header['rhvisitsused']=$report['case']['crvisitsused'];
		$header['rhvisitenddate']=$report['case']['crvisitenddate'];
	
		$header['rhicd9code1']=$report['case']['cricd9code1'];
		$header['rhicd9desc1']=$report['case']['cricd9desc1'];
		$header['rhicd9dxnature1']=NULL; // icd9 injury nature
		$header['rhicd9dxbodypart1']=$report['case']['crdxbodypart']; // icd9 bodypart
		$header['rhicd9dxbodydescriptor1']=NULL; // icd9 descrptor
	
		$header['rhicd9code2']=$report['case']['cricd9code2'];
		$header['rhicd9desc2']=$report['case']['cricd9desc2'];
		$header['rhicd9dxnature2']=NULL; // icd9 injury nature
		$header['rhicd9dxbodypart2']=NULL; // icd9 bodypart
	//	$header['rhicd9dxbodypart2']=$report['case']['crdxbodypart']; // icd9 bodypart
		$header['rhicd9dxbodydescriptor2']=NULL; // icd9 descrptor
		
		$header['rhicd9code3']=$report['case']['cricd9code3'];
		$header['rhicd9desc3']=$report['case']['cricd9desc3'];
		$header['rhicd9dxnature3']=NULL; // icd9 injury nature
		$header['rhicd9dxbodypart3']=NULL; // icd9 bodypart
	//	$header['rhicd9dxbodypart3']=$report['case']['crdxbodypart']; // icd9 bodypart
		$header['rhicd9dxbodydescriptor3']=NULL; // icd9 descrptor
	
		$header['rhicd9code4']=$report['case']['cricd9code4'];
		$header['rhicd9desc4']=$report['case']['cricd9desc4'];
		$header['rhicd9dxnature4']=NULL; // icd9 injury nature
		$header['rhicd9dxbodypart4']=NULL; // icd9 bodypart
	//	$header['rhicd9dxbodypart4']=$report['case']['crdxbodypart']; // icd9 bodypart
		$header['rhicd9dxbodydescriptor4']=NULL; // icd9 descrptor
	
		$header['rhpaid']=$report['case']['crpaid'];
		$header['rhpnum']=$report['case']['crpnum'];
	
		$header['rhfname']=$report['case']['crfname'];
	
		$header['rhlname']=$report['case']['crlname'];
	
		$header['rhdob']=$report['case']['crdob'];
		$header['rhsex']=$report['case']['crsex'];
		$header['rhoccup']=$report['case']['croccup'];
	
		$header['rhdmid']=$report['case']['crrefdmid'];
		$header['rhdmlname']=$report['doctor']['dmlname'];
		$header['rhdmfname']=$report['doctor']['dmfname'];
		$header['rhdocupin']=$report['doctor'][''];
		$header['rhdocnpi']=$report['doctor'][''];
	
		$header['rhdlid']=$report['case']['crrefdlid'];
		$header['rhdlcity']=$report['doctor']['dlcity'];
	
		$header['rhdlsid']=$report['case']['crrefdlsid'];
		$header['rhdlstitle']=$report['doctor']['dlstitle'];
		$header['rhdlsname']=$report['doctor']['dlsname'];
		$header['rhdlsphone']=$report['doctor']['dlsphone'];
		$header['rhdlsfax']=$report['doctor']['dlsfax'];
	
		$header['rhpostsurgical']=$report['case']['crpostsurgical'];
		$header['rhsurgerydate']=$report['case']['crsurgerydate'];
	
		$header['rhtherapcode']=$report['case']['crtherapcode'];
		$header['rhtherapname']=$report['case']['crtherapname'];
		$header['rhtheraplic']=NULL; // Therapist license number
		$header['rhtherapnpi']=$report['case']['crtherapnpi'];
		
		//	$header['rhassessment']='Based on the objective and subjective data in conjunction with the stated outcome measurements physical therapy interventions are indicated at this time to address the patient\'s impairments and subsequent functional limitations.';
		
		$auditfields=getauditfields();
		$header['crtdate']=$auditfields['date'];
		$header['crtuser']=$auditfields['user'];
		$header['crtprog']=$auditfields['prog'];

		if($crid='WalkIn') {
			if(!empty($_POST['crcnum']))
				$header['rhcnum']=$_POST['crcnum'];
		}

// Assign detail_bodypart values
// Insert Header record
		if(is_array($header) && count($header)>0) {
			$fields=array();
			$values=array();
			foreach($header as $field=>$value) {
				if(is_string($value)) {
					$fields[]=$field;
					$values[]="'".mysqli_real_escape_string($dbhandle,$value)."'";
				}
			}
			if(is_array($fields) && count($fields)>0 && is_array($values) && count($fields)==count($values) ) {
				$fields=implode(", ",$fields);
				$values=implode(", ",$values);
				$insertquery="INSERT INTO report_header ($fields) values($values)";
				$fields=array();
				$values=array();
				if($insertresult=mysqli_query($dbhandle,$insertquery)) {
					$rhid=mysql_insert_id();
//					notify('000',"Report Id $rhid inserted into report_header");

// Add Report Bodyparts	
					$bodyparts=$report['detail_bodypart'];
					if(is_array($bodyparts) && count($bodyparts)>0 ) {
						foreach($bodyparts as $bodypartarrayindex=>$bodypartarray) {
							$bcode=$bodypartarray['rdbbcode'];
							if($bodypart=unserialize($bodypartarray)) {
								if(is_array($bodypart) && count($bodypart)>0 ) {
									foreach($bodypart as $field=>$value) {
										$values["$field"]="'".mysqli_real_escape_string($dbhandle,$value)."'";
									}
								}
							}
							else {
								foreach($bodypartarray as $field=>$value) {
									$values["$field"]="'".mysqli_real_escape_string($dbhandle,$value)."'";
								}
							}
							if( is_array($values) && count($values)>0 ) {
	// Pass along the report id
								$values["rdbrhid"]=$rhid;
								$fields=array_keys($values);
								$fields=implode(", ",$fields);
								$values=implode(", ",$values);
								$insertquery="INSERT INTO report_detail_bodypart ($fields) values($values)";
								$fields=array();
								$values=array();
								if($insertresult=mysqli_query($dbhandle,$insertquery)) {
									$rdbid=mysql_insert_id();
//									notify('000',"Report Bodypart Id $rdbid inserted into report_detail_bodypart");
	
// Add Report Bodypart Tests Groups
									$bodypart_test_groups=$report['detail_bodypart_test_group']["$bcode"];
									if(is_array($bodypart_test_groups) && count($bodypart_test_groups)>0 ) {
										foreach($bodypart_test_groups as $groupid=>$bodypartarray) {
//dump("groupid",$groupid);
//dump("bodypartarray",$bodypartarray);
											if($bodypart=unserialize($bodypartarray)) {
												if(is_array($bodypart) && count($bodypart)>0 ) {

													foreach($bodypart as $field=>$value) {
														$values["$field"]="'".mysqli_real_escape_string($dbhandle,$value)."'";
													}
												}
											}

											else {
												foreach($bodypartarray as $field=>$value) {
													$values["$field"]="'".mysqli_real_escape_string($dbhandle,$value)."'";
												}
											}

											if(is_array($values) && count($values)>0 ) {
					// Pass along the report id, bcode, groupid
												$values["rdbtgrhid"]=$rhid;
												$values["rdbtgbcode"]=$bcode;
												$values["rdbtgrtgid"]=$groupid;
												$fields=array_keys($values);
												$fields=implode(", ",$fields);
												$values=implode(", ",$values);
												$insertquery="INSERT INTO report_detail_bodypart_test_group ($fields) values($values)";
												$fields=array();
												$values=array();
												if($insertresult=mysqli_query($dbhandle,$insertquery)) {
													$rdbtgid=mysql_insert_id();
//													notify('000',"Report Bodypart Test group Id $rdbtgid inserted into report_detail_bodypart_test_group");


// Add Report Bodypart Tests
													$bodypart_tests=$report['detail_bodypart_test'];
													if(is_array($bodypart_tests) && count($bodypart_tests)>0 ) {
														foreach($bodypart_tests as $bpindex=>$bodyparttestarrayfield) {
//dump("bcode",$bcode);
//dump("rdbtgid",$rdbtgid);
//dump("bodyparttestarrayfield",$bodyparttestarrayfield);
//exit();
															if($bodyparttestarrayfield['rdbtbcode']==$bcode &&
																$bodyparttestarrayfield['rdbtrtgid']==$groupid) {
																foreach($bodyparttestarrayfield as $field=>$value) {
																	if(is_array($value))
																		$value=serialize($value);
																	else 
																		$value=mysqli_real_escape_string($dbhandle,$value);
																	$values["$field"]="'".$value."'";
																}
															}
														
														if(is_array($values) && count($values)>0 ) {
// Pass along the report id & bodypart id
															$values["rdbtrhid"]=$rhid;
															if(empty($values["rdbtbcode"]))
																$values["rdbtbcode"]=$rdbid;
															if(empty($values["rdbtrtgid"]))
																$values["rdbtrtgid"]=$rdbtgid;
															$fields=array_keys($values);

															$fields=implode(", ",$fields);
															$values=implode(", ",$values);
															$insertquery="INSERT INTO report_detail_bodypart_test ($fields) values($values)";
															$fields=array();
															$values=array();
															if($insertresult=mysqli_query($dbhandle,$insertquery)) {
																$rbtid=mysql_insert_id();
//																notify('000',"Report Bodypart Test Id $rbtid inserted into report_detail_bodypart_test");
															}
															else {
																dump("Query: $insertquery<br>",mysqli_error($dbhandle));
																exit();
															}
														}
														}
													}



												}
												else {
													dump("Query: $insertquery<br>",mysqli_error($dbhandle));
													exit();
												}
											}
										}
									}
								}
								else {
									dump("Query: $insertquery<br>",mysqli_error($dbhandle));
									exit();
								}
							}
							else {
	
	dump("bodyparts",$bodyparts);
	dump("fields",$fields);
	dump("values",$values);
	exit();
							}
						}
					}
	// Transfer to Editing New Report
					$_SESSION['id']=$rhid;
					$_SESSION['button']='Edit';
//					dump('_SESSION[id]',$_SESSION['id']);
//					dump('_SESSION[button]',$_SESSION['button']);
//					exit();
					editReport($rhid);
				}
				else
					error("999","ERROR addReport:INSERT HEADER<br>$insertquery<br>".mysqli_error($dbhandle));
			}
			else
				error("999","ERROR addReport:fields/values array mismatch error.");	
		}
		else
			error("999","ERROR addReport:header array error.");	
	}
}

function deleteReport($button, $rhid) {
	$deletereport=getReport($rhid);
	$header=$deletereport['header'];
	
	$lname=$header['rhlname'];
	$fname=$header['rhfname'];

	if(empty($fname)) {
		if(empty($lname)) 
			$patient="UNSPECIFIED patient name";
		else
			$patient="patient with the last name of $lname";
	}
	else {
		if(empty($lname)) 
			$patient="patient with the first name of $fname";
		else
			$patient="$fname $lname";
	}
	if($button!='Yes, Delete Report') {
		echo '
<div class="clearboth"></div>
<div style="clear:both; padding:5px; border-style:solid; border-color:black; background-color:yellow; width:576px;">
<div class="col20pct">Delete Report</div>
<div class="col70pct">
	<form name="confirmDelete" method="post">
			Are you sure you want to delete report '.$rhid.' for '.$patient.'?
			<div><input name="button['.$rhid.']" type="submit" value="Yes, Delete Report" />
			<input name="button[0]" type="submit" value="Cancel" /></div>
	</form>
</div>
</div>
';
	}
	else {
// Leave Detail for now...
		$deletequery="DELETE FROM report_header WHERE rhid='$rhid'";
		if($deleteresult=mysqli_query($dbhandle,$deletequery)) {
			notify("000","Report $rhid deleted.");
		}
		else
			error("999","ERROR deleteReport:INSERT<br>$insertquery<br>".mysqli_error($dbhandle));
	}
}

function getReportDetailBodypart($rhid) {
	$details=array();
//	$select="SELECT rdbid, rdbrhid, rdbdispseq, rdbbcode, rdbchecked, rdbexpanded, imbinactive, imbparent, imbdescription, imbsdescription FROM report_detail_bodypart JOIN master_injury_bodyparts on concat('A',rdbbcode)=concat('A',imbcode) WHERE rdbrhid='$rhid' ORDER BY rdbdispseq";
	$select="
		SELECT rdbid, rdbrhid, rdbdispseq, rdbbcode, rdbchecked, rdbexpanded, rbinactive, rbdescription, rbsdescription, rbbilatflag 
		FROM report_detail_bodypart 
		JOIN report_bodyparts on rdbbcode=rbbcode
		WHERE rdbrhid='$rhid'
		ORDER BY rdbdispseq
	";
	if($result=mysqli_query($dbhandle,$select)) {
		while($row=mysqli_fetch_assoc($result)) {
			foreach($row as $field=>$value) 
//				$details['record'][$row['rdbid']]["$field"]=stripslashes($value);
				$details[$row['rdbid']]["$field"]=stripslashes($value);
		}
		if(count($details)>0) {
			return($details);
		}
//		else
//			error("999","getReportDetails:Error on FETCH of or no Report Details found.<br>$select<br>".mysqli_error($dbhandle));
	}
	else
		error("999","getReportDetails:Error on SELECT.<br>$select<br>".mysqli_error($dbhandle));
	return(false);
}

function getReportDetailBodypartTestGroup($rhid) {
	$details=array();
	$select="
		SELECT rdbtgid, rdbtgrhid, rdbtgdispseq, rdbtgbcode, rdbtgrtgid, rdbtgchecked, rdbtgexpanded, rtgdescription
		FROM report_detail_bodypart_test_group 
		JOIN report_test_groups ON rdbtgrtgid=rtgid
		WHERE rdbtgrhid='$rhid'
		ORDER BY rdbtgdispseq
	";
	if($result=mysqli_query($dbhandle,$select)) {
		while($row=mysqli_fetch_assoc($result)) {
			foreach($row as $field=>$value) 
//				$details['record'][$row['rdbid']]["$field"]=stripslashes($value);
				$details[$row['rdbtgbcode']][$row['rdbtgrtgid']]["$field"]=stripslashes($value);
		}
		if(count($details)>0) {
			return($details);
		}
//		else
//			error("999","getReportDetails:Error on FETCH of or no Report Details found.<br>$select<br>".mysqli_error($dbhandle));
	}
	else
		error("999","getReportDetailBodypartTestGroup:Error on SELECT.<br>$select<br>".mysqli_error($dbhandle));
	return(false);
}

function getReportDetailBodypartTest($rhid) {
	$details=array();
	$select="
		SELECT rdbtid, rdbtrhid, rdbtbcode, rdbtdispseq, rdbtrtgid, rdbtrtid, rdbtrtname, rdbtnote, rdbtresult1, rdbtresult1date, rdbtresult2, rdbtresult2date, rdbtrtmname, rdbtchecked, rdbtexpanded 
		FROM report_detail_bodypart_test 
		WHERE rdbtrhid='$rhid'
		order by rdbtdispseq, rdbtbcode, rdbtrtgid, rdbtid, rdbtrtname
	";
	if($result=mysqli_query($dbhandle,$select)) {
		$dispseq=0;
		while($row=mysqli_fetch_assoc($result)) {
//			$dispseq=$dispseq+10;
			$rdbtid=$row['rdbtid'];
			foreach($row as $field=>$value) 
//				$details['record'][$row['rdbtid']]["$field"]=stripslashes($value);
//				$details[$row['rdbtid']]["$field"]=stripslashes($value);
				$details["$rdbtid"]["$field"]=stripslashes($value);
		}
		if(count($details)>0) {
			return($details);
		}
//		else
//			error("999","getReportDetails:Error on FETCH of or no Report Details found.<br>$select<br>".mysqli_error($dbhandle));
	}
	else
		error("999","getReportDetails:Error on SELECT.<br>$select<br>".mysqli_error($dbhandle));
	return(false);
}

function getReportHeaderInjury($id) {
	$wherefields[]="rhirhid='$id'";
	if( $result=getTableOptions($tablesarray='report_header_injury', $keyfieldsarray='rhiid', $fieldsarray='*', $wherefieldsarray=$wherefields, $orderfieldsarray='rhidispseq, rhinature,rhidescriptor,rhibodypart,rhiicd9code') ) {
		return($result);
	}
	else {
		errorclear();
		return(array());
	}
}

function getReportHeader($rhid) {
	$report=array();
	$select="SELECT cmbnum rhbumcode, cmpgmcode rhpgmcode, crcnum rhcnum, rh.* 
	FROM report_header rh
	LEFT JOIN cases cr
	ON rhcrid=crid
	LEFT JOIN master_clinics cm
	ON cmcnum=crcnum
	WHERE rhid='$rhid'
	";
	if($result=mysqli_query($dbhandle,$select)) {
		if($report=mysqli_fetch_assoc($result)) {
			foreach($report as $field=>$value) {

//// Deserialize Report Injuries Array
//				if($field=='rhinjuriesarray') {
//					$cleanarray=array();
//					if(!empty($value)) {
//						if($arrayvalues=unserialize($value)) {
//							foreach($arrayvalues as $row=>$injuriesarray) {
//								foreach($injuriesarray as $col=>$val) {
//									$cleanarray["$row"]["$col"]=stripslashes($val);
//								}
//							}
//						}
//					}
//					$report['rhinjuries']=$cleanarray;
//					$report['header_injury']=unserialize($value);
//				}
//				else
					$report["$field"]=stripslashes($value);
			}
			return($report);
		}
		else
			error("999","getReportHeader:Error on FETCH<br>$select<br>".mysqli_error($dbhandle));
	}
	else
		error("999","getReportHeader:Error on SELECT<br>$select<br>".mysqli_error($dbhandle));
	return(false);
}

function getReport($rhid) {
	$report=array();
	if($result=getReportHeader($rhid)) { // gets the report header
		$report['header']=$result;
		$report['header_injury']=unserialize($report['header']['rhinjuriesarray']);
		if($result=getReportDetailBodypart($rhid))    // gets all details
			$report['detail_bodypart']=$result;
		if($result=getReportDetailBodypartTestGroup($rhid))    // gets all details
			$report['detail_bodypart_test_group']=$result;
		if($result=getReportDetailBodypartTest($rhid))  { // gets all details sequentially dispseq
			if(is_array($result) && count($result)>0) {
				foreach($result as $rdbtid=>$detail_bodypart_test) {
					if(strtolower($detail_bodypart_test['rdbtchecked'])!='on') 
						$result["$rdbtid"]['rdbtchecked']='';
					else
						$result["$rdbtid"]['rdbtchecked']='on';
					$result1=unserialize($detail_bodypart_test['rdbtresult1']);
					$result["$rdbtid"]['rdbtresult1']=$result1;
					$result2=unserialize($detail_bodypart_test['rdbtresult2']);
					$result["$rdbtid"]['rdbtresult2']=$result2;
					unset($result1);
					unset($result2);
				}
				$report['detail_bodypart_test']=$result;
			}
		}
	}
	else
		error("999","no header".mysqli_error($dbhandle));
	displaysitemessages();
	return($report);
}




function editReport($rhid, $rtid=NULL, $ritid=NULL) {
//dump("rtid:$rtid ritid: $ritid",$rhid);
	if(!empty($rhid) && empty($rtid) && empty($ritid)) {
// reading report data from database for first editing
		$report=getReport($rhid);
		$rhid=$report['header']['rhid'];
		$rtid=$report['header']['rhrtid'];
		$ritid=$report['header']['rhritid'];

		$bumcode=$report['header']['rhbumcode'];
		$pgmcode=$report['header']['rhpgmcode'];
		$crcnum=$report['header']['rhcnum'];
	
		$crid=$report['header']['rhcrid'];
		$crpnum=$report['header']['rhpnum'];

		if(empty($rhid))
			error("999","Report Id Missing.");

		if( empty($rtid) ) 
			selectReportType($crid, $rtid, $rhid);
		if( empty($ritid)) 
			selectInjuryTemplateType($crid, $rtid, $ritid, $rhid);
	}
	else {
		if(empty($rtid) && !empty($ritid)) {
			$rtid = getReportIdfromInjuryTemplateId($ritid);
		}
	}

	if(empty($bumcode))
		$bumcode='WS';
	if(empty($pgmcode))
		$pgmcode='WS';

// Done return to search
		unset($_SESSION['id']);
		unset($_SESSION['button']);

//	if(empty($crid))
//		notify("999","Case Id Missing.");

//	$rtid=$_POST['report']['header']['rhrtid'];

	displaysitemessages();

// Load Edit Form
//	$editform="editReport_".$rtid.".php";
// NEED TO REMOVE THIS ONCE THE NEW VERSION IS DONE
// This adds the tests for the Dx Body Part
// This is where we will add all tests for all body parts
//if($rtid==4) {
//	$bodypartoptions1=getReportBodypartsOptions();
//	$testtemplaterelations=getReportTestTemplateRelationsOptions();
//	$testtemplates=getReportTestTemplatesOptions();
//	$testtemplatelist=getReportTestTemplateList();

//	if( !is_array($report['detail_bodypart']) ) { // no detail exists yet


//		if( !empty($report['header']['rhicd9dxbodypart1']) || !empty($report['header']['rhicd9dxbodypart2']) || !empty($report['header']['rhicd9dxbodypart3']) || !empty($report['header']['rhicd9dxbodypart4']) ) {
//			$_POST['report']['header']=$report['header'];

//			addTestsUsingDx();
//		}


//	}
//}
	if($rtid=='1') 
		$rtdir='initialevaluation';
	if($rtid=='2') 
		$rtdir='progressreport';
	if($rtid=='3') 
		$rtdir='dischargereport';
	
	$editform=$rtdir."/editReport.php";
//dump("editform $rtid",$editform);
	require_once($editform);

//	if(empty($_POST['template']))
//		$template=getTemplate($rtid);
//	else
//		$template=unserialize($_POST['template']);


	if(errorcount()==0) {
		if(count($report) > 0) {
//			if(count($template) > 0) {
// We have the Report Header, Details, and Template Loaded.
//				validate
//				format
//				display
//				echo '<form name="back" method="post"><input name="back" type="submit" value="Back to Search"></form>';
//				displaysitemessages();
//			}
//			else
//				error("999","Error editReport:No Template Data");
		}
		else
			error("999","Error editReport:No Report Data.");
	}
	else {
		displaysitemessages();
		unset($_SESSION['id']);
		unset($_SESSION['button']);
//		echo '<form name="back" method="post"><input name="back" type="submit" value="Back to Search"></form>';
	}
	exit();
}

function getDataDictionary($tablelist) {
	$dictionary=array();

	if(!is_array($tablelist))
		$tables[]=$tablelist;

	foreach($tables as $index=>$table) {
		$query="SHOW COLUMNS FROM $table";
		if($result=mysqli_query($dbhandle,$query)) {
			$definition=array();
			while($row=mysqli_fetch_assoc($result)) {
				$field=$row["Field"];
				$definition["$field"]=$row;
			}
			$dictionary["$table"]=$definition;
		}
	}
	return($dictionary);
}

function dbFormat($table, $data) {
// get data dictionary of field definitions
	$newdata=array();
	if($dictionary=getDataDictionary($table)) {
		if(array_key_exists("$table", $dictionary))
			$dict=$dictionary["$table"];
		else
			$dict=array();

// Basially for any database field that is enum('0','1') initialize the data value to 0 if it's not set
		foreach($dict as $field=>$value) {
			if($dict["$field"]['Type']=="enum('0','1')") { // checkbox
				if(!isset($data["$field"])) {
					$data["$field"]='0';
				}
				else {
					$data["$field"]='1';
				}
			}
		}

		$newdata=array();
		if( !is_array($data) || count($data)==0 ) {
			dump("table",$table);
			dump("data",$data);
			exit();
		}
		foreach($data as $field=>$value) {
			unset($newvalue);

			if(array_key_exists("$field",$dict)) { 
				$definition=$dict["$field"];
				$VALUE=strtoupper($value);
				unset($newvalue);
				$explodedType=explode("(",$definition['Type']);
				$type=$explodedType[0];
				switch ($type): 
					case 'bool':
					case 'boolean': // 0 = false, otherwise true
						if(empty($value) || $VALUE=='N' || $VALUE=='NO' || $VALUE=='FALSE')
							$newvalue=false;
						else 
							$newvalue=true;
						break;
					case 'tinyint': // -128 to 127 when signed, 0-255 when unsigned
						$newvalue=(integer) $value;
						if($newvalue!=$value) {
							$newvalue=false;
						}
						break;
					case 'smallint': // small integer. The signed range is -32768 to 32767. The unsigned range is 0 to 65535.
						break;
					case 'mediumint': // A medium-sized integer. The signed range is -8388608 to 8388607. The unsigned range is 0 to 16777215.
						break;
					case 'int': // A normal-size integer. The signed range is -2147483648 to 2147483647. The unsigned range is 0 to 4294967295.
					case 'integer':
						$newvalue=(integer) $value;
						break;
					case 'bigint': // A large integer. The signed range is -9223372036854775808 to 9223372036854775807. The unsigned range is 0 to 18446744073709551615.
						break;
					case 'serial': // SERIAL is an alias for BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE.
						break;
					case 'float': //  small (single-precision) floating-point number. Permissible values are -3.402823466E+38 to -1.175494351E-38, 0, and 1.175494351E-38 to 3.402823466E+38.
						break;
					case 'double': // A normal-size (double-precision) floating-point number. Permissible values are -1.7976931348623157E+308 to -2.2250738585072014E-308, 0, and 2.2250738585072014E-308 to 1.7976931348623157E+308.
					case 'real':
					case 'double precision': // These types are synonyms for DOUBLE. Exception: If the REAL_AS_FLOAT SQL mode is enabled, REAL is a synonym for FLOAT rather than DOUBLE.
						break;
					case 'dec': // An unpacked fixed-point number. Behaves like a CHAR column; “unpacked” means the number is stored as a string, using one character for each digit of the value. M is the total number of digits and D is the number of digits after the decimal point. The decimal point and (for negative numbers) the “-” sign are not counted in M, although space for them is reserved. If D is 0, values have no decimal point or fractional part. The maximum range of DECIMAL values is the same as for DOUBLE, but the actual range for a given DECIMAL column may be constrained by the choice of M and D. If D is omitted, the default is 0. If M is omitted, the default is 10.
					case 'decimal':
					case 'numeric':
					case 'fixed':
						break;
	// Date and Time Types
					case 'date': // A date. The supported range is '1000-01-01' to '9999-12-31'. MySQL displays DATE values in 'YYYY-MM-DD' format, but permits assignment of values to DATE columns using either strings or numbers.
						$newvalue=dbDate($value);
						break;
					case 'datetime': // A date and time combination. The supported range is '1000-01-01 00:00:00' to '9999-12-31 23:59:59'. MySQL displays DATETIME values in 'YYYY-MM-DD HH:MM:SS' format, but permits assignment of values to DATETIME columns using either strings or numbers.
						$newvalue=dbDate($value);
						break;
					case 'timestamp': // A timestamp. The range is '1970-01-01 00:00:01' UTC to '2038-01-19 03:14:07' UTC. TIMESTAMP values are stored as the number of seconds since the epoch ('1970-01-01 00:00:00' UTC). A TIMESTAMP cannot represent the value '1970-01-01 00:00:00' because that is equivalent to 0 seconds from the epoch and the value 0 is reserved for representing '0000-00-00 00:00:00', the “zero” TIMESTAMP value.
						break;
					case 'time': // A time. The range is '-838:59:59' to '838:59:59'. MySQL displays TIME values in 'HH:MM:SS' format, but permits assignment of values to TIME columns using either strings or numbers.
						break;
					case 'year': //  year in two-digit or four-digit format. The default is four-digit format. In four-digit format, the permissible values are 1901 to 2155, and 0000. In two-digit format, the permissible values are 70 to 69, representing years from 1970 to 2069. MySQL displays YEAR values in YYYY format, but permits assignment of values to YEAR columns using either strings or numbers. 
						break;
	
	// Strings
					case 'char': //
						$newvalue=strtoupper($value);
						break;
	
					case 'varchar': //
						$newvalue=strtoupper($value);
						break;
	
					case 'binary': //
						break;
	
					case 'varbinary': //
						break;
	
					case 'tinyblob': //
						break;
	
					case 'tinytext': //
						break;
	
					case 'blob': //
						break;
	
					case 'text': //
						$newvalue=$value;
						break;
	
					case 'mediumblob': //
						break;
	
					case 'longblob': //
						break;
	
					case 'longtext': //
						break;
	
					case 'enum': // We'll use this for checkbox fields
						if($definition['Type']=="enum('0','1')"){
							$newvalue=$value;
						}
						break;
	
					case 'set': //
						break;
	
					default:
						$newvalue=$value;
						break;
				endswitch;
				$newdata["$field"]=$newvalue;
			}
			else {
				$errordata["$field"]=value;
			}
		}
	}
	else
		error("999","Error retrieving Data Dictionary. $table");
	return($newdata);
}

function saveReport($button, $rhid) {
//dumppost();
//exit();
	if(empty($rhid)) 
		error("999","Error saving report, report id not passed. $rhid");
	else {
// button and id were cleared, but $report should be populated in POST

//dumppost();
//exit();
		$report=$_POST['report'];
		$header=$report['header'];

		$clinic=$header['rhcnum'];

		if(empty($header['rhicd9desc1'])) 
			$header['rhicd9code1']='';
		if(empty($header['rhicd9dxbodypartdesc1']))
			$header['rhicd9dxbodypart1']='';
		if(empty($header['rhicd9dxbodydescriptordesc1']))
			$header['rhicd9dxbodydescriptor1']='';

		if(empty($header['rhicd9desc2']))
			$header['rhicd9code2']='';
		if(empty($header['rhicd9dxbodypartdesc2']))
			$header['rhicd9dxbodypart2']='';
		if(empty($header['rhicd9dxbodydescriptordesc2']))
			$header['rhicd9dxbodydescriptor2']='';

		if(empty($header['rhicd9desc3']))
			$header['rhicd9code3']='';
		if(empty($header['rhicd9dxbodypartdesc3']))
			$header['rhicd9dxbodypart3']='';
		if(empty($header['rhicd9dxbodydescriptordesc3']))
			$header['rhicd9dxbodydescriptor3']='';

		if(empty($header['rhicd9desc4']))
			$header['rhicd9code4']='';
		if(empty($header['rhicd9dxbodypartdesc4']))
			$header['rhicd9dxbodypart4']='';
		if(empty($header['rhicd9dxbodydescriptordesc4']))
			$header['rhicd9dxbodydescriptor4']='';

		$therapytypecode=$header['rhtherapytypecode'];
		$therapistarray=therapistCodeOptions();
		$therapistcode=$header['rhtherapcode'];
		$therapist=$therapistarray["$therapistcode"];
		$header['rhtherapname']=$therapist['tname'];
		$header['rhtheraplic']=$therapist['tlic'];	
		$header['rhtherapnpi']=$therapist['tnpi'];

// Clean up Report Injuries Array
//		$cleanarray=array();
//	if(is_array($header['rhinjuries']) && count($header['rhinjuries']) > 0) {
//		foreach($header['rhinjuries'] as $row=>$rhinjuriesarray) {
//			foreach($rhinjuriesarray as $col=>$value) {
//				$cleanarray["$row"]["$col"]=mysqli_real_escape_string($dbhandle,$value);
//			}
//		}
//		if(is_array($cleanarray)) {
//			$header['rhinjuriesarray']=serialize($cleanarray);
//		}
//	}

		$rows=array();
		if(is_array($report['header_injury']) && count($report['header_injury'])>1) {
			$header_injury=$report['header_injury'];
//dump("header_injury",$header_injury);
			$fields=array_keys($header_injury);
			if( is_array($header_injury[$fields[0]]) && count($header_injury[$fields[0]])>0 ) {
				$header_injuryindexes=array_keys($header_injury[$fields[0]]);
				foreach($header_injuryindexes as $row=>$header_injuryindex) {
//dump("header_injuryindex",$header_injuryindex);
					if($header_injuryindex!='add') {
						foreach($fields as $column=>$field) {
							$rows["$row"]["$field"]=$header_injury["$field"]["$header_injuryindex"];
						}
					}
				}
			}
		}
//dump("rows",$rows);
		if(is_array($rows) && count($rows)>0) 
			$header['rhinjuriesarray']=serialize($rows);
		else
			$header['rhinjuriesarray']=serialize(array());


//dump("header",$header);



		$dbheader=dbFormat('report_header', $header); // dbFormat needs to know the format of the data contained in fields
		$set=array();
		foreach($dbheader as $field=>$value) {
			if($field!='rhid') {
				$dbvalue=mysqli_real_escape_string($dbhandle,$value);
				$set["$field"]="$field='$dbvalue'";
				$_POST['report']['header']["$field"]=$header["$field"];
			}
		}
		unset($set['rhid']);
		if(count($set)>0) {
			$setsql="SET ".implode(", ",$set);
			$updatequery="UPDATE report_header $setsql WHERE rhid='$rhid'";
			if(!$result=mysqli_query($dbhandle,$updatequery))
				error("999","Error Updating Report Header Record<br />$updatequery<br />".mysqli_error($dbhandle));
		}
		
// Detail - The bodyparts for reports are static. There is a list that every report will contain. Reports that do not contain bodyparts will not write any detail.
		$detail_bodyparts=$report['detail_bodypart'];
		if(is_array($detail_bodyparts) && count($detail_bodyparts)>0) {

// Process each bodypart from objective tab
			$detail_bodypart_results=array(); // array of results inserted, or updated... unset
//dump("detail_bodyparts",$detail_bodyparts);
			foreach($detail_bodyparts as $bp=>$detail_bodypart) {

				if(strtolower($detail_bodypart['rdbchecked'])!='on') 
					$detail_bodypart['rdbchecked']='';
				$detail_bodypart['rdbrhid']=$rhid;
				$rdbbcode=$detail_bodypart['rdbbcode'];

//dump("detail_bodypart",$detail_bodypart);

				$dbdetail_bodypart=dbFormat('report_detail_bodypart', $detail_bodypart); // dbFormat needs to know the format of the data contained in fields
				$set=array();
				foreach($dbdetail_bodypart as $field=>$value) {
					$dbvalue=mysqli_real_escape_string($dbhandle,$value);
					$set["$field"]="$field='$dbvalue'";
				}
				unset($set['rdbid']); // cannot set the value of the auto_increment key
				if(count($set)>0) {
					$setsql="SET ".implode(", ",$set);
					$query="SELECT count(*) as found FROM report_detail_bodypart WHERE rdbrhid='$rhid' AND rdbbcode='$rdbbcode'"; // insert or update?
					if($result=mysqli_query($dbhandle,$query)) {
						if($row=mysqli_fetch_assoc($result)) {
							$function="UNKNOWN FUNCTION";
							if($row['found']==0) {
								$function="INSERTING";
								$query="INSERT INTO report_detail_bodypart $setsql";
// All Bodyparts get written to the database regardless of use, so the bodypart should be just an update to the existing bodypart detail record
							}
							else {
								if($row['found']==1) {
									$function="UPDATING";
									$query="UPDATE report_detail_bodypart $setsql WHERE rdbrhid='$rhid' AND rdbbcode='$rdbbcode' LIMIT 1";
								}
								else
									error("999","SaveReport:ERROR Report Detail Bodypart - too many records found.");
							}
						}
						else
							error("999","SaveReport:ERROR Report Detail Bodypart - Select error.");
						if( errorcount()==0 ) {
//dump("query $query",$row['found']);
							if($result=mysqli_query($dbhandle,$query)) {
								$detail_bodypart_results["$rdbbcode"]=$function;
							}
							else
								error("999","SaveReport:ERROR Report Detail Bodypart - $function");
						}
						else {
							error("999","Error Updating/Inserting Report Detail Body Part Record $rdbbcode <br />$query<br />".mysqli_error($dbhandle));
							unset($detail_bodypart_result["$rdbbcode"]);
						}
					}
					else 
						error("999","SaveReport:ERROR Report Detail Bodypart - Select error.");
				} // if set
			} // for
		}

// Process each group from objective tab
		$detail_bodypart_test_groups=$report['detail_bodypart_test_group'];
		if(is_array($detail_bodypart_test_groups) && count($detail_bodypart_test_groups)>0) {
			$detail_bodypart_test_groups_results=array(); // array of results inserted, or updated... unset
			foreach($detail_bodypart_test_groups as $rdbbcode=>$detail_bodypart_test_grouparray) {
				foreach($detail_bodypart_test_grouparray as $rtgid=>$detail_bodypart_test_group) {
					if(strtolower($detail_bodypart_test_group['rdbtgchecked'])!='on') 
						$detail_bodypart_test_group['rdbtgchecked']='';
					$detail_bodypart_test_group['rdbtgrhid']=$rhid;
					$detail_bodypart_test_group['rdbtgbcode']=$rdbbcode;
					$detail_bodypart_test_group['rdbtgrtgid']=$rtgid;
					$dbdetail_bodypart_test_group=dbFormat('report_detail_bodypart_test_group', $detail_bodypart_test_group); // dbFormat needs to know the format of the data contained in fields
					$set=array();
					foreach($dbdetail_bodypart_test_group as $field=>$value) {
						$dbvalue=mysqli_real_escape_string($dbhandle,$value);
						$set["$field"]="$field='$dbvalue'";
					}
					unset($set['rdbtgid']); // cannot set the value of the auto_increment key
					if(count($set)>0) {
						$setsql="SET ".implode(", ",$set);
						$query="SELECT count(*) as found FROM report_detail_bodypart_test_group WHERE rdbtgrhid='$rhid' AND rdbtgbcode='$rdbbcode' and rdbtgrtgid='$rtgid'"; // insert or update?
						if($result=mysqli_query($dbhandle,$query)) {
							if($row=mysqli_fetch_assoc($result)) {
								$function="UNKNOWN FUNCTION";
								if($row['found']==0) {
									$function="INSERTING";
									$query="INSERT INTO report_detail_bodypart_test_group $setsql";
	// All Bodyparts get written to the database regardless of use, so the bodypart should be just an update to the existing bodypart detail record
								}
								else {
									if($row['found']==1) {
										$function="UPDATING";
										$query="UPDATE report_detail_bodypart_test_group $setsql WHERE rdbtgrhid='$rhid' AND rdbtgbcode='$rdbbcode' and rdbtgrtgid='$rtgid' LIMIT 1";
									}
									else
										error("999","SaveReport:ERROR Report Detail Bodypart Test Groups - too many records found.");
								}
							}
							else
								error("999","SaveReport:ERROR Report Detail Bodypart Test Groups- Select error.");
							if( errorcount()==0 ) {
								if($result=mysqli_query($dbhandle,$query)) 
									$detail_bodypart_test_groups_results["$rdbtgbcode"]["$rdbtgrtgid"]=$function;
								else
									error("999","SaveReport:ERROR Report Detail Bodypart Test Groups - $function");
							}
							else {
								error("999","Error Updating/Inserting Report Detail Body Part Test Groups Record $rdbtgbcode $rdbtgrtgid <br />$query<br />".mysqli_error($dbhandle));
								unset($detail_bodypart_test_groups_results["$rdbtgbcode"]["$rdbtgrtgid"]);
							}
						}
						else 
							error("999","SaveReport:ERROR Report Detail Bodypart Test Groups - Select error.");
					} // if set
				} // for
			} // for
		}

// Delete existing bodypart tests
		$query="DELETE FROM report_detail_bodypart_test WHERE rdbtrhid='$rhid'"; 
		if(!$result=mysqli_query($dbhandle,$query)) {
			error("999","Error removing tests");
		}
		else {

			$detail_bodypart_tests=$report['detail_bodypart_test']; // skip the add indexes
			if(is_array($detail_bodypart_tests) && count($detail_bodypart_tests)>0) {
	// Process each bodypart from objective tab
				$detail_bodypart_tests_results=array(); // array of results inserted, or updated... unset
// dump("detail_bodypart_tests",$detail_bodypart_tests);
// exit();
				foreach($detail_bodypart_tests as $index=>$detail_bodypart_test) {
				if($index!='add') {
					$detail_bodypart_test['rdbtrhid']=$rhid;
					$detail_bodypart_test['rdbtbcode']=$detail_bodypart_test['rdbtbcode'];
					$detail_bodypart_test['rdbtrtgid']=$detail_bodypart_test['rdbtrtgid'];
					if(strtolower($detail_bodypart_test['rdbtchecked'])!='on') 
						$detail_bodypart_test['rdbtchecked']='';
					else
						$detail_bodypart_test['rdbtchecked']='on';
					$detail_bodypart_test['rdbtresult1']=serialize($detail_bodypart_test['rdbtresult1']);
					$detail_bodypart_test['rdbtresult2']=serialize($detail_bodypart_test['rdbtresult2']);
					$dbdetail_bodypart_test=dbFormat('report_detail_bodypart_test', $detail_bodypart_test); // dbFormat needs to know the format of the data contained in fields
//dump("detail_bodypart_test",$detail_bodypart_test);
//dump("dbdetail_bodypart_test",$dbdetail_bodypart_test);
//exit();
					$set=array();
					foreach($dbdetail_bodypart_test as $field=>$value) {
						$dbvalue=mysqli_real_escape_string($dbhandle,$value);
						$set["$field"]="$field='$dbvalue'";
					}
	
					unset($set['rdbtid']); // cannot set the value of the auto_increment key
	
					if(count($set)>0) {
						$setsql="SET ".implode(", ",$set);
						$function="INSERTING";
						$query="INSERT INTO report_detail_bodypart_test $setsql";
						if($result=mysqli_query($dbhandle,$query)) 
							$detail_bodypart_test_results["$rdbtid"]=$function;
						else {
							error("999","Error Updating/Inserting Report Detail Body Part Test Record $rdbtrtname <br />$query<br />".mysqli_error($dbhandle));
							unset($detail_bodypart_result["$rdbtid"]);
						}
					}
				}
				}
			}
		}
		if(errorcount()==0) 
			notify("000","Report $rhid saved successfully. $button");
	}
}

function insertInjuryTemplate($report) {
	$values=array();
	$fields=array();
	$sets=array();
	$values['ritinactive']='0';
	$values['ritrtid']=$report['header']['rhrtid'];
	$values['ritname']=$report['header']['rhritname'];
	$values['ritdescription']=$report['header']['rhritdescription'];
	$values['ritdispseq']='10';
	$auditfields=getauditfields();
	$values['crtdate']=$auditfields['date'];
	$values['crtuser']=$auditfields['user'];
	$values['crtprog']='createInjuryTemplate(ritrtid,ritname,ritdescription)';
	$fields=array_keys($values);

	foreach($fields as $index=>$field) 
		$sets[]="$field='".mysqli_real_escape_string($dbhandle,$values["$field"])."'";
	$set="SET ".implode(',',$sets);
	$insertquery="INSERT INTO report_injury_templates $set";
	if($insertresult=mysqli_query($dbhandle,$insertquery))
		return(mysql_insert_id());
	else
		return(FALSE);
}

function insertInjuryTemplateDefaults($ritid, $report) {
	$header=$report['header'];
	$auditfields=getauditfields();
	$saveheader=array('rhcasetypecode','rhtherapytypecode','rhsubjectivenote','rhworking','rhoccup','rhjobrequirement','rhspecificinjury','rhchiefcomplaint','rhpainrating','rhfunctionalactivity','rhmedicalhistory','rhsurgeries','rhmedications','rhdiagnostictests','rhobjectivenote','rhassessmentnote','rhassessment','rhprognosis','rhtreatmentplannote','rhshortgoals','rhlonggoals','rhtreatmentplan','rhritdescription','rhinjuriesarray');
	foreach($header as $field=>$value) {
		if(!empty($value)) {
			if(in_array($field,$saveheader)) {
				$fields=array();
				$values=array();
				$sets=array();
				$values['ritdsection']='header';
				$values['ritdritid']=$ritid;
				$values['ritdfield']=$field;
				if(is_array($value))
					$values['ritdvalue']=serialize($value);
				else
					$values['ritdvalue']=$value;
				$values['crtdate']=$auditfields['date'];
				$values['crtuser']=$auditfields['user'];
				$values['crtprog']='insertInjuryTemplateDefaults(ritid,report)';
	
				$fields=array_keys($values);
				foreach($fields as $index=>$field) 
					$sets[]="$field='".mysqli_real_escape_string($dbhandle,$values["$field"])."'";
				$set="SET ".implode(',',$sets);
				$insertquery="INSERT INTO report_injury_templates_defaults $set";
				if(!mysqli_query($dbhandle,$insertquery)) {
					dump('insertquery',$insertquery);
					echo 'mysql says:'.mysqli_error($dbhandle).'<br>';
				}
			}
		}
	}

	$bodyparts=$report['detail_bodypart'];
	foreach($bodyparts as $field=>$value) {
		$fields=array();
		$values=array();
		$sets=array();
		$values['ritdsection']='detail_bodypart';
		$values['ritdritid']=$ritid;
		$values['ritdfield']=$field;
		if(is_array($value))
			$values['ritdvalue']=serialize($value);
		else
			$values['ritdvalue']=$value;
		$values['crtdate']=$auditfields['date'];
		$values['crtuser']=$auditfields['user'];
		$values['crtprog']='insertInjuryTemplateDefaults(ritid,report)';
		$fields=array_keys($values);
		foreach($fields as $index=>$field) 
			$sets[]="$field='".mysqli_real_escape_string($dbhandle,$values["$field"])."'";
		$set="SET ".implode(',',$sets);
		$insertquery="INSERT INTO report_injury_templates_defaults $set";
		if(!mysqli_query($dbhandle,$insertquery)) {
			dump('insertquery',$insertquery);
			echo 'mysql says:'.mysqli_error($dbhandle).'<br>';
		}
	}

	$groups=$report['detail_bodypart_test_group'];
	foreach($groups as $field=>$value) {
		$fields=array();
		$values=array();
		$sets=array();
		$values['ritdsection']='detail_bodypart_test_group';
		$values['ritdritid']=$ritid;
		$values['ritdfield']=$field;
		if(is_array($value))
			$values['ritdvalue']=serialize($value);
		else
			$values['ritdvalue']=$value;
		$values['crtdate']=$auditfields['date'];
		$values['crtuser']=$auditfields['user'];
		$values['crtprog']='insertInjuryTemplateDefaults(ritid,report)';
		$fields=array_keys($values);
		foreach($fields as $index=>$field) 
			$sets[]="$field='".mysqli_real_escape_string($dbhandle,$values["$field"])."'";
		$set="SET ".implode(',',$sets);
		$insertquery="INSERT INTO report_injury_templates_defaults $set";
		if(!mysqli_query($dbhandle,$insertquery)) {
			dump('insertquery',$insertquery);
			echo 'mysql says:'.mysqli_error($dbhandle).'<br>';
		}
	}

	$tests=$report['detail_bodypart_test'];
	foreach($tests as $field=>$value) {

		$result=array(
					'rrom1'=>$value['rrom1'],
					'rmmt1'=>$value['rmmt1'],
					'lrom1'=>$value['lrom1'],
					'lmmt1'=>$value['lmmt1']
				);
		$value['rdbtresult1']=$result;
			unset($value['rrom1']);
			unset($value['rmmt1']);
			unset($value['lrom1']);
			unset($value['lmmt1']);
		$result=array(
					'rrom2'=>$value['rrom2'],
					'rmmt2'=>$value['rmmt2'],
					'lrom2'=>$value['lrom2'],
					'lmmt2'=>$value['lmmt2']
				);
		$value['rdbtresult2']=$result;
			unset($value['rrom2']);
			unset($value['rmmt2']);
			unset($value['lrom2']);
			unset($value['lmmt2']);

		$fields=array();
		$values=array();
		$sets=array();

		$values['ritdsection']='detail_bodypart_test';
		$values['ritdritid']=$ritid;
		$values['ritdfield']=$field;
		if(is_array($value))
			$values['ritdvalue']=serialize($value);
		else
			$values['ritdvalue']=mysqli_real_escape_string($dbhandle,$value);
		$values['crtdate']=$auditfields['date'];
		$values['crtuser']=$auditfields['user'];
		$values['crtprog']='insertInjuryTemplateDefaults(ritid,report)';
		$fields=array_keys($values);
		foreach($fields as $index=>$field) 
			$sets[]="$field='".$values["$field"]."'";
		$set="SET ".implode(',',$sets);
		$insertquery="INSERT INTO report_injury_templates_defaults $set";
		if(!$result=mysqli_query($dbhandle,$insertquery)) {
			dump('insertquery',$insertquery);
			echo 'mysql says:'.mysqli_error($dbhandle).'<br>';
		}
	}
}

function insertReportAccessInjuryTemplate($ritid, $report) {
	$values=array();
	$fields=array();
	$sets=array();
	$values['rait_rtid']=mysqli_real_escape_string($dbhandle,$report['header']['rhrtid']);
	$values['rait_ritid']=mysqli_real_escape_string($dbhandle,$ritid);
	if(empty($report['header']['rhbumcode']))
		$report['header']['rhbumcode']='WS';
	if(empty($report['header']['rhpgmcode']))
		$report['header']['rhpgmcode']='WS';
	$values['rait_bumcode']=mysqli_real_escape_string($dbhandle,$report['header']['rhbumcode']);
	$values['rait_pgmcode']=mysqli_real_escape_string($dbhandle,$report['header']['rhpgmcode']);
	$values['rait_cnum']=mysqli_real_escape_string($dbhandle,$report['header']['rhcnum']);
	$values['rait_dispseq']='10';
	$auditfields=getauditfields();
	$values['rait_user']=mysqli_real_escape_string($dbhandle,$auditfields['user']);
	$values['crtdate']=mysqli_real_escape_string($dbhandle,$auditfields['date']);
	$values['crtuser']=mysqli_real_escape_string($dbhandle,$auditfields['user']);
	$values['crtprog']='insertReportAccessInjuryTemplate(report)';
	$fields=array_keys($values);
	foreach($fields as $index=>$field) 
		$sets[]="$field='".$values["$field"]."'";
	$set="SET ".implode(',',$sets);
	$insertquery="INSERT INTO report_access_injury_template $set";
	if($insertresult=mysqli_query($dbhandle,$insertquery))
		return(mysql_insert_id());
	else
		return(FALSE);
}

function saveAsTemplate($button, $id) {
// SHOULD ALREADY HAVE THIS - Write report_templates record
// SHOULD ALREADY HAVE THIS ACCESS TO TEMPLATE - Write report_access_template - allow this user to use this template
//dumppost();
// Write report_injury_templates - Add this injury_template
if($ritid=insertInjuryTemplate($_POST['report'])) {
// Write report_injury_templates_defaults - Add each section default values
	insertInjuryTemplateDefaults($ritid, $_POST['report']);
// Write report_access_injury_template - allow this user to use this injury_template
	insertReportAccessInjuryTemplate($ritid, $_POST['report']);
}
$_SESSION['id']=$id;
$_SESSION['button']='Edit';
return(TRUE);
}

function getCaseOptions($rhid) {
	$selectquery = "
		SELECT rhlname, rhfname, rhcnum FROM report_header where rhid='$rhid' LIMIT 1
	";
	if($selectresult = mysqli_query($dbhandle,$selectquery)) {
		if($selectrow = mysqli_fetch_assoc($selectresult)) {
			$linitial=substr($selectrow['rhlname'],0,1);
			$finitial=substr($selectrow['rhfname'],0,1);
			$cnum=$selectrow['rhcnum'];

			if(!empty($linitial) and !empty($finitial) and !empty($cnum) ) {
				$where=array();
				$where[]="crpnum<>''";
				$where[]="crlname LIKE '$linitial%'";
				$where[]="crfname LIKE '$finitial%'";
				$where[]="crcnum='$cnum'";
				if(count($where)>0)
					$wheresql="WHERE ".implode(" and ", $where);
				$query = "
					SELECT crid, crcnum, crpnum, crlname, crfname, crssn, crinjurydate, crdob, crsex, crtherapytypecode FROM cases $wheresql LIMIT 100
				";
				$thislist=array();
				if($result = mysqli_query($dbhandle,$query)) {
					while ($row = mysqli_fetch_assoc($result)) {
						$crid=$row['crid'];
						$thisarray=array();
						$thisarray['crid']=$crid;
						$thisarray['crcnum']=$row['crcnum'];
						$thisarray['crpnum']=$row['crpnum'];
						$thisarray['crlname']=$row['crlname'];
						$thisarray['crfname']=$row['crfname'];
						$thisarray['crssn']=displaySSN($row['crssn']);
						$thisarray['crinjurydate']=displayDate($row['crinjurydate']);
						$thisarray['crdob']=displayDate($row['crdob']);
						$thisarray['crsex']=$row['crsex'];
						$thisarray['crtherapytypecode']=$row['crtherapytypecode'];
						$thislist["$crid"]=$thisarray;
					}
					return($thislist);
				}
				else {
					error("001",mysqli_error($dbhandle));
					return(false);
			
				}
			}
			else {
				error("999","No first initial, last initial, or clinic in report. $linitial : $finitial : $cnum");
				return(false);
			}
		}
	}
}

function assignReport($rhid) {
// Display dropdown of all cases for Report to be assigned to
	if($caseOptions=getCaseOptions($rhid)) {
?>
<div style="width:auto;" class="containedBox">
	<fieldset>
	<legend style="font-size:large">Select case:</legend>
	<form name="assignReport" method="post">
		<div>
			<select name="crid" id="crid" />
				<?php echo getSelectOptions($arrayofarrayitems=$caseOptions, $optionvaluefield='crid', $arrayofoptionfields=array('crlname'=>', ', 'crfname'=>' PAT:', 'crpnum'=>' SSN:', 'crssn'=>' DOB:', 'crdob'=>' DOI:', 'crinjurydate'=>' Therapy:', 'crtherapytypecode'=>' ' ), $defaultoption=NULL, $addblankoption=TRUE, $arraykey=NULL, $arrayofmatchvalues=NULL); ?>
			</select>
		</div>
		<div style="float:left">
			<input name="button['0']" type="submit" value="Cancel">
		</div>
		<div style="float:left;">
			<input name="button[<?php echo $rhid; ?>]" type="submit" value="Assign Report to Selected Case">
		</div>
	</form>
	</fieldset>
</div>
<?php
	exit();
	}
	else {
		displaysitemessages();
		unset($_SESSION['button']);
		echo '<form name="assignReport" method="post"><input name="button[0]" type="submit" value="Cancel"></form>';
		exit();
	}
}
?>