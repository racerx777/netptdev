<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
function phoneformat($str) {
	$mystr = preg_replace("/[^0-9]/", "", $str);
	if(strlen($mystr)==10) {
		$area = substr($mystr,0,3);
		$exch = substr($mystr,3,3);
		$numb = substr($mystr,6,4);
		$msg = "";
	}
	else {
		$area = "???";
		$exch = "???";
		$numb = "???";
		$msg = "Invalid phone format!";
	}
	return("($area) $exch-$numb $msg");
}
$id = $_SESSION['id'];
if(isset($id) && !empty($id)) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	//if(mysqli_num_rows(mysqli_query($dbhandle,"SELECT * FROM case_scheduling_queue where csqid='$id'"))){
		$callquery = "SELECT * FROM case_scheduling_queue LEFT JOIN cases ON csqcrid=crid LEFT JOIN patients on crpaid=paid WHERE csqid='$id'";
	//}else{
		//$callquery = "SELECT * FROM case_scheduling_queue RIGHT JOIN cases ON csqcrid=crid LEFT JOIN patients on crpaid=paid WHERE crid='$id'";
	//}
	if($callresult = mysqli_query($dbhandle,$callquery)) {
		if(mysqli_num_rows($callresult)==1) {
			$callrow = mysqli_fetch_assoc($callresult);
			foreach($callrow as $key=>$val) {
				$_POST["$key"] = $val;
			}
		}
		else
			error("002", "Non-unique field error (should never happen).");	
	}
	else
		error("001", mysqli_error($dbhandle));	
}
if(errorcount() == 0) {
	// if(isset($_GET['add'])){
	// 	$redirect_url = "http://" . $_SERVER['SERVER_NAME'];
	// 	echo "<script>window.location.href='".$redirect_url."';</script>";
	//     exit;
	// }
?>
<?php if (!isset($_GET['add'])) { ?>
<!-- 	<fieldset style="background-color:#DC143C; color:#FFFFFF; clear: both;">
		<legend style="background-color:#000000; color:#FFFFFF;">Error Message Notifications</legend>
		<div id="err">Clinic location selected on Google Maps does not match any "Clinic" drop down options here.</div>
	</fieldset> -->
<?php
		$crcnum =$_SESSION['crcnum1'];
		$crcnum1 =$_SESSION['crcnum1'];
	}else{
		$crcnum = $_POST['crcnum'];		
		$crcnum1 ='';
} 

$therapytypecodes = therapyTypeOptions(); 
$therapycode = $therapytypecodes[$_POST['crtherapytypecode']]["title"];
if (isset($_GET['loc'])) {
	$loc = $_GET['loc'];
}
if(strtolower($therapycode) == 'weststar'){
	$option_val = $loc;
}else{
	$val = str_replace('weststar ','',strtolower($loc));
	$option_val = $therapycode.'-'.$val;
}
$check_option = strtoupper($option_val);
$check_val = strtoupper($val);
if(!empty($crcnum1)){
	$check_option = $crcnum1;
}
if (!isset($_GET['loc']) && !isset($_GET['add']) && empty($crcnum1)) {
	$check_option = '';
	$check_val = '';
}
?>
<div class="centerFieldset" style="margin-top:100px;">
	<form method="post" name="editForm" id="editForm">
		<fieldset style="text-align:center;">
		<legend>Patient/Case Information:</legend>
		<table style="text-align:left;">
			<tr>
				<td>Patient Name</td>
				<td><?php echo($_POST['pafname'] . " " . $_POST['pamname'] . " " . $_POST['palname']); ?></td>
			</tr>
			<tr>
				<td>Address </td>
				<!-- <td><?php echo($_POST['paaddress']); ?></td> -->
				<td><?php echo $_POST['paaddress1'] . " " . $_POST['paaddress2'];?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo($_POST['pacity'] . ", " . $_POST['pastate'] . " " . $_POST['pazip']); ?></td>
			</tr>
			<tr>
			<tr style="height: 25px;"></tr>
			<tr>
				<td>Clinic</td>
				<td><select name="crcnum" id="crcnum">
					<option <?php if(empty($crcnum1) && !isset($_GET['add'])) echo ' selected="selected"'; ?>>SELECT CLINIC</option>
						<?php echo getSelectOptions($arrayofarrayitems=$_SESSION['useraccess']['clinics'], $optionvaluefield='cmcnum', $arrayofoptionfields=array('cmname'=>' (', 'cmcnum'=>')'), $defaultoption=$crcnum, $addblankoption=FALSE, $arraykey='', $arrayofmatchvalues=array()); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Appointment Date</td>
				<td nowrap="nowrap" style="text-decoration:none"><input id="crfvisitdate" name="crfvisitdate" type="text" size="10" maxlength="10" value="<?php if(isset($_POST['crfvisitdate'])) echo displayDate($_POST['crfvisitdate']); ?>"  onchange="validateDate(this.id)">
					<img  align="absmiddle" name="anchor1" id="anchor1" src="/img/calendar.gif" onclick="cal.select(document.forms['editForm'].crfvisitdate,'anchor1','MM/dd/yyyy'); return false;" /></td>
			</tr>
			<tr>
				<td>Appointment Time</td>
				<td><select name="crfvisitdatetime" id="crfvisitdatetime" />
					<?php echo getSelectOptions($arrayofarrayitems=timeOptions(), $optionvaluefield='value', $arrayofoptionfields=array('title'=>''), $defaultoption=$_POST['crfvisitdatetime'], $addblankoption=FALSE, $arraykey="", $arrayofmatchvalues=array()); ?>
					</select>
				</td>
			</tr>
			<td colspan="2"><div>
						<div style="float:left; margin:20px;">
							<input name="button[<?php echo($id); ?>]" type="submit" value="Back" />
						</div>
						<div style="float:left; margin:20px;">
						<input name="confirm_val" id="my_field" value="" type="hidden"/>
						<input name="button[<?php echo($id); ?>]" onclick="change()" id="mybutton" type="submit" value="Confirm Schedule Referral"/>
						
						</div>
					</div></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<script>

function change(){
	var txt = "confirm";
	document.getElementById("my_field").value = txt;
}
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	jQuery(document).ready(function(){
		var addval = '<?php echo $check_option; ?>';
		if(addval == ''){
			$("#crcnum").prop("selectedIndex", 0);
		}else{
			var check = 0; 
			$('#crcnum > option').each(function() {
			    var str1 = $(this).text();
			    var str2 = '<?php echo $check_option; ?>';
			    if(str1.indexOf(str2) != -1){
			    	check = 1;
				    $(this).prop('selected', true);
				}else{
				    $(this).prop('selected', false);
				}
			});
			if(check == 0){
				$('#crcnum > option').each(function() {
				    var str1 = $(this).text();
				    var str2 = '<?php echo $check_val; ?>';
				    if(str1.indexOf(str2) != -1){
					    $(this).prop('selected', true);
					}else{
					    $(this).prop('selected', false);
					}
				});
			}
		}
    // jQuery('#editForm').on('submit', function(e){
    // 	document.location.href = 'https://netptdev.wsptn.com/?add=13890%20Palmdale%20RdVictorville,%20CA%2092392,%20USA';
    //     e.preventDefault();
   
    // })
    });
</script>

<?php } ?>
