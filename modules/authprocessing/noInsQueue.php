<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


if(isset($_POST['done'])) {
    $noinsid = $_POST['noinsid'];
    if ($noinsid) {
        $sql = "UPDATE noins_queue SET noinsdone = 1 WHERE noinsid = ".$_POST['noinsid'];
    } else {
        $sql = "INSERT INTO noins_queue(noinscrid, noinsdone) VALUES (".$_POST['crid'].",1)";
    }
    mysqli_query($dbhandle,$sql);
    echo mysqli_error($dbhandle);
    $sql = "INSERT INTO case_prescriptions_history(cphcpid, cphdate, cphhistory, cphuser) VALUES(".$_POST['cpid'].", NOW(), 'Marked as Done in the No Ins Queue', '".$_SESSION['user']['umuser']."')";
    mysqli_query($dbhandle,$sql);
    echo mysqli_error($dbhandle);
    
}

if(isset($_POST['callback'])) {
    $callbackDate = date('Y-m-d h:i:s', strtotime(" +".$_POST['callback_time']));
    $noinsid = $_POST['noinsid'];
    if ($noinsid) {
        $sql = "UPDATE noins_queue SET noins_callback = '$callbackDate' WHERE noinsid = ".$_POST['noinsid'];
    } else {
        $sql = "INSERT INTO noins_queue(noinscrid, noins_callback) VALUES (".$_POST['crid'].",'$callbackDate')";
    }
    mysqli_query($dbhandle,$sql);
    echo mysqli_error($dbhandle);
    $sql = "INSERT INTO case_prescriptions_history(cphcpid, cphdate, cphhistory, cphuser) VALUES(".$_POST['cpid'].", NOW(), 'Callback Scheduled for $callbackDate', '".$_SESSION['user']['umuser']."')";
    mysqli_query($dbhandle,$sql);
    echo mysqli_error($dbhandle);
}

if(isset($_POST['msg'])) {
    $sql = "INSERT INTO case_prescriptions_history(cphcpid, cphdate, cphhistory, cphuser) VALUES(".$_POST['cpid'].", NOW(), '".$_POST['msg']."', '".$_SESSION['user']['umuser']."')";
    mysqli_query($dbhandle,$sql);
    echo mysqli_error($dbhandle);   
}

$sql = "
SELECT
	paid, palname, pafname, passn, padob,
	crid, crpnum, crcasestatuscode, crapptdate, cricclaimnumber1, cricid1, criclid1, cricaid1, cricclaimnumber2, cricid2, criclid2, cricaid2, crpostsurgical, crpostsurgical, crsurgerydate,
	cpid, cpdate, cpstatuscode, cpstatusupdated, cpauthstatuscode, cpauthstatusupdated, cprfastatuscode, cprfastatusupdated, cpdocstatuscode, cpdocstatusupdated, cpduration, cpfrequency, cptotalvisits, cpdmid, cpdlid, cpcnum, cptherap, cpdx1, cpdx2, cpdx3, cpdx4, cpttmcode,
	cmbnum, noinsid, noins_callback
FROM cases c
  JOIN patients p ON crpaid=paid
  JOIN case_prescriptions a on crid = cpcrid
  JOIN master_clinics mc on crcnum=cmcnum
  JOIN collection_accounts ca ON ca.capnum = crpnum
  JOIN collection_queue cq ON cqcaid = caid
  LEFT JOIN noins_queue ON noinscrid = crid
WHERE crcasestatuscode <> 'CAN' 
and crcasetypecode <> '5'  
and criclid1 IS NULL 
and cpdate < DATE_SUB(NOW(), INTERVAL 19 day) 
and (noinsdone IS NULL or noinsdone = 0) 
and (noins_callback IS NULL or noins_callback <= NOW())
and cqgroup like '%35INS'
ORDER BY crid
LIMIT 1";

$result = mysqli_query($dbhandle,$sql);
echo mysqli_error($dbhandle);
$row = mysqli_fetch_assoc($result);

$historyquery = "
    SELECT cphdate, cphhistory, cphuser
    FROM case_prescriptions_history
    WHERE cphcpid='".$row['cpid']."'
    ORDER BY cphdate desc
";
$historyResult = mysqli_query($dbhandle,$historyquery);

$notesQuery = "
    SELECT *
    FROM notes
    WHERE nobnum='".$row['cmbnum']."' and nopnum='".$row['crpnum']."'
    ORDER BY crtdate desc";
$notesResult = mysqli_query($dbhandle,$notesQuery);


