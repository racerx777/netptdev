<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(90);

$where = array();
if (isset($_POST['umuser']) && !empty($_POST['umuser']))
	$where[] = "umuser like '%" . $_POST['umuser'] . "%'";

if (isset($_POST['umname']) && !empty($_POST['umname']))
	$where[] = "umname like '%" . $_POST['umname'] . "%'";

if (isset($_POST['umemail']) && !empty($_POST['umemail']))
	$where[] = "umemail like '%" . $_POST['umemail'] . "%'";

if (isset($_POST['ucabumcode']) && !empty($_POST['ucabumcode']))
	$where[] = "ucabumcode= '" . $_POST['ucabumcode'] . "'";

if (isset($_POST['ucapgmcode']) && !empty($_POST['ucapgmcode']))
	$where[] = "ucapgmcode= '" . $_POST['ucapgmcode'] . "'";

if (isset($_POST['ucacmcnum']) && !empty($_POST['ucacmcnum']))
	$where[] = "ucacmcnum= '" . $_POST['ucacmcnum'] . "'";

if (isset($_POST['ucarmcode']) && !empty($_POST['ucarmcode']))
	$where[] = "ucarmcode= '" . $_POST['ucarmcode'] . "'";

if (isset($_POST['ucahpmcode']) && !empty($_POST['ucahpmcode']))
	$where[] = "ucahpmcode= '" . $_POST['ucahpmcode'] . "'";

// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$query = "SELECT * FROM master_user LEFT JOIN user_clinic_access ON umid = ucaumid ";
if (count($where) > 0)
	$query .= " WHERE " . implode(" and ", $where);
$query .= " ORDER BY uminactive, umuser";
//execute the SQL query and return records
$result = mysqli_query($dbhandle, $query);
if ($result) {
	$numRows = mysqli_num_rows($result);
	?>
	<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
		crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
		.callextension {
			align-items: center;
			display: flex;
			/* justify-content: space-between; */

			justify-content: center;
		}
	</style>
	<fieldset>
		<legend>User Search Results</legend>
		<?php
		if ($numRows > 0) {
			echo $numRows . " user(s) found.";
			?>
			<form method="post" name="searchlist">
				<table border="1" cellpadding="3" cellspacing="0" width="100%">
					<tr>
						<th>User</th>
						<th>Name</th>
						<th>Email</th>
						<th>Bus Unit</th>
						<th>Pro Group</th>
						<th>Clinic</th>
						<th>Startup</th>
						<th>Role</th>
						<th> Call </th>
						<!-- <th>CheckBox</th>
								<th>Extension</th> -->
						<th>Functions</th>
					</tr>
					<?php

					while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {


						if ($row['uminactive'] == '1') {
							$rowstyle = ' style="background-color:#FFFFCC;"';
							$togglebutton = 'Make Active';
						} else {
							$rowstyle = '';
							$togglebutton = 'Make Inactive';
						}
						?>
						<tr<?php echo $rowstyle; ?>>
							<td>
								<?php echo $row["umuser"]; ?>&nbsp;
							</td>
							<td>
								<?php echo $row["umname"]; ?>&nbsp;
							</td>
							<td>
								<?php echo $row["umemail"]; ?>&nbsp;
							</td>
							<td>
								<?php echo $row["ucabumcode"]; ?>&nbsp;
							</td>
							<td>
								<?php echo $row["ucapgmcode"]; ?>&nbsp;
							</td>
							<td>
								<?php echo $row["ucacmcnum"]; ?>&nbsp;
							</td>
							<td>
								<?php echo $row["ucahpmcode"]; ?>&nbsp;
							</td>
							<td>
								<?php echo $row["ucarmcode"]; ?>&nbsp;
							</td>

							<td class="callextension">
								<input type="text" id="extension" style="margin-left: 5px; margin-right: 5px; width: 50%;"
									name="extension" value="<?php echo $row['extension']; ?>" placeholder="Extension"
									class="<?php echo $row['umid']; ?>   "
									onfocusout="myFunction(<?php echo $row['umid']; ?>)"><br><br>
								<input type="checkbox" <?php if ($row['call_id'] == 1) { ?> checked="checked" <?php } ?>
									class="callingstatus" id="callingstatus" name="callingstatus"
									value="<?php echo $row['umid']; ?>">

							</td>
							<!--			<td><?php echo $_SESSION['clinics'][$row["umclinic"]]; ?>&nbsp;</td>
									<td><?php echo $_SESSION['homepages'][$row["umhomepage"]]; ?>&nbsp;</td>
									<td><?php echo $_SESSION['roles'][$row["umrole"]]; ?>&nbsp;</td>
						-->
							<!-- <td>  <input type="checkbox" id="firstCheckBox"></td> -->
							<!-- <td></td> -->
							<td><input name="button[<?php echo $row["umid"] ?>]" type="submit" value="Edit" /><input
									name="button[<?php echo $row["umid"] ?>]" type="submit" value="Edit Access" /><input
									name="button[<?php echo $row["umid"] ?>]" type="submit" value="Reset Password" />
								<input name="button[<?php echo $row["umid"] ?>]" type="submit"
									value="<?php echo $togglebutton ?>" />
							</td>
							</tr>
							<?php
					}
					?>
				</table>
			</form>
			<?php
		} else {
			echo ('No users found.');
		}
} else
	error("001", mysqli_error($dbhandle));
//close the connection
mysqli_close($dbhandle);
?>
</fieldset>


<script>





	$(".callingstatus").click(function () {

		let userUmid = $(this).attr('value');
		var ischecked = $(this).is(':checked');
		// console.log("userUmid", userUmid);
		var extension = $(`.${userUmid}`).val();
		// console.log("extensionextension", extension);

		if (extension == "") {
			Swal.fire({
				icon: 'error',
				// title: 'Oops...',
				text: 'Please enter Extension Number',
				// footer: '<a href="">Why do I have this issue?</a>'
			})

			$(this).prop('checked', false);
		} else {

			// console.log("extension" , extension);
			let status = ""
			// console.log("ischecked", ischecked);
			if (ischecked) {
				status = 1
			} else {
				status = 0
			}

			$.ajax({
				type: "POST",
				url: "modules/user/Calling.php",
				data: 'umid=' + userUmid + '&status=' + status + '&extension=' + extension,
				success: function (response) {
					if (response) {

						Swal.fire(
							'Good job!',
							'User Updated Successfully',
							'success'
						)
					}
				}
			});

		}


	});

</script>


<script type="text/javascript">


	function myFunction(userUmid) {
		var extension = $(`.${userUmid}`).val();
		$.ajax({
			type: "POST",
			url: "modules/user/Calling.php",
			data: 'onfocus=' + 1 + '&extension=' + extension + '&umid=' + userUmid,
			success: function (response) {
				if (response) {

					Swal.fire(
						'Good job!',
						'User Updated Successfully',
						'success'
					)
				}
			}
		});

	}

</script>