<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(21); 

// Connect to database 
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


function checkentry($row) {
	$cnum=$row['thcnum'];
	$lname=$row['thlname'];
	$fname=$row['thfname'];
	$entryquery="
		SELECT paid, crptosstatus
		FROM patients
		JOIN cases
		ON paid=crpaid
		WHERE palname = '$lname' and pafname = '$fname' and crcnum = '$cnum' and crcasestatuscode = 'ACT'
	";
	if($entryresult=mysqli_query($dbhandle,$entryquery)) {
		$numrows=mysqli_num_rows($entryresult);
		if($numrows==1) {
			if($entryrow=mysqli_fetch_assoc($entryresult)) 
				return($entryrow['crptosstatus']);
		}
		else
			if(!empty($numrows))
				return("Maybe, Found $numrows");
	}
	return(FALSE);
}

function checkauth($row) {
	$cnum=$row['thcnum'];
	$lname=$row['thlname'];
	$fname=$row['thfname'];
	$authquery="
		SELECT paid, cricid1, cpauthstatuscode, cpauthstatusupdated
		FROM patients
		JOIN cases
		ON paid=crpaid
		LEFT JOIN case_prescriptions
		ON crid=cpcrid and crdate=cpdate
		WHERE palname = '$lname' and pafname = '$fname' and crcnum = '$cnum' and crcasestatuscode='ACT'
	";
	if($authresult=mysqli_query($dbhandle,$authquery)) {
		$numrows=mysqli_num_rows($authresult);
		if($numrows==1) {
			if($authrow=mysqli_fetch_assoc($authresult)) {
				$msg = $authrow['cpauthstatuscode'] . " " . displayDate($authrow['cpauthstatusupdated']);
				if(empty($authrow['cricid1']))
					return("NO INS:$msg");
				else
					return("HAS INS:$msg");
			}
		}
		else
			if(!empty($numrows))
				return("Maybe, Found $numrows");
	}
	return(FALSE);
}

function get_ptos_pnums() {
	$ptos_pnums=array();
	$ptos_pnums_query="SELECT bnum, cnum, pnum, lname, fname, acctype FROM ptos_pnums";
	if($ptos_pnums_result=mysqli_query($dbhandle,$ptos_pnums_query)) {
		while($ptos_pnums_row=mysqli_fetch_assoc($ptos_pnums_result)) {
			$bnum=$ptos_pnums_row['bnum'];
			$cnum=$ptos_pnums_row['cnum'];
			$pnum=$ptos_pnums_row['pnum'];
			$lname=$ptos_pnums_row['lname'];
			$fname=$ptos_pnums_row['fname'];
			$acctype=$ptos_pnums_row['acctype'];
			$lnamefname=$lname.$fname;
			$ptos_pnums_byname["$lnamefname"]["$cnum"][]=$pnum;
			$ptos_pnums_bynum["$pnum"][]=$ptos_pnums_row; 
		}
		$ptos_pnums['byname']=$ptos_pnums_byname;
		$ptos_pnums['bynum']=$ptos_pnums_bynum;
	}
	return($ptos_pnums);
}

function getPNUMx($row) {
	$cnum=$row['thcnum'];
	$lname=$row['thlname'];
	$fname=$row['thfname'];
//	$pnumquery="
//		SELECT bnum, cnum, pnum from patients_active where lname = '$lname' and fname = '$fname' and cnum = '$cnum'
//	";
	$pnumquery="
		SELECT bnum, cnum, pnum from ptos_pnums where lname<>'' and fname<>'' and acctype<>'' and cnum<>'' and lname='$lname' and fname='$fname' and cnum='$cnum'
	";
	if($pnumresult=mysqli_query($dbhandle,$pnumquery)) {
		$numrows=mysqli_num_rows($pnumresult);
		if($numrows==1) {
			if($pnumrow=mysqli_fetch_assoc($pnumresult)) {
//				$message=$pnumrow['bnum'] . " Clinic " . $pnumrow['cnum'] . " Patient ". $pnumrow['pnum'];
				return($pnumrow['pnum']);
			}
		}
	}
	return(FALSE);
}

function getPNUM($row, $ptos_pnums, $quiet=false) {
	$result=getPNUMbyname($row, $ptos_pnums);
	$pnum=$result['pnum'];
	$message=$result['message'];
	if(!$quiet)
		echo("$message <br />");
	return($pnum);
}

