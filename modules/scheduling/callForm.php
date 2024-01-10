<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);

?>

<style>
	.tooltip {
		position: relative;
		display: inline-block;
		/* border-bottom: 1px dotted black; */
	}

	.tooltip .tooltiptext {
		visibility: hidden;
		width: 320px;
		background-color: black;
		color: #fff;
		text-align: center;
		border-radius: 6px;
		padding: 5px 0;

		/* Position the tooltip */
		position: absolute;
		z-index: 1;
	}

	.tooltip:hover .tooltiptext {
		visibility: visible;
	}
</style>

<script type="text/javascript">
	function getCheckedValue(radioObj) {
		if (!radioObj)
			return "";
		var radioLength = radioObj.length;
		if (radioLength == undefined)
			if (radioObj.checked)
				return radioObj.value;
			else
				return "";
		for (var i = 0; i < radioLength; i++) {
			if (radioObj[i].checked) {
				return radioObj[i].value;
			}
		}
		return "";
	}

	function enablecallback() {
		var callback = document.getElementById('callback');
		callback.disabled = false;
	}
	function enableschedule() {
		var schedule = document.getElementById('schedule');
		schedule.disabled = false;
	}
	function enabledone() {
		var done = document.getElementById('done');
		done.disabled = false;
	}
	function disablecallback() {
		var callback = document.getElementById('callback');
		callback.disabled = true;
	}
	function disableschedule() {
		var schedule = document.getElementById('schedule');
		schedule.disabled = true;
	}
	function disabledone() {
		var done = document.getElementById('done');
		done.disabled = true;
	}
	function checkformstate() {
		//	var phone1_nc=getCheckedValue(document.getElementById('phone1_nc'));
		var phone1_b = getCheckedValue(document.getElementById('phone1_b'));
		var phone1_na = getCheckedValue(document.getElementById('phone1_na'));
		var phone1_am = getCheckedValue(document.getElementById('phone1_am'));
		var phone1_ci = getCheckedValue(document.getElementById('phone1_ci'));

		//	var phone2_nc=getCheckedValue(document.getElementById('phone2_nc'));
		var phone2_b = getCheckedValue(document.getElementById('phone2_b'));
		var phone2_na = getCheckedValue(document.getElementById('phone2_na'));
		var phone2_am = getCheckedValue(document.getElementById('phone2_am'));
		var phone2_ci = getCheckedValue(document.getElementById('phone2_ci'));

		//	var phone3_nc=getCheckedValue(document.getElementById('phone3_nc'));
		var phone3_b = getCheckedValue(document.getElementById('phone3_b'));
		var phone3_na = getCheckedValue(document.getElementById('phone3_na'));
		var phone3_am = getCheckedValue(document.getElementById('phone3_am'));
		var phone3_ci = getCheckedValue(document.getElementById('phone3_ci'));

		//	var phonedoc_nc=getCheckedValue(document.getElementById('phonedoc_nc'));
		var phonedoc_c = getCheckedValue(document.getElementById('phonedoc_c'));
		var phonedoc_ci = getCheckedValue(document.getElementById('phonedoc_ci'));

		//	disablecallback();
		//	disableschedule();
		disabledone();

		if (phone1_b != '' || phone1_na != '' || phone1_am != '' || phone1_ci != '') {
			enablecallback();
			enableschedule();
			enabledone();
		}
		if (phone2_b != '' || phone2_na != '' || phone2_am != '' || phone2_ci != '') {
			enablecallback();
			enableschedule();
			enabledone();
		}
		if (phone3_b != '' || phone3_na != '' || phone3_am != '' || phone3_ci != '') {
			enablecallback();
			enableschedule();
			enabledone();
		}
		if (phonedoc_c != '' || phonedoc_ci != '') {
			enablecallback();
			enabledone();
		}
	}
</script>
<style>
	.swal2-html-container {
		font-size: 1rem !important;
	}

	.swal2-footer {

		font-size: 0.85rem !important;
	}
</style>

<?php
function phoneformat($str)
{
	$mystr = preg_replace("/[^0-9]/", "", $str);
	if (strlen($mystr) == 10) {
		$area = substr($mystr, 0, 3);
		$exch = substr($mystr, 3, 3);
		$numb = substr($mystr, 6, 4);
		$msg = "";
	} else {
		$area = "???";
		$exch = "???";
		$numb = "???";
		$msg = "Invalid phone format!";
	}
	return ("($area) $exch-$numb $msg");
}

// Select Call Record $callid
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

unset($caseid);
// if another application requesting contact
if (isset($_POST['contactreferral']))
	$caseid = urldecode($_POST['contactreferral']);


unset($callid);
$user = getuser();

