<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function listProviderInfo($bumcode) {
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
$query  = "SELECT * FROM master_business_units ";
$where = array();
if(isset($_POST['bumcode']) && !empty($_POST['bumcode'])) 
	$where[] = "bumcode= '" . $_POST['bumcode'] . "'";

if(isset($_POST['bumname']) && !empty($_POST['bumname'])) 
	$where[] = "bumname like '%" . $_POST['bumname'] . "%'";

if(isset($_POST['bumtaxid']) && !empty($_POST['bumtaxid'])) 
	$where[] = "bumtaxid like '%" . $_POST['bumtaxid'] . "%'";

if(isset($_POST['bumemail']) && !empty($_POST['bumemail'])) 
	$where[] = "bumemail like '%" . $_POST['bumemail'] . "%'";

if(isset($_POST['bumphone']) && !empty($_POST['bumphone'])) 
	$where[] = "bumphone like '%" . $_POST['bumphone'] . "%'";

if(isset($_POST['bumfax']) && !empty($_POST['bumfax'])) 
	$where[] = "bumfax like '%" . $_POST['bumfax'] . "%'";

if(count($where) > 0) 
	$query .= " WHERE " . implode(" and ", $where);
$query .= " ORDER BY buminactive, bumname"; 
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
		echo $numRows . " business unit(s) found.";
?>
<form method="post" name="searchResults">
	<table border="1" cellpadding="3" cellspacing="0" width="100%">
		<tr>
			<th>Business Unit</th>
			<th>Name</th>
			<th>Tax ID</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Fax</th>
			<th>Provider Info</th>
			<th>Functions</th>
		</tr>
		<?php
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			if($row['buminactive'] == '1') {
				$rowstyle=' style="background-color:#FFFFCC;"'; 
				$togglebutton = 'Make Active';
			}
			else {
				$rowstyle='';
				$togglebutton = 'Make Inactive';
			}
?>
		<tr<?php echo $rowstyle; ?>>
			<td><?php echo $row["bumcode"]; ?>&nbsp;</td>
			<td><?php echo $row["bumname"]; ?>&nbsp;</td>
			<td><?php echo $row["bumtaxid"]; ?>&nbsp;</td>
			<td><?php echo $row["bumemail"]; ?>&nbsp;</td>
			<td><?php echo $row["bumphone"]; ?>&nbsp;</td>
			<td><?php echo $row["bumfax"]; ?>&nbsp;</td>
			<td><?php echo listProviderInfo($row["bumcode"]); ?>&nbsp;</td>
			<td><input name="button[<?php echo $row["bumcode"]?>]" type="submit" value="Edit" />
				<input name="button[<?php echo $row["bumcode"]?>]" type="submit" value="<?php echo $togglebutton ?>" /></td>
		</tr>
		<?php
		}
?>
	</table>
</form>
<?php
	}
	else {
		echo('No business units found.');
	}
}
else 
	error("001", mysqli_error($dbhandle));	
//close the connection
mysqli_close($dbhandle);
?>
</fieldset>