function getPNUMbyname($row, $ptos_pnums) {
	$result=false;
	$pnum=$row['thpnum'];
	if(empty($pnum)) {;
		$byname=$ptos_pnums['byname'];
		unset($pnum);
		unset($message);
		$lname=$row['thlname'];
		$fname=$row['thfname'];
		$lnamefname=$lname.$fname;
		$clinics=$byname["$lnamefname"];
		if(is_array($clinics)) { // GARCIAJOSE
			$cnum=$row['thcnum'];
			if(count($clinics["$cnum"])==1) { // there is only one of that name at this clinic
				$pnum=$clinics["$cnum"][0];
				$message="PNUM $pnum matched only one patient $fname $lname at clinic $cnum.";
			}
			else { // more than one patient at this clinic, go through all clinics
				unset($pnum);
				$pnumcount=0;
				$pnumlist=array();
				foreach($clinics as $clinic=>$pnums) {
					if(is_array($pnums)) 
						$pnumcount=$pnumcount+count($pnums);
					else
						$pnumcount++;
					$pnumlist["$clinic"]="clinic $clinic:".implode(",",$pnums);
				}
				$message=$pnumcount." patient numbers found with the patient name $fname $lname at the following:".implode(":", $pnumlist);
			}
		}
		else {
			$message=" No patient numbers found with the patient name $fname $lname.";
		}
		$result['pnum']=$pnum;
		$result['message']=$message;
	}
	else {
		$result['pnum']=$pnum;
		$result['message']="PNUM $pnum provided by thpnum.";
	}
	return($result);
}

function updatetransactionstatus($pnum, $row) {
	$pnum=trim($pnum);
	if(!empty($pnum)) {
		$cnum=$row['thcnum'];
		$lname=$row['thlname'];
		$fname=$row['thfname'];
		$thquery="
			SELECT thid from treatment_header where thlname = '$lname' and thfname = '$fname' and thcnum = '$cnum' and thsbmstatus between '300' and '399'
		";
		if($thresult=mysqli_query($dbhandle,$thquery)) {
			$thnumrows=mysqli_num_rows($thresult);
			if($thnumrows > 0) {
				$errors=0;
				while($throw=mysqli_fetch_assoc($thresult)) {
					$id=$throw['thid'];
					$updatequery="
						UPDATE treatment_header 
						SET thpnum='$pnum', thsbmstatus='150', upduser='dashboard.php' 
						WHERE (thpnum='' or thpnum IS NULL or thpnum='$pnum') and thid='$id' and thlname = '$lname' and thfname = '$fname' and thcnum = '$cnum' and thsbmstatus between '300' and '399'
						";
					if($updateresult=mysqli_query($dbhandle,$updatequery)) {
						if( $updaterowcount=mysql_affected_rows() ) {
							if($updaterowcount > 0) 
								notify("000","$bnum clinic $cnum patient $pnum transaction $id - $lname, $fname was found in PTOS and sent to UR.");
							else
								info("000","$bnum clinic $cnum patient $pnum transaction $id - $lname, $fname was found by name and clinic in PTOS, but the transaction has a patient number specified already and was NOT automatically sent to UR.");
						}
						else
							info("000","$bnum clinic $cnum patient $pnum transaction $id - $lname, $fname was found by name and clinic in PTOS, but the transaction has a patient number specified already therefore the transactions were NOT automatically sent to UR. You may want to search for this transaction to verify the Account number specified. This patient may be a readmit or simply have the same name as another patient at this clinic.<br>");
				}
					else
						error("000","$bnum clinic $cnum patient $pnum transaction $id - $lname, $fname was NOT UPDATED SUCCESSFULLY.<br>$updatequery<br>".mysqli_error($dbhandle));
				}
				if($errors==0) 
					return(TRUE);
			}
			else
				error("999","clinic $cnum - $lname, $fname - No Transactions found.<br>$thquery");
		}
		else
			error("999","SELECT query error<br>$thquery<br>".mysqli_error($dbhandle));
	}
	return(FALSE);
}

//
// MAIN LOOP
//

$usercliniclist = getUserClinicsList();
	$inqueuequery  = "
