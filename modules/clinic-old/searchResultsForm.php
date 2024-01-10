<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function listTherapistInfo($bumcode, $pgmcode, $cmcnum,$dbhandle) {
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
$query  = "SELECT * FROM master_clinics ";
$where = array();
if(isset($_POST['cmpgmcode']) && !empty($_POST['cmpgmcode'])) 
	$where[] = "cmpgmcode= '" . $_POST['cmpgmcode'] . "'";

if(isset($_POST['cmcnum']) && !empty($_POST['cmcnum'])) 
	$where[] = "cmcnum= '" . $_POST['cmcnum'] . "'";

if(isset($_POST['cmname']) && !empty($_POST['cmname'])) 
	$where[] = "cmname like '%" . $_POST['cmname'] . "%'";

if(isset($_POST['cmemail']) && !empty($_POST['cmemail'])) 
	$where[] = "cmemail= '" . $_POST['cmemail'] . "'";

if(isset($_POST['cmphone']) && !empty($_POST['cmphone'])) 
	$where[] = "cmphone= '" . $_POST['cmphone'] . "'";

if(isset($_POST['cmfax']) && !empty($_POST['cmfax'])) 
	$where[] = "cmfax= '" . $_POST['cmfax'] . "'";

if(count($where) > 0) 
	$query .= " WHERE " . implode(" and ", $where);
$query .= " ORDER BY cminactive, cmname"; 
//execute the SQL query and return records
$result = mysqli_query($dbhandle,$query);
if($result) {
	$numRows = mysqli_num_rows($result);
?>

<fieldset>
<legend style="font-size:large;">Search Results</legend>
<?php
	if($numRows>0) {
		echo $numRows . " clinic(s) found.";
?>
<form method="post" name="searchResults">
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
		<tr>
			<th>Provider Group</th>
			<th>Clinic</th>
			<th>Name</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Fax</th>
			<th>Therapist Info</th>
			<th>Functions</th>
		</tr>
		<?php
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			if($row['cminactive'] == '1') {
				$rowstyle=' style="background-color:#FFFFCC;"'; 
				$togglebutton = 'Make Active';
			}
			else {
				$rowstyle='';
				$togglebutton = 'Make Inactive';
			}
?>
		<tr<?php echo $rowstyle; ?>>
			<td><?php echo $row["cmpgmcode"]; ?>&nbsp;</td>
			<td><?php echo $row["cmcnum"]; ?>&nbsp;</td>
			<td><?php echo $row["cmname"]; ?>&nbsp;</td>
			<td><?php echo $row["cmemail"]; ?>&nbsp;</td>
			<td><?php echo $row["cmphone"]; ?>&nbsp;</td>
			<td><?php echo $row["cmfax"]; ?>&nbsp;</td>
			<td><?php echo listTherapistInfo($row["cmbnum"], $row["cmpgmcode"], $row["cmcnum"],$dbhandle); ?>&nbsp;</td>
			<td><input name="button[<?php echo $row["cmcnum"]?>]" type="submit" value="Edit" />
				<input name="button[<?php echo $row["cmcnum"]?>]" type="submit" value="<?php echo $togglebutton ?>" /></td>
		</tr>
		<?php
		}
?>
	</table>
</form>
<?php
	}
	else {
		echo('No clinics found.');
	}
}
else 
	error("001", mysqli_error($dbhandle));	
//close the connection
mysqli_close($dbhandle);
?>
</fieldset>
