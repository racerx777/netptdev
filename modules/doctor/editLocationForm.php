<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(66);

$script = 'editForm';
$table = 'doctor_locations';
$keyfield = 'dlid';
$fields[$table]=array(
				'dlinactive'=>'boolean',
				'dlsname'=>'name',
				'dlname'=>'name',
				'dldescphys'=>'memo',
				'dldlsid'=>'int',
				'dlphone'=>'phone',
				'dlemail'=>'email',
				'dlfax'=>'phone',
				'dladdress'=>'name',
				'dlcity'=>'name',
				'dlstate'=>'name',
				'dlzip'=>'zip',
				'dlterritory'=>'code',
				'dlofficehours'=>'memo'
			);

$buttonvalue = 'Confirm Add Doctor Location';
$id=$_SESSION['id'];
if(!empty($id)) {
	$buttonvalue = 'Confirm Update Doctor Location';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "SELECT * FROM $table WHERE $keyfield='$id'";
	$result_id = mysqli_query($dbhandle,$query);
	if($result_id) {
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

if(errorcount() == 0) {
	// Clear Selected Specialty Codes
//	foreach($_SESSION['dscodes'] as $key=>$val)
//		$selected_dscode[$key]='';
	// Save Posted Specialty Code as Selected Specialty Code
//	if(isset($_POST['dmdscode']) && !empty($_POST['dmdscode']))
//		$selected_dscode[$_POST['dmdscode']] = ' selected ';

	// Clear Selected Doctor Classes
//	foreach($_SESSION['dclasses'] as $key=>$val)
//		$selected_dclass[$key]='';
	// Save Posted Doctor Classes as Selected Doctor Class
//	if(isset($_POST['dmdclass']) && !empty($_POST['dmdclass']))
//		$selected_dclass[$_POST['dmdclass']] = ' selected ';
//dumppost();
?>
<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="editForm">
		<fieldset>
		<legend>Edit Doctor Location Information</legend>
		<table>
			<tr>
				<td>
					Inactive
				</td>
				<td>
					<input name="dlinactive" type="checkbox" value="1" <?php if(isset($_POST['dlinactive']) && $_POST['dlinactive'] == '1') echo "checked"; ?> />
				</td>
			</tr>
			<tr>
				<td>Short Name
				</td>
				<td><input name="dlsname" type="text" size="6" maxlength="6" value="<?php if(isset($_POST['dlsname'])) echo $_POST['dlsname'];?>" />
				</td>
			</tr>
			<tr>
				<td>Doctor Location Name
				</td>
				<td><input name="dlname" type="text" size="30" maxlength="50" value="<?php if(isset($_POST['dlname'])) echo $_POST['dlname'];?>" />
				</td>
			</tr>
			<tr>
				<td>Physical Description of Doctor Location
				</td>
				<td><input name="dldescphys" type="text" size="50" maxlength="50" value="<?php if(isset($_POST['dldescphys'])) echo $_POST["dldescphys"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Primary Doctor Location Contact Id
				</td>
				<td><input name="dldlsid" type="text" size="11" maxlength="11" value="<?php if(isset($_POST['dldlsid'])) echo $_POST["dldlsid"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Location Phone Number
				</td>
				<td><input name="dlphone" type="text" size="22" maxlength="22" value="<?php if(isset($_POST['dlphone'])) echo $_POST["dlphone"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Doctor Location e-mail address
				</td>
				<td><input name="dlemail" type="text" size="32" maxlength="100" value="<?php if(isset($_POST['dlemail'])) echo $_POST["dmemail"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Doctor Location Fax Number
				</td>
				<td><input name="dlfax" type="text" size="22" maxlength="22" value="<?php if(isset($_POST['dlfax'])) echo $_POST["dlfax"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Doctor Location Address
				</td>
				<td><input name="dladdress" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['dladdress'])) echo $_POST["dladdress"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Doctor Location City
				</td>
				<td><input name="dlcity" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['dlcity'])) echo $_POST["dlcity"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Doctor Location State
				</td>
				<td><input name="dlstate" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['dlstate'])) echo $_POST["dlstate"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Doctor Location Zip
				</td>
				<td><input name="dlzip" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['dlzip'])) echo $_POST["dlzip"]; ?>" />
				</td>
			</tr>
			<tr>
				<td>Doctor Location Territory
				</td>
				<td>
                    <select name="dlterritory" size="1">
						<option label=""></option>
                        <?php
                            //echo "here";;
                            //print_r($_SESSION['dlterritory']);
                            foreach ($_SESSION['dlterritory'] as $key=>$val) {
                                $selected = "";
                                if (isset($_POST['dlterritory']) && $_POST['dlterritory'] == $key) {
                                    $selected = " selected=true ";
                                }
                                echo "<option " . $selected . " value='" . $key . "'>" . $_SESSION['dlterritory'][$key] . "</option>";
                            }
                        ?>
                    </select>
				</td>
			</tr>
			<tr>
				<td>Doctor Location Office Hours
				</td>
				<td><input name="dlofficehours" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['dlofficehours'])) echo $_POST["dlofficehours"]; ?>" />
				</td>
			</tr>
		</table>
		<div class="containedBox">
			<div style="float:left; margin:10px;"><input name="button[]" type="submit" value="Cancel" /></div>
			<div style="float:left; margin:10px;"><input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Make Inactive" /></div>
			<div style="float:left; margin:10px;"><input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="<?php echo $buttonvalue; ?>" /></div>
			<div style="float:right; margin:10px;"><input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Search Doctors" /></div>
			<div style="float:right; margin:10px;"><input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Search Groups" /></div>
		</div>
		</fieldset>
	</form>
</div>
<?php
}
?>