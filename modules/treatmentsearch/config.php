<?php
function counttreatmentsortvalues() {
	return(count($_SESSION['treatmentsearch']['sort']));
}

function cleartreatmentsortvalues() {
	$_POST['sortfields']=array();
	unset($_SESSION['treatmentsearch']['sort']);
}

function gettreatmentsortvalues() {
	$sortarray = $_SESSION['treatmentsearch']['sort'];
	if(count($sortarray) > 0) {
		$_POST['sortfields'] = $sortarray;
	}
}

function puttreatmentsortvalues() {
	$sortarray = $_POST['sortfields'];
	if(count($sortarray) > 0) 
		$_SESSION['treatmentsearch']['sort'] = $sortarray;
	else
		unset($_SESSION['treatmentsearch']['sort']);
}

function counttreatmentsearchvalues() {
	return(count($_SESSION['treatmentsearch']['search']));
}

function cleartreatmentsearchvalues() {
	foreach($_POST as $key=>$value) {
		if(substr($key,0,6) == 'search') 
			unset($_POST[$key]);
	}
	unset($_SESSION['treatmentsearch']['search']);
}

function gettreatmentsearchvalues() {
	$searcharray=array();
	if(!empty($_SESSION['treatmentsearch']['search'])) {
		$searcharray = $_SESSION['treatmentsearch']['search'];
		foreach($searcharray as $key=>$value) {
			$_POST[$key]=$value;
		}
	}
}

function puttreatmentsearchvalues() {
	foreach($_POST as $key=>$value) {
		if(substr($key,0,6) == 'search') 
			$searcharray[$key]=$value;
	}
	if(count($searcharray) > 0) 
		$_SESSION['treatmentsearch']['search'] = $searcharray;
	else
		unset($_SESSION['treatmentsearch']['search']);
}

