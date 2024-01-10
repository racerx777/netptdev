<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function listTherapistInfo($bumcode, $pgmcode, $cmcnum) {
	$therapyarray=array();
	$therapistinfo="";
	$therapyquery="
	SELECT bumcode, pgmcode, cmcnum, cttmttmcode, cttherap, tname
	FROM master_business_units 
	LEFT JOIN master_provider_groups 
	ON bumcode=pgmbumcode
	LEFT JOIN master_clinics
	ON bumcode=cmbnum and pgmcode=cmpgmcode
	LEFT JOIN master_clinics_treatmenttypes
	ON cmcnum=cttmcnum  
	LEFT JOIN master_clinics_therapists
	ON cmcnum=ctcnum and cttmttmcode=ctttmcode
	LEFT JOIN therapists
	ON cttherap=ttherap
	WHERE bumcode='$bumcode' and pgmcode='$pgmcode' and cmcnum='$cmcnum' 
	ORDER BY bumcode, pgmcode, cmcnum, cttmttmcode
	";
	if($therapyresult = mysqli_query($dbhandle,$therapyquery)) {
		while($therapyrow=mysqli_fetch_array($therapyresult,MYSQLI_ASSOC)) {
//			foreach($therapyrow as $therapyfield=>$therapyvalue) {
//				$therapybumcode=$therapyrow['bumcode'];
//				$therapypgmcode=$therapyrow['pgmcode'];
//				$therapycmcnum=$therapyrow['cmcnum'];
				$therapycttmttmcode=$therapyrow['cttmttmcode'];
				$therapycttherap=$therapyrow['cttherap'];
				$therapytname=$therapyrow['tname'];
				$therapyarray[]="$therapycttmttmcode-$therapytname($therapycttherap)";
	// Need to create an array to retrieve the therapist for the current therapy type
//			}
		}
		$therapistinfo="(NONE)";
		if(count($therapyarray)>0) {
			$therapistinfo=implode("<br>",$therapyarray);
//			dump("therapistinfo","$therapistinfo");
		}
		return($therapistinfo);
	}
	else {
		error("987","Cannot retrieve list of Therapist Information.<br>$therapyquery<br>".mysqli_error($dbhandle));
	}
	return("ERROR");
}

function listClinicInfo($bumcode, $pgmcode) {
	return;
	$therapyarray=array();
	$therapistinfo="";
	$therapyquery="
	SELECT bumcode, pgmcode, cmcnum, cttmttmcode, cttherap, tname
	FROM master_business_units 
	LEFT JOIN master_provider_groups 
	ON bumcode=pgmbumcode
	LEFT JOIN master_clinics
	ON bumcode=cmbnum and pgmcode=cmpgmcode
	LEFT JOIN master_clinics_treatmenttypes
	ON cmcnum=cttmcnum  
	LEFT JOIN master_clinics_therapists
	ON cmcnum=ctcnum and cttmttmcode=ctttmcode
	LEFT JOIN therapists
	ON cttherap=ttherap
	WHERE bumcode='$bumcode' and pgmcode='$pgmcode' and cmcnum='$cmcnum' 
	ORDER BY bumcode, pgmcode, cmcnum, cttmttmcode
	";
	if($therapyresult = mysqli_query($dbhandle,$therapyquery)) {
		while($therapyrow=mysqli_fetch_array($therapyresult,MYSQLI_ASSOC)) {
//			foreach($therapyrow as $therapyfield=>$therapyvalue) {
//				$therapybumcode=$therapyrow['bumcode'];
//				$therapypgmcode=$therapyrow['pgmcode'];
//				$therapycmcnum=$therapyrow['cmcnum'];
				$therapycttmttmcode=$therapyrow['cttmttmcode'];
				$therapycttherap=$therapyrow['cttherap'];
				$therapytname=$therapyrow['tname'];
				$therapyarray[]="$therapycttmttmcode-$therapytname($therapycttherap)";
	// Need to create an array to retrieve the therapist for the current therapy type
//			}
		}
		$therapistinfo="(NONE)";
		if(count($therapyarray)>0) {
			$therapistinfo=implode("<br>",$therapyarray);
//			dump("therapistinfo","$therapistinfo");
		}
		return($therapistinfo);
	}
	else {
		error("987","Cannot retrieve list of Therapist Information.<br>$therapyquery<br>".mysqli_error($dbhandle));
	}
	return("ERROR");
}