// echo "<pre>";
// print_r($_SESSION['user']['umpass']);
$bodypartcodeoptions = bodypartCodeOptions(1);
// if case id is provided, then retrieve current call record and display
if (!empty($caseid)) {
	$casequery = "SELECT csqid FROM case_scheduling_queue where csqcrid = '$caseid' limit 1";
	if ($caseresult = mysqli_query($dbhandle, $casequery)) {
		if ($caserow = mysqli_fetch_assoc($caseresult)) {
			// Update LockUser and LockTime
			if ($callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $caserow['csqid'])) {
				//				echo($callid);
			} else {
				echo $_SESSION['button'];
			}
		} else {
			// No call Record, Set Status to PEN and Create Call record
			error("090", "No Call Queue Entry.");
			unset($callid);
		}
	} else
		error("001", "lockquery:" . $lockquery . "<br>Error:" . mysqli_error($dbhandle));
}
$pcallid = $callid;
if (empty($callid)) {
	// Check for records that are locked by the current user and need to be called (schcalldate)
	$lockquery = "
		SELECT csqid 
		FROM case_scheduling_queue 
		WHERE lockuser = '$user' 
		AND csqschcalldate < (NOW() + INTERVAL 5 MINUTE) 
		LIMIT 1
		";
	//dump("lockquery",$lockquery);
	if ($lockresult = mysqli_query($dbhandle, $lockquery)) {
		if ($lockrow = mysqli_fetch_assoc($lockresult)) {
			// Update LockUser and LockTime
			if ($callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $lockrow['csqid'])) {
				//				echo($callid);
			} else
				error("001", $lockquery);
		}
		//		else
//			error("011", "lockquery fetch:" . $lockquery . "<br>Error:" . mysqli_error($dbhandle));
	} else
		error("021", "lockquery query:" . $lockquery . "<br>Error:" . mysqli_error($dbhandle));
}

if (empty($callid)) {
	// Check for calls
	$priorityselect = "SELECT csqid, csqpriority, csqschcalldate FROM case_scheduling_queue ";
	$prioritywhere = "WHERE csqresult IS NULL ";
	$priorityorderby = "ORDER BY csqpriority, csqschcalldate, csqid";

	// Check for primary calls
	$priorityquery = "$priorityselect $prioritywhere AND csqpriority BETWEEN 10 AND 19 AND csqschcalldate <= NOW() - INTERVAL 5 MINUTE $priorityorderby";
	//dump("priorityquery",$priorityquery);
	if ($priorityresult = mysqli_query($dbhandle, $priorityquery)) {
		//			if($priorityrow = mysqli_fetch_assoc($priorityresult)) {
		while ($priorityrow = mysqli_fetch_assoc($priorityresult)) {
			if ($callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $priorityrow['csqid'])) {
				//					echo($callid);
				notify("000", "High Priority Record Retrieved.");
				break;
			}
		}
	} else
		error("002", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
}

if (empty($callid)) {
	// Check for secondary calls
	$priorityquery = "$priorityselect $prioritywhere AND csqpriority BETWEEN 20 AND 29 AND csqschcalldate <= NOW() - INTERVAL 5 MINUTE $priorityorderby";
	//dump("priorityquery",$priorityquery);
	if ($priorityresult = mysqli_query($dbhandle, $priorityquery)) {
		//			if($priorityrow = mysqli_fetch_assoc($priorityresult)) {
		while ($priorityrow = mysqli_fetch_assoc($priorityresult)) {
			if ($callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $priorityrow['csqid'])) {
				//					echo($callid);
				break;
			}
		}
	} else
		error("003", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
}

if (empty($callid)) {
	// Check for remaining calls
	$priorityquery = "$priorityselect $prioritywhere AND csqpriority > 29 $priorityorderby";
	//dump("priorityquery",$priorityquery);
	if ($priorityresult = mysqli_query($dbhandle, $priorityquery)) {
		while ($priorityrow = mysqli_fetch_assoc($priorityresult)) {
			if ($callid = lockrow($dbhandle, 'case_scheduling_queue', 'csqid', $priorityrow['csqid'])) {
				//					echo($callid);
				break;
			}
		}
	} else
		error("004", "priorityquery:" . $priorityquery . "<br>Error:" . mysqli_error($dbhandle));
}

if (empty($callid)) {
	$nextquery = "$priorityselect $prioritywhere $priorityorderby";
	if ($nextresult = mysqli_query($dbhandle, $nextquery)) {
		if ($nextrow = mysqli_fetch_assoc($nextresult)) {
			$nextpriority = $nextrow['csqpriority'];
			$nextschcalldate = $nextrow['csqschcalldate'];
			notify("000", "No Priority calls in queue.<br>Next priority $priority call scheduled for $nextschcalldate.");
		}
	}
}

