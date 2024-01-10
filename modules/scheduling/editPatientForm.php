<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15);
if(isset($_SESSION['id'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		SELECT
			pafname, pamname, palname, pasex, passn, DATE_FORMAT(padob, '%m/%d/%Y') as padob,
			paphone1, paaddress1, pacity, pastate, pazip
		FROM patients
		WHERE paid='" . $_SESSION['id'] . "'";
	$result_id = mysqli_query($dbhandle,$query);
	if($result_id) {
		$numRows = mysqli_num_rows($result_id);
		if($numRows==1) {
			if($result = mysqli_fetch_assoc($result_id))
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
if(errorcount() == 0) {
?>
<div class="centerFieldset" style="margin-top:100px;">
	<form action="" method="post" name="editForm">
		<fieldset style="text-align:center;">
		<legend>Edit Patient Information</legend>
		<table style="text-align:left;">
			<tr>
				<td>First Name </td>
				<td><input name="pafname" id="pafname" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['pafname'])) echo $_POST['pafname'];?>" onchange="upperCase(this.id)" />
				</td>
			</tr>
			<tr>
				<td>Middle Name </td>
				<td><input name="pamname" id="pamname" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['pamname'])) echo $_POST['pamname'];?>" onchange="upperCase(this.id)" />
				</td>
			</tr>
			<tr>
				<td>Last Name </td>
				<td><input name="palname" id="palname" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['palname'])) echo $_POST['palname'];?>"  onchange="upperCase(this.id)"/>
				</td>
			</tr>
			<tr>
				<td>Gender </td>
				<td><select name="pasex" id="pasex" />
					<?php echo getSelectOptions($arrayofarrayitems=sexOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>''), $defaultoption=$_POST['pasex'], $addblankoption=TRUE, $arraykey="", $arrayofmatchvalues=array()); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Social Security Number </td>
				<td><input name="passn" id="passn" type="text" size="11" maxlength="11" value="<?php if(isset($_POST['passn'])) echo displaySsnAll($_POST['passn']);?>" onchange="displayssn(this.id)" />
				</td>
			</tr>
			<tr>
				<td>Date of Birth </td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="padob" name="padob" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['padob'])) echo $_POST['padob']; ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.editForm.padob,'anchor1','MM/dd/yyyy'); return false;" /></td>			</tr>
			<tr>
				<td>Phone Number </td>
				<td><input name="paphone1" id="paphone1" type="text" size="14" maxlength="14" value="<?php if(isset($_POST['paphone1'])) echo displayPhone($_POST['paphone1']);?>" onchange="displayphone(this.id)" />
				</td>
			</tr>
			<tr>
				<td>Address </td>
				<td><input name="paaddress1" id="paaddress1" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['paaddress1'])) echo $_POST['paaddress1'];?>" onchange="upperCase(this.id)" />
				</td>
			</tr>
			<tr>
				<td>City </td>
				<td><input name="pacity" id="pacity" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['pacity'])) echo $_POST['pacity'];?>" onchange="upperCase(this.id)" />
				</td>
			</tr>
			<tr>
				<td>State </td>
				<td><input name="pastate" id="pastate" type="text" size="2" maxlength="2" value="<?php if(isset($_POST['pastate'])) echo $_POST['pastate'];?>" onchange="upperCase(this.id)"/>
				</td>
			</tr>
			<tr>
				<td>Zip </td>
				<td><input name="pazip" id="pazip" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['pazip'])) echo displayZip($_POST['pazip']);?>" onchange="displayzip(this.id)"/>
				</td>
			</tr>
            <?php /*
            <tr>
                <td>No Insurance </td>
				<td>
                    <input name="panoins" id="panoins" type="checkbox" value="1" />
				</td>
            </tr>
             *
             */?>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Back" />
						</div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Update Patient" />
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