SELECT s.*
FROM (
SELECT thcnum, thlname, thfname, min(thid) as thid, count(*) as countwaiting, min(thdate) as firstwaiting, max(thdate) as lastwaiting, min(thsbmdate) as thsbmdate
FROM treatment_header
WHERE thsbmstatus between '300' and '399' and thcnum in " . $usercliniclist . " 
GROUP BY thcnum, thlname, thfname) as s
ORDER BY s.firstwaiting, s.countwaiting, thcnum, thlname, thfname
";

$inqueueresult = mysqli_query($dbhandle,$inqueuequery);
if(!$inqueueresult) {
		error("002","MySql[inbillingresult]:$inqueuequery " . mysqli_error($dbhandle));	
		$numRows = 0;
}
else 
	$numRows = mysqli_num_rows($inqueueresult);
?>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large">Patient Entry Dashboard</legend>
	<?php
if($numRows>0) {
	$nowdate = date('Y/m/d', time());
	$ptos_pnums=get_ptos_pnums();
//	dump("ptos_pnums",$ptos_pnums);
	echo("$numRows patients in list.<br>");
?>
	<div class="containedBox">
		<form method="post" name="searchlist">
			<table border="1" cellpadding="3" cellspacing="0" width="100%">
				<tr>
					<th>Clinic</th>
					<th>Patient</th>
					<th>Patient Last Name</th>
					<th>Patient First Name</th>
					<th>Entry</th>
					<th>Authorization</th>
					<th>Pending Treatments</th>
					<th>&nbsp;</th>
				</tr>
				<?php
	while($row = mysqli_fetch_assoc($inqueueresult)) {
		unset($entry);
		unset($auth);
		unset($pnum);
		if($pnum=getPNUM($row, $ptos_pnums, $quiet=true)) {
			echo("Attempting to update transaction pnum and status for ".$pnum.' '.$row['thfname'].' '.$row['thlname'].'.<br />');
			updatetransactionstatus($pnum, $row); // process slows
		}
//		if(empty($row['thpnum'])) {
//			if($pnumarray=getPNUM($row, $ptos_pnums, $quiet=false)) 
//				if(isarray($pnumarray['pnum'])) { // more than one patient at this clinic 
//				}
//				else 
//					updatetransactionstatus($pnumarray['pnum'], $row); // process slows
//			else {
//				$pnum="";
//				$entry=checkentry($row); // Query
//				$auth=checkauth($row); // Query
//			}
//		}
//		else
//			$pnum=$row['thpnum'];
			$searchurl="/modules/treatmentsearch/search.php?searchcnum=".$row["thcnum"]."&searchlname=".$row["thlname"]."&searchfname=".$row["thfname"]."&searchfromtreatmentdate=".date("m/d/Y", strtotime($row["firstwaiting"]))."&searchtotreatmentdate=".date("m/d/Y", strtotime($row["lastwaiting"]))."&searchfunction=Search";
?>
				<tr>
					<td><?php echo($row["thcnum"]); ?>&nbsp;</td>
					<td><?php echo($pnum); ?>&nbsp;</td>
					<td><?php echo($row["thlname"]); ?>&nbsp;</td>
					<td><?php echo($row["thfname"]); ?>&nbsp;</td>
					<td><?php echo($entry); ?> &nbsp;</td>
					<td><?php echo($auth); ?> &nbsp;</td>
					<td><?php echo($row['countwaiting'] . " pending from " . date("m/d/Y", strtotime($row["firstwaiting"])) . "-" . date("m/d/Y", strtotime($row["lastwaiting"]))); ?>&nbsp;</td>
					<td>
						<div id="functions">
							<div>
	<!--						<input id="dummycheckbox" type="checkbox" title="Entered Into Billing System" />-->
							<input name="button[<?php echo $row["thid"]; ?>]" type="submit" value="To UR" />
							<input name="button[<?php echo $row["thid"]; ?>]" type="submit" value="Patient Entered" />
							<input id="search" type="button" value="search" onclick="javascript:window.open('<?php echo $searchurl; ?>','searchwindow');" />
							</div>
							<div>Submitted on <?php echo date('m/d/Y', strtotime($row['thsbmdate'])); ?></div>
						</div>
					</td>
				</tr>
				<?php
	}
?>
			</table>
		</form>
		<div class="donotprintthis" style="float:right">
			<input name="print" type="button" value="Print" onclick="window.print();">
		</div>
		<?php
}
else {
	echo('No treatments found.');
}
mysqli_close($dbhandle);
?>
	</div>
	</fieldset>
</div>
<?php displaysitemessages() ?>