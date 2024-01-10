<?php
// Setup Page
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

function fetchprescription($id, $fields=NULL) {
	if(empty($fields))
		$fields='*';
	$query="
		SELECT $fields 
		FROM case_prescriptions 
		WHERE cpid='$id'
	";
	if($result=mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_assoc($result)) {
			return($row);
		}
	}
	return(false);
}
function fetchcase($id, $fields=NULL) {
	if(empty($fields))
		$fields='*';
	$query="
		SELECT $fields 
		FROM cases 
		WHERE crid='$id'
	";
	if($result=mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_assoc($result)) {
			return($row);
		}
	}
	return(false);
}
function fetchpatient($id, $fields=NULL) {
	if(empty($fields))
		$fields='*';
	$query="
		SELECT $fields 
		FROM patients 
		WHERE paid='$id'
	";
	if($result=mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_assoc($result)) {
			return($row);
		}
	}
	return(false);
}
function fetchemployer($id, $fields=NULL) {
	if(empty($fields))
		$fields='*';
	$query="
		SELECT $fields 
		FROM employers 
		WHERE emid='$id'
	";
	if($result=mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_assoc($result)) {
			return($row);
		}
	}
	return(false);
}
function fetchattorney($id, $fields=NULL) {
	if(empty($fields))
		$fields='*';
	$query="
		SELECT $fields 
		FROM attorneys 
		WHERE atid='$id'
	";
	if($result=mysqli_query($dbhandle,$query)) {
		if($row=mysqli_fetch_assoc($result)) {
			return($row);
		}
	}
	return(false);
}
function fetchtreatments($id, $fields=NULL) {
	if(empty($fields))
		$fields='*';
	$query="
		SELECT $fields 
		FROM treatment_header 
		WHERE thpnum='$id'
	";
	$rows=array();
	if($result=mysqli_query($dbhandle,$query)) {
		while($row=mysql_fetch_row($result)) {
			$rows[]=$row[0];
		}
		return($rows);
	}
	return(false);
}

function getCaseTypeDescription($ctmcode) {
	$select = "SELECT ctmcode, ctmdescription FROM master_casetypes WHERE ctmcode='$ctmcode'";
	if($result = mysqli_query($dbhandle,$select)) {
		if($row = mysqli_fetch_assoc($result)) {
			return(dbText($row['ctmdescription']));
		}
	}
	return(false);
}

function getTreatmentTypeDescription($ttmcode) {
	$select = "SELECT ttmcode, ttmdescription FROM master_treatmenttypes WHERE ttmcode='$ttmcode'";
	if($result = mysqli_query($dbhandle,$select)) {
		if($row = mysqli_fetch_assoc($result)) {
			return(dbText($row['ttmdescription']));
		}
	}
	return(false);
}
// The cpid is required!
if(empty($_POST['cpid'])) {
	if(!empty($_REQUEST['cpid']))
		$_POST['cpid']=$_REQUEST['cpid'];
	else {
		echo "OOPS.";
		exit();
	}
}
// If cancel close window...
if($_POST['Cancel']=='Cancel') {
	echo("<script>");
	echo("window.close();");
	echo("</script>");
}
// If reset back to database values...
if($_POST['Reset']=='Reset')
	unset($_POST['init']);

