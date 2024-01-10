<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(13); 

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


require_once($_SERVER['DOCUMENT_ROOT'] . '/common/clinic.options.php');

$useraccessclinics="'".implode("','", array_keys($_SESSION['useraccess']['clinics']))."'";

//$clinicinformation=getClinicInformation($default['crcnum'], 1);

$where = "WHERE rhcnum IN ($useraccessclinics) and rhcrid=0";

$query  = "
	SELECT * 
	FROM report_header 
	LEFT JOIN master_clinics
	ON rhcnum=cmcnum
	$where
	ORDER BY rhlname, rhfname, rhvisitdate
	LIMIT 100
";

//dump("query",$query);
	if($result = mysqli_query($dbhandle,$query)) {
		$numRows = mysqli_num_rows($result);
	?>

<div class="containedBox">
	<fieldset>
	<legend style="font-size:large;">Unassigned Walk In Patient Report List:</legend>
	<form method="post" name="searchResultsWalkIns">
		<?php
		if($numRows > 0) {
			if($numRows == 1)
				echo "$numRows rows found.";
			else {
				if($numRows < 100)
					echo "$numRows rows found.";
				else
					echo "Over $numRows rows found. Did not display all rows.";
			}
	?>
		<table border="1" cellpadding="3" cellspacing="0" width="100%">
			<tr>
				<th>&nbsp;</th>
				<th>Patient Number</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>DOB</th>
				<th>Phone</th>
				<th>SSN</th>
				<th>DOI</th>
				<th>Clinic</th>
				<th>First Appt</th>
				<th>ICD9</th>
				<th>Status</th>
				<th>Therapy</th>
				<th>Report ID</th>
			</tr>
			<?php
			$icd9CodeOptions=icd9CodeOptions(1);
			$bodypartCodeOptions=bodypartCodeOptions(1);
			$bodypartdescriptorCodeOptions=bodypartdescriptorCodeOptions(1);

			while($row = mysqli_fetch_assoc($result)) {
				$rowid=$row['rhid'];
				$icd9codes=array();
				if(!empty($row['cricd9code1']))
					$icd9codes[]=$row['cricd9code1'];
				if(!empty($row['cricd9code2']))
					$icd9codes[]=$row['cricd9code2'];
				if(!empty($row['cricd9code3']))
					$icd9codes[]=$row['cricd9code3'];
				if(!empty($row['cricd9code4']))
					$icd9codes[]=$row['cricd9code4'];
				$icd9codeslist=implode(", ",$icd9codes);
				$descriptor=$row['crdxbodydescriptor'];
				$bodypart=$bodypartCodeOptions[$row['crdxbodypart']]['description'];
				$injurydescription=substr("$icd9codeslist $descriptor $bodypart",0,28);
// Create Report HTML for this case and count reports
				$reportcount=0;
				$reportquery="select * from report_header where rhid='$rowid'";
				if($reportresult=mysqli_query($dbhandle,$reportquery)) 
					$reportcount=mysqli_num_rows($reportresult);
				if(empty($reportcount))
					unset($reportrowspan);
				else
					$reportrowspan = 'rowspan="' . ($reportcount+1) . '"';
?>
			<tr<?php echo "$rowstyle"; ?>>
				<td valign="top" align="right" ><input name="button[<?php echo $row["rhid"]?>]" type="submit" value="Assign" /></td>
				<td><?php 
					if(!empty($row["rhpnum"])) 
						echo $row['rhpnum']; 
					else 
						echo 'WALK-IN PATIENT'; 
					?>&nbsp;</td>
				<td><?php echo $row["rhlname"]; ?>&nbsp;</td>
				<td><?php echo $row["rhfname"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["rhdob"]); ?>&nbsp;</td>
				<td><?php echo displayPhone($row["rhphone1"]); ?>&nbsp;</td>
				<td><?php echo displaySsn($row["rhssn"]); ?>&nbsp;</td>
				<td><?php echo displayDate($row["rhinjurydate"]); ?>&nbsp;</td>
				<td><?php echo $row["rhcnum"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["rhvisitdate"]); ?>&nbsp;</td>
				<td><?php echo $injurydescription ?></td>
				<td><?php echo $row["rhcasestatuscode"]; ?>&nbsp;</td>
				<td><?php echo $row["rhtherapytypecode"]; ?>&nbsp;</td>
				<td><?php echo $row["rhid"]; ?>&nbsp;</td>
			</tr>
			<?php
				while($reportrow=mysqli_fetch_assoc($reportresult)) {
					$rhid=$reportrow['rhid'];

					$icd9codes=array();
					if(!empty($row['rhicd9code1']))
						$icd9codes[1]=$row['rhicd9code1'];
					if(!empty($row['rhicd9code2']))
						$icd9codes[2]=$row['rhicd9code2'];
					if(!empty($row['rhicd9code3']))
						$icd9codes[3]=$row['rhicd9code3'];
					if(!empty($row['rhicd9code4']))
						$icd9codes[4]=$row['rhicd9code4'];
	
					$icd9dxbodyparts=array();
					if(!empty($row['rhicd9dxbodypart1']))
						$icd9dxbodyparts[1]=$row['rhicd9dxbodypart1'];
					if(!empty($row['rhicd9dxbodypart2']))
						$icd9dxbodyparts[2]=$row['rhicd9dxbodypart2'];
					if(!empty($row['rhicd9dxbodypart3']))
						$icd9dxbodyparts[3]=$row['rhicd9dxbodypart3'];
					if(!empty($row['rhicd9dxbodypart4']))
						$icd9dxbodyparts[4]=$row['rhicd9dxbodypart4'];
	
					$icd9dxbodydescriptors=array();
					if(!empty($row['rhicd9dxbodydescriptor1']))
						$icd9dxbodydescriptors[1]=$row['rhicd9dxbodydescriptor1'];
					if(!empty($row['rhicd9dxbodydescriptor2']))
						$icd9dxbodydescriptors[2]=$row['rhicd9dxbodydescriptor2'];
					if(!empty($row['rhicd9dxbodydescriptor3']))
						$icd9dxbodydescriptors[3]=$row['rhicd9dxbodydescriptor3'];
					if(!empty($row['rhicd9dxbodydescriptor4']))
						$icd9dxbodydescriptors[4]=$row['rhicd9dxbodydescriptor4'];
	
					$descriptions=array();
					foreach($icd9codes as $index=>$code) {
						$icd9description=$icd9CodeOptions["$code"]['description'];
						$descriptordescription=$bodypartdescriptorCodeOptions[$icd9dxbodydescriptors["$index"]]['description'];
						$bodypartdescription=$bodypartCodeOptions[$icd9dxbodyparts["$index"]]['description'];
						if(strlen($icd9description)>20)
							$icd9description=substr($icd9description,0,25)."...";
						if(strlen($descriptordescription)>9)
							$descriptordescription=substr($descriptordescription,0,9)."...";
						if(strlen($bodypartdescription)>20)
							$bodypartdescription=substr($bodypartdescription,0,25)."...";
	
						$descriptions["$index"]="$icd9description $descriptordescription $bodypartdescription";
					}

					$reportinjurydescription=implode("<br />",$descriptions);

switch($reportrow['rhstatus']) {
	case 'NEW':
		$disableedit='';
		$disablegenerate='disabled="disabled"';
		$disableview='disabled="disabled"';
		$disablefile='disabled="disabled"';
		$disabledelete='';
		if(!empty($reportrow['rhvisitdate']))
			$dateparm=$reportrow['rhvisitdate'];
		else
			$dateparm=$reportrow['rhdate'];
		$rowcolorstyle=getRowColorStyle($dateparm);
		break;
	case 'GENERATED':
		$disableedit='';
		$disablegenerate='disabled="disabled"';
		$disableview='';
		$disablefile='';
		$disabledelete='';
		if(!empty($reportrow['rhvisitdate']))
			$dateparm=$reportrow['rhvisitdate'];
		else
			$dateparm=$reportrow['rhdate'];
		$rowcolorstyle=getRowColorStyle($dateparm);
		break;
	case 'FILED':
		$disableedit='disabled="disabled"';
		$disablegenerate='disabled="disabled"';
		$disableview='';
		$disablefile='disabled="disabled"';
		$disabledelete='disabled="disabled"';
		$rowcolorstyle='';
		break;
	default:
		$disableedit='disabled="disabled"';
		$disablegenerate='disabled="disabled"';
		$disableview='disabled="disabled"';
		$disablefile='disabled="disabled"';
		$disabledelete='disabled="disabled"';
		break;
}
?>
			<tr>
				<td <?php echo $rowcolorstyle; ?>>&nbsp;</td>
				<td colspan="13">
					<input name="button[<?php echo $reportrow["rhid"]?>]" type="submit" value="Edit" <?php echo $disableedit; ?> />
					<input type="hidden" name="bumcode" id="bumcode" value="<?php echo $row['cmbnum']; ?>" />
					<input type="hidden" name="pgmcode" id="pgmcode" value="<?php echo $row['cmpgmcode']; ?>" />
					<input type="hidden" name="crcnum" id="crcnum" value="<?php echo $default['crcnum']; ?>" />
<?php
/*					<input name="button[<?php echo $rhid; ?>]" type="submit" value="Generate" <?php echo $disablegenerate; ?> />*/
//					$url = "'/modules/reportmanager/viewReport.php?rhid=$rhid'";
//					$title="'View Report'";
//					$width="'height=1024px,width=1280px,scrollbars=yes'";
//					$onclick='onclick="window.open('.$url.','.$title.','.$width.');"';
//					$input="<input name='button[$rhid]' type='submit' value='View' $onclick $disableview. />";
//					echo $input;
?>
				<?php echo displayDate($reportrow['rhvisitdate']) . " " .getReportDescription($reportrow['rhrtid']); ?> 
<?php
					if($reportrow['rharchivestatus']!='ARCHIVED') 
						echo '<input name="button['.$reportrow["rhid"].']" type="submit" value="Delete" />';
					else
						echo("&nbsp;");
?>
				</td>
			</tr>
			<?php
				} 
?>
			<tr<?php echo $rowstyle; ?>> </tr>
			<?php
			}
			foreach($_POST as $key=>$val)
				unset($_POST[$key]);
		?>
		</table>
	<?php
		}
		else 
			echo('No patients found.');
	}
	else 
		error('001', "QUERY:" . $query . ":" . mysqli_error($dbhandle));
	//close the connection
	mysqli_close($dbhandle);
	?>
		<div>
		<input type="hidden" name="bumcode" id="bumcode" value="<?php echo $clinicinformation['cmbnum']; ?>" />
		<input type="hidden" name="pgmcode" id="pgmcode" value="<?php echo $clinicinformation['cmpgmcode']; ?>" />
		<input name="crcnum" type="hidden" value="<?php echo $default['crcnum']; ?>" />
		<input id="WalkinButton" name="buttonWalkIn" type="submit" value="Add Report (Walk In)" /></div>
		</form>
	</fieldset>
</div>
<?php
displaysitemessages();
?>
