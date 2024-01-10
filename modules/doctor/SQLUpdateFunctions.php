<?php
function doctorupdateinactivate($id) {
	if(isset($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(66);
		errorclear();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query1 = "UPDATE doctors SET dminactive = NOT dminactive WHERE dmid='" . $id . "'";
		$result1 = mysqli_query($dbhandle,$query1);
		if($result1)
			$_SESSION['notify'][] = $numRows . "Record(s) $id Updated.";
		else
			error("001", mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("002", "Error: Missing Record Id.");
}

function locationupdateinactivate($id) {
	if(isset($id)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(66);
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query1 = "UPDATE doctor_locations SET dlinactive = NOT dlinactive WHERE dlid='" . $id . "'";
		$result1 = mysqli_query($dbhandle,$query1);
		if($result1)
			$_SESSION['notify'][] = $numRows . "Record(s) $id Updated.";
		else
			error("001", mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("002", "Error: Missing Record Id.");
}

function selectdoctorfromdropdownarray($selecteddoctors) {

}

function mergeselecteddoctors($selecteddoctors, $todoctor) {
	if(count($selecteddoctors)>1 ) {
		if(!empty($todoctor)) {
			$merged=0;
			$tomerge=0;
			foreach($selecteddoctors as $key=>$val) {
				if($val != $todoctor) {
					$tomerge++;
					if(mergedoctor($val, $todoctor))
						$merged++;
				}
			}
			notify("000","$merged/$tomerge doctors merged into one.");
		}
		else
			error("000","Please be sure to select a 'merge into doctor' before clicking 'Confirm Merge Selected Doctors'. Merge operation not performed.");
	}
	else
		error("000","Please select 2 or more doctors before clicking 'Merge Selected Doctors'. Merge operation not performed.");
}

function mergedoctor($fromdmid, $todmid) {
	if(!empty($fromdmid) && !empty($todmid)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(66);
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$auditfields = getauditfields();
		$upduser = mysqli_real_escape_string($dbhandle,$auditfields['user']);
		$upddate = mysqli_real_escape_string($dbhandle,$auditfields['date']);
		$updprog = mysqli_real_escape_string($dbhandle,$auditfields['prog']);
		$updateerror=array();
// Update Cases with fromdmid to be todmid
		$casesquery = "UPDATE cases SET crrefdmid='$todmid', upduser='$upduser', upddate='$upddate', updprog='olddmid:$fromdmid' WHERE crrefdmid='$fromdmid'";
		if($casesresult = mysqli_query($dbhandle,$casesquery))
			notify("000","Existing cases with referring doctor $fromdmid have been reassigned to doctor $todmid.");
		else {
			$updateerror[]="cases";
			error("001", mysqli_error($dbhandle));
		}

// Update Case Prescriptions with fromdmid to be todmid
		$caseprescriptionsquery = "UPDATE case_prescriptions SET cpdmid='$todmid', upduser='$upduser', upddate='$upddate', updprog='olddmid:$fromdmid' WHERE cpdmid='$fromdmid'";
		if($caseprescriptionsresult = mysqli_query($dbhandle,$caseprescriptionsquery))
			notify("000","Existing case prescriptions with referring doctor $fromdmid have been reassigned to doctor $todmid.");
		else {
			$updateerror[]="case prescriptions";
			error("002", mysqli_error($dbhandle));
		}

// Update Doctor Relationships with fromdmid to be todmid
		$doctorrelationshipsquery = "UPDATE doctor_relationships SET drdmid='$todmid', upduser='$upduser', upddate='$upddate', updprog='olddmid:$fromdmid' WHERE drdmid='$fromdmid'";
		if($doctorrelationshipsresult = mysqli_query($dbhandle,$doctorrelationshipsquery))
			notify("000","Existing doctor relationships with referring doctor $fromdmid have been reassigned to doctor $todmid.");
		else
			notify("001","Duplicates were found. Existing doctor relationships with referring doctor $fromdmid have NOT been reassigned to doctor $todmid.");

// Update Doctors with fromdmid
		$doctorsquery = "UPDATE doctors SET dminactive='1', upduser='$upduser', upddate='$upddate', updprog='newdmid:$todmid' WHERE dmid='$fromdmid'";
		if($doctorsresult = mysqli_query($dbhandle,$doctorsquery))
			notify("000","Referring doctor $fromdmid has been inactivated.");
		else {
			$updateerror[]="doctors";
			error("004", mysqli_error($dbhandle));
		}

		mysqli_close($dbhandle);
		if(count($updateerror)==0)
			return(true);
		else {
			$updateerrors=implode(", ", $updateerror);
			error("999", "Error updating the following tables from $fromdmid to $todmid: $updateerrors");
			return(false);
		}
	}
	else
		error("003", "Error: Missing from or to id. ($fromdmid & $todmid)");
	return(false);
}

function mergeselecteddoctorlocations($dmid, $selected, $toid) {
	if(count($selected)>1 ) {
		if(!empty($toid)) {
			if(!empty($dmid)) {
				$merged=0;
				$tomerge=0;
				foreach($selected as $key=>$val) {
					if($val != $toid) {
						$tomerge++;
						if(mergedoctorlocation($dmid, $val, $toid)) {
							$merged++;
							$locations++;
							if(relationshipDelete($dmid, $val))
								$deletedlocations++;
						}
					}

				}
				notify("000","$merged/$tomerge doctor locations merged into one and $deletedlocations/$locations relationships removed.");
			}
			else
				error("000","Please be sure to select a doctor before clicking 'Confirm Merge Selected Doctors Location'. Merge operation not performed.");
		}
		else
			error("000","Please be sure to select a 'merge into doctor location' before clicking 'Confirm Merge Selected Doctors Location'. Merge operation not performed.");
	}
	else
		error("000","Please select 2 or more doctor locations before clicking 'Merge Selected Doctor Locations'. Merge operation not performed.");
}

function locationMergeUndoForm($dmid) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
	securitylevel(66);
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	

// Load this doctor
	$doctor="??? UNKNOWN ERROR ???";
	$doctorselect="select dminactive, dmlname, dmfname FROM doctors where dmid='$dmid'";
	if($doctorresult=mysqli_query($dbhandle,$doctorselect)) {
		if($doctorrow=mysqli_fetch_assoc($doctorresult))
			$doctor=$doctorrow['dmlname'].', '.$doctorrow['dmfname'];
		if($doctorrow['dminactive'])
			$doctor.=' *** INACTIVE ***';
	}
// Load all locations
	$locationsselect="select dlinactive, dlid, dlname, dlphone, dlfax, dladdress, dlcity, dlstate, dlzip FROM doctor_locations";
	if($locationsresult=mysqli_query($dbhandle,$locationsselect)) {
		while($locationsrow=mysqli_fetch_assoc($locationsresult)) {
			$dlid=$locationsrow['dlid'];
			$location["$dlid"]=$locationsrow;
		}
	}
// Way to select doctor locations to undo
	$user=getuser();
	$select = "SELECT crrefdmid, upduser, crrefdlid, crrefdlsid, updprog, upddate, count(*) casecount FROM cases where crrefdmid='$dmid' and upduser='$user' and updprog LIKE '%olddlid%' GROUP BY crrefdmid, crrefdlid, crrefdlsid, upduser, updprog, upddate ORDER BY upduser, upddate DESC, updprog";
	if($result=mysqli_query($dbhandle,$select)) {
		echo('<div style="clear:both">');
		echo('<form name="undoLocationMerge" method="post">');
		echo('<fieldset><legend style="font-size:large;">Undo Location Merge by '.$user.' of cases and prescriptions for '.$doctor.'</legend>');
		echo('<table>');
		echo('<th>&nbsp;</th><th>Current Id</th><th>Current Name</th><th>Previous Id</th><th>Previous Name</th><th>Merged Date</th><th>Case Count</th>');
		while($row=mysqli_fetch_assoc($result)) {
//			$undoables[]=$row;
			$olddlid=str_replace('olddlid:','',$row['updprog']);
			$oldname=$location["$olddlid"]['dlname'];
			$currentdlid=$row["crrefdlid"];
			$currentname=$location["$currentdlid"]['dlname'];
			echo('
<tr>
	<td><input name="checkbox['.$currentdlid.':'.$olddlid.']" type="checkbox"></td>
	<td align="right">'.$currentdlid.'</td>
	<td>'.$currentname.'</td>
	<td align="right">'.$olddlid.'</td>
	<td>'.$oldname.'</td>
	<td>'.$row['upddate'].'</td>
	<td align="right">'.$row['casecount'].'</td>
</tr>
');
		}
		echo('<tr><td colspan="7">
		<div style="float:left;"><input name="button[]" type="submit" value="Cancel"></div>
		<div style="float:right;"><input name="button['.$dmid.']" type="submit" value="Confirm UNDO Merge Selected Doctor Locations"></div>
		</td></tr>');
// Display dropdown of undoables - get the dmid, crrefdlid, parse the olddlid
//		echo('<select name="olddlid" id="olddlid">');
//		echo getSelectOptions($arrayofarrayitems=$undoables, $optionvaluefield='updprog', $arrayofoptionfields=array('upddate'=>':', 'upduser'=>' updated location', 'crrefdlid'=>' to ', 'updprog'=>' cases:', 'casecount'=>''), $defaultoption=NULL, $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array());
//		echo('</select>');
		echo('</table></fieldset></form></div>');
	}
	else
		error("999","Error selecting undo's for $user and doctor $dmid. Undo operation not performed.<br>$select<br>".mysqli_error($dbhandle));
	mysqli_close($dbhandle);
}

function mergeselecteddoctorlocationsUndo($dmid, $selected) {
	if(count($selected)>0 ) {
		if(!empty($dmid)) {
			$merged=0;
			$tomerge=0;
			$locations=0;
			$createdlocations=0;
			foreach($selected as $key=>$val) { // $dmid is doctor, $key is the old number to restore, $val is "on"
				list($current,$old)=split(":",$key);
				$tomerge++;
				if(mergedoctorlocation($dmid, $current, $old, true)) {
					$merged++;
					$locations++;
					if(relationshipCreate($dmid, $old))
						$createdlocations++;
				}
			}
			notify("000","$merged/$tomerge doctor locations UnMerged from one and $createdlocations/$locations relationships created.");
		}
		else
			error("999","Please be sure to select a doctor before clicking 'Confirm UNDO Merge Selected Doctors Location'. UNDO Merge operation not performed.");
	}
	else
		error("999","Please select 1 or more doctor locations before clicking 'Confirm UNDO Merge Selected Doctor Locations'. UNDO Merge operation not performed.");
}

function mergedoctorlocation($dmid, $fromid, $toid, $undo=NULL) {
	if(!empty($dmid)) {
		if(!empty($fromid) && !empty($toid)) {
			require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
			securitylevel(66);
			require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
			$dbhandle = dbconnect();
			
			$auditfields = getauditfields();
			$upduser = mysqli_real_escape_string($dbhandle,$auditfields['user']);
			$upddate = mysqli_real_escape_string($dbhandle,$auditfields['date']);
			$updateerror=array();
			if($undo) {
				$updprog = 'mergedoctorlocationUndo';
				$undowhere = "and updprog = 'olddlid:$toid'";
			}
			else {
				$updprog="olddlid:$fromid";
				$undowhere = "";
			}

	// Update Cases FOR THIS DOCTOR ONLY
			$casesquery = "UPDATE cases SET crrefdlid='$toid', upduser='$upduser', upddate='$upddate', updprog='$updprog' WHERE crrefdmid='$dmid' and crrefdlid='$fromid' $undowhere";
//dump("casesquery",$casesquery);
			if($casesresult = mysqli_query($dbhandle,$casesquery))
				notify("000","Existing cases with referring doctor $dmid location $fromid have been reassigned to doctor location $toid.");
			else {
				$updateerror[]="cases";
				error("001", mysqli_error($dbhandle));
			}

	// Update Case Prescriptions FOR THIS DOCTOR ONLY
			$caseprescriptionsquery = "UPDATE case_prescriptions SET cpdlid='$toid', upduser='$upduser', upddate='$upddate', updprog='$updprog' WHERE cpdmid='$dmid' and cpdlid='$fromid' $undowhere";
//dump("caseprescriptionsquery",$caseprescriptionsquery);
			if($caseprescriptionsresult = mysqli_query($dbhandle,$caseprescriptionsquery))
				notify("000","Existing case prescriptions with referring doctor $dmid location $fromid have been reassigned to doctor location $toid.");
			else {
				$updateerror[]="case prescriptions";
				error("002", mysqli_error($dbhandle));
			}

	// Update Doctor Relationships : REMOVE THE RELATIONSHIP FOR THIS DOCTOR
//			$doctorrelationshipsquery = "UPDATE doctor_relationships SET drdlid='$toid', upduser='$upduser', upddate='$upddate', updprog='olddlid:$fromid' WHERE drdmid='$dmid' and drdlid='$fromid'";
//			if($doctorrelationshipsresult = mysqli_query($dbhandle,$doctorrelationshipsquery))
//				notify("000","Existing doctor relationships with referring doctor $dmid location $fromid have been removed.");
//			else
//				notify("001","ERROR removing doctor location relationships for doctor $dmid for location $fromid");

// Do not inactivate the doctor location as other doctors may be linked to the location
	// Update Doctor Locations
//			$doctorlocationsquery = "UPDATE doctor_locations SET dlinactive='1', upduser='$upduser', upddate='$upddate', updprog='newdlid:$toid' WHERE dlid='$fromid'";
//			if($doctorlocationsresult = mysqli_query($dbhandle,$doctorlocationsquery))
//				notify("000","Referring doctor location $fromid has been inactivated.");
//			else {
//				$updateerror[]="doctors locations";
//				error("004", mysqli_error($dbhandle));
//			}

			mysqli_close($dbhandle);
			if(count($updateerror)==0)
				return(true);
			else {
				$updateerrors=implode(", ", $updateerror);
				error("999", "Error updating the following tables from $fromid to $toid: $updateerrors");
				return(false);
			}
		}
		else
			error("003", "Error: Missing from or to id. ($fromid & $toid)");
	}
	else
		error("005", "Error: Missing Doctor id. ($dmid)");
	return(false);
}

function locationupdatemerge($fromlocid, $tolocid) {
	if(!empty($fromlocid) && !empty($tolocid)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(66);
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$auditfields = getauditfields();
		$upduser = mysqli_real_escape_string($dbhandle,$auditfields['user']);
		$upddate = mysqli_real_escape_string($dbhandle,$auditfields['date']);
		$updprog = mysqli_real_escape_string($dbhandle,$auditfields['prog']);
		$query1 = "UPDATE doctor_locations SET dlinactive = '1', upduser='$upduser', upddate='$upddate', updprog='locationupdatemerge' WHERE dlid='$fromlocid'";
//		dump("query1",$query1);
		if($result1 = mysqli_query($dbhandle,$query1)) {
			$_SESSION['notify'][] = "Old Location $fromlocid Inactivated.";
			$query2 = "UPDATE cases SET crrefdlid = '$tolocid', upduser='$upduser', upddate='$upddate', updprog='locationupdatemerge' WHERE crrefdlid = '$fromlocid'";
//			dump("query2",$query2);
			if($result2 = mysqli_query($dbhandle,$query2)) {
//				$numRows2 = mysqli_num_rows($result2);
				$_SESSION['notify'][] = "Cases updated from location $fromlocid to location $tolocid.";
			}
			else
				error("001", mysqli_error($dbhandle));
		}
		else
			error("002", mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("003", "Error: Missing from or to id. ($fromlocid & $tolocid)");
}

function relationshipCreate($dm, $dl) {
	$returnvalue=false;
	$dmid=$dm;
	$dlid=$dl;
	if(!empty($dmid) && !empty($dlid)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(66);
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query0 = "
			SELECT drdmid FROM doctor_relationships
			WHERE drdmid='$dmid' and drdlid='$dlid'
			";
		if($result0 = mysqli_query($dbhandle,$query0)) {
			if(mysqli_num_rows($result0) > 0)
				notify("000","Doctor $dmid was already related to location $dlid. Relationship not added.");
			else {
				$auditfields = getauditfields();
				$upduser = mysqli_real_escape_string($dbhandle,$auditfields['user']);
				$upddate = mysqli_real_escape_string($dbhandle,$auditfields['date']);
				$updprog = mysqli_real_escape_string($dbhandle,$auditfields['prog']);
				$query1 = "
					INSERT INTO doctor_relationships
					SET drdmid = '$dmid', drdlid='$dlid', upduser='$upduser', upddate='$upddate', updprog='relationshipCreate'
					";
		//		dump("query1",$query1);
				if($result1 = mysqli_query($dbhandle,$query1)) {
					notify("000", "Doctor $dmid added relationship to Location $dlid.");
					$returnvalue=true;
				}
				else
					error("002", mysqli_error($dbhandle));
			}
		}
		mysqli_close($dbhandle);
	}
	else
		error("003", "Error: Missing Doctor or Location id. ($dmid & $dlid)");
	return($returnvalue);
}

function relationshipDelete($dmid, $dlid) {
	$returnvalue=false;
	if(!empty($dmid) && !empty($dlid)) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
		securitylevel(66);
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$auditfields = getauditfields();
		$deletequery = "
			DELETE FROM doctor_relationships
			WHERE drdmid='$dmid' and drdlid='$dlid'
		";
//		dump("query1",$query1);
		if($deleteresult = mysqli_query($dbhandle,$deletequery)) {
			notify("000", "Relationship between Doctor $dmid and Location $dlid removed.");
			$returnvalue=true;
		}
		else {
			error("002", "Error removing relationship between Doctor $dmid and Location $dlid.<br>$deletequery<br>".mysqli_error($dbhandle));
		}
		mysqli_close($dbhandle);
	}
	else
		error("003", "Error: Missing Doctor or Location id. ($dmid & $dlid)");

	return($returnvalue);
}

function updateTerritory($territory) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
    $dbhandle = dbconnect();
    

    foreach($territory as $locid => $terrid) {
        $sql = "UPDATE doctor_locations SET dlterritory = $terrid WHERE dlid=$locid";
        mysqli_query($dbhandle,$sql);
    }
}

?>