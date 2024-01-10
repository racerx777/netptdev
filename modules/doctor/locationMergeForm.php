<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(66); 
$dmid=$_POST['dmid'];
if(!empty($dmid)) {
	$selected=$_POST['checkbox'];
	if(count($selected)>1) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/doctor.options.php');
		$doctorlocationlist=getDoctorLocationList($dmid);
		if(count($doctorlocationlist) > 0) {
			$doctorlocationlistoptions =  getSelectOptions(
				$arrayofarrayitems=$doctorlocationlist, 
				$optionvaluefield='dlid', 
				$arrayofoptionfields=array(
					'dlname'=>', ', 
					'dladdress'=>'',
					'dlcity'=>'',
					'dlzip'=>'',
					'dlphone'=>'',
					'dlfax'=>'',
					'dlterritory'=>''
					), 
				$defaultoption=NULL, 
				$addblankoption=FALSE, 
				$arraykey='dlid', 
				$arrayofmatchvalues=$selected); 
	?>
	
	<div class="containedBox">
		<fieldset>
		<legend style="font-size:large;">Merge Selected Doctor Locations for Doctor Id: <?php echo $dmid;?></legend>
		<form method="post" name="selectmergedoctorlocation">
			<table>
				<tr>
					<td>Merge into Doctor Location</td>
					<td><select id="todoctorlocation" name="todoctorlocation" type="text" size="1" maxlength="30" value="<?php if(isset($_POST['crrefdlid'])) echo $_POST['crrefdlid'];?>" />
							<?php echo $doctorlocationlistoptions; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2"><input name="button[]" type="submit" value="Confirm Merge Selected Doctor Locations" />
	<?php
	foreach($selected as $key=>$val)
		echo('<input name="checkbox['.$key.']" type="hidden" value="'.$val.'" />');
	echo('<input name="dmid" type="hidden" value="'.$dmid.'" />');
	?>
					</td>
				</tr>
			</table>
		</form>
		</fieldset>
	</div>
	<?php
		}
		else
			error("997","Could not retrieve doctor location list.".dump("DoctorLocationList",$doctorlocationlist));
	}
	else
		error("998","Must select more than one location." . count($selected));
}
else
	error("999","Missing Doctor id. $dmid");
?>
