<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
?>
<script type="text/javascript">
function getCheckedValue(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}

function enablecallback(){
	var callback = document.getElementById('callback');
	callback.disabled=false;
}
function enableschedule(){
	var schedule = document.getElementById('schedule');
	schedule.disabled=false;
}
function enabledone(){
	var done = document.getElementById('done');
	done.disabled=false;
}
function disablecallback(){
	var callback = document.getElementById('callback');
	callback.disabled=true;
}
function disableschedule(){
	var schedule = document.getElementById('schedule');
	schedule.disabled=true;
}
function disabledone(){
	var done = document.getElementById('done');
	done.disabled=true;
}
function checkformstate() {
//	var phone1_nc=getCheckedValue(document.getElementById('phone1_nc'));
	var phone1_b=getCheckedValue(document.getElementById('phone1_b'));
	var phone1_na=getCheckedValue(document.getElementById('phone1_na'));
	var phone1_am=getCheckedValue(document.getElementById('phone1_am'));
	var phone1_ci=getCheckedValue(document.getElementById('phone1_ci'));
	
//	var phone2_nc=getCheckedValue(document.getElementById('phone2_nc'));
	var phone2_b=getCheckedValue(document.getElementById('phone2_b'));
	var phone2_na=getCheckedValue(document.getElementById('phone2_na'));
	var phone2_am=getCheckedValue(document.getElementById('phone2_am'));
	var phone2_ci=getCheckedValue(document.getElementById('phone2_ci'));
	
//	var phone3_nc=getCheckedValue(document.getElementById('phone3_nc'));
	var phone3_b=getCheckedValue(document.getElementById('phone3_b'));
	var phone3_na=getCheckedValue(document.getElementById('phone3_na'));
	var phone3_am=getCheckedValue(document.getElementById('phone3_am'));
	var phone3_ci=getCheckedValue(document.getElementById('phone3_ci'));

//	var phonedoc_nc=getCheckedValue(document.getElementById('phonedoc_nc'));
	var phonedoc_c=getCheckedValue(document.getElementById('phonedoc_c'));
	var phonedoc_ci=getCheckedValue(document.getElementById('phonedoc_ci'));
	
//	disablecallback();
//	disableschedule();
	disabledone();
	
	if(phone1_b!='' || phone1_na!='' || phone1_am!='' || phone1_ci!='') {
		enablecallback();
		enableschedule();
		enabledone();
	}
	if(phone2_b!='' || phone2_na!='' || phone2_am!='' || phone2_ci!='') {
		enablecallback();
		enableschedule();
		enabledone();
	}
	if(phone3_b!='' || phone3_na!='' || phone3_am!='' || phone3_ci!='') {
		enablecallback();
		enableschedule();
		enabledone();
	}
	if(phonedoc_c!='' || phonedoc_ci!='') {
		enablecallback();
		enabledone();
	}
}
</script>
<?php
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

// Select Call Record $callid
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

unset($caseid);
// if another application requesting contact
if(isset($_POST['contactreferral'])) 
	$caseid = urldecode($_POST['contactreferral']);


unset($callid);
$user = getuser();
$bodypartcodeoptions=bodypartCodeOptions(1);
// if case id is provided, then retrieve current call record and display
if(!empty($caseid)) {
	$casequery  = "SELECT csqid FROM case_scheduling_queue where csqcrid = '$caseid' limit 1";
	if($caseresult = mysqli_query($dbhandle,$casequery)) {
		if($caserow = mysqli_fetch_assoc($caseresult)) {
// Update LockUser and LockTime
			if($callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $caserow['csqid'])) {
//				echo($callid);
			}
			else {
				echo $_SESSION['button'];
			}
		}
		else {
// No call Record, Set Status to PEN and Create Call record
			error("090", "No Call Queue Entry.");
			unset($callid);
		}
	}
	else
		error("001", "lockquery:" . $lockquery . "<br>Error:" . mysqli_error($dbhandle));
}
$pcallid = $callid;
if(empty($callid)) {
// Check for records that are locked by the current user and need to be called (schcalldate)
	$lockquery  = "
		SELECT csqid 
		FROM case_scheduling_queue 
		WHERE lockuser = '$user' 
		AND csqschcalldate < (NOW() + INTERVAL 5 MINUTE) 
		LIMIT 1
		";
//dump("lockquery",$lockquery);
	if($lockresult = mysqli_query($dbhandle,$lockquery)) {
		if($lockrow = mysqli_fetch_assoc($lockresult)) {
// Update LockUser and LockTime
			if($callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $lockrow['csqid'])) {
//				echo($callid);
			}
			else
				error("001",$lockquery);
		}
//		else
//			error("011", "lockquery fetch:" . $lockquery . "<br>Error:" . mysqli_error($dbhandle));
	}
	else
		error("021", "lockquery query:" . $lockquery . "<br>Error:" . mysqli_error($dbhandle));
}

