<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16);
?>
<script type="text/javascript">
function searchpatient(last,first) {
	var palname=document.getElementById('palname');
	var pafname=document.getElementById('pafname');
	var criclid1NULL=document.getElementById('criclid1NULL');
	var test=document.getElementById('test');
	f=document.getElementById("searchForm");
	f.reset();
	criclid1NULL.checked=false;
	palname.value=last;
	pafname.value=first;
	test.value='1';
	f.submit();
}
</script>
<?
$searchSaved = getformvars('authprocessing', 'search');
if( !empty($searchSaved) || !empty($_POST['formSubmit']) || !empty($_POST['buttonSetSearch']) || !empty($_POST['sort']) ) {
	// sort is an array of the sort fields and properties "field"=>array("title", "collation")
	$sortSaved = getformvars('authprocessing', 'searchResults');
	// if Sort button pressed set sort values
	if(count($_POST['sort']) > 0) {
	// If Reset Sort Pressed then clear saved values
		if(!empty($_POST['sort']['RESETSORT'])) {
			clearformvars('authprocessing', 'searchResults');
			unset($sortSaved);
		}
		else {
	// determine sort field name from key
			$sortfield=key($_POST['sort']);
	// if that key exists in the sort then toggle collation
			if(array_key_exists($sortfield, $sortSaved)) {
				$collation = $sortSaved["$sortfield"]['collation'];
				if($collation == 'desc')
					$sortSaved["$sortfield"]["collation"] = '';
				else
					$sortSaved["$sortfield"]["collation"] = 'desc';
			}
			else
				$sortSaved["$sortfield"] = $searchvars["$sortfield"];
			setformvars('authprocessing', 'searchResults', $sortSaved);
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query  = "SELECT
	paid, palname, pafname, passn, padob,crinjurydate,
	crid, crpnum, crcasestatuscode, crapptdate, cricclaimnumber1, cricid1, criclid1, cricaid1, cricclaimnumber2, cricid2, criclid2, cricaid2, crpostsurgical, crpostsurgical, crsurgerydate,
	cpid, cpdate, cpstatuscode, cpstatusupdated, cpauthstatuscode, cpauthstatusupdated, cprfastatuscode, cprfastatusupdated, cpdocstatuscode, cpdocstatusupdated, cpduration, cpfrequency, cptotalvisits, cpdmid, cpdlid, cpcnum, cptherap, cpdx1, cpdx2, cpdx3, cpdx4, cpttmcode,
	cmbnum
FROM cases c
  LEFT JOIN patients p ON crpaid=paid
  LEFT JOIN case_prescriptions a on crid = cpcrid
  LEFT JOIN master_clinics mc on crcnum=cmcnum
";

	$where = array();
if($_POST['criclid1NULL']=='1') {
	$where[] = "crcasestatuscode <> 'CAN' and crcasetypecode <>'5' ";
	$where[] = 'criclid1 IS NULL';
	$where[] = 'cpdate < DATE_SUB(NOW(), INTERVAL 19 day)';
}
else {
	$where[] = "crcasestatuscode IN ('PEA', 'ACT') and crcasetypecode <>'5' ";
}
	$where[] = "cpcnum IN " . getUserClinicsList();
	foreach($searchSaved as $formvar=>$formvarvalue) {
		if( isset($formvarvalue) && !empty($formvarvalue) )  {
			$title = $searchvars["$formvar"]['title'];
			$type = $searchvars["$formvar"]['type'];
			$dbformat = $searchvars["$formvar"]['dbformat'];
			$dblength = $searchvars["$formvar"]['dblength'];
			$displayformat = $searchvars["$formvar"]['displayformat'];
			$displaylength = $searchvars["$formvar"]['displaylength'];
			$length = $searchvars["$formvar"]['length'];
			$test = $searchvars["$formvar"]['test'];

			switch($dbformat):
				case 'date':
					$formvarvalue = date("Y-m-d", strtotime($formvarvalue));
					break;
				case 'ssn':
					$formvarvalue = dbSsn($formvarvalue);
					break;
			endswitch;

			switch($test):
				case 'LIKE':
					$test = "LIKE '" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "%'";
					break;
				default:
					$test = "= '" . mysqli_real_escape_string($dbhandle,$formvarvalue) . "'";
			endswitch;
			$where[] = "$formvar $test";
		}
	}


//	if(empty($_POST['search']['crcasestatuscode']))
//		$query .= "WHERE c.crcasestatuscode IN ('PEN', 'PEA', 'SCH', 'ACT') ";
//	else
//		$query .= "WHERE 1=1 ";

	if(count($where) > 0)

// Default search for cases with status SCH and authorization status of NEW

		$query .= "WHERE " . implode(" and ", $where);
		$groupby =  "
					GROUP BY c.crid, p.palname, p.pafname, p.passn, p.padob, crcasestatuscode, cpid
					";
	//
	// Sort Order - Contained in Session variable 'customerservice'=>'searchResults'=>array(field=>collation) as sort
	//

	$orderby=array();
	if(empty($sortSaved)) {
	// default sort here
		$sortvartitles = "unsorted (click column titles to add/toggle sort)";
	}
	else {
		$sortvartitles = "sorted by ";
		foreach($sortSaved as $sortvar=>$sortvarproperty) {
			$orderby[] = trim($sortvar . " " . $sortvarproperty["collation"]);
			$sortvartitles .= trim($sortvarproperty["title"] . " " . $sortvarproperty["collation"]) . ", ";
		}
	}
	if(count($orderby) > 0) {
		$sortvartitles = substr($sortvartitles,0,-2) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="sort[RESETSORT]" type="submit" value="Reset Sort">';
		$order = "ORDER BY " . implode(",", $orderby);
	}
	else {
		$order = "ORDER BY paid, crid, cpid";
	}

	$query.=" $order
LIMIT 100 ";
//dump("query",$query);
//dump("groupby",$groupby);
//dump("order",$order);
	$result = mysqli_query($dbhandle,$query);
	if($result) {
		$numRows = mysqli_num_rows($result);
	?>
<div class="containedBox">
	<fieldset>
	<legend style="font-size:large;"><form method="post" name="sortReset">Authorization Processing Search Results **** <?php echo $sortvartitles;?></form>
</legend>
	<?php
	if($numRows > 0) {
		if($numRows == 1)
			echo "$numRows match found.";
		else {
			if($numRows < 100)
				echo "$numRows matches found.";
			else
				echo "Not displaying records with appointment date < 08/01/2021 or RFA Sts 'SNT' (Tip: Show 'SNT' records by adjusting search filters.)";
		}
	?>
	<form method="post" name="searchResults">
		<table cellpadding="3" cellspacing="0" width="100%" style="border: 2px solid rgb(0,0,0);">
			<tr>
				<th><input name="sort[palname]" type="submit" value="Last Name" /></th>
				<th><input name="sort[pafname]" type="submit" value="First Name" /></th>
				<th><input name="sort[crpnum]" type="submit" value="Number" /></th>
				<th><input name="sort[cpcnum]" type="submit" value="Clinic" /></th>
				<th><input name="sort[crapptdate]" type="submit" value="Appt Date" /></th>
				<th><input name="sort[passn]" type="submit" value="SSN" /></th>
				<th><input name="sort[padob]" type="submit" value="DOB" /></th>
				<th><input name="sort[crinjurydate]" type="submit" value="DOI" /></th>
				<th><input name="sort[crcasestatuscode]" type="submit" value="Case Status" /></th>
				<th><input name="sort[crpostsurgical]" type="submit" value="P/Sx" /></th>
				<th><input name="sort[crsurgerydate]" type="submit" value="Sx Date" /></th>
				<th><input name="sort[cricclaimnumber1]" type="submit" value="Pri Claim" /></th>
				<th>Pri Ins</th>
				<th><input name="sort[cricclaimnumber2]" type="submit" value="Sec Claim" /></th>
				<th>Sec Ins</th>
				<th><input name="sort[cpdate]" type="submit" value="Rx Date" /></th>
				<th><input name="sort[cpstatuscode]" type="submit" value="Rx Status" /></th>
				<th><input name="sort[cpauthstatuscode]" type="submit" value="Auth Status" /></th>
				<th><input name="sort[cprfastatuscode]" type="submit" value="RFA Status" /></th>
				<th><input name="sort[cpdocstatuscode]" type="submit" value="Doc Status" /></th>
				<th>&nbsp;</th>
			</tr>
			<?php
		$casestatuscodes = caseStatusCodes();
		$caseprescriptionstatuscodes = casePrescriptionStatusCodes();
		$caseprescriptionauthorizationstatuscodes = casePrescriptionAuthorizationStatusCodes();
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/insurance.options.php');
		$allinsurancecompanies = getInsuranceCompaniesList();
		$allinsurancecompanieslocations = getInsuranceCompaniesLocationsList();
		$allinsurancecompaniesadjusters = getInsuranceCompaniesAdjustersList();

$ptosnoinsurance=array();
$ptosnoinsurancequery="SELECT bnum, pnum, pinsurance, sinsurance FROM ptos_pat1 WHERE pinsurance=''";
if($ptosnoinsuranceresult=mysqli_query($dbhandle,$ptosnoinsurancequery)) {
	while($ptosnoinsurancerow=mysqli_fetch_assoc($ptosnoinsuranceresult)) {
		$ptosnoinsurance[$ptosnoinsurancerow['pnum']]=$ptosnoinsurancerow;
	}
}
$ptosinsurance=array();
$ptosinsurancequery="SELECT bnum, pnum, pinsurance, sinsurance FROM ptos_pat1 WHERE pinsurance!=''";
if($ptosinsuranceresult=mysqli_query($dbhandle,$ptosinsurancequery)) {
	while($ptosinsurancerow=mysqli_fetch_assoc($ptosinsuranceresult)) {
		$ptosinsurance[$ptosinsurancerow['pnum']]=$ptosinsurancerow;
	}
}
//$ptosinsurance="SELECT * FROM ptos_pat1 WHERE pinsurance!=''";

		while($row = mysqli_fetch_assoc($result)) {

 // $date_now = strtotime($row["crapptdate"]); // this format is string comparable	
 // $aug = strtotime('2021-08-01');	
 // $match = $date_now > $aug;	
// if (!$match) {
if ($row) {
			if($savedid != $row['cpid']) {
				$breakid=TRUE;
				$savedid = $row['cpid'];
				if($row['crcasestatuscode']=='PEA') {
					$rowcolor = "#CC0000";
				}
				else {
					if($rowcolor == "#FFFFFF")
						$rowcolor = "#CCCCCC";
					else
						$rowcolor = "#FFFFFF";
				}

// Insurance Button
//	$insurancelist  = getInsuranceList();
				$company1="Add Ins";
				unset($company1note);
				if( !empty($row['cricid1']) ) {
					$company1 = $allinsurancecompanies[$row['cricid1']]["icname"];
			//		$locationlist = getInsuranceLocationList($_POST['cricid1']);
					if( !empty($row['criclid1']) ) {
						$location1 = $allinsurancecompanieslocations[$row['criclid1']]["iclname"]."(".$allinsurancecompanieslocations[$row['criclid1']]["iclcity"].")";
			//			$adjusterlist = getInsuranceLocationAdjusterList($_POST['cricid1']);
						if( !empty($row['cricaid1']) ) {
							$lname = $allinsurancecompaniesadjusters[$row['cricaid1']]["icalname"];
							$fname = $allinsurancecompaniesadjusters[$row['cricaid1']]["icafname"];
							if(!empty($fname))
								$adjuster1 = "$lname, $fname";
							else
								$adjuster1 = "$lname";
						}
					}
				}
				else {
					if(!empty($row['crpnum'])) {
						if( count( $ptosinsurance[$row['crpnum']]) != 0 ) {
							$company1note = 'PTOS:'.$allinsurancecompanies[$allinsurancecompanieslocations[$ptosinsurance[$row['crpnum']]['pinsurance']]['iclicid']]["icname"]."(".$ptosinsurance[$row['crpnum']]['pinsurance'].")";
						}
					}

				}
				$company2="Add Ins";
				if( !empty($row['cricid2']) ) {
					$company2 = $allinsurancecompanies[$row['cricid2']]["icname"];
			//		$locationlist = getInsuranceLocationList($_POST['cricid2']);
					if( !empty($row['criclid2']) ) {
						$location2 = $allinsurancecompanieslocations[$row['criclid2']]["iclname"]."(".$allinsurancecompanieslocations[$row['criclid2']]["iclcity"].")";
			//			$adjusterlist = getInsuranceLocationAdjusterList($_POST['cricid2']);
						if( !empty($row['cricaid2']) ) {
							$lname = $allinsurancecompaniesadjusters[$row['cricaid2']]["icalname"];
							$fname = $allinsurancecompaniesadjusters[$row['cricaid2']]["icafname"];
							if(!empty($fname))
								$adjuster2 = "$lname, $fname";
							else
								$adjuster2 = "$lname";
						}
					}
				}
//	if( (userlevel()==21 && (empty($_POST['crptosstatus']) || $_POST['crptosstatus']=='NEW')) or isuserlevel(90)) {
				$insurancebutton1 = '<input name="insurance1[' . $row['crid'] .']" type="button" value="'.substr($company1,0,7).'" onClick="window.open(' . "'modules/authprocessing/insuranceEditForm.php?crid=" . $row['crid'] . "&icseq=1','UpdateInsuranceInformation','width=700,height=800')" .'"/>';
				$insurancebutton2 = '<input name="insurance2[' . $row['crid'] .']" type="button" value="'.substr($company2,0,7).'" onClick="window.open(' . "'modules/authprocessing/insuranceEditForm.php?crid=" . $row['crid'] . "&icseq=2','UpdateInsuranceInformation','width=700,height=800')" .'"/>';
//	}
//	else {
//		unset($insurancebutton1);
//		unset($insurancebutton2);
//	}
				if(!empty($company1)) {
					if(!empty($location1)) {
						if(!empty($adjuster1)) {
							$insurance1html = "$company1, $location1 $adjuster1";
						}
						else
							$insurance1html = "$company1, $location1";
					}
					else
						$insurance1html = "$company1;";
				}
				else
					$insurance1html = "&nbsp;";

				if(!empty($company2)) {
					if(!empty($location2)) {
						if(!empty($adjuster2))
							$insurance2html = "$company2, $location2 $adjuster2";
						else
							$insurance2html = "$company2, $location2";
					}
					else
						$insurance2html = "$company2;";
				}
				else
					$insurance2html = "&nbsp;";

			$buttons=array();
// Authorization People
			if(userlevel()==99) {
				$buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Push Conversion" />';
				$buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Push Auth" />';
			}
			if(userlevel()==16) {
				$url = "'/modules/authprocessing/authprocessingHistoryAdd.php?cpid=".$row['cpid'] ."'";
				$name="'PrescriptionHistory'";
//				$width="'width=720,scrollbars=yes,resizable=yes,directories=no,location=no,menubar=no,status=no,toolbar=no'";
				$width="'width=720,height=420,scrollbars=yes'";
				$buttons[]='<input name="historyButton" type="button" value="History" onclick="window.open(' . "$url, $name, $width" . ');" />';

				if(!empty($row['crid']) && !empty($row['crpnum'])) {
					$url = "'/modules/collections/collectionsNotesDtl.php?app=collections&bnum=".$row['cmbnum']."&pnum=".$row['crpnum']."'";
					$name="'NotesDetail'";
					$width="'width=1024,scrollbars=yes,resizable=yes'";
					$buttons[]='<input name="NotesDetail" type="button" value="Collections Notes" onclick="window.open(' . "$url, $name, $width" . ');" />';
				}

				if($row['cpstatuscode']='ACT') {
					if($row['cpauthstatuscode']=='NEW') {

// Show Print RFA button if all of either insurance information is entered
						if(($row['cprfastatuscode']=='NEW' || empty($row['cprfastatuscode'])) && (
						(!empty($row['cricid1']) && !empty($row['criclid1']) && !empty($row['cricaid1']) && !empty($row['cricclaimnumber1']) ) ||
						(!empty($row['cricid2']) && !empty($row['criclid2']) && !empty($row['cricaid2']) && !empty($row['cricclaimnumber2']) )
						) ) {
                            $rfaurl = "'/modules/authprocessing/authprocessingPrintRfa.php?cpid=".$row['cpid'] ."&printed=1'";
                            $rfatitle="'PrintRFA'";
                            $rfawidth="'width=1024,scrollbars=yes,resizable=yes'";

                            $rfaPDFurl = "'/modules/authprocessing/authprocessingPrintRfaPDF.php?cpid=".$row['cpid']."'";
                            $rfaPDFtitle = "'PrintRFAPDF'";
                            $rfaPDFwidth="'width=1024,scrollbars=yes,resizable=yes'";

                            $posurl = "'/modules/authprocessing/authprocessingPrintPos.php?cpid=".$row['cpid'] ."&printed=1'";
                            $postitle="'PrintProofOfService'";
                            $poswidth="'width=1024,scrollbars=yes,resizable=yes'";

                            $buttons[]='<input name="printBOTH" type="button" value="Print RFA" onclick="window.open('.$rfaurl.','.$rfatitle.','.$rfawidth.'); window.open('.$rfaPDFurl.','.$rfaPDFtitle.','.$rfaPDFwidth.'); window.open('.$posurl.','.$postitle.','.$poswidth.');" />';
                        }
						else {
							$url = "'/modules/authprocessing/authprocessingPrintRFIForm.php?cpid=".$row['cpid'] ."'";
							$title="'RequestInsuranceForm'";
							$width="'width=1024,scrollbars=yes,resizable=yes'";
							$buttons[]='<input name="RequestInsuranceForm" type="button" value="Request Insurance" onclick="window.open('.$url.','.$title.','.$width.'); " />';
						}
					}
					if($row['cprfastatuscode']=='PRT')
						$buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Sent RFA" />';

					if($row['cprfastatuscode']=='PRT' || $row['cprfastatuscode']=='SNT') {

                        $rfaurl = "'/modules/authprocessing/authprocessingPrintRfa.php?cpid=".$row['cpid'] ."'";
						$rfatitle="'RePrintRFA'";
						$rfawidth="'width=1024,scrollbars=yes,resizable=yes'";

                        $rfaPDFurl = "'/modules/authprocessing/authprocessingPrintRfaPDF.php?cpid=".$row['cpid']."'";
                        $rfaPDFtitle = "'PrintRFAPDF'";
                        $rfaPDFwidth="'width=1024,scrollbars=yes,resizable=yes'";

                        $posurl = "'/modules/authprocessing/authprocessingPrintPos.php?cpid=".$row['cpid'] ."'";
						$postitle="'RePrintProofOfService'";
						$poswidth="'width=1024,scrollbars=yes,resizable=yes'";

						$printurl = "'/modules/authprocessing/authprocessingPrint.php?cpid=".$row['cpid'] ."'";
						$printtitle="'RePrintProofOfService'";
						$printwidth="'width=1024,scrollbars=yes,resizable=yes'";
						//window.open('.$rfaurl.','.$rfatitle.','.$rfawidth.'); window.open('.$rfaPDFurl.','.$rfaPDFtitle.','.$rfaPDFwidth.');window.open('.$posurl.','.$postitle.','.$poswidth.');
						$buttons[]='<input name="RePrintRFA" type="button" value="Re-Print RFA" onclick="window.open('.$printurl.','.$printtitle.','.$printwidth.')" />';
						$buttons[]='<input name="RePrintPOS" type="button" value="Re-Print Proof" onclick="window.open('.$posurl.','.$postitle.','.$poswidth.')" />';
					}
					if($row['cpauthstatuscode']=='NEW') {
						if($row['cpdocstatuscode']=='RQS')
							$buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Sent Docs/Info" />';
						if($row['cpdocstatuscode']!='RQS') {
							if($row['cprfastatuscode']=='SNT') {
								$buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Requested Docs" />';
								$buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Authorized" />';
								$buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Denied" />';
							}
						}
					}
					if($row['cpauthstatuscode']=='ASU') {
						$buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Requested Docs Late" />';
						$buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Authorized" />';
						$buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Denied Late" />';
					}
				}
			}
//			$buttons[]=$insurancebutton1;
//			$buttons[]=$insurancebutton2;
			unset($functions);
			foreach($buttons as $key=>$value)
				$functions .= $value;
?>
			<tr style="background-color:<?php echo $rowcolor; ?>">
				<td onclick="searchpatient('<?php echo $row["palname"]; ?>', '<?php echo $row["pafname"]; ?>');"><?php echo $row["palname"]; ?>&nbsp;</td>
				<td onclick="searchpatient('<?php echo $row["palname"]; ?>', '<?php echo $row["pafname"]; ?>')"><?php echo $row["pafname"]; ?>&nbsp;</td>
				<td><?php echo $row["crpnum"]; ?>&nbsp;</td>
				<td><?php echo $row["cpcnum"]; ?>&nbsp;</td>
				<td><?php echo displayDate($row["crapptdate"]); ?>&nbsp;</td>
				<td><?php echo displaySsn($row["passn"]); ?>&nbsp;</td>
				<td><?php echo displayDate($row["padob"]); ?>&nbsp;</td>
				<td><?php echo displayDate($row["crinjurydate"]); ?>&nbsp;</td>
				<td><?php echo $row["crcasestatuscode"]; ?>&nbsp;</td>
				<td><?php if(!empty($row["crpostsurgical"])) echo "Yes"; ?>&nbsp;</td>
				<td><?php if(!empty($row["crsurgerydate"])) echo displayDate($row["crsurgerydate"]); ?>&nbsp;</td>
				<td><?php echo $row["cricclaimnumber1"]; ?>&nbsp;</td>
				<td nowrap="nowrap"><?php if(!empty($company1note)) echo $company1note.'<br />'; echo $insurancebutton1; ?>&nbsp;</td>
				<td><?php echo $row["cricclaimnumber2"]; ?>&nbsp;</td>
				<td><?php echo $insurancebutton2; ?>&nbsp;</td>
				<td><?php echo displayDate($row["cpdate"]); ?>&nbsp;</td>
				<td><?php echo $row["cpstatuscode"]; ?>&nbsp;</td>
				<td><?php echo $row["cpauthstatuscode"]; ?>&nbsp;</td>
				<td><?php echo $row["cprfastatuscode"]; ?>&nbsp;</td>
				<td><?php echo $row["cpdocstatuscode"]; ?>&nbsp;</td>
				<td nowrap="nowrap"><?php echo $functions; ?></td>
			</tr>
<?php
			}
		}
		}
?>
		</table>
	</form>
	<?php
		foreach($_POST as $key=>$val)
			unset($_POST[$key]);
		}
		else
			echo('No matches found.');
	}
	else
		error('001', "QUERY:" . $query . ":" . mysqli_error($dbhandle));
//close the connection
mysqli_close($dbhandle);
?>
	</fieldset>
</div>
<?php
}
echo "* = Check PTOS database for insurance information";
//dump("where",$where);
?>