if (isset($callid) && !empty($callid)) {
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
	if ($_SESSION['button'] == 'fromschedulequeuelist') {
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
	if ($callresult = mysqli_query($dbhandle, $callquery)) {
		if (mysqli_num_rows($callresult) == 1) {
			$callrow = mysqli_fetch_assoc($callresult);
			foreach ($callrow as $key => $val) {
				$_POST["$key"] = $val;
			}
		} else
			error("002", "Non-unique field error (should never happen).");
	} else
		error("001", mysqli_error($dbhandle));
}
// echo "<pre>";
// print_r($_SESSION['user']['umuser']);
// die();
if (errorcount() != 0 || notifycount() != 0)
	displaysitemessages();

if (!empty($callid) && errorcount() == 0) {
	$casetypecodes = caseTypeOptions();
	$therapytypecodes = therapyTypeOptions();
	$thiscasetype = $casetypecodes[$_POST['crcasetypecode']]["title"];
	$thistherapytype = $therapytypecodes[$_POST['crtherapytypecode']]["title"];
	$_SESSION['crtherapytypecode'] = $_POST['crtherapytypecode'];
	$csqcrid = $_POST['csqcrid'];
	// print_r($_POST['csqcrid']);

	$callhistory = "";
	$callhistoryquery = "
		SELECT crtdate, ucase(cshdata) as cshdata, ucase(crtuser) as crtuser ,crtnotes,crtstatus
		FROM case_scheduling_history 
		WHERE cshcrid='$csqcrid'
		";
	$callhistory = array();
	if ($callhistoryresult = mysqli_query($dbhandle, $callhistoryquery)) {
		while ($callhistoryrow = mysqli_fetch_assoc($callhistoryresult)) {
			$callhistorydate = displayDate($callhistoryrow['crtdate']);
			$callhistorytime = displayTime($callhistoryrow['crtdate']);
			$callhistorytext = $callhistoryrow['cshdata'];
			$callhistoryuser = $callhistoryrow['crtuser'];

			$crtstatus = $callhistoryrow['crtstatus'];
			$crtnotes = $callhistoryrow['crtnotes'];
			// 05/04/2023 9:39am Busy PEN TATIANA This is my note that can be longer than...
			// echo substr($crtnotes ,0 ,10)
			// . substr($crtnotes,0,20).
			$newnotes = "";
			if (strlen($crtnotes) > 60) {
				$newnotes = "<span class='tooltiptext'>$crtnotes</span>";
			} else {
				$newnotes = "";

			}
			// $newnotestext= "";


			// if (strlen($callhistorytext) > 20) {
			// 	$newnotestext = "<span class='tooltiptext'>$callhistorytext</span>";
			// } else {
			// 	$newnotestext = "";

			// }
			if ($crtstatus) {
				$callhistory[] = "<tr><td width='75'>$callhistorydate</td><td width='73px'>$callhistorytime</td><td width='158px'>$callhistoryuser</td>
				<td class='tooltip'> <span 
				style='
					text-overflow: ellipsis;
					overflow: hidden;
					width: 450px;
					white-space: nowrap;
					display: block;
				
				'	>
				$crtnotes
				
			   </span> 

			   $newnotes
	   
			   </td>
				<td>$crtstatus</td>
			
	
				</tr>";
			} else {
				$callhistory[] = "<tr><td width='75'>$callhistorydate</td><td width='73px'>$callhistorytime</td>
				<td width='158px'>$callhistoryuser</td>
				<td>$callhistorytext</td>
				</tr>";
			}


		}
		if (count($callhistory) == 0)
			$callhistoryhtml = '<tr><td colspan="4">No call history.</td></tr>';
		else
			$callhistoryhtml = implode("", $callhistory);
	} else
		error("801", "Call History SELECT error. $query<br>" . mysqli_error($dbhandle));

	if ($_POST['crreadmit'] == TRUE)
		$readmitnew = "*** READMIT PATIENT ";
	if ($_POST['crrelocate'] == TRUE)
		$readmitnew = "*** RELOCATED PATIENT ";

	if ($_POST['crreadmit'] != TRUE && $_POST['crrelocate'] != TRUE)
		$readmitnew = "*** NEW PATIENT ";

	if ($_POST['crcasestatuscode'] == 'PEA')
		$readmitnew .= "PENDING AUTHORIZATION";
	if ($_POST['crcasestatuscode'] == 'PEN')
		$readmitnew .= "PENDING SCHEDULING";

	$readmitnew .= " ***";

	if ($_POST['confirm_val'] == 'confirm') {
		$redirect_url = "http://" . $_SERVER['SERVER_NAME'];
		echo "<script>window.location.href='" . $redirect_url . "';</script>";
		exit;
	}

	if (isset($_GET['map_option']) && !empty($_GET['map_option'])) {
		$delid = $_GET['map_option'];
		$delfirm = "DELETE FROM case_google_option WHERE case_id = '$delid'";
		$firmres = mysqli_query($dbhandle, $delfirm);
		$updquery = "UPDATE cases SET crcnum ='' WHERE crid = '" . $delid . "' ";
		mysqli_query($dbhandle, $updquery);
		$redirect_url = "http://" . $_SERVER['SERVER_NAME'];
		echo "<script>window.location.href='" . $redirect_url . "';</script>";
		exit;
	}

	// print_r($_POST);
	?>


	<input type="hidden" value="<?php echo $_POST['csqcrid']; ?>" name="callid" id="callid" />
	<input type="hidden" value="<?php echo $_SESSION['user']['umuser']; ?>" name="usernameajax" id="usernameajax" />





	<div class="centerFieldset" style="margin-top:35px; margin-right: 100px;">
		<form method="post">
			<div class="menuTabItem" style="float: right;"><input type="submit" name="pending_list"
					value="Scheduled Pending List"></div>
			<?php if (isset($_POST['pending_list'])) {

				require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/schedulingqueuelist/schedule-pendinglist.php'); ?>

				<style>
					.centerFieldset fieldset {
						display: block;
					}
				</style>

				<?php
				die();
			} ?>

			<?php if (isset($_POST['location']) || isset($_GET['add_more']) || isset($_GET['show_more']) || isset($_GET['map'])) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/schedulingqueuelist/map-locator.php');
				die();
			} ?>


		</form>

		<form method="post" name="editForm">
			<fieldset style="text-align:left;">

				<legend style="font-size:xx-large; text-align:center">
					<?php echo $readmitnew; ?>
				</legend>
				<table>
					<tr>
						<td valign="top" width="50%">
							<table width="100%">
								<tr>
									<th colspan="3">Patient Information</th>
								</tr>
								<tr>
									<td width="150px">Name</td>
									<td style="font-size:large;">
										<?php echo $_POST['pafname'] . " " . $_POST['palname']; ?>
									</td>
									<td><input name="button[<?php echo ($_POST['paid']); ?>]" type="submit"
											value="Edit Patient" /></td>
								</tr>
								<tr>
									<td>DOB</td>
									<td>
										<?php echo displayDate($_POST['padob']); ?>
									</td>
									<td><input type="button" value="Print Sheet"
											onclick="window.open('<?php echo ("/modules/scheduling/printPatientInformationSheet.php?crid=$csqcrid"); ?>');" />
									</td>
								</tr>
								<tr>
									<td>SSN</td>
									<td>
										<?php echo displaySsn($_POST['passn']); ?>
									</td>
									<td><input type="button" value="Print Letter"
											onclick="window.open('<?php echo ("/modules/scheduling/printSchedulingUpdateLetter.php?crid=$csqcrid"); ?>');" />
									</td>
								</tr>
								<tr>
									<td>Gender</td>
									<td>
										<?php echo $_POST['pasex']; ?>
									</td>
									<td><input type="button" value="Print Lien" disabled="disabled" /></td>
								</tr>
								<tr>
									<td>Address&nbsp;</td>
									<td>
										<?php echo $_POST['paaddress1'] . " " . $_POST['paaddress2']; ?>
									</td>
								</tr>

								<tr>
									<td>City </td>
									<td>
										<?php echo $_POST['pacity']; ?> &nbsp;&nbsp;&nbsp;&nbsp;St
										<?php echo $_POST['pastate']; ?> &nbsp;&nbsp;&nbsp;&nbsp;Zip
										<?php echo $_POST['pazip']; ?>
									</td>
								</tr>
								<td><input type="submit" value="Find Clinic" name="location" id="getdirection" /></td>

								<tr>

									<?php if (isset($_GET['add'])) { ?>
										<!-- <?php //if(isset($_POST['google_loc'])){ ?> -->

										<td><b>Clinic Selected:</b></td>
										<td><b>
												<?php echo str_replace('!', '#', $_GET['add']); ?>
											</b></td>
										<!-- <td><b><?php //echo $_POST['google_loc']; ?></b></td> -->
										<td><a href="/"><input type="button" value="Clear Location" /></a></td>
									<?php } else {
										$googleoptquery1 = "SELECT * FROM cases LEFT JOIN master_clinics ON cases.crcnum=master_clinics.cmcnum WHERE crid='$csqcrid' LIMIT 1";
										$googleoptquery_connect1 = mysqli_query($dbhandle, $googleoptquery1);
										if (mysqli_num_rows($googleoptquery_connect1) > 0) {
											while ($googleoptquery_att_row1 = mysqli_fetch_array($googleoptquery_connect1)) {
												$_SESSION['crcnum1'] = $googleoptquery_att_row1['crcnum'];
												?>
												<td><b>Clinic Selected:</b></td>
												<td><b>
														<?php echo $googleoptquery_att_row1['cmname']; ?>
													</b></td>
												<td><a href="/?map_option=<?php echo $csqcrid; ?>"><input type="button"
															value="Clear Location" /></a></td>
												<?php
											}
										} else {
											$googleoptquery = "SELECT * FROM `case_google_option` WHERE `case_id` = '$csqcrid' LIMIT 1";
											$googleoptquery_connect = mysqli_query($dbhandle, $googleoptquery);
											if (mysqli_num_rows($googleoptquery_connect) > 0) {
												while ($googleoptquery_att_row = mysqli_fetch_array($googleoptquery_connect)) {
													$opt_strip = strip_tags(urldecode($googleoptquery_att_row['google_option']));
													$variable_opt = substr($opt_strip, 0, strpos($opt_strip, " mi "));
													preg_match('#\((.*?)\)#', $variable_opt, $match);
													$_SESSION['crcnum1'] = $match[1];
													?>
													<td><b>Clinic Selected:</b></td>
													<td><b>
															<?php echo $variable_opt; ?>
														</b></td>
													<td><a href="/?map_option=<?php echo $csqcrid; ?>"><input type="button"
																value="Clear Location" /></a></td>
													<?php
												}
											}
										}
									} ?>
								</tr>

								<tr>
									<td>Patient Note</td>
									<td>
										<?php echo $_POST['panote']; ?>
									</td>
								</tr>
							</table>
						</td>
						<td valign="top" width="50%">
							<table width="100%">
								<?php if (!empty($_POST['paphone1']) || !empty($_POST['paphone2']) || !empty($_POST['pacellphone'])) { ?>
									<tr>
										<th colspan="2">Patient #</th>
										<th>NoCal</th>
										<th>Busy</th>
										<th>NoAns</th>
										<th>AnsMach</th>
										<th>CallIn</th>
									</tr>
									<?php if (!empty($_POST['paphone1'])) { ?>
										<tr>
											<td width="100px" style="text-align:right;">Home: </td>
											<!-- https://$user:Jesus1919@pbxsip1.apmi.net/call.php?exten=1124&phone=(562)243-5959-->
											<td>
												<!-- https://222:73bf72541a9070c3996d88c58f139ea9@pbxsip1.apmi.net/call.php?exten=1139&phone=3233565930		 -->

												<!-- https://9900:d6ab7ad197d687ff02d44ef111c444b4@pbxsip1.apmi.net/call.php?exten=1139&phone=7143573726 -->

												<a style="cursor: pointer; color: blue;" onclick="alertfunction('Home')">
													<?php echo displayPhone($_POST['paphone1']); ?>
												</a> &nbsp; &nbsp;<input style="cursor: pointer;" class="abcdefgh"
													name="callphone1note[<?php echo ($callid); ?>]" type="button" value="Note" />

												<input type="hidden" value="<?php echo $_POST['pafname']; ?>" id="pa_fname_home" />
												<input type="hidden" value="<?php echo $_POST['palname']; ?>" id="pa_lname_home" />
												<input type="hidden" value="<?php echo $user; ?>" id="user_name_home" />
												<input type="hidden" value="<?php echo $_SESSION['user']['extension']; ?>"
													id="user_extension_home" />
												<input type="hidden" value="<?php echo $_POST['paphone1']; ?>" id="pa_phone_home" />
												<input type="hidden" value="<?php echo $_SESSION['user']['umid']; ?>"
													id="uuid_home" />
												<input type="hidden" value="<?php echo $_SESSION['user']['umpass']; ?>"
													id="umpass_home" />




											</td>
											<!-- <td> -->
											<!-- <input type="button" style="cursor: pointer;" value="Call"
													onclick="alertfunction() " /> -->

											<!-- <a type="button" style="padding: 5px;" href="https://</?php echo $_SESSION['user']['umid']; ?>:<//?php echo $_SESSION['user']['umpass']; ?>@pbxsip1.apmi.net/call.php?exten=<//?php echo $_SESSION['user']['extension']; ?>&phone=<//?php echo $_POST['paphone1']; ?>">Call</a> -->
											<!-- </td> -->
											<td><input type="radio" name="phone1[<?php echo ($callid); ?>]" checked value=""
													id="phone1_nc" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone1[<?php echo ($callid); ?>]" value="Busy"
													id="phone1_b" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone1[<?php echo ($callid); ?>]" value="No Answer"
													id="phone1_na" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone1[<?php echo ($callid); ?>]" value="Ans Mach"
													id="phone1_am" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone1[<?php echo ($callid); ?>]" value="Call In"
													id="phone1_ci" onclick="javascript:checkformstate();" /></td>


										</tr>
									<?php } ?>
									<?php if (!empty($_POST['paphone2'])) { ?>
										<tr>
											<td style="text-align:right;">Work: </td>
											<td>
												<a style="cursor: pointer; color: blue;" onclick="alertfunction('Work')">
													<?php echo displayPhone($_POST['paphone2']); ?>


												</a>
												&nbsp; &nbsp;<input style="cursor: pointer;" class="abcdefgh"
													name="callphone1note[<?php echo ($callid); ?>]" type="button" value="Note" />
												<!-- <input type="hidden" value="<//?php echo $_POST['pafname']; ?>" id="pa_fname_work" />
												<input type="hidden" value="<//?php echo $_POST['palname']; ?>" id="pa_lname_work" />
												<input type="hidden" value="<//?php echo $user; ?>" id="user_name_work" />
												<input type="hidden" value="<//?php echo $_SESSION['user']['extension']; ?>"
													id="user_extension_work" /> -->
												<input type="hidden" value="<?php echo $_POST['paphone2']; ?>" id="pa_phone_work" />
											</td>
											<td><input type="radio" name="phone2[<?php echo ($callid); ?>]" checked value="0"
													id="phone2_nc" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone2[<?php echo ($callid); ?>]" value="Busy"
													id="phone2_b" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone2[<?php echo ($callid); ?>]" value="No Answer"
													id="phone2_na" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone2[<?php echo ($callid); ?>]" value="Ans Mach"
													id="phone2_am" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone2[<?php echo ($callid); ?>]" value="Call In"
													id="phone2_ci" onclick="javascript:checkformstate();" /></td>
										</tr>
									<?php } ?>
									<?php if (!empty($_POST['pacellphone'])) { ?>
										<tr>
											<td style="text-align:right;">Mobile: </td>
											<td>
												<a style="cursor: pointer; color: blue;" onclick="alertfunction('Mobile')">
													<?php echo displayPhone($_POST['pacellphone']); ?>
												</a>
												&nbsp; &nbsp;<input style="cursor: pointer;" class="abcdefgh"
													name="callphone1note[<?php echo ($callid); ?>]" type="button" value="Note" />

												<!-- <input type="hidden" value="<//?php echo $_POST['pafname']; ?>"
													id="pa_fname_mobile" />
												<input type="hidden" value="<//?php echo $_POST['palname']; ?>"
													id="pa_lname_mobile" />
												<input type="hidden" value="<//?php echo $user; ?>" id="user_name_mobile" />
												<input type="hidden" value="<//?php echo $_SESSION['user']['extension']; ?>"
													id="user_extension_mobile" /> -->
												<input type="hidden" value="<?php echo $_POST['pacellphone']; ?>"
													id="pa_phone_mobile" />
											</td>
											<td><input type="radio" name="phone3[<?php echo ($callid); ?>]" checked value="0"
													id="phone3_nc" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone3[<?php echo ($callid); ?>]" value="Busy"
													id="phone3_b" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone3[<?php echo ($callid); ?>]" value="No Answer"
													id="phone3_na" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone3[<?php echo ($callid); ?>]" value="Ans Mach"
													id="phone3_am" onclick="javascript:checkformstate();" /></td>
											<td><input type="radio" name="phone3[<?php echo ($callid); ?>]" value="Call In"
													id="phone3_ci" onclick="javascript:checkformstate();" /></td>
										</tr>
									<?php } ?>
								<?php } else {
									echo ("<script>enablecallback();</script>");
									?>
									<tr>
										<th>No Telephone Numbers on file</th>
									</tr>
								<?php } ?>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="top" width="50%">
							<table width="100%">
								<tr>
									<th colspan="2">Prescription Information </th>
								</tr>
								<tr>
									<td width="150px">Doctor</td>
									<td>
										<?php echo $_POST['dmlname'] . ", " . $_POST['dmfname']; ?>
									</td>
								</tr>
								<tr>
									<td>City</td>
									<td>
										<?php echo $_POST['dlcity']; ?>
									</td>
								</tr>
								<tr>
									<td>Referral Date</td>
									<td>
										<?php echo displayDate($_POST['crdate']); ?>
									</td>
								</tr>
								<tr>
									<td>Case Type</td>
									<td>
										<?php echo $_POST['crcasetypecode'] . "-" . $thiscasetype; ?>
									</td>
								</tr>
								<tr>
									<td>Therapy Type</td>
									<td>
										<?php echo $_POST['crtherapytypecode'] . "-" . $thistherapytype; ?>
									</td>
								</tr>
								<tr>
									<td>Freq & Dur</td>
									<td>
										<?php if (!empty($_POST['crfrequency']) && !empty($_POST['crduration']))
											echo $_POST['crfrequency'] . "x" . $_POST['crduration'];
										else if (!empty($_POST['crtotalvisits']))
											echo "Visits:" . $_POST['crtotalvisits'];
										else
											echo "See Prescription"; ?>
									</td>
								</tr>
								<tr>
									<td>Clinic</td>
									<td>
										<?php if (!empty($_POST['crcnum']))
											echo $_POST['crcnum'];
										else
											echo "Not Assigned"; ?>
									</td>
								</tr>
								<tr>
									<td>Major Body Part</td>
									<td>
										<?php if (!empty($_POST['crdxbodypart']))
											echo $bodypartcodeoptions[$_POST['crdxbodypart']]['description'];
										else
											echo "Not Specified"; ?>
									</td>
								</tr>
								<tr>
									<td>ICD9 Codes</td>
									<td>
										<?php if (!empty($_POST['cricd9desc1']))
											echo $_POST['cricd9desc1'];
										else
											echo "Not Specified";
										if (!empty($_POST['cricd9desc2']))
											echo "<br />" . $_POST['cricd9desc2'];
										if (!empty($_POST['cricd9desc3']))
											echo "<br />" . $_POST['cricd9desc3'];
										if (!empty($_POST['cricd9desc4']))
											echo "<br />" . $_POST['cricd9desc4']; ?>
									</td>
								</tr>
								<tr>
									<td>Case Note </td>
									<td>
										<?php echo $_POST['crnote']; ?>
									</td>
								</tr>
							</table>
						</td>
						<td valign="top" width="50%">
							<table width="100%">
								<?php if (!empty($_POST['dlphone'])) { ?>
									<tr>
										<th colspan="2">Referrer #</th>
										<th>No Call</th>
										<th>Called</th>
										<th>CallIn</th>
									</tr>
									<?php if (!empty($_POST['dlphone'])) { ?>
										<tr>
											<td width="100px" style="text-align:right;">Doctor:</td>
											<td>
												<?php echo displayPhone($_POST['dlphone']); ?> &nbsp;&nbsp;<input
													name="calldlphone[<?php echo ($callid); ?>]" class="abcdefgh" type="button"
													value="Note" onchange="javascript:checkformstate();" />
											</td>
											<td><input type="radio" name="phonedoc[<?php echo ($callid); ?>]" checked value="0"
													id="phonedoc_nc" /></td>
											<td><input type="radio" name="phonedoc[<?php echo ($callid); ?>]" value="15"
													id="phonedoc_c" /></td>
											<td><input type="radio" name="phonedoc[<?php echo ($callid); ?>]" value="15"
													id="phonedoc_ci" /></td>
										</tr>
									<?php } ?>
								<?php } ?>
								<?php if (!empty($_POST['crapptdate']) || !empty($_POST['crapptscheduler']) || !empty($_POST['crapptdatescheduled'])) { ?>
									<tr>
										<th colspan="2">Previous Appt Information </th>
									</tr>
									<tr>
										<td nowrap="nowrap">Prev Appt</td>
										<td>
											<?php echo displayDate($_POST['crapptdate']) . ' ' . displayTime($_POST['crapptdate']); ?>
										</td>
									</tr>
									<tr>
										<td nowrap="nowrap">Sched By</td>
										<td>
											<?php echo strtoupper($_POST['crapptscheduler']); ?>
										</td>
									</tr>
									<tr>
										<td nowrap="nowrap">Sched On</td>
										<td>
											<?php echo displayDate($_POST['crapptdatescheduled']) . ' ' . displayTime($_POST['crapptdatescheduled']); ?>
										</td>
									</tr>
								<?php } ?>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="top" colspan="2" width="100%">
							<table style="text-align:center;" width="100%">
								<tr>
									<td><input
											style="background-color:#FFFF00; font-size:medium; height: 30px; width: 100px;"
											id="callback" name="button[<?php echo $callid; ?>]" type="submit"
											value="Callback" /></td>
									<td><input style="background-color:green; font-size:medium; height: 30px; width: 100px;"
											id="schedule" name="button[<?php echo $callid; ?>]" type="submit"
											value="Schedule" />
									</td>
									<td><input style="background-color:red; font-size:medium; height: 30px; width: 100px;"
											id="cancel" name="button[<?php echo $callid; ?>]" type="submit"
											value="Cancel" />
									</td>
									<td><input
											style="background-color:#FFFFFF; font-size:medium; height: 30px; width: 100px;"
											id="done" name="button[<?php echo $callid; ?>]" type="submit"
											disabled="disabled" value="Done" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="top" colspan="2" width="100%">
							<table width="100%">
								<tr>
									<th colspan="6">Call History</th>
								</tr>
								<?php echo $callhistoryhtml; ?>
							</table>
						</td>
					</tr>
				</table>
			</fieldset>
		</form>

	</div>
	<?php
}
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"
	integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
	/* The modal (background) */
	.modal {
		display: none;
		/* Hidden by default */
		position: fixed;
		/* Stay in place */
		z-index: 1;
		/* Sit on top */
		left: 0;
		top: 0;
		width: 100%;
		/* Full width */
		height: 100%;
		/* Full height */
		overflow: auto;
		/* Enable scroll if needed */
		background-color: rgb(0, 0, 0);
		/* Fallback color */
		background-color: rgba(0, 0, 0, 0.4);
		/* Black w/ opacity */
	}

	/* Modal Content/Box */
	.modal-content {
		background-color: #fefefe;
		margin: 15% auto;
		/* 15% from the top and centered */
		padding: 20px;
		border: 1px solid #888;
		width: 30%;
		/* Could be more or less, depending on screen size */
	}

	/* The Close Button */
	.close {
		color: #aaaaaa;
		float: right;
		font-size: 28px;
		font-weight: bold;
	}

	.close:hover,
	.close:focus {
		color: #000;
		text-decoration: none;
		cursor: pointer;
	}

	/* Style the buttons */
	.modal-footer button {
		background-color: #4CAF50;
		/* Green */
		color: white;
		padding: 10px 24px;
		border: none;
		border-radius: 4px;
		margin-right: 10px;
		cursor: pointer;
	}

	/* Change the background color of the buttons on hover */
	.modal-footer button:hover {
		background-color: #3e8e41;
	}

	/* .form-control-textarea {
		min-width: 100%;
		width: 539px;
		height: 109px;
	} */


	.form-control-textarea {
		min-width: 95%;
		width: 532px;
		height: 109px;
		padding: 10px;
	}

	/* .placeholderclass::placeholder {
	  margin-left: 100%
} */
</style>


