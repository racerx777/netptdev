<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(5);

if(isset($_SESSION['id'])) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		SELECT * 
		FROM patients 
		WHERE paid='" . $_SESSION['id'] . "'";
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
if(errorcount() == 0) {
?>

<div class="centerFieldset" style="margin-top:100px;">
	<form action="" method="post" name="viewForm">
		<fieldset style="text-align:center;">
		<legend>View Patient Information</legend>
		<table style="text-align:left;">
			<tr>
				<td>First Name</td>
				<td><?php echo $_POST['pafname'];?></td>
			</tr>
			<tr>
				<td>Middle Name</td>
				<td><?php echo $_POST['pamname'];?></td>
			</tr>
			<tr>
				<td>Last Name</td>
				<td><?php echo $_POST['palname'];?></td>
			</tr>
			<tr>
				<td>Gender</td>
				<td><?php echo $_POST['pasex']; ?></td>
			</tr>
			<tr>
				<td>Social Security #</td>
				<td><?php echo displaySsnAll($_POST['passn']); ?></td>
			</tr>
			<tr>
				<td>Date of Birth</td>
				<td><?php echo displayDate($_POST['padob']); ?></td>
			</tr>
			<tr>
				<td>Home Phone Number</td>
				<td><?php echo displayPhone($_POST['paphone1']); ?></td>
			</tr>
			<tr>
				<td>Work Phone Number</td>
				<td><?php echo displayPhone($_POST['paphone2']); ?></td>
			</tr>
			<tr>
				<td>Cell Phone Number </td>
				<td><?php echo displayPhone($_POST['pacellphone']); ?></td>
			</tr>
			<tr>
				<td>E-mail Address </td>
				<td><?php echo $_POST['paemail']; ?></td>
			</tr>
			<tr>
				<td>Address Line 1</td>
				<td><?php echo $_POST['paaddress1']; ?></td>
			</tr>
			<tr>
				<td>Address Line 2</td>
				<td><?php echo $_POST['paaddress2']; ?></td>
			</tr>
			<tr>
				<td>City</td>
				<td><?php echo $_POST['pacity']; ?></td>
			</tr>
			<tr>
				<td>State</td>
				<td><?php echo $_POST['pastate']; ?></td>
			</tr>
			<tr>
				<td>Zip</td>
				<td><?php echo displayZip($_POST['pazip']); ?></td>
			</tr>
			<tr>
				<td>Note</td>
				<td><?php echo $_POST['panote'];?></td>
			</tr>
			<tr>
				<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[]" type="submit" value="Exit" />
						</div>
						<div style="float:right; margin:20px;">
							<input name="button[<?php echo $_SESSION['id']; ?>]" type="submit" value="Search Cases" />
						</div>
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