function getcasetypes() {
	if(!isset($_SESSION['casetypes']) || (isset($_SESSION['casetypes']) && (count($_SESSION['casetypes'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT ctmcode, ctmdescription FROM master_casetypes ";
		if(!userisadmin())
			$query .= "WHERE ctminactive = 0 ";
		$query .= "ORDER BY ctmdescription ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);

		$casetypesarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$casetypesarray[$result['ctmcode']] = $result['ctmdescription'];
		}
		return($casetypesarray);
	}
	else
		return($_SESSION['casetypes']);
}

function getclinics() {
	if(!isset($_SESSION['clinics']) || (isset($_SESSION['clinics']) && (count($_SESSION['clinics'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT cmcnum, cmname FROM master_clinics ";
		if(isuserlevel(20))
			$query .= "WHERE cminactive = 0 ";
		else
			$query .= "WHERE cminactive = 0 and cmcnum='" . getuserclinic() . "' ";
		$query .= "ORDER BY cmname ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$clinicsarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$clinicsarray[$result['cmcnum']] = $result['cmname'];
		}
		return($clinicsarray);
	}
	else
		return($_SESSION['clinics']);
}

function getgroups() {
	if(!isset($_SESSION['groups']) || (isset($_SESSION['groups']) && (count($_SESSION['groups'])==0))) {
		$groupsarray=array();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT gmcode, gmdescription FROM master_groups ";
		if(!userisadmin())
			$query .= "WHERE gminactive = 0 ";
		$query .= "ORDER BY gmseq, gmdescription ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$groupsarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$groupsarray[$result['gmcode']] = $result['gmdescription'];
		}
		return($groupsarray);
	}
	else
		return($_SESSION['groups']);
}

function getmodalities() {
	if(!isset($_SESSION['modalities']) || (isset($_SESSION['modalities']) && (count($_SESSION['modalities'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT mmcode, mmdescription FROM master_modalities ";
		if(!userisadmin())
			$query .= "WHERE mminactive = 0 ";
		$query .= "ORDER BY mmdescription ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$groupsarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$modalitiesarray[$result['mmcode']] = $result['mmdescription'];
		}
		return($modalitiesarray);
	}
	else
		return($_SESSION['modalities']);
}

function getsubmitstatus() {
	if(!isset($_SESSION['submitstatus']) || (isset($_SESSION['submitstatus']) && (count($_SESSION['submitstatus'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT ssmcode, ssmdescription FROM master_submitstatus ";
		if(!userisadmin())
			$query .= "WHERE ssminactive = 0 ";
		$query .= "ORDER BY ssmdescription ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$submitstatusarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$submitstatusarray[$result['ssmcode']] = $result['ssmdescription'];
		}
		return($submitstatusarray);
	}
	else
		return($_SESSION['submitstatus']);
}

function gettreatmenttypes() {
	if(!isset($_SESSION['treatmenttypes']) || (isset($_SESSION['treatmenttypes']) && (count($_SESSION['treatmenttypes'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT ttmcode, ttmdescription FROM master_treatmenttypes ";
		if(!userisadmin())
			$query .= "WHERE ttminactive = 0 ";
		$query .= "ORDER BY ttmdescription ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$treatmenttypesarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$treatmenttypesarray[$result['ttmcode']] = $result['ttmdescription'];
		}
		return($treatmenttypesarray);
	}
	else
		return($_SESSION['treatmenttypes']);
}

function getvisittypes() {
	if(!isset($_SESSION['visittypes']) || (isset($_SESSION['visittypes']) && (count($_SESSION['visittypes'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT vtmcode, vtmdescription FROM master_visittypes ";
		if(!userisadmin())
			$query .= "WHERE vtminactive = 0 ";
		$query .= "ORDER BY vtmdescription ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$visittypesarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$visittypesarray[$result['vtmcode']] = $result['vtmdescription'];
		}
		return($visittypesarray);
	}
	else
		return($_SESSION['visittypes']);
}

function getindividualprocedures($treatmenttype) {
	if(!isset($_SESSION['individualprocedures']["$treatmenttype"]) || (isset($_SESSION['individualprocedures']["$treatmenttype"]) && (count($_SESSION['individualprocedures']["$treatmenttype"])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT a.pmcode, c.pmdescription
					FROM treatmenttype_procedures a
					LEFT JOIN master_treatmenttypes b ON a.ttmcode = b.ttmcode
					LEFT JOIN master_procedures c ON a.pmcode = c.pmcode
					WHERE b.ttmcode = '" . $treatmenttype . "' ";
		$query .= "ORDER BY c.pmdescription ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$individualproceduresarray=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$individualproceduresarray[$result['pmcode']] = $result['pmdescription'];
		}
		return($individualproceduresarray);
	}
	else {
		return($_SESSION['individualprocedures']["$treatmenttype"]);
	}
}

function gettreatmenttypeprocedures($treatmenttype) {
	if(!isset($_SESSION['treatmenttypeprocedures']) || (isset($_SESSION['treatmenttypeprocedures']) && (count($_SESSION['treatmenttypeprocedures'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT pg.gmcode, g.gmdescription 
		FROM treatmenttype_procedure_groups pg 
		LEFT JOIN master_treatmenttypes tt ON pg.ttmcode=tt.ttmcode 
		LEFT JOIN master_groups g ON pg.gmcode=g.gmcode 
		WHERE tt.ttmcode='" . $treatmenttype . "' ";
		if(!userisadmin())
			$query .= "AND tt.ttminactive=0 AND g.gminactive=0 ";
		$query .= "ORDER BY g.gmseq, g.gmdescription ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$treatmenttypeprocedures=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$treatmenttypeprocedures[$result['gmcode']] = $result['gmdescription'];
		}
		return($treatmenttypeprocedures);
	}
	else
		return($_SESSION['treatmenttypeprocedures']);
}

function gettreatmenttypemodalities($treatmenttype) {
	if(!isset($_SESSION['treatmenttypemodalities']) || (isset($_SESSION['treatmenttypemodalities']) && (count($_SESSION['treatmenttypemodalities'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT mm.mmcode, mm.mmdescription 
		FROM treatmenttype_modalities m 
		LEFT JOIN master_treatmenttypes tt ON m.ttmcode=tt.ttmcode 
		LEFT JOIN master_modalities mm ON m.mmcode=mm.mmcode 
		WHERE mm.mmtype='M' and tt.ttmcode='" . $treatmenttype . "' ";
		if(!userisadmin())
			$query .= "AND tt.ttminactive=0 AND mm.mminactive=0 ";
		$query .= "ORDER BY mm.mmdescription ";
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$treatmenttypemodalities=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			if($result) 
				$treatmenttypemodalities[$result['mmcode']] = $result['mmdescription'];
		}
		return($treatmenttypemodalities);
	}
	else
		return($_SESSION['treatmenttypemodalities']);
}

function getsupplymodalities() {
	if(!isset($_SESSION['supplymodalities']) || (isset($_SESSION['supplymodalities']) && (count($_SESSION['supplymodalities'])==0))) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		
		$query = "SELECT mm.mmcode, mm.mmdescription 
		FROM master_modalities mm 
		WHERE mm.mmtype='SM'";
		if(!userisadmin())
			$query .= " AND mm.mminactive=0 ";
		$query .= "ORDER BY mm.mmdescription ";
//dump("query",$query);
		$result_id = mysqli_query($dbhandle,$query);
		$numRows = mysqli_num_rows($result_id);
		$supplymodalities=array();
		for($i=1; $i<=$numRows; $i++) {
			$result = mysqli_fetch_assoc($result_id);
			if($result) 
				$supplymodalities[$result['mmcode']] = $result['mmdescription'];
		}
		return($supplymodalities);
	}
	else
		return($_SESSION['supplymodalities']);
}

function gettreatmentdata() {
	$_SESSION['visittypes'] = getvisittypes();
	$_SESSION['casetypes'] =  getcasetypes();
	$_SESSION['treatmenttypes'] = gettreatmenttypes();
unset($_SESSION['supplymodalities']);
	foreach($_SESSION['treatmenttypes'] as $key=>$val) {
		$_SESSION['individualprocedures']["$key"]=getindividualprocedures($key);
		$procedures[$key] = gettreatmenttypeprocedures($key);
		$modalities[$key] = gettreatmenttypemodalities($key);
		$supplymodalities[$key] = getsupplymodalities();
	}
	$_SESSION['procedures'] = $procedures;
	$_SESSION['modalities'] = $modalities;
	$_SESSION['supplymodalities'] = $supplymodalities;
	$_SESSION['clinics'] = getclinics();
}

function gettreatmentstatussummary() {
	$statusarray=array();
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query  = "
		SELECT thsbmstatus, ssmdescription, count(*) as cntstatus 
		FROM treatment_header
		LEFT JOIN master_submitstatus ON thsbmstatus = ssmcode
		WHERE thsbmstatus < 700
		GROUP BY thsbmstatus
		ORDER BY thsbmstatus
		";
	if($result = mysqli_query($dbhandle,$query)) {
		while($row = mysqli_fetch_assoc($result)) {
			$statusarray[] = '<td align="right">' . $row['cntstatus'] . "</td><td>" . strtoupper($row['ssmdescription']) . "</td><td>(" . strtoupper($row['thsbmstatus']) . ")</td>";
		}
	}
	return($statusarray);
}

gettreatmentdata();

$instructions=array();
$_SESSION['headerspace']="";

$instructions['default']= "<ul><u>Search Treatments Instructions</u>
	<li>Enter Search Criteria (at least one field is required).</li>
	<li>Click the Search Button.</li>
	<li>Up to 100 found entries will be displayed.</li>
	<li>For additional support, please contact Nancy at 714-236-7959</li>
</ul>";
$instructions['Edit'] = "<ul><u>Edit Treatment Instructions</u>
	<li>Please update all necessary information </li>
	<li>Click the UPDATE button to save and return to the Treatment List screen</li>
	<li>For additional support, please contact Nancy at 714-236-7959</li>
</ul>";

if(isset($instructions[$_SESSION['button']]))
	$_SESSION['headerspace'] = $instructions[$_SESSION['button']];
else
	$_SESSION['headerspace'] = $instructions['default'];;

//if(!isset($_POST['workingDate']) || empty($_POST['workingDate'])) 
//	$_POST['workingDate'] = date('m/d/Y');

//if(isset($_POST['workingDate'])) 
//	$_SESSION['workingDate'] = $_POST['workingDate'];

unset($statusarray);
if(userlevel()==23) {
	$statusarray = gettreatmentstatussummary();
	$_SESSION['headerspace'] = '<div style="float:left;">' . $_SESSION['headerspace'] . '</div>';
	$_SESSION['headerspace'] .= '<div style="float:left;"><table>
	<tr>
		<th colspan="3"><u>Treatment Reporting Queue Status:</u></th>
	</tr>
	<tr>' . implode("</tr>
	<tr>", $statusarray) . '</tr>
	</table></div>';
}

$functions['functions'] = array('add', 'update', 'delete', 'search');
$_SESSION['module']['treatment']=$functions;
$_SESSION['init']['treatment']=1;
?>