if(empty($callid)) {
// Check for calls
		$priorityselect = "SELECT csqid, csqpriority, csqschcalldate FROM case_scheduling_queue ";
		$prioritywhere = "WHERE csqresult IS NULL ";
		$priorityorderby = "ORDER BY csqpriority, csqschcalldate, csqid";

// Check for primary calls
		$priorityquery = "$priorityselect $prioritywhere AND csqpriority BETWEEN 10 AND 19 AND csqschcalldate <= NOW() - INTERVAL 5 MINUTE $priorityorderby";
//dump("priorityquery",$priorityquery);
		if($priorityresult = mysqli_query($dbhandle,$priorityquery)) {
//			if($priorityrow = mysqli_fetch_assoc($priorityresult)) {
			while($priorityrow = mysqli_fetch_assoc($priorityresult)) {
				if($callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $priorityrow['csqid'])) {
//					echo($callid);
					notify("000", "High Priority Record Retrieved.");
					break;
				}
			}
		}
		else
			error("002", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
	}

if(empty($callid)) {
		// Check for secondary calls
		$priorityquery = "$priorityselect $prioritywhere AND csqpriority BETWEEN 20 AND 29 AND csqschcalldate <= NOW() - INTERVAL 5 MINUTE $priorityorderby";
//dump("priorityquery",$priorityquery);
		if($priorityresult = mysqli_query($dbhandle,$priorityquery)) {
//			if($priorityrow = mysqli_fetch_assoc($priorityresult)) {
			while($priorityrow = mysqli_fetch_assoc($priorityresult)) {
				if($callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $priorityrow['csqid'])) {
//					echo($callid);
					break;
				}
			}
		}
		else
			error("003", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
	}

if(empty($callid)) {
// Check for remaining calls
		$priorityquery  = "$priorityselect $prioritywhere AND csqpriority > 29 $priorityorderby";
//dump("priorityquery",$priorityquery);
		if($priorityresult = mysqli_query($dbhandle,$priorityquery)) {
			while($priorityrow = mysqli_fetch_assoc($priorityresult)) {
				if($callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $priorityrow['csqid'])) {
//					echo($callid);
					break;
				}
			}
		}
		else
			error("004", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
	}

if(empty($callid)) {
		$nextquery  = "$priorityselect $prioritywhere $priorityorderby";
		if($nextresult = mysqli_query($dbhandle,$nextquery)) {
			if($nextrow = mysqli_fetch_assoc($nextresult)) {
				$nextpriority = $nextrow['csqpriority'];
				$nextschcalldate = $nextrow['csqschcalldate'];
				notify("000", "No Priority calls in queue.<br>Next priority $priority call scheduled for $nextschcalldate.");
			}
		}
}