//declare the SQL statement that will query the database
$query  = "SELECT * FROM master_provider_groups ";
$where = array();
if(isset($_POST['pgmbumcode']) && !empty($_POST['pgmbumcode'])) 
	$where[] = "pgmbumcode= '" . $_POST['pgmbumcode'] . "'";

if(isset($_POST['pgmcode']) && !empty($_POST['pgmcode'])) 
	$where[] = "pgmcode like '%" . $_POST['pgmcode'] . "%'";

if(isset($_POST['pgmname']) && !empty($_POST['pgmname'])) 
	$where[] = "pgmname like '%" . $_POST['pgmname'] . "%'";

if(isset($_POST['pgmemail']) && !empty($_POST['pgmemail'])) 
	$where[] = "pgmemail like '%" . $_POST['pgmemail'] . "%'";

if(isset($_POST['pgmphone']) && !empty($_POST['pgmphone'])) 
	$where[] = "pgmphone like '%" . $_POST['pgmphone'] . "%'";

if(isset($_POST['pgmfax']) && !empty($_POST['pgmfax'])) 
	$where[] = "pgmfax like '%" . $_POST['pgmfax'] . "%'";

if(count($where) > 0) 
	$query .= " WHERE " . implode(" and ", $where);
$query .= " ORDER BY pgminactive, pgmname"; 
//dump("query",$query);
//execute the SQL query and return records
$result = mysqli_query($dbhandle,$query);
if($result) {
	$numRows = mysqli_num_rows($result);
?>

<fieldset>
<legend style="font-size:large;">Search Results</legend>
<?php
	if($numRows>0) {
		echo $numRows . " providers(s) found.";
?>
<form method="post" name="searchResults">
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
		<tr>
			<th>Business Unit</th>
			<th>Provider</th>
			<th>Name</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Fax</th>
			<th>Clinic Info</th>
			<th>Functions</th>
		</tr>
		<?php
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			if($row['pgminactive'] == '1') {
				$rowstyle=' style="background-color:#FFFFCC;"'; 
				$togglebutton = 'Make Active';
			}
			else {
				$rowstyle='';
				$togglebutton = 'Make Inactive';
			}
?>
		<tr<?php echo $rowstyle; ?>>
			<td><?php echo $row["pgmbumcode"]; ?>&nbsp;</td>
			<td><?php echo $row["pgmcode"]; ?>&nbsp;</td>
			<td><?php echo $row["pgmname"]; ?>&nbsp;</td>
			<td><?php echo $row["pgmemail"]; ?>&nbsp;</td>
			<td><?php echo $row["pgmphone"]; ?>&nbsp;</td>
			<td><?php echo $row["pgmfax"]; ?>&nbsp;</td>
			<td><?php echo listClinicInfo($row["pgmbumcode"], $row["pgmcode"]); ?>&nbsp;</td>
			<td><input name="button[<?php echo $row["pgmcode"]?>]" type="submit" value="Edit" />
				<input name="button[<?php echo $row["pgmcode"]?>]" type="submit" value="<?php echo $togglebutton ?>" /></td>
		</tr>
		<?php
		}
?>
	</table>
</form>
<?php
	}
	else {
		echo('No providers found.');
	}
}
else 
	error("001", mysqli_error($dbhandle));	
//close the connection
mysqli_close($dbhandle);
?>
</fieldset>
