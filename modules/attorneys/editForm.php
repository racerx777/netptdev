<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(23);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(isset($_POST['uid']))
{
	$query = "UPDATE attorney SET name_first='".$_POST['name_first']."',name_middle='".$_POST['name_middle']."',name_last='".$_POST['name_last']."',address='".$_POST['address']."',address2='".$_POST['address2']."',city='".$_POST['city']."',state='".$_POST['state']."',zip='".$_POST['zip']."',phone='".$_POST['phone']."',email='".$_POST['email']."' WHERE id='".$_POST['uid']."'";
	mysqli_query($dbhandle,$query);
		if(isset($_POST['firmid']))
		{
			$queryfirm = "UPDATE attorney_firm SET firm_name='".$_POST['firm']."' WHERE firm_id='".$_POST['firmid']."'";
			mysqli_query($dbhandle,$queryfirm);
		}
	die;
}

?>
<div class="centerFieldset" style="margin-top:100px;">
<?php
	$editid=$_SESSION['id'];
	$query = "SELECT attorney.*, attorney_firm.firm_name FROM attorney INNER JOIN attorney_firm ON attorney.id=attorney_firm.firm_id WHERE attorney.id='$editid'";
	$result = mysqli_query($dbhandle,$query);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
?>

<form  class="edit-form" action="" id='editform'>
	<fieldset style="width: 80%;">
	<legend>Edit Reffering Attorney Information</legend>
	<table>
		<tbody>
			<span id='error'></span>
			<tr>
				<td>Firm Name</td>
				<td><input name="firm" class="edit-firm" type="text" value="<?php echo $row['firm_name']; ?>" id="firmname"> <span class="edit-firm-msg" style="color:red; display:none;">This field is required</span>
					<input type="hidden" name="firmid" value="<?php echo $row['firm']; ?>">
				</td>
			</tr>
			<tr>
				<td>First Name </td>
				<td><input name="name_first" class="edit-firstname" id="fname" type="text" value="<?php echo $row['name_first']; ?>"> <span class="edit-fn-msg" style="color:red; display:none;">This field is required</span>
					<input type="hidden" name="uid" value="<?php echo $row['id']; ?>">
				</td>
			</tr>
			<tr>
				<td>Middle Name </td>
				<td><input name="name_middle" class="edit-middlename" id="mname" type="text" value="<?php echo $row['name_middle']; ?><?php echo $row['name_first']; ?>">
				</td>
			</tr>
			<tr>
				<td>Last Name </td>
				<td><input name="name_last" class="edit-lastname" type="text" id="lname" value="<?php echo $row['name_last']; ?>"> <span class="edit-ln-msg" style="color:red; display:none;">This field is required</span>
				</td>
			</tr>
			<tr>
				<td>Address </td>
				<td><input name="address" class="edit-address" type="text" value="<?php echo $row['address']; ?>" id="address">
				</td>
			</tr>
			<tr>
				<td>Suite Number</td>
				<td><input name="address2" class="edit-address2" type="text" value="<?php echo $row['address2']; ?>" id="address2">
				</td>
			</tr>
			<tr>
				<td>City </td>
				<td><input name="city" class="edit-city" type="text" value="<?php echo $row['city']; ?>" id="city"> <span class="edit-city-msg" style="color:red; display:none;">This field is required</span>
				</td>
			</tr>
			<tr>
				<td>State </td>
				<td><input name="state" class="edit-state" type="text" value="<?php echo $row['state']; ?>" id="state">
				</td>
			</tr>
			<tr>
				<td>Zip </td>
				<td><input name="zip" class="edit-zip" id="zip" type="text" value="<?php echo $row['zip']; ?>"> <span class="edit-zip-msg" style="color:red; display:none;">This field is required</span>
				</td>
			</tr>
			<tr>
				<td>Phone </td>
				<td><input name="phone" class="edit-phone" id="phone" type="phone" value="<?php echo $row['phone']; ?>"> <span class="edit-phone-msg" style="color:red; display:none;">Phone number is not valid</span>
				</td>
			</tr>
			<tr>
				<td>Email </td>
				<td><input name="email" class="edit-email" type="text" id="email" value="<?php echo $row['email']; ?>"> <span class="edit-email-msg" style="color:red; display:none;">Email is not valid</span>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="containedBox">
		<div style="float:left; margin:10px;">
			<input type="hidden" name="paid_id" value="<?php echo 'paid_'.$_POST['paid']; ?>">
			<input type="hidden" class="edit_id" name="edit_id" value="<?php echo $edit_id_sel; ?>">
			<input type="hidden" name="attorney_form" value="edit">
			<input type="button" class="modal-close cancel-btn" value="Cancel">
		</div>
		<div style="float:left; margin:10px;">
			<input type="button" name="edit" id="save" value="Save">
		</div>
	</div>
	</fieldset>
</form>	
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript">
(function($){
	$('#save').on('click',function(){
		changeText();
	})
	$('#address2').blur(function() {
		changeText();
	});
	function changeText(){
		var str = $('#address2').val()
		str = str.replace(/\s/g, '');
		str = str.toUpperCase();
		str = str.replace('SUITE', '#').replace('STE', '#').replace('STE.', '#').replace('ROOM', '#').replace('APARTMENT', '#').replace('APT', '#').replace('BLDG', '#').replace('BLDG.', '#').replace('FLOOR', '#').replace('FL', '#').replace('UNIT', '#').replace('SLIP', '#').replace('##', '#').replace('###', '#');
		$('#address2').val(str);
	}
 })(jQuery); 
</script>
<script type="text/javascript">
$('#save').click(function(){
	if( $('#fname').val().length === 0 ) {
		$('#fname').next('span').show();
    }else{
		$('#fname').next('span').hide();
    }
    if( $('#lname').val().length === 0 ){
		$('#lname').next('span').show();
    }else{
		$('#lname').next('span').hide();
    }
    if( $('#firmname').val().length === 0 ){
    	$('#firmname').next('span').show();
    }else{
		$('#firmname').next('span').hide();
    }
    if( $('#city').val().length === 0 ){
    	$('#city').next('span').show();
    }else{
		$('#city').next('span').hide();
    }
    if( $('#zip').val().length === 0 ){
    	$('#zip').next('span').show();
    }else{
		$('#zip').next('span').hide();
    }
    if( $('#phone').val().length === 0 ){
    	$('#phone').next('span').show();
    }else{
		$('#phone').next('span').hide();
    }
    if( $('#email').val().length === 0 ){
    	$('#email').next('span').show();
    }else{
    	var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    	if (testEmail.test($('#email').val())){
			$('#email').next('span').hide();
    	}else{
    		$('#email').next('span').show();
    		return;
    	}
    }

	if( ($('#fname').val().length !== 0) && ($('#lname').val().length !== 0) && ($('#firmname').val().length !== 0) && ($('#city').val().length !== 0) && ($('#zip').val().length !== 0) && ($('#phone').val().length !== 0) && ($('#email').val().length !== 0) ) {
		$.ajax({
			type: 'post',
			url: 'modules/attorneys/editForm.php',
			data: $('#editform').serialize(),
			success: function (data) {
				$('.attorney-tab').trigger('click');
			}
		});
	}
});
$('.cancel-btn').click(function(){
	$('.attorney-tab').trigger('click');
});
</script>