if(isset($callid) && !empty($callid)) {
	// if(empty($pcallid) && !empty($caseid)){
	// 	$callquery = "
	// 		SELECT * 
	// 		FROM case_scheduling_queue 
	// 			RIGHT JOIN cases 
	// 			ON csqcrid=crid 
	// 			LEFT JOIN patients 
	// 			ON crpaid=paid 
	// 			LEFT JOIN doctors
	// 			ON crrefdmid=dmid
	// 			LEFT JOIN doctor_locations
	// 			ON crrefdlid=dlid
	// 		WHERE crid='$caseid'";
	// }else{
	    if($_SESSION['button'] == 'fromschedulequeuelist'){
	    	$callid = $_SESSION['id'];
	    } 
		$callquery = "
			SELECT * 
			FROM case_scheduling_queue 
				LEFT JOIN cases 
				ON csqcrid=crid 
				LEFT JOIN patients 
				ON crpaid=paid 
				LEFT JOIN doctors
				ON crrefdmid=dmid
				LEFT JOIN doctor_locations
				ON crrefdlid=dlid
			WHERE csqid='$callid'";
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

if(errorcount() !=0 || notifycount()!=0)
	displaysitemessages();

if(!empty($callid) && errorcount() == 0) {
$casetypecodes = caseTypeOptions();
$therapytypecodes = therapyTypeOptions();
$thiscasetype = $casetypecodes[$_POST['crcasetypecode']]["title"];
$thistherapytype = $therapytypecodes[$_POST['crtherapytypecode']]["title"];

$csqcrid = $_POST['csqcrid'];
$callhistory="";
$callhistoryquery = "
		SELECT crtdate, ucase(cshdata) as cshdata, ucase(crtuser) as crtuser 
		FROM case_scheduling_history 
		WHERE cshcrid='$csqcrid'
		";
$callhistory=array();
if($callhistoryresult = mysqli_query($dbhandle,$callhistoryquery)) {
	while($callhistoryrow = mysqli_fetch_assoc($callhistoryresult)) {
		$callhistorydate=displayDate($callhistoryrow['crtdate']);
		$callhistorytime=displayTime($callhistoryrow['crtdate']);
		$callhistorytext=$callhistoryrow['cshdata'];
		$callhistoryuser=$callhistoryrow['crtuser'];		
		$callhistory[] = "<tr><td width='75'>$callhistorydate</td><td width='75px'>$callhistorytime</td><td>$callhistorytext</td><td>$callhistoryuser</td></tr>";
	}
	if(count($callhistory)==0) 
		$callhistoryhtml='<tr><td colspan="4">No call history.</td></tr>';
	else
		$callhistoryhtml=implode("",$callhistory);
}
else
	error("801", "Call History SELECT error. $query<br>".mysqli_error($dbhandle));

if($_POST['crreadmit']==TRUE)
	$readmitnew = "*** READMIT PATIENT ";
if($_POST['crrelocate']==TRUE)
	$readmitnew = "*** RELOCATED PATIENT ";

if($_POST['crreadmit']!=TRUE && $_POST['crrelocate']!=TRUE)
	$readmitnew = "*** NEW PATIENT ";

if($_POST['crcasestatuscode']=='PEA')
	$readmitnew .= "PENDING AUTHORIZATION";
if($_POST['crcasestatuscode']=='PEN')
	$readmitnew .= "PENDING SCHEDULING";

$readmitnew .= " ***";
?>

<div class="centerFieldset">
	<form method ="post">
	<div class="menuTabItem" style="float: right;"><input type="submit" name="pending_list" value="Scheduled Pending List"></div>
	<?php if(isset($_POST['pending_list'])){
		
		require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/schedulingqueuelist/schedule-pendinglist.php'); ?>

<style>
	.centerFieldset fieldset {
    display: block;
}
</style>

<?php
		die();
	} ?>
</form>
	<form method="post" name="editForm">
		<fieldset style="text-align:left;">
		<legend style="font-size:xx-large; text-align:center"><?php echo $readmitnew; ?></legend>
		<table>
			<tr>
				<td valign="top" width="50%"><table width="100%">
						<tr>
							<th colspan="3">Patient Information</th>
						</tr>
						<tr>
							<td width="150px">Name</td>
							<td style="font-size:large;"><?php echo $_POST['pafname'] . " " . $_POST['palname']; ?></td>
							<td><input name="button[<?php echo($_POST['paid']); ?>]" type="submit" value="Edit Patient" /></td>
						</tr>
						<tr>
							<td>DOB</td>
							<td><?php echo displayDate($_POST['padob']);?></td>
							<td><input type="button" value="Print Sheet" onclick="window.open('<?php echo("/modules/scheduling/printPatientInformationSheet.php?crid=$csqcrid"); ?>');" /></td>
						</tr>
						<tr>
							<td>SSN</td>
							<td><?php echo displaySsn($_POST['passn']);?> </td>
							<td><input type="button" value="Print Letter" onclick="window.open('<?php echo("/modules/scheduling/printSchedulingUpdateLetter.php?crid=$csqcrid"); ?>');" /></td>
						</tr>
						<tr>
							<td>Gender</td>
							<td><?php echo $_POST['pasex'];?></td>
							<td><input type="button" value="Print Lien" disabled="disabled" /></td>
						</tr>
						<tr>
							<td>Address&nbsp;<?php echo('<a target="_blank" href="http://maps.google.com?q=' . urlencode($_POST['paaddress1']." ".$_POST['pacity'].",".$_POST['pastate']." ".$_POST['pazip']) . '">Map</a>');
			?></td>
							<td><?php echo $_POST['paaddress1'] . " " . $_POST['paaddress2'];?> </td>
						</tr>
						<tr>
							<td>City </td>
							<td><?php echo $_POST['pacity'];?> &nbsp;&nbsp;&nbsp;&nbsp;St <?php echo $_POST['pastate'];?> &nbsp;&nbsp;&nbsp;&nbsp;Zip <?php echo $_POST['pazip'];?> </td>
						</tr>
						<tr>
							<td>Patient Note</td>
							<td><?php echo $_POST['panote'];?> </td>
						</tr>
					</table></td>
				<td valign="top" width="50%"><table width="100%">
						<?php if(!empty($_POST['paphone1'])||!empty($_POST['paphone2'])||!empty($_POST['pacellphone'])) { ?>
						<tr>
							<th colspan="2">Patient #</th>
							<th>NoCal</th>
							<th>Busy</th>
							<th>NoAns</th>
							<th>AnsMach</th>
							<th>CallIn</th>
						</tr>
							<?php if(!empty($_POST['paphone1'])) { ?>
						<tr>
							<td width="100px" style="text-align:right;">Home:</td>
							<td><a href="tel:<?php echo displayPhone($_POST['paphone1']);?>">
							    <input type="button"value="<?php echo displayPhone($_POST['paphone1']);?>"></a>
							
							<input name="callphone1note[<?php echo($callid); ?>]" type="button" value="Note" /></td>
							<td><input type="radio" name="phone1[<?php echo($callid); ?>]" checked value="" id="phone1_nc" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone1[<?php echo($callid); ?>]" value="Busy" id="phone1_b" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone1[<?php echo($callid); ?>]" value="No Answer" id="phone1_na" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone1[<?php echo($callid); ?>]" value="Ans Mach" id="phone1_am" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone1[<?php echo($callid); ?>]" value="Call In" id="phone1_ci" onclick="javascript:checkformstate();" /></td>
						</tr>
							<?php } ?>
							<?php if(!empty($_POST['paphone2'])) { ?>
						<tr>
							<td style="text-align:right;">Work: </td>
							<td><?php echo displayPhone($_POST['paphone2']);?><input name="callphone2note[<?php echo($callid); ?>]" type="button" value="Note" /></td>
							<td><input type="radio" name="phone2[<?php echo($callid); ?>]" checked value="0" id="phone2_nc" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone2[<?php echo($callid); ?>]" value="Busy" id="phone2_b" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone2[<?php echo($callid); ?>]" value="No Answer" id="phone2_na" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone2[<?php echo($callid); ?>]" value="Ans Mach" id="phone2_am" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone2[<?php echo($callid); ?>]" value="Call In" id="phone2_ci" onclick="javascript:checkformstate();" /></td>
						</tr>
							<?php } ?>
							<?php if(!empty($_POST['pacellphone'])) { ?>
						<tr>
							<td style="text-align:right;">Mobile: </td>
							
							<td><a href="tel:<?php echo displayPhone($_POST['pacellphone']);?>">
							    <input type="button"value="<?php echo displayPhone($_POST['pacellphone']);?>"></a>
							    
							    <input name="callcellphonenote[<?php echo($callid); ?>]" type="button" value="Note" /></td>
							<td><input type="radio" name="phone3[<?php echo($callid); ?>]" checked value="0" id="phone3_nc" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone3[<?php echo($callid); ?>]" value="Busy" id="phone3_b" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone3[<?php echo($callid); ?>]" value="No Answer" id="phone3_na" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone3[<?php echo($callid); ?>]" value="Ans Mach" id="phone3_am" onclick="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phone3[<?php echo($callid); ?>]" value="Call In" id="phone3_ci" onclick="javascript:checkformstate();" /></td>
						</tr>
							<?php } ?>
						<?php } 
						else {
							echo ("<script>enablecallback();</script>");
						?>
						<tr>
							<th>No Telephone Numbers on file</th>
						</tr>
						<?php }?>
					</table></td>
			</tr>
			<tr>
				<td valign="top" width="50%"><table width="100%">
						<tr>
							<th colspan="2">Prescription Information </th>
						</tr>
						<tr>
							<td width="150px">Doctor</td>
							<td><?php echo $_POST['dmlname'] . ", " . $_POST['dmfname'];?></td>
						</tr>
						<tr>
							<td>City</td>
							<td><?php echo $_POST['dlcity'];?></td>
						</tr>
						<tr>
							<td>Referral Date</td>
							<td><?php echo displayDate($_POST['crdate']);?></td>
						</tr>
						<tr>
							<td>Case Type</td>
							<td><?php echo $_POST['crcasetypecode'] . "-" . $thiscasetype;?></td>
						</tr>
						<tr>
							<td>Therapy Type</td>
							<td><?php echo $_POST['crtherapytypecode'] . "-" . $thistherapytype;?></td>
						</tr>
						<tr>
							<td>Freq & Dur</td>
							<td><?php if(!empty($_POST['crfrequency']) && !empty($_POST['crduration'])) echo $_POST['crfrequency'] . "x" . $_POST['crduration']; else if(!empty($_POST['crtotalvisits'])) echo "Visits:".$_POST['crtotalvisits']; else echo "See Prescription"; ?></td>
						</tr>
						<tr>
							<td>Clinic</td>
							<td><?php if(!empty($_POST['crcnum'])) echo $_POST['crcnum']; else echo "Not Assigned"; ?></td>
						</tr>
						<tr>
							<td>Major Body Part</td>
							<td><?php if(!empty($_POST['crdxbodypart'])) echo $bodypartcodeoptions[$_POST['crdxbodypart']]['description']; else echo "Not Specified"; ?></td>
						</tr>
						<tr>
							<td>ICD9 Codes</td>
							<td><?php if(!empty($_POST['cricd9desc1'])) echo $_POST['cricd9desc1']; else echo "Not Specified"; if(!empty($_POST['cricd9desc2'])) echo "<br />".$_POST['cricd9desc2']; if(!empty($_POST['cricd9desc3'])) echo "<br />".$_POST['cricd9desc3']; if(!empty($_POST['cricd9desc4'])) echo "<br />".$_POST['cricd9desc4']; ?></td>
						</tr>
						<tr>
							<td>Case Note </td>
							<td><?php echo $_POST['crnote'];?> </td>
						</tr>
					</table></td>
				<td valign="top" width="50%"><table width="100%">
						<?php if(!empty($_POST['dlphone'])) { ?>
						<tr>
							<th colspan="2">Referrer #</th>
							<th>No Call</th>
							<th>Called</th>
							<th>CallIn</th>
						</tr>
						<?php if(!empty($_POST['dlphone'])) { ?>
						<tr>
							<td width="100px" style="text-align:right;">Doctor:</td>
							<td><a href="tel:<?php echo displayPhone($_POST['dlphone']);?>"><input type="button" value="<?php echo displayPhone($_POST['dlphone']);?>"></a>
							
							<input name="calldlphone[<?php echo($callid); ?>]" type="button" value="Note" onchange="javascript:checkformstate();" /></td>
							<td><input type="radio" name="phonedoc[<?php echo($callid); ?>]" checked value="0" id="phonedoc_nc" /></td>
							<td><input type="radio" name="phonedoc[<?php echo($callid); ?>]" value="15" id="phonedoc_c" /></td>
							<td><input type="radio" name="phonedoc[<?php echo($callid); ?>]" value="15" id="phonedoc_ci" /></td>
						</tr>
						<?php } ?>
						<?php } ?>
						<?php if(!empty($_POST['crapptdate']) || !empty($_POST['crapptscheduler']) || !empty($_POST['crapptdatescheduled'])) { ?>
						<tr>
							<th colspan="2">Previous Appt Information </th>
						</tr>
						<tr>
							<td nowrap="nowrap">Prev Appt</td>
							<td><?php echo displayDate($_POST['crapptdate']) . ' ' . displayTime($_POST['crapptdate']); ?></td>
						</tr>
						<tr>
							<td nowrap="nowrap">Sched By</td>
							<td><?php echo strtoupper($_POST['crapptscheduler']); ?></td>
						</tr>
						<tr>
							<td nowrap="nowrap">Sched On</td>
							<td><?php echo displayDate($_POST['crapptdatescheduled']) . ' ' . displayTime($_POST['crapptdatescheduled']); ?></td>
						</tr>
						<?php } ?>
					</table></td>
			</tr>
			<tr>
				<td valign="top" colspan="2" width="100%"><table style="text-align:center;" width="100%">
						<tr>
							<td><input style="background-color:#FFFF00; font-size:medium; height: 30px; width: 100px;" id="callback" name="button[<?php echo $callid; ?>]"  type="submit" value="Callback" /></td>
							<td><input style="background-color:green; font-size:medium; height: 30px; width: 100px;" id="schedule" name="button[<?php echo $callid; ?>]" type="submit"  value="Schedule" />
							</td>
							<td><input style="background-color:red; font-size:medium; height: 30px; width: 100px;" id="cancel" name="button[<?php echo $callid; ?>]" type="submit" value="Cancel" />
							</td>
							<td><input style="background-color:#FFFFFF; font-size:medium; height: 30px; width: 100px;" id="done" name="button[<?php echo $callid; ?>]" type="submit" disabled="disabled" value="Done" />
							</td>
						</tr>
					</table></td>
			</tr>
			<tr>
				<td valign="top" colspan="2" width="100%"><table width="100%">
						<tr>
							<th colspan="4">Call History</th>
						</tr>
						<?php echo $callhistoryhtml; ?>
					</table></td>
			</tr>
		</table>
		</fieldset>
	</form>
</div>
<?php
}
?>
