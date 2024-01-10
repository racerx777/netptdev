<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(isset($_POST['AddTherapist']))
	echo "Adding Therapist";
if(isset($_SESSION['id'])) {
	$query = "SELECT * FROM master_clinics WHERE cmcnum='" . $_SESSION['id'] . "'";
	$result_id = mysqli_query($dbhandle,$query);
	if($result_id) {
		$numRows = mysqli_num_rows($result_id);
		if($numRows==1) {
			$result = mysqli_fetch_array($result_id,MYSQLI_ASSOC);
			foreach($result as $key=>$val) {
				$_POST[$key] = $val;
			}
		}
		else
			error("002", "Non-unique field error (should never happen).");	
	}
	else
		error("001", mysqli_error($dbhandle));	
}
else
	error("003", "Non-unique field error (should never happen).");	
if(errorcount() == 0) {
	$ttmquery = "SELECT ttmcode, ttmdescription FROM master_treatmenttypes WHERE ttminactive='0'";
	if($ttmresult = mysqli_query($dbhandle,$ttmquery)) {
		$ttmnumRows = mysqli_num_rows($ttmresult);
		while($ttmrow = mysqli_fetch_array( $ttmresult,MYSQLI_ASSOC )) {
			$ttmcodes[$ttmrow['ttmcode']]["ttmcode"]=$ttmrow['ttmcode'];
			$ttmcodes[$ttmrow['ttmcode']]["ttmdescription"]=$ttmrow['ttmdescription'];
		} 
	}
	else
		error("001", "master_treatmenttypes error. ".mysqli_error($dbhandle));
	$tquery = "SELECT ttherap, tname FROM therapists ";
	if($tresult = mysqli_query($dbhandle,$tquery)) {
		$tnumRows = mysqli_num_rows($tresult);
		while($trow = mysqli_fetch_array( $tresult,MYSQLI_ASSOC )) {
			$tcodes[$trow['ttherap']]["ttherap"]=$trow['ttherap'];
			$tcodes[$trow['ttherap']]["tname"]=$trow['tname'];
		} 
	}
	else
		error("001", "therapists error. ".mysqli_error($dbhandle));
?>
<div class="centerFieldset" style="margin-top:100px;">
	<form action="" method="post" name="editForm">
		<fieldset style="text-align:center;">
		<legend>Edit Clinic Information</legend>
		<table style="text-align:left;">
			<tr>
				<td> Inactive </td>
				<td><input name="cminactive" type="checkbox" value="1" <?php if(isset($_POST['cminactive']) && $_POST['cminactive'] == '1') echo "checked"; ?> />
				</td>
			</tr>
			<tr>
				<td>Provider Group</td>
				<td><select name="cmpgmcode" id="cmpgmcode">
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['providergroups'], $optionvaluefield='pgmcode', $arrayofoptionfields=array('pgmname'=>' (', 'pgmcode'=>')'), $defaultoption=$_POST['cmpgmcode'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select></td>
			</tr>
			<tr>
				<td>Clinic Id </td>
				<td>
<?php 
if(userlevel() >= '90') 
	echo '<input id="cmcnum" name="cmcnum" type="text" size="2" maxlength="2" value="'.$_POST['cmcnum'].'" onchange="upperCase(this.id)" />'; 
else  
	echo $_POST['cmcnum'].'<input name="cmcnum" type="hidden" value="'.$_POST['cmcnum'].'" />';
?>
				</td>
			</tr>
			<tr>
				<td>Clinic Name </td>
				<td><input name="cmname" type="text" size="50" maxlength="50" value="<?php if(isset($_POST['cmname'])) echo $_POST['cmname'];?>" />
				</td>
			</tr>
			<tr>
				<td>Clinic Address Line 1</td>
				<td><input name="cmaddress1" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['cmaddress1'])) echo $_POST['cmaddress1'];?>" />
				</td>
			</tr>
			<tr>
				<td>Clinic Address Line 2</td>
				<td><input name="cmaddress2" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['cmaddress2'])) echo $_POST['cmaddress2'];?>" />
				</td>
			</tr>
			<tr>
				<td>Clinic City</td>
				<td><input name="cmcity" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['cmcity'])) echo $_POST['cmcity'];?>" />
				</td>
			</tr>
			<tr>
				<td>Clinic State</td>
				<td><input name="cmstate" type="text" size="2" maxlength="2" value="<?php if(isset($_POST['cmstate'])) echo $_POST['cmstate'];?>" />
				</td>
			</tr>
			<tr>
				<td>Clinic Zip</td>
				<td><input name="cmzip" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['cmzip'])) echo displayZip($_POST['cmzip']);?>" />
				</td>
			</tr>
			<tr>
				<td>e-Mail Address </td>
				<td><input name="cmemail" type="text" size="20" maxlength="64" value="<?php if(isset($_POST['cmemail'])) echo $_POST['cmemail'];?>" />
				</td>
			</tr>
			<tr>
				<td>Phone Number </td>
				<td><input name="cmphone" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['cmphone'])) echo displayPhonePTOS($_POST['cmphone']);?>" />
				</td>
			</tr>
			<tr>
				<td>Fax Number </td>
				<td><input name="cmfax" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['cmfax'])) echo displayPhonePTOS($_POST['cmfax']);?>" />
				</td>
			</tr>
			<tr>
			<tr>
				<td colspan="2">
					<table border="1">
						<tr>
							<th>Therapy Type
							</th>
							<th>Therapist
							</th>
							<th>&nbsp;</th>
						</tr>
