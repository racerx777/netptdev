<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(66); 
$selected=$_POST['checkbox'];
if(count($selected)>1) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');
	$doctorlist=getDoctorList();
	if(count($doctorlist) > 0) {
		$doctorlistoptions =  getSelectOptions(
			$arrayofarrayitems=$doctorlist, 
			$optionvaluefield='dmid', 
			$arrayofoptionfields=array(
				'dmlname'=>', ', 
				'dmfname'=>'' 
				), 
			$defaultoption=NULL, 
			$addblankoption=FALSE, 
			$arraykey='dmid', 
			$arrayofmatchvalues=$selected); 
?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large;">Merge Selected Doctors</legend>
	<form method="post" name="selectmergedoctor">
		<table>
			<tr>
				<td>Merge into Doctor</td>
				<td><select id="todoctor" name="todoctor" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['crrefdmid'])) echo $_POST['crrefdmid'];?>" />
						<?php echo $doctorlistoptions; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2"><input name="button[]" type="submit" value="Confirm Merge Selected Doctors" />
<?php
foreach($selected as $key=>$val)
	echo('<input name="checkbox['.$key.']" type="hidden" value="'.$val.'" />');
?>
				</td>
			</tr>
		</table>
	</form>
	</fieldset>
</div>
<?php
	}
}
?>