// Get Default Values for this case from database
if(empty($_POST['init'])) {
	$_POST['today']=today();
	$_POST['signedname']=getusername();
	$_POST['signedtitle']="Customer Service Representative";
	$cpid=$_POST['cpid'];
	if($prescription=fetchprescription($cpid, 'cpid, cpcrid, cpdate, cpttmcode, cpdmid, cpdlid, cptherap, cpcnum')) {
		foreach($prescription as $field=>$value)
			$_POST["$field"]=$value;
		$_POST['cnum']=$prescription['cpcnum'];
		$crid=$prescription['cpcrid'];
		if($case=fetchcase($crid, 'crid, crpaid, crdate, crinjurydate, crcasetypecode, crtherapytypecode, crcnum, crapptdate, crpaid, crpnum, crfname, crlname, craddress1, craddress2, crcity, crstate, crzip, crphone1, crphone2, crphone3, crdob, crempid, crempname, crempadd1, crempadd2, crempcity, crempstate, crempzip, crempphone, crrefdmid, crrefdlid, crrefdlsid, crrefdoc, crrefdoccity, crtherapcode, crtherapname')) {
			foreach($case as $field=>$value)
				$_POST["$field"]=$value;
			$_POST['injurydate']=$case['crinjurydate'];
// Retrieve Case Type Description crcasetypecode
			$_POST['casetype']=getCaseTypeDescription($case['crcasetypecode']);
// Retrieve Treatment Type Description crtherapytypecode
			$_POST['treatmenttype']=getTreatmentTypeDescription($case['crtherapytypecode']);

			$_POST['pnum']=$case['crpnum'];
			if(empty($_POST['cnum']))
				$_POST['cnum']=$case['crcnum'];
			$pnum=$case['crpnum'];
			$paid=$case['crpaid'];
			if($patient=fetchpatient($paid, 'paid, pafname, palname, padob, paphone1, paphone2, pacellphone, paaddress1, paaddress2, pacity, pastate, pazip')) {
				foreach($patient as $field=>$value)
					$_POST["$field"]=$value;

				$array=array();
				$array['name']=$patient['pafname'] . " " .$patient['palname'];
				$array['address1']=$patient['paaddress1'];
				$array['address2']=$patient['paaddress2'];
				$array['city']=$patient['pacity'];
				$array['state']=$patient['pastate'];
				$array['zip']=$patient['pazip'];
				$_POST['patient']=$array;

				if($treatments=fetchtreatments($pnum, 'DATE_FORMAT(thdate, "%Y%m%d")')) {
					sort($treatments);
					$_POST['fvisit']=$treatments[0];
					rsort($treatments);
					$_POST['lvisit']=$treatments[0];
				}
// SUCCESSFUL AREA
			}
			require_once($_SERVER['DOCUMENT_ROOT'] . "/common/clinic.options.php");
			$array = getMaster_Clinics(NULL, $_POST['cnum']);
			$array['name']=$array['cmname'];
			$array['address1']=$array['cmaddress1'];
			$array['address2']=$array['cmaddress2'];
			$array['city']=$array['cmcity'];
			$array['state']=$array['cmstate'];
			$array['zip']=$array['cmzip'];
			$_POST['clinic']=$array;
			$_POST['bnum']=$array['cmbnum'];
//			if($employer=fetchemployer($empid) {
//			}
//			if($attorney=fetchattorney($attyid) {
//			}
//			foreach($employer as $field=>$value)
//				$_POST["$field"]=$value;			
			$array=$case['employer'];
			$array['name']=$array['crempname'];
			$array['address1']=$array['crempadd1'];
			$array['address2']=$array['crempadd2'];
			$array['city']=$array['crempcity'];
			$array['state']=$array['crempstate'];
			$array['zip']=$array['crempzip'];
			$_POST['employer']=$array;

//			foreach($attorney as $field=>$value)
//				$_POST["$field"]=$value;			
			$array=$case['attorney'];
			$array['name']=$array['crattyname'];
			$array['address1']=$array['crattyadd1'];
			$array['address2']=$array['crattyadd2'];
			$array['city']=$array['crattycity'];
			$array['state']=$array['crattystate'];
			$array['zip']=$array['crattyzip'];
			$_POST['attorney']=$array;
		}
	}
}

// Format Values
$_POST['today']=displayDate($_POST['today']);
$_POST['injurydate']=displayDate($_POST['injurydate']);
$_POST['fvisit']=displayDate($_POST['fvisit']);
$_POST['lvisit']=displayDate($_POST['lvisit']);
$_POST['casetype']=dbText($_POST['casetype']);
$_POST['treatmenttype']=dbText($_POST['treatmenttype']);
$_POST['signedname']=dbText($_POST['signedname']);
$_POST['signedtitle']=dbText($_POST['signedtitle']);

