<?php
// Read through Authorizations1.esd.csv
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
errorclear();
$fieldnames = array();
//ALPHA
$fieldnames["Adjuster"] ="CHAR";
$fieldnames["AdjusterExt"] ="CHAR";
$fieldnames["Authorizer"] ="CHAR";
$fieldnames["Claim"] ="CHAR";
$fieldnames["DX"] ="CODE50";
$fieldnames["Email"] ="CHAR";
$fieldnames["EMPLOYER"] ="CODE50";
$fieldnames["FirstName"] ="CHAR";
$fieldnames["InsuranceName"] ="CODE50";
$fieldnames["Intake"] ="CODE50";
$fieldnames["LastName"] ="CHAR";
$fieldnames["Notes"] ="CHAR";
$fieldnames["PTNotes"] ="CHAR";

// NUMERIC
$fieldnames["NumberAuthorized"] ="NUMERIC";
$fieldnames["AdjGross"] ="NUMERIC";

// BOOLEAN
$fieldnames["Active"] ="CODE50";
$fieldnames["InitialEval"] ="CODE50";
$fieldnames["PossibleReAdmit"] ="CODE50";
$fieldnames["PotentialReAdmit"] ="CODE50";
$fieldnames["PR2"] ="CODE50";
$fieldnames["Prescription"] ="CODE50";
$fieldnames["PSP"] ="CODE50";
$fieldnames["ReAdmitReLocated"] ="CODE50";
$fieldnames["ReportProtocol"] ="CODE50";
$fieldnames["StillTreating"] ="CODE50";

// DATE & TIME
$fieldnames["ApptDate"] ="DATE";
$fieldnames["ApptTime"] ="TIME";
$fieldnames["AuthDate"] ="DATE";
$fieldnames["DateAppointmentMade"] ="DATE";
$fieldnames["DateCanned"] ="DATE";
$fieldnames["DateofAuthChange"] ="DATE";
$fieldnames["DateReferred"] ="DATE";
$fieldnames["DOB"] ="DATE";
$fieldnames["DOI"] ="DATE";
$fieldnames["NextActionDate"] ="DATE";
$fieldnames["SurgDate"] ="DATE";

// CODE
$fieldnames["AuthStatus"] ="CODE50";
$fieldnames["MDClass"] ="CODE50";
$fieldnames["Status"] ="CODE50";

// CODE50
$fieldnames["ClinicReferredTo"] ="CODE50";
$fieldnames["DrCity"] ="CODE50";
$fieldnames["FreqandDuration"] ="CODE50";
$fieldnames["LocationWanted"] ="CODE50";
$fieldnames["Marketer"] ="CODE50";
$fieldnames["ReasonforNotScheduling"] ="CODE50";
$fieldnames["RefPhysician"] ="CODE50";
$fieldnames["ScheduledBy"] ="CODE50";
$fieldnames["TreatingPT"] ="CODE50";
$fieldnames["TypeofTherapy"] ="CODE50";

// PHONE
$fieldnames["AdjusterFax"] ="PHONE";
$fieldnames["DRFax"] ="PHONE";
$fieldnames["DRPhone"] ="PHONE";
$fieldnames["InsurancePhone"] ="PHONE";
$fieldnames["Pt"] ="PHONE";

// SEX
$fieldnames["MF"] ="CODE50";

// SSN
$fieldnames["SocSecuirty"] ="SSN";

