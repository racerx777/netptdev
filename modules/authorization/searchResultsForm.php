<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11);
if (isset($_REQUEST['print']))
	$print = TRUE;
else
	$print = FALSE;
$searchSaved = getformvars('authorization', 'search');
if (!empty($searchSaved) || !empty($_POST['formSubmit']) || !empty($_POST['sort']) || $print) {
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$searchvars = array(
		"crcasestatuscode" => array(
			"title" => "Case Status",
			"type" => "text",
			"dbformat" => "varchar",
			"dblength" => "3",
			"displayformat" => "text",
			"displaylength" => "3",
			"test" => "EQUAL"
		),
		"palname" => array(
			"title" => "Last Name",
			"type" => "text",
			"dbformat" => "varchar",
			"dblength" => "30",
			"displayformat" => "name",
			"displaylength" => "30",
			"test" => "LIKE"
		),
		"pafname" => array(
			"title" => "First Name",
			"type" => "text",
			"dbformat" => "varchar",
			"dblength" => "30",
			"displayformat" => "name",
			"displaylength" => "30",
			"test" => "LIKE"
		),
		"padob" => array(
			"title" => "Birth Date",
			"type" => "text",
			"dbformat" => "date",
			"dblength" => "8",
			"displayformat" => "date",
			"displaylength" => "10",
			"test" => "EQUAL"
		),
		"paphone1" => array(
			"title" => "Phone Number",
			"type" => "text",
			"dbformat" => "phone",
			"dblength" => "18",
			"displayformat" => "phone",
			"displaylength" => "22",
			"test" => "EQUAL"
		),
		"passn" => array(
			"title" => "Social Security Number",
			"type" => "text",
			"dbformat" => "ssn",
			"dblength" => "9",
			"displayformat" => "ssn",
			"displaylength" => "11",
			"test" => "EQUAL"
		),
		"crinjurydate" => array(
			"title" => "Injury Date",
			"type" => "text",
			"dbformat" => "date",
			"dblength" => "8",
			"displayformat" => "date",
			"displaylength" => "10",
			"test" => "EQUAL"
		),
		"cpdate" => array(
			"title" => "Rx Date",
			"type" => "text",
			"dbformat" => "date",
			"dblength" => "8",
			"displayformat" => "date",
			"displaylength" => "10",
			"test" => "LIKE"
		),
		"crapptdate" => array(
			"title" => "Appt Date",
			"type" => "text",
			"dbformat" => "date",
			"dblength" => "8",
			"displayformat" => "date",
			"displaylength" => "10",
			"test" => "LIKE"
		),
		"crptosstatus" => array(
			"title" => "PTOS Status Code",
			"type" => "text",
			"dbformat" => "varchar",
			"dblength" => "3",
			"displayformat" => "text",
			"displaylength" => "3",
			"test" => "EQUAL"
		)
	);
	$sortSaved = getformvars('authorization', 'searchResults');
	// if Sort button pressed set sort values
	if (count($_POST['sort']) > 0) {
		// If Reset Sort Pressed then clear saved values
		if (!empty($_POST['sort']['RESETSORT'])) {
			clearformvars('authorization', 'searchResults');
			unset($sortSaved);
		} else {
			// determine sort field name from key
			$sortfield = key($_POST['sort']);
			// if that key exists in the sort then toggle collation
			if (array_key_exists($sortfield, $sortSaved)) {
				$collation = $sortSaved["$sortfield"]['collation'];
				if ($collation == 'desc')
					$sortSaved["$sortfield"]["collation"] = '';
				else
					$sortSaved["$sortfield"]["collation"] = 'desc';
			} else
				$sortSaved["$sortfield"] = $searchvars["$sortfield"];
			setformvars('authorization', 'searchResults', $sortSaved);
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();

	/*	$query  = "
	SELECT
	paid, palname, pafname, coalesce(passn,'') as passn, coalesce(padob,'') as padob, coalesce(paphone1,'') as paphone1,
	crid, crpnum, crcasetypecode, crcasestatuscode, crempname, croccup, crtherapytypecode, coalesce(crinjurydate,'') as crinjurydate, coalesce(crdate,'') as crdate, coalesce(crapptdate,'') as crapptdate, coalesce(crcnum,'') as crcnum, crptosstatus, cricid1, cricid2, crptosupdated,
	cpid, coalesce(cpdate,'') as cpdate, coalesce(cpstatuscode,'none') as cpstatuscode, coalesce(cpauthstatuscode,'none') as cpauthstatuscode, cpdx1, cpdx2, cpdx3, cpdx4, cpdmid, cpdlid, cpfrequency, cpduration, cptotalvisits, cpttmcode, cpcnum, cptherap,
	dmlname, dmfname,
	dlname
	FROM cases c
	LEFT JOIN patients p ON crpaid=paid
	LEFT JOIN case_prescriptions a on crid = cpcrid
	LEFT JOIN doctors d on cpdmid = dmid
	LEFT JOIN doctor_locations dl on cpdlid = dlid
	";*/
	$query = "
	SELECT
	paid, palname, pafname, passn, padob, paphone1,
	crid, crpnum, crcasetypecode, crcasestatuscode, crempname, croccup, crtherapytypecode, crinjurydate, crdate, crapptdate, crcnum, crptosstatus, cricid1, cricid2, crptosupdated,
	cpid, cpdate, cpstatuscode, cpauthstatuscode, cpdx1, cpdx2, cpdx3, cpdx4, cpdmid, cpdlid, cpfrequency, cpduration, cptotalvisits, cpttmcode, cpcnum, cptherap,cpauthstatusupdated,
	dmlname, dmfname,
	dlname
FROM cases c
  LEFT JOIN patients p ON crpaid=paid
  LEFT JOIN case_prescriptions a on crid = cpcrid
  LEFT JOIN doctors d on cpdmid = dmid
  LEFT JOIN doctor_locations dl on cpdlid = dlid
";
	$where = array();

	if (empty($searchSaved['crcasestatuscode'])) {
		$searchSaved['crcasestatuscode'] = '(ACTIVE)';
	}

	foreach ($searchSaved as $formvar => $formvarvalue) {
		if (isset($formvarvalue) && !empty($formvarvalue)) {
			if (!is_array($formvarvalue)) {
				$title = $searchvars["$formvar"]['title'];
				$type = $searchvars["$formvar"]['type'];
				$dbformat = $searchvars["$formvar"]['dbformat'];
				$dblength = $searchvars["$formvar"]['dblength'];
				$displayformat = $searchvars["$formvar"]['displayformat'];
				$displaylength = $searchvars["$formvar"]['displaylength'];
				$length = $searchvars["$formvar"]['length'];
				$test = $searchvars["$formvar"]['test'];
				switch ($dbformat):
					case 'date':
						$formvarvalue = date("Y-m-d", strtotime($formvarvalue));
						break;
					case 'phone':
						$formvarvalue = dbPhone($formvarvalue);
						break;
					case 'ssn':
						$formvarvalue = dbSsn($formvarvalue);
						break;
				endswitch;

				switch ($test):
					case 'LIKE':
						$test = "LIKE '" . mysqli_real_escape_string($dbhandle, $formvarvalue) . "%'";
						break;
					default:
						$test = "= '" . mysqli_real_escape_string($dbhandle, $formvarvalue) . "'";
				endswitch;

				if ($formvarvalue == '(EMPTY)') {
					//					if(userlevel()==99) {
//						dump("formvarvalue","$formvarvalue");
//						dump("formvar","$formvar");
//					}
					$test = "($formvar = '0' or $formvar = '' or $formvar IS NULL)";
					$formvar = '';
				}
				if ($formvarvalue == '(NOT_EMPTY)') {
					$test = "$formvar <> ''";
					$formvar = '';
				}
				if ($formvarvalue == '(ACTIVE)') {
					$test = "$formvar IN ('SCH', 'ACT') ";
					$formvar = '';
				}
				if ($formvarvalue == '(NOT_IN_PTOS)') {
					$test = "($formvar = '0' or $formvar = '' or $formvar IS NULL) and crpnum <> '' AND crpnum NOT IN ( SELECT pnum FROM PTOS_Patients )";
					$formvar = '';
				}
			} else {
				$test = "$formvar IN ('" . implode("', '", $formvarvalue) . "')";
				$formvar = "";
			}
			$where[] = "$formvar $test";
		}
	}

	//	if(empty($_POST['search']['crcasestatuscode']))
//		$query .= "WHERE crcasestatuscode IN ('PEA', 'PEN', 'SCH', 'ACT') ";
//	else
//		$query .= "WHERE 1=1 ";

	// if no case status selected then select non canceled cases
	if (count($where) > 0)
		// Default search for cases with status SCH and authorization status of NEW

		$query .= "WHERE " . implode(" and ", $where);
	//		$query .= "WHERE " . implode(" and ", $where);

	//		$groupby = "GROUP BY crid, palname, pafname, passn, padob, crinjurydate, paphone1, crcnum, crcasestatuscode, cpid";
//		$query .= " $groupby";
//		$query .= "WHERE crdate >= 2009-12-07 and (cpdate >= 2009-12-07 or cpdate is null) and (crapptdate >= 2009-12-07 or crapptdate is null) and " . implode(" and ", $where) . "
//GROUP BY c.crid, p.palname, p.pafname, p.passn, p.padob, c.crinjurydate, p.paphone1, crcnum, crcasestatuscode
//";
	//
	// Sort Order - Contained in Session variable 'customerservice'=>'searchResults'=>array(field=>collation) as sort
	//

	$orderby = array();
	if (empty($sortSaved)) {
		// default sort here
		$sortvartitles = "unsorted (click column titles to add/toggle sort)";
	} else {
		$sortvartitles = "sorted by ";
		foreach ($sortSaved as $sortvar => $sortvarproperty) {
			$orderby[] = trim($sortvar . " " . $sortvarproperty["collation"]);
			$sortvartitles .= trim($sortvarproperty["title"] . " " . $sortvarproperty["collation"]) . ", ";
		}
	}
	if (count($orderby) > 0) {
		$sortvartitles = substr($sortvartitles, 0, -2) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="sort[RESETSORT]" type="submit" value="Reset Sort">';
		$query .= " ORDER BY " . implode(",", $orderby);
	} else {
		$query .= " ORDER BY DATE_FORMAT(crapptdate, '%Y%m%d'), palname, crid, cpid limit 100";
	}

	//if(!$print) {
//	$query.="
//LIMIT 100 ";
//}

	if ($result = mysqli_query($dbhandle, $query)) {
		$numRows = mysqli_num_rows($result);
		?>

		<div class="containedBox">
			<fieldset>
				<legend style="font-size:large;">
					<form method="post" name="sortReset">Search Authorization Information Results
						<?php echo $sortvartitles; ?>
					</form>
				</legend>
				<?php
				if ($numRows > 0) {
					if ($numRows == 1)
						echo "$numRows match found.";
					else {
						if ($numRows < 100 || $_REQUEST['print'] == '1')
							echo "$numRows matches found.";
						else
							echo "Over $numRows matches found. Did not display all rows.";
					}
					if (!$print) {
						$url = "'/modules/authorization/searchResultsForm.php?print=1'";
						$windowname = "'printSearchResultsForm'";
						$windowfeatures = "'status=1,toolbar=1,location=1,menubar=1,directories=1,resizable=1,scrollbars=1,height=800,width=800'";
						echo ('<a href="' . "javascript:window.open($url, $windowname, $windowfeatures)" . '">Print list</a>');
					}
					?>
					<form method="post" name="searchResults">
						<table cellpadding="3" cellspacing="0" width="100%" style="border: 2px solid rgb(0,0,0);">
							<tr>
								<th colspan="2" align="left"><input name="sort[paid]" type="submit" value="Patient Id" /></th>
								<th colspan="2" align="left"><input name="sort[palname]" type="submit" value="Last Name" /></th>
								<th colspan="2" align="left"><input name="sort[pafname]" type="submit" value="First Name" /></th>
								<th colspan="2" align="left"><input name="sort[passn]" type="submit" value="SSN" /></th>
								<th colspan="2" align="left"><input name="sort[padob]" type="submit" value="DOB" /></th>
								<th colspan="2" align="left"><input name="sort[paphone1]" type="submit" value="Phone" /></th>
								<th colspan="2">&nbsp;</th>
							</tr>
							<tr>
								<th>&nbsp;</th>
								<th colspan="2" align="left"><input name="sort[crid]" type="submit" value="Case Id" /></th>
								<th colspan="2" align="left"><input name="sort[crtherapytypecode]" type="submit"
										value="TType" /><input name="sort[crcasestatuscode]" type="submit" value="CStatus" /></th>
								<th colspan="2" align="left"><input name="sort[crinjurydate]" type="submit" value="DOI" /></th>
								<th colspan="2" align="left"><input name="sort[crdate]" type="submit" value="Ref Date" /></th>
								<th colspan="2" align="left"><input name="sort[crapptdate]" type="submit" value="Appt Date" /></th>
								<th colspan="3">&nbsp;</th>
							</tr>
							<tr>
								<th colspan="2"
									style="border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:2px">&nbsp;
								</th>
								<th colspan="2"
									style="border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:2px"><input
										name="sort[cpid]" type="submit" value="Rx Id" /></th>
								<th colspan="2"
									style="border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:2px"><input
										name="sort[cpdate]" type="submit" value="Rx Date" /></th>
								<th colspan="2"
									style="border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:2px"><input
										name="sort[cpstatuscode]" type="submit" value="Rx Status" /></th>
								<th colspan="2"
									style="border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:2px"><input
										name="sort[cpdmid]" type="submit" value="Rx Dr" /></th>
								<th colspan="2"
									style="border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:2px"><input
										name="sort[cpdlid]" type="submit" value="Rx Loc" /></th>
								<th colspan="2"
									style="border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:2px">&nbsp;
								</th>
							</tr>
							<?php
							$casestatuscodes = caseStatusCodes();
							$caseprescriptionstatuscodes = casePrescriptionStatusCodes();
							$caseprescriptionauthorizationstatuscodes = casePrescriptionAuthorizationStatusCodes();
							$pfirstname = "";
							$pLastName = "";

							while ($row = mysqli_fetch_assoc($result)) {
								$pfirstname = $row['pafname'];
								$pLastName = $row['palname'];
								unset($msg);
								unset($sendtoauthbutton);
								unset($sendtoauthbuttonstatus);
								unset($sendtoauthbuttonerror);
								$sendtoauthbuttonerror = array();
								if ($row['crcasetypecode'] == '5') {
									$sendtoauthbuttonerror[] = "Case:Case is PI.";
								} else {
									if ($row['crcasestatuscode'] == 'GIF' || $row['crcasestatuscode'] == 'CAN') {
										$sendtoauthbuttonerror[] = "Case:Case was cancelled";
									} else {
										if ($row['crcasestatuscode'] != 'PEA' && $row['crcasestatuscode'] != 'ACT')
											$sendtoauthbuttonerror[] = "Case:Must be a prior auth or have been seen to send to Authorizer. Case status is " . $row['crcasestatuscode'] . ".";
										else {
											if (empty($row['palname']) || empty($row['pafname']))
												$sendtoauthbuttonerror[] = "Patient:Missing First and Last Name";
											if (empty($row['passn']))
												$sendtoauthbuttonerror[] = "Patient:Missing SSN";
											if (empty($row['padob']))
												$sendtoauthbuttonerror[] = "Patient:Missing DOB";
											if (empty($row['crempname']))
												$sendtoauthbuttonerror[] = "Case:Missing Employer";
											if (empty($row['crinjurydate']))
												$sendtoauthbuttonerror[] = "Case:Missing DOI";
											if (empty($row['crapptdate']) && $row['crcasestatuscode'] != 'PEA')
												$sendtoauthbuttonerror[] = "Case:Missing First Visit Date";
											//						if( empty($row['cricid1']) && empty($row['cricid2']) )
											//							$sendtoauthbuttonerror[]="Case:Missing Insurance Information";
											if (empty($row['cpdx1']) && empty($row['cpdx2']) && empty($row['cpdx3']) && empty($row['cpdx4']))
												$sendtoauthbuttonerror[] = "Rx:Missing ICD9 Information";
											if (empty($row['cpdmid']))
												$sendtoauthbuttonerror[] = "Rx:Missing Referring Doctor";
											if (empty($row['cpdlid']))
												$sendtoauthbuttonerror[] = "Rx:Missing Referring Doctor Location";
											if (empty($row['cpcnum']))
												$sendtoauthbuttonerror[] = "Rx:Missing Clinic Location";
											if (empty($row['cptherap']))
												$sendtoauthbuttonerror[] = "Rx:Missing Therapist";
											if ((empty($row['cpduration']) || empty($row['cpfrequency'])) && empty($row['cptotalvisits']))
												$sendtoauthbuttonerror[] = "Rx:Missing Frequency and Duration or Visits";
											if (empty($row['cpttmcode']))
												$sendtoauthbuttonerror[] = "Rx:Missing Treatment Type";
											if (!empty($row['cpauthstatuscode']))
												$sendtoauthbuttonerror[] = "Rx:Sent to Authorization on " . displayDate($row['cpauthstatusupdated']) . " " . displayTime($row['cpauthstatusupdated']);
											//					else
											//						dump("row['cpauthstatuscode']",$row['cpauthstatuscode']);
										}
									}
								}
								if (count($sendtoauthbuttonerror) == 0) {
									$sendtoauthbuttonstatus = "";
									$msg = "Ready to send to Authorization.";
								} else {
									$sendtoauthbuttonstatus = 'disabled="disabled"';
									$msg = implode(". ", $sendtoauthbuttonerror);
									if (userlevel() == 99)
										unset($sendtoauthbuttonstatus);
								}
								if (!empty($row['cpauthstatuscode']))
									$sendtoauthbutton = "Sent to Auth:" . displayDate($row['cpauthstatusupdated']) . " " . displayTime($row['cpauthstatusupdated']);
								else {
									$sendtoauthbuttonalt = "title='$msg' alt='$msg'";
									$sendtoauthbutton = '<input name="button[' . $row["cpid"] . ']" type="submit" value="Send to Authorization" ' . "$sendtoauthbuttonstatus $sendtoauthbuttonalt" . ' />';
								}
								unset($sendtoptosbutton);
								unset($sendtoptosbuttonstatus);
								$sendtoptosbuttonerror = array();
								if ($row['crcasestatuscode'] == 'GIF' || $row['crcasestatuscode'] == 'CAN') {
									$sendtoptosbuttonerror[] = "Case:Case was cancelled";
								} else {
									if ($row['crcasestatuscode'] == 'NEW' || $row['crcasestatuscode'] == 'PEN' || $row['crcasestatuscode'] == 'PEA' || $row['crcasestatuscode'] == 'SCH')
										$sendtoptosbuttonerror[] = "Case:Patient has not been seen yet";
									else {
										if (empty($row['palname']) || empty($row['pafname']))
											$sendtoptosbuttonerror[] = "Patient:Missing First and Last Name";
										if (empty($row['passn']))
											$sendtoptosbuttonerror[] = "Patient:Missing SSN";
										if (empty($row['padob']))
											$sendtoptosbuttonerror[] = "Patient:Missing DOB";
										if (empty($row['crpnum']))
											$sendtoptosbuttonerror[] = "Case:Missing PTOS Number";
										if ($row['crcasetypecode'] != '5') {
											if (empty($row['crempname']))
												$sendtoptosbuttonerror[] = "Case:Missing Employer";
											//						if( empty($row['cricid1']) && empty($row['cricid2']) )
											//							$sendtoptosbuttonerror[]="Case:Missing Insurance Information";
										}
										if (!empty($row['crptosstatus']) && $row['crptosstatus'] != 'NEW')
											$sendtoptosbuttonerror[] = "Case:Sent to PTOS as " . $row['crpnum'] . " on " . displayDate($row['crptosupdated']) . " " . displayTime($row['crptosupdated']);
										if ($row['crcasestatuscode'] != 'ACT')
											$sendtoptosbuttonerror[] = "Case:Case Status is not ACT";
										if (empty($row['crinjurydate']))
											$sendtoptosbuttonerror[] = "Case:Missing DOI";
										if (empty($row['crapptdate']))
											$sendtoptosbuttonerror[] = "Case:Missing First Visit Date";
										if (empty($row['cpdx1']) && empty($row['cpdx2']) && empty($row['cpdx3']) && empty($row['cpdx4']))
											$sendtoptosbuttonerror[] = "Rx:Missing ICD9 Information";
										if (empty($row['cpdmid']))
											$sendtoptosbuttonerror[] = "Rx:Missing Referring Doctor";
										if (empty($row['cpdlid']))
											$sendtoptosbuttonerror[] = "Rx:Missing Referring Doctor Location";
										if (empty($row['cpcnum']))
											$sendtoptosbuttonerror[] = "Rx:Missing Clinic Location";
										if (empty($row['cptherap']))
											$sendtoptosbuttonerror[] = "Rx:Missing Therapist";
										if ((empty($row['cpduration']) || empty($row['cpfrequency'])) && empty($row['cptotalvisits']))
											$sendtoptosbuttonerror[] = "Rx:Missing Frequency and Duration or Visits";
										if (empty($row['cpttmcode']))
											$sendtoptosbuttonerror[] = "Rx:Missing Treatment Type";
										if ($row['crdate'] != $row['cpdate'])
											$sendtoptosbuttonerror[] = "Rx:Referral Date/Prescription date mismatch. Please correct the Case Referral Date or Prescription Date. They must match.";
									}
								}
								if (count($sendtoptosbuttonerror) == 0) {
									$sendtoptosbuttonstatus = "";
									$msg = "Ready to send to PTOS Interface.";
								} else {
									$sendtoptosbuttonstatus = 'disabled="disabled"';
									$msg = implode(". ", $sendtoptosbuttonerror);
									if (userlevel() == 99)
										unset($sendtoptosbuttonstatus);
								}
								$sendtoptosbuttonalt = "title='$msg' alt='$msg'";
								if (!empty($sendtoptosbuttonstatus)) {
									//dump("msg", $msg);
									if (!empty($row['crpnum'])) {
										$sendtoptosbutton = '<span>PTOS #' . $row['crpnum'] . '</span> ';
									}
									$sendtoptosbutton .= '<span ' . $sendtoptosbuttonalt . '>See Alt Text</span>';
								} else
									$sendtoptosbutton = '<input name="button[' . $row["crid"] . ']" type="submit" value="Send to PTOS" ' . " $sendtoptosbuttonalt" . ' />';

								$printmsg = implode(", ", $sendtoauthbuttonerror) . "<br>" . implode(", ", $sendtoptosbuttonerror);

								if ($rowcolor == "#FFFFFF")
									$rowcolor = "#CCCCCC";
								else
									$rowcolor = "#FFFFFF";

								if ($savedpaid != $row['paid']) {
									$breakpaid = TRUE;
									$savedpaid = $row['paid'];
									?>
									<tr style="background-color:<?php echo $rowcolor; ?>">
										<td colspan="2">
											<?php echo $row["paid"]; ?>&nbsp;
										</td>
										<td colspan="2">
											<?php echo $row["palname"]; ?>&nbsp;
										</td>
										<td colspan="2">
											<?php echo $row["pafname"]; ?>&nbsp;
										</td>
										<td colspan="2">SSN:
											<?php echo displaySsn($row["passn"]); ?>&nbsp;
										</td>
										<td colspan="2">DOB:
											<?php echo displayDate($row["padob"]); ?>&nbsp;
										</td>
										<td colspan="2">
											<?php echo displayPhone($row["paphone1"]); ?>&nbsp;
										</td>
										<?php
										if (!$print) {
											?>
											<td nowrap="nowrap">
												<input name="button[<?php echo $row["paid"] ?>]" type="submit" value="Edit Patient" />
											</td>
											<?php
										} else {
											?>
											<td rowspan="5" valign="top"
												style="border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:2px">
												<?php echo $printmsg; ?>
											</td>
											<?php
										}
										?>
									</tr>
									<?php
								}
								if ($savedcrid != $row['crid']) {
									$breakcrid = TRUE;
									$savedcrid = $row['crid'];
									?>
									<tr style="background-color:<?php echo $rowcolor; ?>">
										<td>&nbsp;</td>
										<td colspan="2">
											<?php echo $row["crid"]; ?>&nbsp;
										</td>
										<td colspan="2">
											<?php echo $row["crtherapytypecode"] . "/" . $row["crcasestatuscode"];
											; ?>&nbsp;
										</td>
										<td colspan="2">DOI:
											<?php echo displayDate($row["crinjurydate"]); ?>&nbsp;
										</td>
										<td colspan="2">REF:
											<?php echo displayDate($row["crdate"]); ?>&nbsp;
										</td>
										<td colspan="3">APPT:
											<?php echo displayDate($row["crapptdate"]) . " " . displayTime($row["crapptdate"]) . " at " . $row['crcnum']; ?>&nbsp;
										</td>
										<?php
										if (!$print) {
											?>
											<td nowrap="nowrap">
												<input name="button[<?php echo $row["crid"] ?>]" type="submit" value="Edit Case" />
												<!-- <input name="button[<//?php echo $row["crid"] ?>]" type="submit" value="Add Prescription" /> -->
												<input name="button[<?php echo $row["crid"] ?>]" type="submit" value="Add Prescription" disabled />
												<?php echo $sendtoptosbutton; ?>
											</td>
											<?php
										}
										?>
									</tr>
									<?php
								}
								//if(!empty($row['cpid'])) {
								if (array_key_exists($row["cpstatuscode"], $caseprescriptionstatuscodes))
									$prescriptionstatus = $caseprescriptionstatuscodes[$row["cpstatuscode"]]['description'];
								else
									$prescriptionstatus = "NONE";

								if (array_key_exists($row["cpauthstatuscode"], $caseprescriptionauthorizationstatuscodes))
									$authorizationstatus = $caseprescriptionauthorizationstatuscodes[$row["cpauthstatuscode"]]['description'];
								else
									$authorizationstatus = "NONE";
								if (!empty($row['dmfname']))
									$doctorname = $row['dmlname'] . ", " . $row['dmfname'];
								else
									$doctorname = $row['dmlname'];
								if (!empty($row['dlname']))
									$locationname = $row['dlname'];
								else
									$locationname = "NOT SPECIFIED";
								?>
								<tr style="background-color:<?php echo $rowcolor; ?>">
									<td colspan="2">&nbsp;</td>
									<td colspan="2">
										<?php echo $row['cpid']; ?>
									</td>
									<td colspan="2">RxDate:&nbsp;
										<?php echo displayDate($row["cpdate"]); ?>
									</td>
									<td colspan="2">RxSts:&nbsp;
										<?php echo $prescriptionstatus . "/" . $authorizationstatus; ?>
									</td>
									<td colspan="2">RxDoc:&nbsp;
										<?php echo $doctorname; ?>
									</td>
									<td colspan="2">RxLoc:&nbsp;
										<?php echo $locationname; ?>
									</td>
									<?php
									if (!$print) {
										?>
										<td nowrap="nowrap">
											<input name="button[<?php echo $row["cpid"] ?>]" type="submit" <?php if ($row["cpid"] == 0): ?>
													disabled='disabled' <?php endif ?> value="Edit Prescription" />
											<?php echo $sendtoauthbutton; ?>
										</td>
										<?php
									}
									?>
								</tr>
								<tr style="background-color:<?php echo $rowcolor; ?>">
									<td colspan="3">&nbsp;</td>
									<td colspan="10">Dx:&nbsp;
										<?php echo ($row["cpdx1"] . " " . $row["cpdx2"] . " " . $row["cpdx3"] . " " . $row["cpdx4"]); ?>
									</td>
								</tr>
								<tr style="background-color:<?php echo $rowcolor; ?>">
									<td colspan="3"
										style="border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:2px">&nbsp;
									</td>
									<td colspan="10"
										style="border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:2px">
										Treatment:&nbsp;
										<?php echo ($row["cpfrequency"] . "x" . $row["cpduration"] . '=' . $row["cptotalvisits"]); ?>&nbsp;
									</td>
								</tr>
								<?php
								//}
							}
							?>
						</table>
			
						<input type="hidden" value="<?php echo $pfirstname . " " . $pLastName; ?>" name="patientName" />

					</form>
					<?php
					foreach ($_POST as $key => $val)
						unset($_POST[$key]);
				} else
					echo ('No matches found.');
	} else
		error('001', "QUERY:" . $query . ":" . mysqli_error($dbhandle));
	//close the connection
	mysqli_close($dbhandle);
	?>
		</fieldset>
	</div>
	<?php
}
?>