<!-- Button to open the modal -->
<!-- <button class="abcdefgh">Open Modal</button> -->

<!-- The modal -->
<div id="myModal" class="modal">

	<!-- Modal content -->
	<div class="modal-content">
		<div class="modal-header">
			<!-- <h2>Modal Title</h2> -->
			<span class="close">&times;</span>
		</div>
		<div class="modal-body">
			<div id="callingdetails" class="callingtext" style="text-align: center; ">
				<h2 style="font-size: 0.95rem !important; color: #4d4c4c; margin-bottom: -11px;"></h2>
				<p style="font-size: 0.95rem !important; color: #4d4c4c;  margin-block-start: 0.83em;
	margin-block-end: 0.83em;
	margin-inline-start: 0px;
	margin-inline-end: 0px;
	font-weight: bold;"></p>

			</div>

			<textarea placeholder="Notes:" class="form-control-textarea placeholderclass" id="callnotes"></textarea>
		</div>
		&nbsp;
		&nbsp;
		&nbsp;

		<div class="modal-footer" style="margin-left: 4%;">
			<div style="display: flex">
				<p>Save as:</p> &nbsp;&nbsp;
				<button id="btn1" style="background-color:#33a0ef !important;">Note</button>
				<button id="btn2" style="background-color:#33a0ef !important;">Busy</button>
				<button id="btn3" style="background-color:#33a0ef !important;">No Answer</button>
				<button id="btn4" style="background-color:#33a0ef !important;">Left Message</button>
			</div>
		</div>

	</div>



	<!-- Button to trigger the modal -->


	<script>
		var urlOppen;
		// Get the modal
		var modal = document.getElementById("myModal");

		// Get the button that opens the modal
		var btn = document.getElementsByClassName("abcdefgh");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks on the button, open the modal
		// console.log("btn" , btn);

		$(".abcdefgh").click(function () {
			//   alert("The paragraph was clicked.");
			modal.style.display = "block";

		});

		// btn.onclick = function () {
		// 	modal.style.display = "block";
		// }

		// When the user clicks on <span> (x), close the modal
		span.onclick = function () {
			modal.style.display = "none";
			$("#callingdetails h2").text("");
			$(".callingtext p").text("");

		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function (event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}

		// Handle button clicks
		document.getElementById("btn1").addEventListener("click", function () {
			submitData("Note");
			// alert("Button 1 clicked");
			// urlOppen.close();


		});

		document.getElementById("btn2").addEventListener("click", function () {
			submitData("Busy");
		});

		document.getElementById("btn3").addEventListener("click", function () {
			submitData("No Answer");
		});

		document.getElementById("btn4").addEventListener("click", function () {
			submitData("Left Message");
		});

		function submitData(callStatus) {
			var message = $('textarea#callnotes').val();
			var callid = $('#callid').val();
			var usernameajax = $('#usernameajax').val();

			let messagevalue = ""
			if (message != "") {
				messagevalue = message
			} else {
				messagevalue = callStatus
			}

			// console.log("callid" , callid);
			// console.log(message);
			// console.log(callid);
			// alert(`datainserted with status ${callStatus}`)
			$.ajax({
				type: 'post',
				url: 'modules/scheduling/submitNotesData.php',
				data: { message: messagevalue, callStatus: callStatus, callid: callid, usernameajax: usernameajax },
				success: function (data) {
					console.log("data", data);
					if (data) {
						modal.style.display = "none";
						window.location.replace("https://netpt.wsptn.com/");
						// location.reload();
						// Swal.fire({

						// 	title: 'Save!',
						// 	text: 'Notes saved successfully',
						// 	type: "success"

						// }).then((result) => {
						// 	// Reload the Page

						// });



					}

				}
			});
		}

	</script>


	<script>
		var getUrlParameter = function getUrlParameter(sParam) {
			var sPageURL = window.location.search.substring(1),
				sURLVariables = sPageURL.split('&'),
				sParameterName,
				i;

			for (i = 0; i < sURLVariables.length; i++) {
				sParameterName = sURLVariables[i].split('=');

				if (sParameterName[0] === sParam) {
					return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
				}
			}
			return false;
		};


		var tech = getUrlParameter('section');
		// var blog = getUrlParameter('blog');

		if (tech != "") {
			if (tech == "Home") {
				var phonenumber = $("#pa_phone_home").val();

			} else if (tech == "Mobile") {

				var phonenumber = $("#pa_phone_mobile").val();

			} else if (tech == "Work") {
				var phonenumber = $("#pa_phone_work").val();
			}
			var pa_fname = $("#pa_fname_home").val();
			var pa_lname = $("#pa_lname_home").val();
			var user_name = $("#user_name_home").val();
			var user_extension = $("#user_extension_home").val();
			var pa_phone = phonenumber
			var uuid_home = $("#uuid_home").val();
			var umpass_home = $("#umpass_home").val();
			var urldata = `Calling patient: ${pa_fname} ${pa_lname} at ${pa_phone}.`;
			var secondurldata = `${user_name}'s  extension# ${user_extension}  will ring momentarily.`;
			$("#callingdetails h2").text(urldata);
			$(".callingtext p").text(secondurldata);
			$(".abcdefgh").click();
		}
		function alertfunction(section) {
			// console.log("section" , section)

			if (section == "Home") {
				var phonenumber = $("#pa_phone_home").val();

			} else if (section == "Mobile") {

				var phonenumber = $("#pa_phone_mobile").val();

			} else if (section == "Work") {
				var phonenumber = $("#pa_phone_work").val();
			}


			var pa_fname = $("#pa_fname_home").val();
			var pa_lname = $("#pa_lname_home").val();
			var user_name = $("#user_name_home").val();
			var user_extension = $("#user_extension_home").val();
			var pa_phone = phonenumber
			var uuid_home = $("#uuid_home").val();
			var umpass_home = $("#umpass_home").val();

			// var callUrl = `https://${uuid_home}:${umpass_home}@pbxsip1.apmi.net/call.php?exten=${user_extension}&phone=${pa_phone}`

			var callUrl = `https://9900:d6ab7ad197d687ff02d44ef111c444b4@pbxsip1.apmi.net/call.php?exten=${user_extension}&phone=${pa_phone}`



			// var callUrl = "https://9900:d6ab7ad197d687ff02d44ef111c444b4@pbxsip1.apmi.net/call.php?exten=1139&phone=7143573726";


			// $('<a>', {
			// 	href: callUrl,
			// 	target: '_blank'
			// }).appendTo('body')[0].click();

			// window.focus();

			// window.open('#','_blank');
			// window.open(callUrl,'_self');
			// window.open(callUrl, '_blank');
			// window.open('https://netpt.wsptn.com/', '_self');










			// window.open(window.location.href)
			var message = `Calling patient: ${pa_fname} ${pa_lname} at ${pa_phone}. 
		${user_name}'s extension# ${user_extension}  will ring momentarily.`
			// var message2 = `${user_name}'s extension# ${user_extension}  will ring momentarily.`

			// const lines = [message, message2];

			// var myhtml = document.createElement("div");
			// myhtml.innerHTML = `Calling patient:${pa_fname} ${pa_lname} at ${pa_phone}. <br> ${user_name}'s  extension# ${user_extension}  will ring momentarily.`;

			var myhtmlnew = document.createElement("div");
			myhtmlnew.innerHTML = `Call ${pa_fname} ${pa_lname} at  ${pa_phone}. <br> Connect to ${user_name}'s ext ${user_extension}.`

			Swal.fire({
				// title: 'Are you sure?',
				// html:true
				html: myhtmlnew,
				// icon: 'warning',
				// showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Call Now',
				// input: 'textarea'
				// footer: 'https://9900:d6ab7ad197d687ff02d44ef111c444b4@pbxsip1.apmi.net/call.php?exten=1139&phone=7143573726'
			}).then((result) => {
				if (result.isConfirmed) {
					// window.open("https://9900:d6ab7ad197d687ff02d44ef111c444b4@pbxsip1.apmi.net/call.php?exten=1139&phone=7143573726");
					// window.location.replace(callUrl);
					console.log("callUrl", callUrl);
					window.open('?section=' + section, '_blank');
					urlOppen = window.open(callUrl, '_self');
					// window.open(callUrl)
					// window.open(callUrl, '_blank');
				}
			})
		}

	// $("#callingdetails h2").text(myhtml);
	// 	$(".abcdefgh").click();
	// function createButton(text, cb) {
	// 	return $('<button>' + text + '</button>').on('click', cb);
	// }


	</script>

	<!-- Swal.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, delete it!'
}).then((result) => {
  if (result.isConfirmed) {
	Swal.fire(
	  'Deleted!',
	  'Your file has been deleted.',
	  'success'
	)
  }
}) -->