$buttons = array();
if($row['cpstatuscode']=='ACT') {
    if($row['cpauthstatuscode']=='NEW') {

        // Show Print RFA button if all of either insurance information is entered
        if(($row['cprfastatuscode']=='NEW' || empty($row['cprfastatuscode'])) && (
        (!empty($row['cricid1']) && !empty($row['criclid1']) && !empty($row['cricaid1']) && !empty($row['cricclaimnumber1']) ) ||
        (!empty($row['cricid2']) && !empty($row['criclid2']) && !empty($row['cricaid2']) && !empty($row['cricclaimnumber2']) )
        ) ) {
            $rfaurl = "'/modules/authprocessing/authprocessingPrintRfa.php?cpid=".$row['cpid'] ."&printed=1'";
            $rfatitle="'PrintRFA'";
            $rfawidth="'width=1024,scrollbars=yes,resizable=yes'";
            
            $rfaPDFurl = "'/modules/authprocessing/authprocessingPrintPDFRfa.php?cpid=".$row['cpid']."'";
            $rfaPDFtitle = "'PrintRFA'";
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
        $posurl = "'/modules/authprocessing/authprocessingPrintPos.php?cpid=".$row['cpid'] ."'";
        $postitle="'RePrintProofOfService'";
        $poswidth="'width=1024,scrollbars=yes,resizable=yes'";
        $buttons[]='<input name="RePrintRFA" type="button" value="Re-Print RFA" onclick="window.open('.$rfaurl.','.$rfatitle.','.$rfawidth.');" />';
        $buttons[]='<input name="RePrintPOS" type="button" value="Re-Print Proof" onclick="window.open('.$posurl.','.$postitle.','.$poswidth.')" />';
    }
    if($row['cpauthstatuscode']=='NEW') {
        if($row['cpdocstatuscode']=='RQS')
            $buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Sent Docs/Info" />';
        if($row['cpdocstatuscode']!='RQS') {
            if($row['cprfastatuscode']=='SNT') {
                $buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Authorized" />';
                $buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Denied" />';
            }
        }
    }
    if($row['cpauthstatuscode']=='ASU') {
        $buttons[]='<input name="button[' . $row["cpid"] . ']" type="submit" value="Authorized" />';
    }
}

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

//Insurance Buttons
$company1="Add Primary Ins";
unset($company1note);
if( !empty($row['cricid1']) ) {
    $company1 = $allinsurancecompanies[$row['cricid1']]["icname"];
    if( !empty($row['criclid1']) ) {
        $location1 = $allinsurancecompanieslocations[$row['criclid1']]["iclname"]."(".$allinsurancecompanieslocations[$row['criclid1']]["iclcity"].")";
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
$company2="Add Secondary Ins";
if( !empty($row['cricid2']) ) {
    $company2 = $allinsurancecompanies[$row['cricid2']]["icname"];
    if( !empty($row['criclid2']) ) {
        $location2 = $allinsurancecompanieslocations[$row['criclid2']]["iclname"]."(".$allinsurancecompanieslocations[$row['criclid2']]["iclcity"].")";
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
$insurancebutton1 = '<input name="insurance1[' . $row['crid'] .']" type="button" value="'.$company1.'" onClick="window.open(' . "'modules/authprocessing/insuranceEditForm.php?crid=" . $row['crid'] . "&icseq=1','UpdateInsuranceInformation','width=700,height=800')" .'"/>';
$insurancebutton2 = '<input name="insurance2[' . $row['crid'] .']" type="button" value="'.$company2.'" onClick="window.open(' . "'modules/authprocessing/insuranceEditForm.php?crid=" . $row['crid'] . "&icseq=2','UpdateInsuranceInformation','width=700,height=800')" .'"/>';

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
    $insurance2html = "&nbsp;"

?>
<form name="noInsQueue" method="POST">
    <div class="containedBox">
        <div class='containedBox'>

            <div id="information">
                <fieldset>
                    <legend>Patient Information</legend>
                    <div><div class="noINSLabel">First Name:</div> <?php echo $row["pafname"]; ?></div>
                    <div><div class="noINSLabel">Last Name:</div> <?php echo $row["palname"]; ?></div>
                    <div><div class="noINSLabel">PNum:</div> <?php echo $row["crpnum"]; ?></div>
                    <div><div class="noINSLabel">Clinic:</div> <?php echo $row["cpcnum"]; ?></div>
                    <div><div class="noINSLabel">Appt Date:</div> <?php echo displayDate($row["crapptdate"]); ?></div>
                    <div><div class="noINSLabel">SSN:</div> <?php echo displaySsn($row["passn"]); ?></div>
                    <div><div class="noINSLabel">DOB:</div> <?php echo displayDate($row["padob"]); ?></div>
                    <div><div class="noINSLabel">Case Status:</div> <?php echo $row["crcasestatuscode"]; ?></div>
                    <div><div class="noINSLabel">P/Sx:</div> <?php if(!empty($row["crpostsurgical"])) echo "Yes"; ?></div>
                    <div><div class="noINSLabel">Sx Date:</div> <?php if(!empty($row["crsurgerydate"])) echo displayDate($row["crsurgerydate"]); ?></div>
                    <div><div class="noINSLabel">Pri Claim:</div> <?php echo $row["cricclaimnumber1"]; ?></div>
                    <div><?php if(!empty($company1note)) echo $company1note.'<br />'; ?></div>
                    <div><div class="noINSLabel">Sec Claim:</div> <?php echo $row["cricclaimnumber2"]; ?></div>
                    <div><div class="noINSLabel">Rx Date:</div> <?php echo displayDate($row["cpdate"]); ?></div>
                    <div><div class="noINSLabel">Rx Status:</div> <?php echo $row["cpstatuscode"]; ?></div>
                    <div><div class="noINSLabel">Auth Status:</div> <?php echo $row["cpauthstatuscode"]; ?></div>
                    <div><div class="noINSLabel">RFA Status:</div> <?php echo $row["cprfastatuscode"]; ?></div>
                    <div><div class="noINSLabel">Doc Status:</div> <?php echo $row["cpdocstatuscode"]; ?></div>
                </fieldset>
            </div>
            <div id="actions">
                <fieldset>
                    <legend>Actions</legend>
                    <?php if($row['noins_callback']): ?>
                    <div>Callback was set for: <?php echo $row['noins_callback']; ?></div>
                    <?php endif; ?>
                    <div style="display: inline-block">
                        <div><?php echo $insurancebutton1; ?></div>
                        <div><?php echo $insurancebutton2; ?></div>
                        <?php foreach($buttons as $index => $button): ?>
                        <div><?php echo $button; ?></div>
                        <?php endforeach; ?>
                        <div>
                            <input name="callback" type="submit" value="Callback" />
                            in
                            <select name="callback_time">
                                <option>1 week</option>
                                <option>2 weeks</option>
                                <option>1 month</option>
                            </select>
                        </div>
                        <div>
                            <input name="done" type="submit" value="Done">
                            <input name="crid" type="hidden" value="<?php echo $row['crid']; ?>" />
                            <input type="hidden" name="noinsid" value="<?php echo $row['noinsid']; ?>" />
                            <input type="hidden" name="cpid" value="<?php echo $row['cpid']; ?>" />
                        </div>
                    </div>
                    <div style="display: inline-block;vertical-align: top">
                        <textarea name="msg" placeholder="Enter a message." rows="7"></textarea>
                    </div>
                </fieldset>
            </div>
        </div>
        <div id="history" class='containedBox'>
            <fieldset>
                <legend>History</legend>
                <table cellpadding="3" cellspacing="0" border="1">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($historyRow = mysqli_fetch_assoc($historyResult)): ?>
                        <tr>
                            <td><?php echo displayDate($historyRow['cphdate']) . " " . displayTime($historyRow['cphdate']); ?></td>
                            <td><?php echo strtoupper($historyRow['cphhistory']); ?></td>
                            <td><?php echo strtoupper($historyRow['cphuser']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </fieldset>
        </div>
        <div id="collections" class='containedBox'>
            <fieldset>
                <legend>Collections History</legend>
                <table cellpadding="3" cellspacing="0" border="1">
                    <thead>
                        <tr>
                            <th>Date Added</th>
                            <th>Button</th>
                            <th>Notes</th>
                            <th>Added by User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($noteRow = mysqli_fetch_assoc($notesResult)): ?>
                        <tr>
                            <td><?php echo displayDate($noteRow['crtdate']) . " " . displayTime($noteRow['crtdate']); ?></td>
                            <td><?php echo $noteRow['nobutton']; ?></td>
                            <td><?php echo $noteRow['nonote']; ?></td>
                            <td><?php echo $noteRow['crtuser']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </fieldset>
        </div>
    </div>
</form>
<style>
    #information {
        float:left;
    }
    #actions {
        float:left;
    }

    .noINSLabel {
        display: inline-block;
        width: 100px;
        text-align: right;
        font-weight: bold;
    }
    .column {
        display: inline-block;
        vertical-align: top;
    }
</style>