// Patient Address
$array=$_POST['patient'];
	$array['name']=dbText($array['name']);
	$array['address1']=dbText($array['address1']);
	$array['address2']=dbText($array['address2']);
	$array['city']=dbText($array['city']);
	$array['state']=dbText($array['state']);
	$array['zip']=dbZip($array['zip']);
$_POST['patient']=$array;
// Employer Address
$array=$_POST['employer'];
	$array['name']=dbText($array['name']);
	$array['address1']=dbText($array['address1']);
	$array['address2']=dbText($array['address2']);
	$array['city']=dbText($array['city']);
	$array['state']=dbText($array['state']);
	$array['zip']=dbZip($array['zip']);
$_POST['employer']=$array;
// Attorney Address
$array=$_POST['attorney'];
	$array['name']=dbText($array['name']);
	$array['address1']=dbText($array['address1']);
	$array['address2']=dbText($array['address2']);
	$array['city']=dbText($array['city']);
	$array['state']=dbText($array['state']);
	$array['zip']=dbZip($array['zip']);
$_POST['attorney']=$array;

if(errorcount()==0 && $_POST['RequestInsurance']=="Print Insurance Request Letters") {
// Format message fields and use notes system to insert note
	$type='SYS';
	$app="authprocessing";
	$appid=$_POST['cpid'];
	$bnum=$_POST['bnum'];
	if(empty($_POST['pnum']))
		$pnum='N/A';
	else
		$pnum=$_POST['pnum'];
	$button='RequestInsurance';
	$formdata['today']=dbDate($_POST['today'], "Y-m-d");
	$formdata['injurydate']=dbDate($_POST['injurydate'], "Y-m-d");
	$formdata['fvisit']=dbDate($_POST['fvisit'], "Y-m-d");
	$formdata['lvisit']=dbDate($_POST['lvisit'], "Y-m-d");
	$formdata['casetype']=dbText($_POST['casetype']);
	$formdata['treatmenttype']=dbText($_POST['treatmenttype']);
	$formdata['signedname']=dbText($_POST['signedname']);
	$formdata['signedtitle']=dbText($_POST['signedtitle']);

	$arraylist['patient']=$_POST['patient'];
	$arraylist['employer']=$_POST['employer'];
	$arraylist['attorney']=$_POST['attorney'];
	$arraylist['clinic']=$_POST['clinic'];
	$formdata['arraylist']=serialize($arraylist);

	$address[]="PAT:".implode(", ", $_POST['patient']);
	$address[]="EMP:".implode(", ", $_POST['employer']);
	$address[]="ATY:".implode(", ", $_POST['attorney']);
	$addressees = implode("
", $address);
	$note="Insurance requested from: $addressees";

// Generate REQUEST
	$_SESSION['navigation']=$app;
	$_SESSION['id']=$appid;
	$_REQUEST['app']=$app;
	$_REQUEST['appid']=$appid;
	$_REQUEST['bnum']=$bnum;
	$_REQUEST['pnum']=$pnum;
	$req="app=$app&appid=$appid&bnum=$bnum&pnum=$pnum&button=$button&language=en";
	foreach($formdata as $key=>$val) {
		$req.="&$key=" . urlencode($val);
		$data[]="$key=".mysqli_real_escape_string($dbhandle,$val);
	}

	$data=implode(":",$data);

// Add Note to Authorization System
	require_once('historySQLFunctions.php');
	addPrescriptionHistorySimple($cpid, $note, 'authprocessing');

// Add Note to Notes System
//	require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/notes/notesSQLFunctions.php');
//	noteAddSimple($type, $app, $appid, $bnum, $pnum, $button, $note, $data);	

	echo("<script>");
	echo("window.open('/modules/authprocessing/authprocessingPrintForms.php?$req','PrtInsReq');");
	echo("window.opener.location.href = window.opener.location.href;");
	echo("if (window.opener.progressWindow) window.opener.progressWindow.close();");
	echo("window.close();");
	echo("</script>");
}
else {
	displaysitemessages();
?>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large;">Print Request for Insurance</legend>
	<form method="post" name="printrfi">
		<table>
			<tr>
				<td>Today's Date: </td>
				<td><input name="today" type="text" id="today" value="<?php echo $_POST['today']; ?>" size="10" /></td>
			</tr>
			<tr>
				<td>Injury Date: </td>
				<td><input type="text" id="injurydate" name="injurydate" value="<?php echo $_POST['injurydate']; ?>" size="10" /></td>
			</tr>
			<tr>
				<td>Visit Date Range (First, Last): </td>
				<td><input type="text" id="fvisit" name="fvisit" value="<?php echo $_POST['fvisit']; ?>" size="10" />
					<input type="text" id="lvisit" name="lvisit" value="<?php echo $_POST['lvisit']; ?>" size="10" /></td>
			</tr>
			<tr>
				<td>Case Type Description: </td>
				<td><input type="text" id="casetype" name="casetype" value="<?php echo $_POST['casetype']; ?>" size="30" /></td>
			</tr>
			<tr>
				<td>Treatment Type Description: </td>
				<td><input type="text" id="treatmenttype" name="treatmenttype" value="<?php echo $_POST['treatmenttype']; ?>" size="30" /></td>
			</tr>
			<tr>
				<td valign="top">Patient: </td>
				<td><fieldset>
					<table>
						<tr>
							<td> Name (First Last): </td>
							<td><input type="text" name="patient[name]" id="name1" size="30" value="<?php echo $_POST['patient']['name']; ?>" /></td>
						</tr>
						<tr>
							<td> Address Line 1: </td>
							<td><input type="text" name="patient[address1]" id="address11" size="30" value="<?php echo $_POST['patient']['address1']; ?>" /></td>
						</tr>
						<tr>
							<td> Address Line 2: </td>
							<td><input type="text" name="patient[address2]" id="address21" size="30" value="<?php echo $_POST['patient']['address2']; ?>" /></td>
						</tr>
						<tr>
							<td> City, State Zip: </td>
							<td><input name="patient[city]" id="city1" type="text" size="30" value="<?php echo $_POST['patient']['city']; ?>" />
								<input name="patient[state]" id="state1" type="text" size="2" value="<?php echo $_POST['patient']['state']; ?>" />
								<input name="patient[zip]" id="zip1" type="text" size="10" value="<?php echo $_POST['patient']['zip']; ?>" /></td>
						</tr>
					</table>
					</fieldset></td>
			</tr>
			<tr>
				<td valign="top">Employer: </td>
				<td><fieldset>
					<table>
						<tr>
							<td> Name: </td>
							<td><input type="text" name="employer[name]" id="name2" size="60" value="<?php echo $_POST['employer']['name']; ?>" /></td>
						</tr>
						<tr>
							<td> Address Line 1: </td>
							<td><input type="text" name="employer[address1]" id="address12"  size="30" value="<?php echo $_POST['employer']['address1']; ?>" /></td>
						</tr>
						<tr>
							<td> Address Line 2: </td>
							<td><input type="text" name="employer[address2]" id="address22"  size="30" value="<?php echo $_POST['empoyer']['address2']; ?>" /></td>
						</tr>
						<tr>
							<td> City, State Zip: </td>
							<td><input name="employer[city]" id="city2" type="text"  size="30" value="<?php echo $_POST['employer']['city']; ?>" />
								<input name="employer[state]" id="state2" type="text"  size="2" value="<?php echo $_POST['employer']['state']; ?>" />
								<input name="employer[zip]" id="zip2" type="text"  size="10" value="<?php echo $_POST['employer']['zip']; ?>" /></td>
						</tr>
					</table>
					</fieldset></td>
			</tr>
			<tr>
				<td valign="top">Applicant Attorney: </td>
				<td><fieldset>
					<table>
						<tr>
							<td> Name: </td>
							<td><input type="text" name="attorney[name]" id="name3"  size="60" value="<?php echo $_POST['attorney']['name']; ?>" /></td>
						</tr>
						<tr>
							<td> Address Line 1: </td>
							<td><input type="text" name="attorney[address1]" id="address13"  size="30" value="<?php echo $_POST['attorney']['address1']; ?>" /></td>
						</tr>
						<tr>
							<td> Address Line 2: </td>
							<td><input type="text" name="attorney[address2]" id="address23"  size="30" value="<?php echo $_POST['attorney']['address2']; ?>" /></td>
						</tr>
						<tr>
							<td> City, State Zip: </td>
							<td><input name="attorney[city]" id="city3" type="text"  size="30" value="<?php echo $_POST['attorney']['city']; ?>" />
								<input name="attorney[state]" id="state3" type="text"  size="2" value="<?php echo $_POST['attorney']['state']; ?>" />
								<input name="attorney[zip]" id="zip3" type="text"  size="10" value="<?php echo $_POST['attorney']['zip']; ?>" /></td>
						</tr>
					</table>
					</fieldset></td>
			</tr>
			<tr>
				<td valign="top">Clinic Information: </td>
				<td><fieldset>
					<table>
						<tr>
							<td> Name: </td>
							<td><input type="text" name="clinic[name]" id="name4"  size="60" value="<?php echo $_POST['clinic']['name']; ?>" /></td>
						</tr>
						<tr>
							<td> Address Line 1: </td>
							<td><input type="text" name="clinic[address1]" id="address14"  size="30" value="<?php echo $_POST['clinic']['address1']; ?>" /></td>
						</tr>
						<tr>
							<td> Address Line 2: </td>
							<td><input type="text" name="clinic[address2]" id="address24"  size="30" value="<?php echo $_POST['clinic']['address2']; ?>" /></td>
						</tr>
						<tr>
							<td> City, State Zip: </td>
							<td><input name="clinic[city]" id="city4" type="text"  size="30" value="<?php echo $_POST['clinic']['city']; ?>" />
								<input name="clinic[state]" id="state4" type="text"  size="2" value="<?php echo $_POST['clinic']['state']; ?>" />
								<input name="clinic[zip]" id="zip4" type="text"  size="10" value="<?php echo $_POST['clinic']['zip']; ?>" /></td>
						</tr>
					</table>
					</fieldset></td>
			</tr>
			<tr>
				<td>Signed Name</td>
				<td><input type="text" name="signedname" id="signedname"  size="30" value="<?php echo $_POST['signedname']; ?>" /></td>
			</tr>
			<tr>
				<td>Signed Title </td>
				<td><input type="text" name="signedtitle" id="signedtitle"  size="30" value="<?php echo $_POST['signedtitle']; ?>" /></td>
			</tr>
			<tr>
				<td><input type="submit" name="Cancel" id="cancel" value="Cancel" />
					<input type="submit" name="Reset" id="reset" value="Reset" />
				</td>
				<td><input type="submit" name="RequestInsurance" id="RequestInsurance" value="Print Insurance Request Letters" />
					<input type="hidden" name="init" value="1">
					<input type="hidden" name="cpid" value="<?php echo $_POST['cpid']; ?>">
					<input type="hidden" name="crid" value="<?php echo $_POST['crid']; ?>">
					<input type="hidden" name="paid" value="<?php echo $_POST['paid']; ?>">
					<input type="hidden" name="bnum" value="<?php echo $_POST['bnum']; ?>">
					<input type="hidden" name="cnum" value="<?php echo $_POST['cnum']; ?>">
					<input type="hidden" name="pnum" value="<?php echo $_POST['pnum']; ?>">
				</td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
<?php
}
?>