<?php
// Get list of Authorized Therapytype and therapists
$select="SELECT cttmcnum, cttmttmcode, cttherap, tname 
FROM master_clinics_treatmenttypes 
LEFT JOIN master_clinics_therapists ON cttmcnum=ctcnum and cttmttmcode=ctttmcode 
LEFT JOIN therapists ON cttherap=ttherap 
WHERE cttmcnum='".$_SESSION['id']."'";
//dump("select",$select);
if($result=mysqli_query($dbhandle,$select)) {
	while($row=mysqli_fetch_assoc($result)) {
		$cnum=$row['cttmcnum'];
		$ttname=$ttmcodes[$row['cttmttmcode']]['ttmdescription'];
		$ttcode=$ttmcodes[$row['cttmttmcode']]['ttmcode'];
		$tcode=$row['cttherap'];
		$tname=$row['tname'];
		$windowstring="window.open('/modules/clinic/confirm_remove.php?cnum=$cnum&therapytype=$ttcode&ttherap=$tcode&tname=$tname','ConfirmWindow','width=600,height=320')";
?>
						<tr>
							<td><?php echo "$ttname ($ttcode)"; ?>
							</td>
							<td><?php echo "$tname ($tcode)"; ?>
							</td>
							<td><input name="RemoveTherapist" type="button" value="Remove" onclick="<?php echo $windowstring;?>" />
							</td>
						</tr>
<?php
	}
}
?>
						<tr>
							<td>
<select name="ttmcode" id="ttmcode">
						<?php echo getSelectOptions($arrayofarrayitems=$ttmcodes, $optionvaluefield='ttmcode', $arrayofoptionfields=array('ttmdescription'=>' (', 'ttmcode'=>')'), $defaultoption=$_POST['ttmcode'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
							</td>
							<td><select name="ttherap" id="ttherap">
						<?php echo getSelectOptions($arrayofarrayitems=$tcodes, $optionvaluefield='ttherap', $arrayofoptionfields=array('tname'=>' (', 'ttherap'=>')'), $defaultoption=$_POST['ttherap'], $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
							</td>
							<td><input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Add Therapist" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Cancel" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Delete" <?php if(userlevel()!='90') echo 'disabled="disabled"' ?> />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Update" />
						</div>
					</div></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<?php
}
?>