$fieldnamescount = count($fieldnames);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$query = "select * from Authorizations1";
if($result = mysqli_query($dbhandle,$query)) {
//	echo('<table border="1">');
	while($row = mysqli_fetch_assoc($result)) {
		$r++;
//		echo '<th colspan="5">Record '.$r.'</th>';
		foreach($row as $field=>$value) {
			unset($newvalue);
			unset($error["$field"]);
			switch($fieldnames["$field"]) {
				case 'BOOLEAN':
					if($value == "T" || $value == "Y") {
						$newvalue = '1';
						$error["$field"] = FALSE;
					}
					else {
						if($value == "F" || $value == "N") {
							$newvalue = '0';
							$error["$field"] = FALSE;
						}
						else {
							$newvalue = '0';
							$error["$field"] = TRUE;
						}
					}
					break;
				case 'CHAR':
					$newvalue = strtoupper(trim($value));
					$error["$field"] = FALSE;
					break;
				case 'CHARNOSPACES':
					$newvalue = strtoupper(str_replace( ' ', '', $value));
					$error["$field"] = FALSE;
					break;
				case 'CODE':
					$key = substr(strtoupper(str_replace( ' ', '', $value)),0,50);
					$code["$field"]["$key"]['ckey']=$key;
					$code["$field"]["$key"]['ccount']++;
					$code["$field"]["$key"]['cvalue']=$value;
					$code["$field"]["$key"]['calpha']=ereg_replace("[^A-Z]", "", $key);
					$code["$field"]["$key"]['cnum']=ereg_replace("[^0-9]", "", $key);
					$code["$field"]["$key"]['calphanum']=ereg_replace("[^A-Z0-9]", "", $key);
					$code["$field"]["$key"]['calphanumplus']=ereg_replace("[^A-Z0-9] ", "", $key);
					$code["$field"]["$key"]['code3']=substr($code["$field"]["$key"]['calphanum'],0,3);
					$code["$field"]["$key"]['csname']=substr($code["$field"]["$key"]['calphanum'],0,6);
					$newvalue=$code["$field"]["$key"]['code3'];
					$error["$field"] = FALSE;
					break;
				case 'CODE50':
					$key = substr(strtoupper(str_replace( ' ', '', $value)),0,50);
					$code["$field"]["$key"]['ckey']=$key;
					$code["$field"]["$key"]['ccount']++;
					$code["$field"]["$key"]['cvalue']=$value;
					$code["$field"]["$key"]['calpha']=ereg_replace("[^A-Z]", "", $key);
					$code["$field"]["$key"]['cnum']=ereg_replace("[^0-9]", "", $key);
					$code["$field"]["$key"]['calphanum']=ereg_replace("[^A-Z0-9]", "", $key);
					$code["$field"]["$key"]['calphanumplus']=ereg_replace("[^A-Z0-9] ", "", $key);
					$code["$field"]["$key"]['code3']=substr($code["$field"]["$key"]['calphanum'],0,3);
					$code["$field"]["$key"]['csname']=substr($code["$field"]["$key"]['calphanum'],0,6);
					$newvalue=$key;
					$error["$field"] = FALSE;
					break;
				case 'SEX':
					$mystr = strtoupper(trim($value));
					if($mystr == 'M' || $mystr == 'F') {
						$newvalue=$mystr;
						$error["$field"] = FALSE;
					}
					else {
						$newvalue=NULL;
						$error["$field"] = TRUE;
					}
					break;
				case 'NUMERIC':
					if(is_numeric($value)) {
						$newvalue = $value;
						$error["$field"] = FALSE;
					}
					else {
						$newvalue = 0;
						$error["$field"] = TRUE;
					}
					break;
				case 'PHONE':
					$error["$field"] = TRUE;
					$mystr = ereg_replace("[^0-9]", "", $value);
					if(is_numeric($mystr)) {
						if(strlen($mystr) == 10) {
							$newvalue = $mystr;
							$error["$field"] = FALSE;
						}
					}
					break;
				case 'SSN':
					$error["$field"] = TRUE;
					$mystr = ereg_replace("[^0-9]", "", $value);
					if(is_numeric($mystr)) {
						if(strlen($mystr) == 9) {
							$newvalue = $mystr;
							$error["$field"] = FALSE;
						}
					}
					break;
				case 'DATE':
					$error["$field"] = TRUE;
					if(!empty($value) && $mystr = strtotime($value)) {
						$newvalue = date("Y-m-d", $mystr);
						$error["$field"] = FALSE;
					}
					break;
				case 'TIME':
					$error["$field"] = TRUE;
					if(!empty($value) && $mystr = strtotime($value)) {
						$newvalue = date("H:i:s", $mystr);
						$error["$field"] = FALSE;
					}
					else
						$newvalue=NULL;
					break;
			}
			if(empty($newvalue))
				$newvalue = "(BLANK/EMPTY)";
			if($error["$field"] && !empty($value) )
				$errorcount["$field"]++;
//				echo("<tr>	
//				<td>$r</td>
//				<td>$field</td>
//				<td>$value&nbsp;</td>
//				<td>$newvalue&nbsp;</td>
//				<td>".$error["$field"]."&nbsp;</td>
//				</tr>");
		} // for each
	} // while
//	echo("</table>");
} // if

echo("<h1>Error Counts</h1>");
echo('<table border="1">');
foreach($errorcount as $field=>$value) {
	echo('<th colspan="2" style="background:#CCCCCC">' . $field . '</th>');
	echo("<tr><td>$field&nbsp;</td><td>$value&nbsp;</td></tr>");
}
echo('</table>');

echo("<h1>Codes Tables</h1>");
echo('<table border="1">');
ksort($code);
foreach($code as $field=>$value) {
	addCode50($dbhandle, $field, $code["$field"]);
	echo('<th colspan="3" style="background:#CCCCCC">' . $field . '</th>');
	ksort($value);
	foreach($value as $valuecode=>$valuecount) {
		echo("<tr><td>$field&nbsp;</td><td>$valuecode&nbsp;</td><td>$valuecount&nbsp;</td></tr>");
	}
}
echo('</table>');
mysql_close($dbhandle);

function addCode50($link_id, $cfield, $codearray) {
	foreach($codearray as $key50=>$key50array) {
		$sqlfield=array();
		$sqlvalue=array();

		$sqlfield[]='cfield';
		$sqlvalue[]="'" . mysqli_real_escape_string($dbhandle,$cfield) . "'";
		foreach($key50array as $field=>$value) {
			$sqlfield[]=$field;
			$sqlvalue[]= "'" . mysqli_real_escape_string($dbhandle,$value) . "'";
		}
//		$key3 = substr($key50,0,3);
//		$key50 = mysqli_real_escape_string($dbhandle,$key50);
//		$key3 = mysqli_real_escape_string($dbhandle,$key3);
//		$query = "INSERT INTO xrefCode50 (code, code50, codecount, newcode) VALUES('$field', '$key50','$count', '$key3')";
		$fields = implode(", ", $sqlfield);
		$values = implode(", ", $sqlvalue);
		$query = "INSERT INTO xrefCode50 ($fields) VALUES($values)";
		if($result=mysqli_query($dbhandle,$query, $link_id))
			echo("$field : $key50 Added<br>");
		else
			echo("$field : Error Adding $key50<br>QUERY:$query<br>");
	}
}
?>