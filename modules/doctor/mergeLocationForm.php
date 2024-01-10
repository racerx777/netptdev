<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(66); 
$script = 'mergeLocationForm';
$table = 'doctor_locations';
$keyfield = 'dlid';
$fields[$table]=array(
				'dlid' => 'id',
				'dlname'=>'name',
				'dldescphys'=>'memo',
				'dlphone'=>'phone',
				'dlfax'=>'phone',
				'dladdress'=>'name',
				'dlcity'=>'name',
				'dlstate'=>'name',
				'dlzip'=>'zip',
				'dlterrtory'=>'integer',
				'dlofficehours'=>'char'
			);
$fromids=$_SESSION['id'];
list($fromdoc,$fromloc) = explode("|",$fromids);
if(!empty($fromdoc) && !empty($fromloc)) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * FROM $table WHERE $keyfield='$fromloc'";
	if($result_id = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result_id);
		if($numRows==1) {
			$result = mysqli_fetch_assoc($result_id);
			foreach($result as $fieldname=>$fieldvalue) {
				$_POST[$fieldname] = $fieldvalue;
			}
		}
		else
			error('001', "Non-unique field error (should never happen).");	
	}
	else
		error('002', mysqli_error($dbhandle));
}
else
	error('003', "Must provide from id.");

if(errorcount() == 0) {
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');

$doctorlocationlistoptions="";
$doctorlocationdisabled='disabled="disabled"';
if(!empty($fromdoc)) {
	$doctorlocationdisabled="";
	$doctorlocationlist = getDoctorLocationList($fromdoc, '1');
	$doctorlocationlistoptions = getSelectOptions(
		$arrayofarrayitems=$doctorlocationlist, 
		$optionvaluefield='dlid', 
		$arrayofoptionfields=array(
			'dlname'=>', ', 
			'dlcity'=>', ', 
			'dlphone'=>'' 
			), 
		$defaultoption=$fromid, 
		$addblankoption=TRUE, 
		$arraykey='', 
		$arrayofmatchvalues=array());
}
else
	$doctorlocationlistoptions = '<option value="">Select a Doctor...</option>';

?>
<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="mergeLocationForm">
		<fieldset>
		<legend>Merge From Location Information</legend>
		<table>
			<tr>
				<td>Id
				</td>
				<td><?php echo $_POST['dlid']; ?>
				</td>
			</tr>
			<tr>
				<td>Name
				</td>
				<td><?php echo $_POST['dlname']; ?>
				</td>
			</tr>
			<tr>
				<td>Description
				</td>
				<td><?php echo $_POST['dldescphys']; ?>
				</td>
			</tr>
			<tr>
				<td>Phone
				</td>
				<td><?php echo $_POST['dlphone']; ?>
				</td>
			</tr>
			<tr>
				<td>Fax
				</td>
				<td><?php echo $_POST['dlfax']; ?>
				</td>
			</tr>
			<tr>
				<td>Address
				</td>
				<td><?php echo $_POST['dladdress']; ?>
				</td>
			</tr>
			<tr>
				<td>City
				</td>
				<td><?php echo $_POST['dlcity']; ?>
				</td>
			</tr>
			<tr>
				<td>State
				</td>
				<td><?php echo $_POST['dlstate']; ?>
				</td>
			</tr>
			<tr>
				<td>Zip
				</td>
				<td><?php echo $_POST['dlzip']; ?>
				</td>
			</tr>
			<tr>
				<td>Territory
				</td>
				<td><?php echo $_POST['dlterritory']; ?>
				</td>
			</tr>
			<tr>
				<td>Office Hours
				</td>
				<td><?php echo $_POST['dlofficehours']; ?>
				</td>
			</tr>
<tr>
				<td>Merge to Location</td>
				<td><select id="dlid" name="dlid" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['dlid'])) echo $_POST['dlid'];?>" onchange="showDoctorLocations(this.id)" /><?php echo $doctorlocationlistoptions; ?>
					</select>
</tr>
<tr>
<td>
		<input name="mergefromid" type="hidden" value="<?php echo $_POST['dlid']; ?>">
		</fieldset>
		<div class="containedBox">
			<div style="float:left; margin:10px;"><input name="button[]" type="submit" value="Back" /></div>
			<div style="float:right; margin:10px;"><input name="button[<?php echo $_POST['dlid']; ?>]" type="submit" value="Confirm Merge Locations" /></div>
		</div>
			</td>
		</tr>
		</table>
		</fieldset>
	</form>
</div>
<?php
}
?>