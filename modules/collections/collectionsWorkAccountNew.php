<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);

$user = getuser();
$group=getUserQueueAssignment($user);
//If Update button pressed
$getfromptos=true;
if($_POST['update']=='Update') {
	if(!empty($_POST['caid'])) {
		$caid=$_POST['caid'];
		require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
		$dbhandle = dbconnect();
		

		$auditfields = getauditfields();
		$upduser = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
		$upddate = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
		$updprog = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";
		if(
			empty($_POST['cainsname1select']) &&
			empty($_POST['caadjuster1']) &&
			empty($_POST['caadjuster1phone']) &&
			empty($_POST['caadjuster1ext']) &&
			empty($_POST['caadjuster1fax']) &&
			empty($_POST['caadjuster1email']) &&
			empty($_POST['caclaimnumber1']) &&
			empty($_POST['cavenue1']) &&
			empty($_POST['cawcab1']) &&
			empty($_POST['cainsname2select']) &&
			empty($_POST['caadjuster2']) &&
			empty($_POST['caadjuster2phone']) &&
			empty($_POST['caadjuster2ext']) &&
			empty($_POST['caadjuster2fax']) &&
			empty($_POST['caadjuster2email']) &&
			empty($_POST['caclaimnumber2']) &&
			empty($_POST['cavenue2']) &&
			empty($_POST['cawcab2']) &&
			empty($_POST['caattorneyname1']) &&
			empty($_POST['caattorneyphone1']) &&
			empty($_POST['caattorneyext1']) &&
			empty($_POST['caattorneyfax1']) &&
			empty($_POST['caattorneyemail1']) &&
			empty($_POST['caattorneyname2']) &&
			empty($_POST['caattorneyphone2']) &&
			empty($_POST['caattorneyext2']) &&
			empty($_POST['caattorneyfax2']) &&
			empty($_POST['caattorneyemail2'])
		) {
			info("000","Insurance and Attorney information is blank in the collections system.<br />Displaying insurance and attorney information from PTOS (as of yesterday).");
			$upddate="NULL";
			unset($_POST['cainsname1']);
		}

                // If changed insurance name *** THIS NEEDS MORE QUALIFICATION ***
		if(!empty($_POST['cainsname1select']))
			$_POST['cainsname1']=$_POST['cainsname1select'];
		else
			unset($_POST['cainsname1']);
		if(!empty($_POST['cainsname2select']))
			$_POST['cainsname2']=$_POST['cainsname2select'];
		else
			unset($_POST['cainsname2']);

                // update screen fields
		$insname1=mysqli_real_escape_string($dbhandle,strtoupper($_POST['cainsname1']));
		$adjuster1=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caadjuster1']));
		$adjuster1phone=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caadjuster1phone']));
		$adjuster1ext=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caadjuster1ext']));
		$adjuster1fax=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caadjuster1fax']));
		$adjuster1email=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caadjuster1email']));
		$claimnumber1=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caclaimnumber1']));
		$venue1=mysqli_real_escape_string($dbhandle,strtoupper($_POST['cavenue1']));
		$wcab1=mysqli_real_escape_string($dbhandle,strtoupper($_POST['cawcab1']));

		$insname2=mysqli_real_escape_string($dbhandle,strtoupper($_POST['cainsname2']));
		$adjuster2=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caadjuster2']));
		$adjuster2phone=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caadjuster2phone']));
		$adjuster2ext=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caadjuster2ext']));
		$adjuster2fax=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caadjuster2fax']));
		$adjuster2email=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caadjuster2email']));
		$claimnumber2=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caclaimnumber2']));
		$venue2=mysqli_real_escape_string($dbhandle,strtoupper($_POST['cavenue2']));
		$wcab2=mysqli_real_escape_string($dbhandle,strtoupper($_POST['cawcab2']));

		$attorneyname1=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caattorneyname1']));
		$attorneyphone1=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caattorneyphone1']));
		$attorneyext1=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caattorneyext1']));
		$attorneyfax1=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caattorneyfax1']));
		$attorneyemail1=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caattorneyemail1']));

		$attorneyname2=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caattorneyname2']));
		$attorneyphone2=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caattorneyphone2']));
		$attorneyext2=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caattorneyext2']));
		$attorneyfax2=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caattorneyfax2']));
		$attorneyemail2=mysqli_real_escape_string($dbhandle,strtoupper($_POST['caattorneyemail2']));

		$updatequery="
			UPDATE collection_accounts set
			cainsname1='$insname1',
			caadjuster1='$adjuster1',
			caadjuster1phone='$adjuster1phone',
			caadjuster1ext='$adjuster1ext',
			caadjuster1fax='$adjuster1fax',
			caadjuster1email='$adjuster1email',
			caclaimnumber1='$claimnumber1',
			cavenue1='$venue1',
			cawcab1='$wcab1',
			cainsname2='$insname2',
			caadjuster2='$adjuster2',
			caadjuster2phone='$adjuster2phone',
			caadjuster2ext='$adjuster2ext',
			caadjuster2fax='$adjuster2fax',
			caadjuster2email='$adjuster2email',
			caclaimnumber2='$claimnumber2',
			cavenue2='$venue2',
			cawcab2='$wcab2',
			caattorneyname1='$attorneyname1',
			caattorneyphone1='$attorneyphone1',
			caattorneyext1='$attorneyext1',
			caattorneyfax1='$attorneyfax1',
			caattorneyemail1='$attorneyemail1',
			caattorneyname2='$attorneyname2',
			caattorneyphone2='$attorneyphone2',
			caattorneyext2='$attorneyext2',
			caattorneyfax2='$attorneyfax2',
			caattorneyemail2='$attorneyemail2',
			upddate=$upddate,
			upduser=$upduser,
			updprog=$updprog
			where caid='$caid'
		";
        $updateresult = mysqli_query($dbhandle,$updatequery);


        //Convert Dates
        $_POST['cicdate'] = convertDate($_POST['cicdate']);
        $_POST['surgerydate1'] = convertDate($_POST['surgerydate1']);

        $_POST['casedenialdate'] = convertDate($_POST['casedenialdate']);
        $_POST['accepteddate'] = convertDate($_POST['accepteddate']);
        $_POST['pqmedate'] = convertDate($_POST['pqmedate']);
        $_POST['doi2'] = convertDate($_POST['doi2']);

        $_POST['lienstatusdate'] = convertDate($_POST['lienstatusdate']);
        $_POST['dorstatusdate'] = convertDate($_POST['dorstatusdate']);

        $_POST['casewatch'] = convertDate($_POST['casewatch']);

        if ($_POST['psid']) {
            //dump('update', 'update');
            $sql = "UPDATE patients_status
                    SET
                        cicstatus = '".mysql_escape_string($dbhandle,$_POST['cicstatus'])."',
                        cicdate = ".$_POST['cicdate'].",
                        surgeon = '".mysql_escape_string($dbhandle,$_POST['surgeon'])."',
                        surgerydate1 = ".$_POST['surgerydate1'].",
                        surgerybodypart = '".mysql_escape_string($dbhandle,$_POST['surgerybodypart'])."',
                        refdoctor1 = '".mysql_escape_string($dbhandle,$_POST['refdoctor1'])."',
                        refdoctor2 = '".mysql_escape_string($dbhandle,$_POST['refdoctor2'])."',
                        refdoctor3 = '".mysql_escape_string($dbhandle,$_POST['refdoctor3'])."',
                        casedenialdate = ".$_POST['casedenialdate'].",
                        reasonfordenial = '".mysql_escape_string($dbhandle,$_POST['reasonfordenial'])."',
                        acceptedbodypart = '".mysql_escape_string($dbhandle,$_POST['acceptedbodypart'])."',
                        accepteddate = ".$_POST['accepteddate'].",
                        pqmedate = ".$_POST['pqmedate'].",
                        cabto = '".mysql_escape_string($dbhandle,$_POST['cabto'])."',
                        wcabhearing = '".mysql_escape_string($dbhandle,$_POST['wcabhearing'])."',
                        medlegaldoctor = '".mysql_escape_string($dbhandle,$_POST['medlegaldoctor'])."',
                        overallstatus = '".mysql_escape_string($dbhandle,$_POST['overallstatus'])."',
                        doi2 = ".$_POST['doi2'].",
                        dorstatusdate = ".$_POST['dorstatusdate'].",
                        lienstatusdate = ".$_POST['lienstatusdate'].",
                        ct = '".mysql_escape_string($dbhandle,$_POST['ct'])."',
                        chartnotes = '".mysql_escape_string($dbhandle,$_POST['chartnotes'])."',
                        candr = '".mysql_escape_string($dbhandle,$_POST['candr'])."',
                        casewatch = ".$_POST['casewatch']."
                    WHERE psid = ".$_POST['psid'];
            mysqli_query($dbhandle,$sql);
            if (mysqli_errno($dbhandle)) {
                echo mysqli_error($dbhandle);exit;
            }
        } else {
            //dump('insert', 'insert');
            $sql = "INSERT INTO patients_status
                    (
                        bnum,
                        pnum,
                        cicstatus,
                        cicdate,
                        surgeon,
                        surgerydate1,
                        surgerybodypart,
                        refdoctor1,
                        refdoctor2,
                        refdoctor3,
                        casedenialdate,
                        reasonfordenial,
                        acceptedbodypart,
                        accepteddate,
                        pqmedate,
                        cabto,
                        wcabhearing,
                        medlegaldoctor,
                        overallstatus,
                        doi2,
                        lienstatusdate,
                        dorstatusdate,
                        ct,
                        chartnotes,
                        candr,
                        casewatch
                    )
                    VALUES (
                        '".$_POST['bnum']."',
                        '".$_POST['pnum']."',
                        '".mysql_escape_string($dbhandle,$_POST['cicstatus'])."',
                        ".$_POST['cicdate'].",
                        '".mysql_escape_string($dbhandle,$_POST['surgeon'])."',
                        ".$_POST['surgerydate1'].",
                        '".mysql_escape_string($dbhandle,$_POST['surgerybodypart'])."',
                        '".mysql_escape_string($dbhandle,$_POST['refdoctor1'])."',
                        '".mysql_escape_string($dbhandle,$_POST['refdoctor2'])."',
                        '".mysql_escape_string($dbhandle,$_POST['refdoctor3'])."',
                        ".$_POST['casedenialdate'].",
                        '".mysql_escape_string($dbhandle,$_POST['reasonfordenial'])."',
                        '".mysql_escape_string($dbhandle,$_POST['acceptedbodypart'])."',
                        ".$_POST['accepteddate'].",
                        ".$_POST['pqmedate'].",
                        '".mysql_escape_string($dbhandle,$_POST['cabto'])."',
                        '".mysql_escape_string($dbhandle,$_POST['wcabhearing'])."',
                        '".mysql_escape_string($dbhandle,$_POST['medlegaldoctor'])."',
                        '".mysql_escape_string($dbhandle,$_POST['overallstatus'])."',
                        ".$_POST['doi2'].",
                        ".$_POST['lienstatusdate'].",
                        ".$_POST['dorstatusdate'].",
                        '".mysql_escape_string($dbhandle,$_POST['ct'])."',
                        '".mysql_escape_string($dbhandle,$_POST['chartnotes'])."',
                        '".mysql_escape_string($dbhandle,$_POST['candr'])."',
                        ".$_POST['casewatch']."
                    )";
            //dump('sql', $sql);
            mysqli_query($dbhandle,$sql);
            if (mysqli_errno($dbhandle)) {
                echo mysqli_error($dbhandle);exit;
            }
        }

		if($updateresult)
			notify("000","collection_account $caid updated.");
		else
			error("987","Error updating collection_account record $caid<br>$updatequery<br>".mysqli_error($dbhandle));


	}
	else {
		dumppost();
    }
}

// If Navigation Information Passed In via SESSION
if(!empty($_SESSION['navigationid']) && !empty($_SESSION['button'])) {
	if(empty($_POST['button']))
		$_POST['button']=$_SESSION['button'];

	$n=urldecode($_SESSION['navigationid']);
	$argv=split("&", $n);
	foreach($argv as $index=>$expression) {
		list($field,$value)=split("=", $expression);
		if(empty($_POST[$field]))
			$_POST[$field]=$value;
	}
}

// If Navigation Information Passed In via REQUEST
if(!empty($_REQUEST['navigation'])) {
	foreach($_REQUEST['navigation'] as $varstr=>$button) {
		$n=urldecode($varstring);
		$argv=split("&", $n);
		foreach($argv as $index=>$expression) {
			list($field,$value)=split("=", $expression);
			if(empty($_POST[$field]))
				$_POST[$field]=$value;
		}
	}
}

// Move Request fields to POST fields
foreach($_REQUEST as $field=>$value) {
	if(empty($_POST[$field])) {
		$_POST[$field]=$value;
	}
}

// If in queue mode, Display Next button otherwise display Exit button
if(strstr($_SESSION['navigationid'], 'Collections+Queue')) {
	$exitvalue="Next";
}
else
	$exitvalue="Exit";
$app=$_POST['app'];
$appid=$_POST['appid'];
$bnum=$_POST['bnum'];
$pnum=$_POST['pnum'];
$button=$_POST['button'];
$exitdisabled='disabled="disabled"';

if(!empty($caid) && empty($appid)) {
	$app=$_POST['application'];
	$appid=$caid;
}

// Check required fields
if(empty($app) || empty($appid)) {
	if(empty($bnum) || empty($pnum)) {
		error('999', "WorkAccount:Required parameter missing. (app:$app && appid:$appid) (bnum:$bnum && pnum:$pnum)");
		displaysitemessages();
		dump("REQUEST",$_REQUEST);
		dumppost();
		dump("SESSION-navigation",$_SESSION['navigation']);
		dump("SESSION-navigationid",$_SESSION['navigationid']);
		dump("SESSION-button",$_SESSION['button']);
		dump("SESSION-id",$_SESSION['id']);
		exit();
	}
	else
		$where = "bnum='$bnum' and pnum='$pnum'";
}
else
	$where = "caid='$appid'";

// Get information from database using either BNUM and PNUM or CAID
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$query = "
SELECT *
FROM PTOS_Patients p
LEFT JOIN collection_accounts ca ON p.bnum=cabnum and p.pnum=capnum
LEFT JOIN master_clinics ON cnum=cmcnum
LEFT JOIN therapists ON therap=ttherap
LEFT JOIN patients_status ps ON p.bnum = ps.bnum AND p.pnum = ps.pnum
WHERE $where
";

//dump('query', $query);

// NOTE if Next button pressed and not updated then there are very few ca fields populated.
// Initialize values that are possibly not re-populated.

$result = mysqli_query($dbhandle,$query);

// Notify Multiple Records - Should only get one
if($result) {
	$numrows=mysqli_num_rows($result);
	if($numrows==0) {
		notify("000","No Patients or Collections Accounts Found for $bnum $pnum. No Row Fetched.");
		displaysitemessages();
		exit();
	}
	if($numrows > 1)
		notify("000","Multiple Records Found for $bnum $pnum. First Row Fetched.");
	$row = mysqli_fetch_assoc($result);
	if($row) {
		$accttype='??';
		$acctsubtype='??';
		$lienstatus='??';
		$acctinfoarray = collectionsAccountTypeXref( $row['acctype'] );
			$accttype=$acctinfoarray[0]['accttype'];
			$acctsubtype=$acctinfoarray[0]['acctsubtype'];
			$acctgroup=$acctinfoarray[0]['acctgroup'];
			$acctstatus=$acctinfoarray[0]['acctstatus'];
			$lienstatus=$acctinfoarray[0]['lienstatus'];
			$dorstatus=$acctinfoarray[0]['dorstatus'];
			$settlestatus=$acctinfoarray[0]['settlestatus'];
		$acctstatus='NEW';
// Create collection record if one doesn't already exist
		if(empty($row['caid'])) {
			$auditfields = getauditfields();
			$crtuser = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
			$crtdate = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
			$crtprog = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";

			$caquery = "
				INSERT INTO collection_accounts (cabnum, cacnum, capnum, caaccttype, caacctsubtype, caacctgroup, caacctstatus, calienstatus, cadorstatus, casettlestatus, crtdate, crtuser, crtprog) VALUES('$bnum', '$cnum', '$pnum', '$accttype', '$acctsubtype', '$acctgroup', '$acctstatus', '$lienstatus', '$dorstatus', '$settlestatus', $crtdate, $crtuser, $crtprog);
				";
			$caresult = mysqli_query($dbhandle,$caquery);
			if($caresult) {
				notify('000','Inserted NEW collection_account');
				$result = mysqli_query($dbhandle,$query);
				if($result) {
					$numrows=mysqli_num_rows($result);
					if($numrows==0) {
						echo("No Patients or Collections Accounts Found for $bnum $pnum. No Row Fetched.");
						exit();
					}
//					if($numrows == 1)
//						echo("One Record Found for $bnum $pnum. Row Fetched.");
					if($numrows > 1)
						echo("Multiple Records Found for $bnum $pnum. First Row Fetched.");
					$row = mysqli_fetch_assoc($result);
				}
				else
					error('111', "SELECT error.$query<br>".mysqli_error($dbhandle));
			}
			else
				error('011', "INSERT error.$caquery<br>".mysqli_error($dbhandle));
		}
// Populate $_POST variables with table row/field values
		if($row) {
            //dump('row', $row);
			foreach($row as $fieldname=>$fieldvalue) {
				if(!empty($fieldvalue)) {
					$_POST[$fieldname] = $fieldvalue;
				}
			}
		}
		else
			error('111', "Row error.$query<br>".mysqli_error($dbhandle));
	}
	else
		error('011', "Row error.$query<br>".mysqli_error($dbhandle));
}
else
	error('011', "SELECT error.$query<br>".mysqli_error($dbhandle));


if(errorcount()>0) {
	displaysitemessages();
	exit();
}
else {
	if(notifycount()>0)
		displaysitemessages();

// Display Output
	$name = $_POST['pnum'].'-'.$_POST['lname'] . ", " . $_POST['fname'].' ('.$_POST['acctype'].')';
	$dob = displayDate($_POST['birth']);
	$_POST['ssn']=dbSsn($_POST['ssn']);
	if(!empty($_POST['ssn']))
		$ssn = displaySsnAll($_POST['ssn']);
	else {
		if(!empty($_POST['pid']))
			$ssn = displaySsnAll($_POST['pid']);
		else
			$ssn="000-00-0000";
	}

    //dumppost();

    $psid = (isset($_POST['psid'])) ? $_POST['psid'] : null;

	$doi = displayDate($_POST['injury']);
	//$office = $_POST['cnum'];
    $office = $_POST['cmname'];
	$therapist = $_POST['tname'];
	$emp = $_POST['emp'];
	$refdr=$_POST['doc'];
	$sxdate=$_POST['retdr'];
	$accttype = $_POST['caaccttype'];
	$acctsubtype = $_POST['caacctsubtype'];
	if(empty($acctsubtype))
		$accttypesubtype = $accttype;
	else
		$accttypesubtype = $accttype."-".$acctsubtype;
	$acctstatus = $_POST['caacctstatus'];
	$lienstatus = collectionsLienStatusDescription($accttype, $_POST['calienstatus']);
	if(is_array($lienstatus))
		$lienstatus=$_POST['calienstatus'];
	if($lienstatus=='NON')
		$lienstatus="";
	$dorstatus = collectionsDORStatusDescription($accttype, $_POST['cadorstatus']);
	if(is_array($dorstatus))
		$dorstatus=$_POST['cadorstatus'];
	if($dorstatus=='NON')
		$dorstatus="";
	$settlestatus = collectionsSettleStatusDescription($accttype, $_POST['casettlestatus']);
	if(is_array($settlestatus))
		$settlestatus=$_POST['casettlestatus'];
	if($settlestatus=='NON')
		$settlestatus="";
	$charges= number_format($_POST['charges'],2,".","");
	$adjust = number_format($_POST['adjust'],2,".","");
	$payments = number_format($_POST['payments'],2,".","");
	$balance = $charges-$payments;
	$balanceadjust = $charges-$payments+$adjust;

	$charges = displayCurrency($charges);
	$adjust = displayCurrency($adjust);
	$payments = displayCurrency($payments);
	$balance = displayCurrency($balance);
	$balanceadjust = displayCurrency($balanceadjust);

	$fvisit = displayDate($_POST['fvisit']);
	$lvisit = displayDate($_POST['lvisit']);
	$visits = $_POST['visits'];

	// Need to calculate from transv
//	$paidvis = $_POST['paidvis'];

// Get Authorization Information from case_prescriptions
	$authpnum=$_POST['pnum'];
	$authselect="SELECT cpcrid, max(cpauthdate) authdate, sum(cpauthtotalvisits) authvisits
                FROM cases
                LEFT JOIN case_prescriptions ON crid=cpcrid
                WHERE crpnum='$authpnum' and cpauthstatuscode='AUT'
                GROUP BY cpcrid";

	if($authresult=mysqli_query($dbhandle,$authselect)) {
		if($authrow=mysqli_fetch_assoc($authresult)) {
			$authvis = $authrow['authvisits'];
			if($authrow['authdate']=='0000-00-00 00:00:00')
				$authdate = 'See AuthDtl Notes';
			else
				$authdate = displayDate($authrow['authdate']);
		}
		else
			$authvis="$authcrid";
	}
	else
		$authvis="$authcrid";
	$payor = $_POST['payor'];



// If the collection account has not been updated then default values to PTOS values
	if(empty($_POST['upddate'])) {
//		if(empty($_POST['cainsname1']))
			$_POST['cainsname1']=$_POST['pinsurance'];
		//	if(empty($_POST['cainsname1description']))
		//		$_POST['cainsname1description']=//lookup insurance company

//		if(empty($_POST['caadjuster1']))
			$_POST['caadjuster1']=$_POST['padjust'];

//		if(empty($_POST['caadjuster1phone'])) {
			$phone = preg_replace("/[^0-9]/", "", $_POST['sgroup']);
			$phonelen=strlen($phone);
			if($phonelen > 10) {
				$_POST['caadjuster1ext']=substr($phone, 10, $phonelen-10);
				$_POST['caadjuster1phone']=displayPhone(substr($phone, 0, 10));
			}
			else {
				$_POST['caadjuster1ext']="";
				$_POST['caadjuster1phone']=displayPhone($phone);
			}
//		}
		//	if(empty($_POST['caadjuster1fax']))
		//		$_POST['caadjuster1fax']=$_POST['padjust'];
		//	if(empty($_POST['caadjuster1email']))
		//		$_POST['caadjuster1email']=$_POST['padjust'];
//		if(empty($_POST['caclaimnumber1']))
			$_POST['caclaimnumber1']=$_POST['pgroup'];
		if(empty($_POST['caclaimnumber1']))
			$_POST['caclaimnumber1']=$_POST['dx4'];
		//	if(empty($_POST['cavenue1']))
		//		$_POST['cavenue1']=$_POST['dx4'];
//		if(empty($_POST['cawcab1']))
			$_POST['cawcab1']=$_POST['email'];

//		if(empty($_POST['cainsname2']))
			$_POST['cainsname2']=$_POST['sinsurance'];

		//	if(empty($_POST['cainsname2description']))
		//		$_POST['2cainsname1description']=//lookup insurance company

//		if(empty($_POST['caadjuster2']))
			$_POST['caadjuster2']=$_POST['sadjust'];
//		if(empty($_POST['caadjuster2phone'])) {
			$phone = preg_replace("/[^0-9]/", "", $_POST['sgroup']);
			$phonelen=strlen($phone);
			if($phonelen > 10) {
				$_POST['caadjuster2ext']=substr($phone, 10, $phonelen-10);
				$_POST['caadjuster2phone']=displayPhone(substr($phone, 0, 10));
			}
			else {
				$_POST['caadjuster2ext']="";
				$_POST['caadjuster2phone']=displayPhone($phone);
			}
//		}
		//	if(empty($_POST['caadjuster2fax']))
		//		$_POST['caadjuster2fax']=$_POST['padjust'];
		//	if(empty($_POST['caadjuster2email']))
		//		$_POST['caadjuster2email']=$_POST['padjust'];
//		if(empty($_POST['caclaimnumber2']))
			$_POST['caclaimnumber2']=$_POST['sgroup'];
		//	if(empty($_POST['caclaim2']))
		//		$_POST['caclaim2']=$_POST['dx4'];
		//	if(empty($_POST['cavenue2'])) {
		//		$_POST['cavenue2']=$_POST['dx4'];
		//	if(empty($_POST['cawcab2'])) {
		//		$_POST['cawcab2']=$_POST['dx4'];

		//if(empty($_POST['caattorneyname1']))
		//	$_POST['caattorneyname1']=$_POST['attorney'];

		//if(empty($_POST['caattorneyphone1']))
		//	$_POST['caattorneyphone1']=$_POST[''];

		//if(empty($_POST['caattorneyext1']))
		//	$_POST['caattorneyext1']=$_POST[''];

		//if(empty($_POST['caattorneyfax1']))
		//	$_POST['caattorneyfax1']=$_POST[''];

		//if(empty($_POST['caattorneyemail1']))
		//	$_POST['caattorneyemail1']=$_POST[''];

//		if(empty($_POST['caattorneyname2']))
		$_POST['caattorneyname2']=$_POST['attorney'];

		//if(empty($_POST['caattorneyphone2']))
		//	$_POST['caattorneyphone2']=$_POST[''];

		//if(empty($_POST['caattorneyext2']))
		//	$_POST['caattorneyext2']=$_POST[''];

		//if(empty($_POST['caattorneyfax2']))
		//	$_POST['caattorneyfax2']=$_POST[''];

		//if(empty($_POST['caattorneyemail2']))
		//	$_POST['caattorneyemail2']=$_POST[''];
	}
	else
		notify("000","Account was last updated by ".$_POST['upduser']." on ".$_POST['upddate'].".");
	require_once($_SERVER['DOCUMENT_ROOT'] . '/common/insurance.options.php');
	$code=$_POST['cainsname1'];
	unset($description);
	if(!empty($bnum) && !empty($code)) {
		if($insuranceCompanyInformation1=getPTOSInsuranceCompanyInformation($bnum, $code)) {
			$description=$insuranceCompanyInformation1['ipname'] . ' ' . $insuranceCompanyInformation1['iphone'];
		}
		else
			$description='Not on PTOS file';
	}
	else {
		if($_POST['t30']==0 && $_POST['t60']==0 && $_POST['t90']==0 && $_POST['t120']==0) {
			notify("000","Account has no past due balances yet. Account is not in collections.");
		}
		else {
			notify("000","Unknown error, bnum:$bnum code:$code");
		}
	}
	$_POST['insurancecompany1']=$description;

	$insurancelistoptions1 = getSelectOptions(
		$arrayofarrayitems=getPTOSInsuranceCompaniesOptions($bnum),
		$optionvaluefield='value',
		$arrayofoptionfields=array(
			'title'=>''
			),
		$defaultoption=$_POST['cainsname1'],
		$addblankoption=TRUE,
		$arraykey='',
		$arrayofmatchvalues=array());

	$code=$_POST['cainsname2'];
	unset($description);
	if(!empty($bnum) && !empty($code)) {
		if($insuranceCompanyInformation1=getPTOSInsuranceCompanyInformation($bnum, $code))
			$description=$insuranceCompanyInformation1['ipname'] . ' ' . $insuranceCompanyInformation1['iphone'];
		else
			$description='Not on file';
	}
	$_POST['insurancecompany2']=$description;

	$insurancelistoptions2 =  getSelectOptions(
		$arrayofarrayitems=getPTOSInsuranceCompaniesOptions($bnum),
		$optionvaluefield='value',
		$arrayofoptionfields=array(
			'title'=>''
			),
		$defaultoption=$_POST['cainsname2'],
		$addblankoption=TRUE,
		$arraykey='',
		$arrayofmatchvalues=array());

	$notehtmlrows=array();
	$notenumrows=0;
	unset($notequery);
	if(!empty($app) && !empty($appid)) {
		$notequery = "
			SELECT *
			FROM notes
			WHERE noapp='$app' and noappid='$appid'
			ORDER BY crtdate desc
			LIMIT 5
		";
	}
	else {
		if(!empty($bnum) && !empty($pnum)) {
			$notequery = "
				SELECT *
				FROM notes
				WHERE nobnum='$bnum' and nopnum='$pnum'
				ORDER BY crtdate desc
				LIMIT 5
			";
		}
	}
	if(!empty($notequery)) {
		if($noteresult = mysqli_query($dbhandle,$notequery)) {
			$notenumrows=mysqli_num_rows($noteresult);
			if($notenumrows > 0) {
				$notehtmlrows[]="<tr><th>Date Added</th><th>Button</th><th>Notes</th><th>Added by User</th></tr>";
				while($noterow=mysqli_fetch_assoc($noteresult)) {
					if(displayDate($noterow['crtdate']) == today() && $noterow['crtuser']==$user && $noterow['notype'] !='USR')
						unset($exitdisabled);
					$notedate=displayDate($noterow['crtdate']) . " " . displayTime($noterow['crtdate']);
					$notebutton=$noterow['nobutton'];
					$notedescription=strtoupper($noterow['nonote']);
					$noteuser=strtoupper($noterow['crtuser']);
					$notehtmlrows[]="<tr><td>$notedate</td><td>$notebutton</td><td>$notedescription</td><td>$noteuser</td></tr>";
				}
			}
		}
		else
			error("001","collectionsNotes: SELECT Error. $notequery<br>".mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("002","Missing required identifier. (noid:$noid) (app:$app & appid:$appid) (bnum:$bnum & pnum:$pnum) button:$button notequery:$notequery");

	if(count($notehtmlrows) > 0)
		$notehtml=implode("", $notehtmlrows);
}
// Query database for simplar account
unset($multiaccountmessage);
$thisfname = substr($_POST['fname'], 0, 3);
$thislname = substr($_POST['lname'], 0, 3);
$thisbirth = $_POST['birth'];
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

$multiquery = "
	SELECT count(*) as multicount
	FROM PTOS_Patients
	WHERE lname like '$thislname%' and fname like '$thisfname%' and birth='$thisbirth'
";
if($multiresult = mysqli_query($dbhandle,$multiquery)) {
	if($multirow=mysqli_fetch_assoc($multiresult)) {
		if($multirow['multicount'] > 1) {
			$multiaccountmessage = '<tr>
<td>
	<table width="100%">
		<tr>
			<td>
				<div style="background-color:#F00; color:#FFF; text-align:center">'.$multirow['multicount'].' Accounts May Exist</div>
			</td>
		</tr>
	</table>
</td>
</tr>
';
		}
	}
}

$bodypart=$_POST['bodypart'];

if(empty($_POST['caadjuster1'])) $adjuster1disabled='disabled="disabled"';
if(empty($_POST['caadjuster2'])) $adjuster2disabled='disabled="disabled"';
if(empty($_POST['cainsname1'])) $insurance1disabled='disabled="disabled"';
if(empty($_POST['cainsname2'])) $insurance2disabled='disabled="disabled"';

if(empty($_POST['caattorneyname1'])) $attorney1disabled='disabled="disabled"';
if(empty($_POST['caattorneyname2'])) $attorney2disabled='disabled="disabled"';

$queuerowarray=array();
$queueselect="
SELECT cqcaid, cqgroup, cqpriority, cqrtbal, cqschcalldate, cqstrcalldate, cqendcalldate, cqlastcalldate, cqlastbutton, cqphone, cqresult, lockuser, lockdate
FROM collection_queue
WHERE cqcaid='".$appid."'
";
if($queueresult=mysqli_query($dbhandle,$queueselect)) {
	if($queuerow=mysqli_fetch_assoc($queueresult)) {
		foreach($queuerow as $field=>$value) {
			$_POST["$field"]=stripslashes($value);
			$queuerowarray[]="$field='$value'";
		}
	}
}
//else
//	echo("$queueselect");

// Retrieve Post Surgical Date
unset($crpostsurgical);
unset($crsurgerydate);
$caseselect="SELECT crpostsurgical, crsurgerydate FROM cases WHERE crpnum='$pnum'";
if($caseresult=mysqli_query($dbhandle,$caseselect)) {
	if($caserow=mysqli_fetch_assoc($caseresult)) {
		if(!empty($caserow['crpostsurgical'])) {
			$crpostsurgical='Yes';
			if(!empty($caserow['crsurgerydate']))
				$crsurgerydate=displayDate($caserow['crsurgerydate']);
			else
				$crsurgerydate='NOT SPECIFIED';
		}
	}
}
/*
$selectrx = "
SELECT cphdate, cphhistory
FROM cases
  JOIN case_prescriptions
  ON crid=cpcrid
  JOIN case_prescriptions_history
  ON cpid=cphcpid
WHERE
  crpnum='".$_POST['pnum']."' and (
    cphhistory like 'Prescription authorized%' or
    cphhistory like 'Prescription assumed authorized%' or
    cphhistory like 'Prescription denied%'
  )
  ";
$authhtmlarray=array();
$assumed="";
$denied=0;
if($resultrx=mysqli_query($dbhandle,$selectrx)) {
	$authvis=0;
	while($rowrx=mysqli_fetch_assoc($resultrx)) {
		$auth=array();
		if(substr($rowrx['cphhistory'],0,23)=='Prescription authorized') {
			$authdata1=strpos($rowrx['cphhistory'],'(');
			$authdata2=strpos($rowrx['cphhistory'],')');
			$authdatalength=$authdata2-$authdata1;
			list($auth['date'],$auth['frequency'],$auth['duration'],$auth['visits'],$auth['note'])=explode(":",substr($rowrx['cphhistory'],$authdata1+1,$authdatalength-1));
			$authvis=$authvis+$auth['visits'];
			$thisauth=$auth['visits'];
		}
		else {
			if(substr($rowrx['cphhistory'],0,31)=='Prescription assumed authorized') {
				$assumed="+";
				$thisauth="ASSUMED";
			}
			else {
				if(substr($rowrx['cphhistory'],0,19)=='Prescription denied') {
					$denied++;
					$thisauth="DENIED";
				}
			}
		}
		$authhtmlarray[]='<tr><td>'.$rowrx['cphdate'].'</td><td>'.$rowrx['cphhistory'].'</td><td align="right">'.$thisauth.'</td></tr>';
	}
}
if(count($authhtmlarray) > 0) {
	$authhtml='<tr><td colspan="10"><table width="100%"><tr><th>Auth Date</th><th>Auth Note</th><th>Auth Visits</th></tr><tr>'.implode("",$authhtmlarray).'</table></td></tr>';
}
else {
	$authhtml="";
}
$authvis="$authvis $assumed";

*/?>
<style type="text/css">
.subtablesection {
	background-color:#DDD;
}
.readonlytext {
	background-color: #DDD;
}

#left-column, #right-column {
    width: 47%;
    display: inline-block;
    vertical-align:top;
}
.status-block {
    border: 1px solid black;
    margin: 5px;
    padding: 5px;
}

.status-block label, .status-block input {
    vertical-align: top;
}
</style>
<script type="text/javascript" src="/javascript/calendarpopup/combinedcompact/CalendarPopup.js"></script>
<script>
function validateDate(x) {
	var strDate=document.getElementById(x).value;
	var parsedDate = new Array();

	highlightelementclear(x);
	if (strDate.search("/") > 0) {
		parsedDate = strDate.split("/");
	}
	else {
		if (strDate.search("-") > 0) {
			parsedDate = strDate.split("-");
		}
		else {
			if (strDate.search(".") > 0) {
			parsedDate = strDate.split(".");
			}
			else { // No Separators
				   // could be
				   // 		length 4 : mdyy
				   // 		length 5 : mmdyy, mddyy
				   // 		length 6 : mmddyy,  mdyyyy
				   // 		length 7 : mmdyyyy, mddyyyy
				   //		length 8 : mmddyyyy
				   //
				   //  Assume 4 is mdyy, 5 is invalid, 6 is mmddyy, 7 is invalid, 8 is mmddyyyy
				strDate = stripChars(strDate);
				if(strDate.length == 4) {
					parsedDate = Array(strDate.substr(0,1), strDate.substr(1,1), '20'+strDate.substr(2,2));
				}
				else {
					if(strDate.length == 6) { // mmddyy
						parsedDate = Array(strDate.substr(0,2), strDate.substr(2,2), '20'+strDate.substr(4,2));
					}
					else {
						if(strDate.length == 8) { // mmddyyyy
							parsedDate = Array(strDate.substr(0,2), strDate.substr(2,2), strDate.substr(4,4));
						}
					}
				}
			}
		}
	}
	if (parsedDate.length == 3) {
		var day, month, year;
		var l0, l1, l2;
		l0 = stripChars(parsedDate[0]);
		l1 = stripChars(parsedDate[1]);
		l2 = stripChars(parsedDate[2]);
		if(l0.length == 4) {
			year = +parsedDate[0];
			month = +parsedDate[1];
			day = +parsedDate[2];
		}
		else {
			month = +parsedDate[0];
			day = +parsedDate[1];
			year = +parsedDate[2];
		}
		newDate = month + "/" + day + "/" + year;
		var objDate = new Date (newDate);
		var m = objDate.getMonth()+1;
		var d = objDate.getDate();
		var y = objDate.getFullYear();
		if (month==m && day == d) {
			highlightelementclear(x);
			document.getElementById(x).value = objDate.getMonth()+1 + "/" + objDate.getDate() + "/" + objDate.getFullYear();
			return(true);
		}
	}
	else {
		if(parsedDate.length == 0) {
			if(document.getElementById(x).value == "") {
				highlightelementclear(x);
				return(true);
			}
		}
	}
	highlightelement(x);
	return false;
}
</script>
<div class="centerFieldset" style="margin-top:50px;">
	<form action="" method="post" name="editForm">
		<input type="hidden" name="bnum" value="<?php echo $_POST['bnum']; ?>">
		<input type="hidden" name="pnum" value="<?php echo $_POST['pnum']; ?>">
		<input type="hidden" name="caid" value="<?php echo $_POST['caid']; ?>">
		<fieldset style="text-align:center;">
			<?php
//				notify("000", implode(", ", $queuerowarray));
			if($group == $_POST['cqgroup']) {
				$queuetitle='<span style="background-color:green; color:white;">'.$_POST['cqgroup'].'</span>';
				$notassignedtoyoumessage="";
			}
			else {
				$queuetitle='<span style="background-color:red; color:white;">'.$_POST['cqgroup'].'</span>';
				$notassignedtoyoumessage = '<tr>
<td>
	<table width="100%">
		<tr>
			<td>
				<div style="background-color:#F00; color:#FFF; text-align:center">This account is not assigned to '.$group.' it is assigned to '.$_POST['cqgroup'].'</div>
			</td>
		</tr>
	</table>
</td>
</tr>
';

			}
			 ?>
			<legend>Collections <?php echo $queuetitle; ?> - New Account Detail Record #<?php echo($_POST['caid']." Priority(".$_POST['cqpriority']."). Call Scheduled for ".$_POST['cqschcalldate']." or sooner."); ?></legend>
			<table width="760px" style="text-align:left;">
				<?php echo $notassignedtoyoumessage; ?>
				<?php echo $multiaccountmessage; ?>
				<tr>
					<td colspan="4" align="center"><input type="submit" name="exit" id="exit" value="<?php echo $exitvalue; ?>" <?php echo $exitdisabled; ?> /></td>
				</tr>
				<tr>
					<td><table width="100%">
							<tr>
								<td colspan="4" align="center"><table width="760px" style="text-align:left;">
										<tr>
											<td><table class="" width="100%">
                                                <tr>
                                                    <th colspan="10">Patient Information: <?php echo $pnum; ?></th>
                                                </tr>
                                                <tr>
                                                    <td nowrap="nowrap" align="right">Name</td>
                                                    <td colspan="4"><input name="name" type="text" class="readonlytext" id="name" value="<?php echo $name; ?>" size="32" maxlength="64" readonly="readonly" /></td>
                                                    <td nowrap="nowrap" align="right">DOB</td>
                                                    <td><input name="dob" type="text" class="readonlytext" id="dob" value="<?php echo $dob; ?>" size="15" maxlength="20" readonly="readonly" /></td>
                                                    <td nowrap="nowrap" align="right">SSN</td>
                                                    <td><input name="ssn" type="text" class="readonlytext" id="ssn" value="<?php echo $ssn; ?>" size="15" maxlength="11" readonly="readonly" /></td>
                                                </tr>
                                                <tr>
                                                    <td nowrap="nowrap" align="right">DOI </td>
                                                    <td colspan="3" nowrap="nowrap">
                                                        <input name="doi" type="text" class="readonlytext" id="doi" value="<?php echo $doi; ?>" size="15" maxlength="10" readonly="readonly" />
                                                        <input name="doi2" type="text" id="doi2" value="<?php echo ($_POST['doi2']) ? date('m/d/Y', strtotime($_POST['doi2'])) : ''; ?>" size="15" maxlength="10" />
                                                        <img id="doi2imgdate" align="absmiddle" onclick="cal.select(document.editForm.doi2,'doi2imgdate','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="doi2imgdate">
                                                    </td>
                                                    <td nowrap="nowrap" align="right">CT
                                                        <input type="checkbox" id="ct" name="ct" value="1" <?php echo ($_POST['ct']) ? 'checked=checked' : null ; ?> />
                                                    </td>

                                                    <td nowrap="nowrap" align="right">Office</td>
                                                    <td><input name="office" type="text" class="readonlytext" id="office" value="<?php echo $office; ?>" size="32" maxlength="2" readonly="readonly" /></td>
                                                    <td nowrap="nowrap" align="right">Therapist</td>
                                                    <td><input name="therapist" type="text" class="readonlytext" id="therapist" value="<?php echo $therapist; ?>" size="32" maxlength="2" readonly="readonly" /></td>
                                                </tr>
                                                <tr>
                                                    <td nowrap="nowrap" align="right">Employer </td>
                                                    <td colspan="2"><input name="emp" type="text" class="readonlytext" id="emp" value="<?php echo $emp; ?>" size="32" maxlength="64" readonly="readonly" /></td>
                                                    <td colspan="2" nowrap="nowrap" align="right">
                                                        Chart Notes
                                                        <input type="checkbox" id="chartnotes" name="chartnotes" value="1" <?php echo ($_POST['chartnotes']) ? 'checked=checked' : null ; ?> />
                                                    </td>
                                                    <td nowrap="nowrap" align="right">Ref. Dr. </td>
                                                    <td><input name="refdr" type="text" class="readonlytext" id="refdr" value="<?php echo $refdr; ?>" size="15" maxlength="20" readonly="readonly" /></td>
                                                    <td nowrap="nowrap" align="right">Ret. Dr.</td>
                                                    <td><input name="sxdate" type="text" class="readonlytext" id="sxdate" value="<?php echo displayDate($sxdate); ?>" size="15" maxlength="20" readonly="readonly" /></td>
                                                </tr>
                                            </table></td>
											<td valign="top">
                                                <input style="height:20px;" id="PatDtl" name="PatDtl" type="button" value="PatDtl" onclick="window.open('/modules/collections/collectionsPatDtl.php?bnum=<?php echo $bnum; ?>&pnum=<?php echo $pnum; ?>','PatientDetail','scrollbars=yes');" />
                                                <br />
                                                <input style="height:20px;" id="PatStatus" name="PatStatus" type="button" value="PatStatus" onclick="window.open('/modules/collections/collectionsPatStatus.php?bnum=<?php echo $bnum; ?>&pnum=<?php echo $pnum; ?>','PatientStatus','scrollbars=yes');" />
                                                <br />
                                                <?php if(!empty($crpostsurgical)) echo "<div nowrap='nowrap' style='width=100px; background-color:Red; color:white;'>*****************<br />&nbsp;PostSurgical&nbsp;<br />&nbsp;&nbsp;".$crsurgerydate."&nbsp;&nbsp;<br />*****************</div>"; ?>
                                            </td>
										</tr>
										<tr>
											<td>
                                                <table width="100%">
													<tr>
														<th colspan="10">Account Information: </th>
													</tr>
													<tr>
														<td nowrap="nowrap" align="right">Type</td>
														<td><input name="accttypesubtype" type="text" class="readonlytext" id="accttypesubtype" value="<?php echo "$accttypesubtype"; ?>" size="10" maxlength="7" readonly="readonly" /></td>
														<td nowrap="nowrap" align="right">Sts</td>
														<td><input name="acctstatus" type="text" class="readonlytext" id="acctstatus" value="<?php echo $acctstatus; ?>" size="5" maxlength="3" readonly="readonly" /></td>
														<td nowrap="nowrap" align="right">Lien Sts</td>
														<td nowrap="nowrap">
                                                            <input id="lienstatusdate" name="lienstatusdate" onchange="validateDate(this.id)" type="text" value="<?php if ($_POST['lienstatusdate']) { echo ($_POST['lienstatusdate']) ? date('m/d/Y', strtotime($_POST['lienstatusdate'])) : null; } ?>" placeholder="Lien Status Date" size="12"  />
                                                            <img id="lienstatusdateimg" align="absmiddle" onclick="cal.select(document.editForm.lienstatusdate,'lienstatusdateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="lienstatusdateimg">

                                                            <?php /*<input name="lienstatus" type="text" class="readonlytext" id="lienstatus" value="<?php echo $lienstatus; ?>" size="15" maxlength="15" readonly="readonly" />*/ ?>
                                                        </td>
														<td nowrap="nowrap" align="right">DOR Sts</td>
														<td>
                                                            <input id="dorstatusdate" name="dorstatusdate" onchange="validateDate(this.id)" type="text" value="<?php if ($_POST['dorstatusdate']) { echo ($_POST['dorstatusdate']) ? date('m/d/Y', strtotime($_POST['dorstatusdate'])) : null; } ?>" placeholder="DOR Status Date" size="12" />
                                                            <img id="dorstatusdateimg" align="absmiddle" onclick="cal.select(document.editForm.dorstatusdate,'dorstatusdateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="dorstatusdateimg">
                                                            <?php /* <input class="readonlytext" type="text" id="dorstatus" name="dorstatus" readonly="readonly" value="<?php echo $dorstatus; ?>" /></td> */ ?>
														<td nowrap="nowrap" align="right">Settle Sts</td>
														<td><input class="readonlytext" type="text" id="settlestatus" name="settlestatus" readonly="readonly" value="<?php echo $settlestatus; ?>" /></td>
													</tr>
													<tr>
														<td nowrap="nowrap" align="right">Chgs</td>
														<td><input name="charges" type="text" class="readonlytext" id="charges" value="<?php echo $charges; ?>" size="15" maxlength="13" readonly="readonly" /></td>
														<td nowrap="nowrap" align="right">Pmts</td>
														<td><input name="payments" type="text" class="readonlytext" id="payments" value="<?php echo $payments; ?>" size="15" maxlength="13" readonly="readonly" /></td>
														<td nowrap="nowrap" align="right">Full Bal</td>
														<td><input name="balance" type="text" class="readonlytext" id="balance" value="<?php echo $balance; ?>" size="15" maxlength="13" readonly="readonly" /></td>
														<td nowrap="nowrap" align="right">Adjs</td>
														<td><input name="adjust" type="text" class="readonlytext" id="adjust" value="<?php echo $adjust; ?>" size="15" maxlength="13" readonly="readonly" /></td>
														<td nowrap="nowrap" align="right">Adj Bal</td>
														<td><input name="balanceadjust" type="text" class="readonlytext" id="balanceadjust" value="<?php echo $balanceadjust; ?>" size="15" maxlength="13" readonly="readonly" /></td>
													</tr>
													<tr>
														<td nowrap="nowrap" align="right">First Vis</td>
														<td><input name="fvisit" type="text" class="readonlytext" id="fvisit" value="<?php echo $fvisit; ?>" size="15" maxlength="10" readonly="readonly" /></td>
														<td nowrap="nowrap" align="right">Last Vis</td>
														<td><input name="lvisit" type="text" class="readonlytext" id="lvisit" value="<?php echo $lvisit; ?>" size="15" maxlength="10" readonly="readonly" /></td>
														<td nowrap="nowrap" align="right"># Vis</td>
														<td><input name="visits" type="text" class="readonlytext" id="visits" value="<?php echo $visits; ?>" size="5" maxlength="3" readonly="readonly" /></td>
														<!--<td nowrap="nowrap" align="right"># Pd Vis</td>
                                                        <td><input name="paidtreatments" type="text" class="readonlytext" id="paidtreatments" value="<?php //echo $paidtreatments; ?>" size="5" maxlength="3" readonly="readonly" /></td>-->
														<td nowrap="nowrap" align="right"># Auth Vis</td>
														<td><input name="authvis" type="text" class="readonlytext" id="authvis" value="<?php echo $authvis; ?>" size="5" maxlength="3" readonly="readonly" /></td>
														<td nowrap="nowrap" align="right">Auth Dt</td>
														<td><input name="authdate" type="text" class="readonlytext" id="authdate" value="<?php echo $authdate; ?>" size="15" maxlength="10" readonly="readonly" /></td>
													</tr>
													<?php echo $authhtml; ?>
												</table></td>
											<td valign="top"><input style="height:20px;" id="AcctDtl" name="AcctDtl" type="button" value="AcctDtl" onclick="window.open('/modules/collections/collectionsAcctDtl.php?bnum=<?php echo $bnum; ?>&pnum=<?php echo $pnum; ?>','AccountDetail','type=fullWindow,fullscreen,scrollbars=yes');" /></td>
										</tr>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="bnum" value="<?php echo $bnum; ?>" />
                                                <input type="hidden" name="pnum" value="<?php echo $pnum; ?>" />
                                                <input type="hidden" name="psid" value="<?php echo $psid; ?>" />
                                                <table class="subtablesection" width="100%">
													<tr>
														<th colspan="10">Patient Status</th>
													</tr>
                                                </table>

                                                <div id="left-column">

                                                    <div class='status-block'>
                                                        <label for="cicstatus">CIC Status</label>
                                                        <select id="cicstatus" name="cicstatus">
                                                            <option value=""></option>
                                                            <option <?php if ($_POST['cicstatus'] == 'cr') { echo "selected=true"; } ?> value="cr">C & R</option>
                                                            <option <?php if ($_POST['cicstatus'] == 'dismissed') { echo "selected=true"; } ?> value="dismissed">Dismissed</option>
                                                            <option <?php if ($_POST['cicstatus'] == 'fa') { echo "selected=true"; } ?> value="fa">F & A</option>
                                                            <option <?php if ($_POST['cicstatus'] == 'fo') { echo "selected=true"; } ?> value="fo">F & O</option>
                                                            <option <?php if ($_POST['cicstatus'] == 'sa') { echo "selected=true"; } ?> value="sa">S & A</option>
                                                        </select>
                                                        <input id="cicdate" name="cicdate" onchange="validateDate(this.id)" type="text" value="<?php if ($_POST['cicdate']) { echo $_POST['cicdate']; } ?>" placeholder="CIC Date"  />
                                                        <img id="cicdateimg" align="absmiddle" onclick="cal.select(document.editForm.cicdate,'cicdateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="cicdateimg">
                                                    </div>
                                                    <div class="status-block">
                                                        <label for="candr">C & R</label>
                                                        <input type="text" id="candr" name="candr" value="<?php echo $_POST['candr']; ?>" />

                                                        <label for="casewatch">Case Watch</label>
                                                        <input id="casewatch" name="casewatch" onchange="validateDate(this.id)" type="text" value="<?php if ($_POST['casewatch']) { echo $_POST['casewatch']; } ?>" placeholder="Case Watch"  />
                                                        <img id="casewatchimg" align="absmiddle" onclick="cal.select(document.editForm.casewatch,'casewatchimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="casewatchimg">
                                                    </div>

                                                    <div class='status-block'>
                                                        <label for="refdoctor1">1st Referral Dr</label>
                                                        <input type="text" name="refdoctor1" value="<?php if ($_POST['refdoctor1']) { echo $_POST['refdoctor1']; } ?>" placeholder="1st Referral Dr" /> <br />
                                                        <label for="refdoctor2">2nd Referral Dr</label>
                                                        <input type="text" name="refdoctor2" value="<?php if ($_POST['refdoctor2']) { echo $_POST['refdoctor2']; } ?>" placeholder="2nd Referral Dr" /> <br />
                                                        <label for="refdoctor3">3rd Referral Dr</label>
                                                        <input type="text" name="refdoctor3" value="<?php if ($_POST['refdoctor3']) { echo $_POST['refdoctor3']; } ?>" placeholder="3rd Referral Dr" /> <br />
                                                    </div>

                                                    <div class='status-block'>
                                                        <label for="acceptedbodypart">Accepted Body Part</label>
                                                        <input type="text" name="acceptedbodypart" value="<?php if ($_POST['acceptedbodypart']) { echo $_POST['acceptedbodypart']; } ?>" placeholder="Accepted Body Part">
                                                    </div>

                                                    <div class='status-block'>
                                                        <label for="pqmedate">PQME/AME DATE</label>
                                                        <input type="text" name="pqmedate" value="<?php if ($_POST['pqmedate']) { echo $_POST['pqmedate']; } ?>" placeholder="PQME AME Date" />
                                                        <img id="pqmedateimg" align="absmiddle" onclick="cal.select(document.editForm.pqmedate,'pqmedateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="pqmedateimg">
                                                    </div>

                                                    <div class='status-block'>
                                                        <label for="wcabhearing">WCAB Hearing</label>
                                                        <input name="wcabhearing" type="text" max-length="255" value="<?php if ($_POST['wcabhearing']) { echo $_POST['wcabhearing']; } ?>" placeholder="WCAB Hearing" />
                                                    </div>
                                                </div>
                                                <div id="right-column">

                                                    <div class='status-block'>
                                                        <label for="surgeon">Surgeon</label>
                                                        <input type="text" name="surgeon" value="<?php if ($_POST['surgeon']) { echo $_POST['surgeon']; } ?>" placeholder="Surgeon" />
                                                        <input id="surgerydate1" name="surgerydate1" type="text" value="<?php if ($_POST['surgerydate1']) { echo $_POST['surgerydate1']; } ?>" placeholder="Surgery Date" />
                                                        <img id="surgerydate1img" align="absmiddle" onclick="cal.select(document.editForm.surgerydate1,'surgerydate1img','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="surgerydate1img">
                                                        <br />
                                                        <label for="surgerybodypart">Surgery Body Part
                                                        <input type="text" id="surgerybodypart" name="surgerybodypart" type="text" value="<?php if ($_POST['surgerybodypart']) { echo $_POST['surgerybodypart']; } ?>" placeholder="Surgery Body Part" />
                                                    </div>

                                                    <div class='status-block'>
                                                        <label for="casedenialdate">Case Denial Date</label>
                                                        <input type="text" name="casedenialdate" value="<?php if ($_POST['casedenialdate']) { echo $_POST['casedenialdate']; } ?>" placeholder="Case Denial Date" />
                                                        <img id="casedenialdateimg" align="absmiddle" onclick="cal.select(document.editForm.casedenialdate,'casedenialdateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="casedenialdateimg">
                                                    </div>

                                                    <div class='status-block'>
                                                        <label for="accepteddate">Accepted Date</label>
                                                        <input type="text" name="accepteddate" value="<?php if ($_POST['accepteddate']) { echo $_POST['accepteddate']; } ?>" placeholder="Accepted Date" />
                                                        <img id="accepteddateimg" align="absmiddle" onclick="cal.select(document.editForm.accepteddate,'accepteddateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="accepteddateimg">
                                                    </div>

                                                    <div class='status-block'>
                                                        <label for="cabto">Claim accepted but tx objected</label>
                                                        <select name="cabto">
                                                            <option value=""></option>
                                                            <option <?php if ($_POST['cabto'] == 'body-part') { echo "selected=true"; } ?> value="body-part">Body Part</option>
                                                            <option <?php if ($_POST['cabto'] == 'co-def') { echo "selected=true"; } ?> value="co-def">Co Def</option>
                                                            <option <?php if ($_POST['cabto'] == 'medical-only') { echo "selected=true"; } ?> value="medical-only">Medical Only</option>
                                                            <option <?php if ($_POST['cabto'] == 'non-mpn') { echo "selected=true"; } ?> value="non-mpn">Non-MPN</option>
                                                            <option <?php if ($_POST['cabto'] == 'not-ptp') { echo "selected=true"; } ?> value="not-ptp">Not PtP</option>
                                                            <option <?php if ($_POST['cabto'] == 'over-24') { echo "selected=true"; } ?> value="over-24">Over 24</option>
                                                            <option <?php if ($_POST['cabto'] == 'prior-p-s') { echo "selected=true"; } ?> value="prior-p-s">Prior P&S</option>
                                                            <option <?php if ($_POST['cabto'] == 'ur-denial') { echo "selected=true"; } ?> value="ur-denial">UR Denial</option>
                                                        </select>
                                                    </div>

                                                    <div class='status-block'>
                                                        <label for="medlegaldoctor">Med Legal Doctor</label>
                                                        <input type="text" name="medlegaldoctor" placeholder="Med Leagal Doctor" value="<?php if ($_POST['medlegaldoctor']) { echo $_POST['medlegaldoctor']; } ?>" />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
										<tr>
											<td><table class="subtablesection" width="100%">
													<tr>
														<th colspan="10">Primary Insurance Information:</th>
													</tr>
													<tr>
														<td nowrap="nowrap" align="right"><input id="Insurance1Lookup" name="Insurance1Lookup" type="button" value="Company" onclick="window.open('/modules/collections/collectionsInsuranceAccountsList.php?insname=<?php echo urlencode($_POST['cainsname1']); ?>','InsuranceDetail','type=fullWindow,fullscreen,scrollbars=yes');" <?php echo $insurance1disabled; ?> /></td>
														<td colspan="7"><input style="font-family:'Lucida Console', Monaco, monospace" type="hidden" id="cainsname1" name="cainsname1" value="<?php echo $_POST['cainsname1']; ?>" />
														<select id="cainsname1select" name="cainsname1select" type="text" size="1" maxlength="30" value=""  />
															<?php echo $insurancelistoptions1; ?>
														</select></td>
													</tr>
													<tr>
														<td nowrap="nowrap" align="right"><input id="Adjuster1Lookup" name="Adjuster1Lookup" type="button" value="Adjuster" onclick="window.open('/modules/collections/collectionsAdjusterAccountsList.php?adjuster=<?php echo urlencode($_POST['caadjuster1']); ?>','AdjusterDetail','type=fullWindow,fullscreen,scrollbars=yes');" <?php echo $adjuster1disabled; ?> /></td>
														<td><input id="caadjuster1" name="caadjuster1" size="32" maxlength="64" value="<?php echo $_POST['caadjuster1']; ?>" /></td>
														<td nowrap="nowrap" align="right">Ph #</td>
														<td><input id="caadjuster1phone" name="caadjuster1phone" size="15" maxlength="20" value="<?php echo $_POST['caadjuster1phone']; ?>" />
															<input id="caadjuster1ext" name="caadjuster1ext" size="6" maxlength="8" value="<?php echo $_POST['caadjuster1ext']; ?>" /></td>
														<td nowrap="nowrap" align="right">Fax #</td>
														<td><input id="caadjuster1fax" name="caadjuster1fax" size="15" maxlength="20" value="<?php echo $_POST['caadjuster1fax']; ?>" /></td>
														<td nowrap="nowrap" align="right">E-mail</td>
														<td><input id="caadjuster1email" name="caadjuster1email" size="15" maxlength="64" value="<?php echo $_POST['caadjuster1email']; ?>" /></td>
													</tr>
													<!--<tr>
                            <td nowrap="nowrap" align="right">Bill Review</td>
                            <td><input id="billreview1" name="billreview1" size="32" maxlength="64" value="<?php echo $billreview1; ?>" /></td>
                            <td nowrap="nowrap" align="right">Ph #</td>
                            <td><input id="billreview1phone" name="billreview1phone" size="15" maxlength="20" value="<?php echo $billreview1phone; ?>" /></td>
                            <td nowrap="nowrap" align="right">Fax #</td>
                            <td><input id="billreview1fax" name="billreview1fax" size="15" maxlength="20" value="<?php echo $billreview1fax; ?>" /></td>
                            <td nowrap="nowrap" align="right">E-mail</td>
                            <td><input id="billreview1email" name="billreview1email" size="15" maxlength="64" value="<?php echo $billreview1email; ?>" /></td>
                          </tr>-->
													<tr>
														<td nowrap="nowrap" align="right">Claim #</td>
														<td><input id="caclaimnumber1" name="caclaimnumber1" size="32" maxlength="64" value="<?php echo $_POST['caclaimnumber1']; ?>" /></td>
														<td nowrap="nowrap" align="right">Venue</td>
														<td><input id="cavenue1" name="cavenue1" size="15" maxlength="20" value="<?php echo $_POST['cavenue1']; ?>" /></td>
														<td nowrap="nowrap" align="right">WCAB #</td>
														<td colspan="3"><input id="cawcab1" name="cawcab1" size="15" maxlength="20" value="<?php echo $_POST['cawcab1']; ?>" /></td>
													</tr>
												</table></td>
											<td valign="top"><input style="height:20px;" id="Ins1Dtl" name="Ins1Dtl" type="button" value="Ins1Dtl" onclick="window.open('/modules/collections/collectionsIns1Dtl.php?bnum=<?php echo $bnum; ?>&amp;pnum=<?php echo $pnum; ?>','Insurance1Detail','scrollbars=yes');" /></td>
										</tr>
										<tr>
											<td><table class="subtablesection" width="100%">
													<tr>
														<th colspan="10">Secondary Insurance Information:</th>
													</tr>
													<tr>
														<td align="right" nowrap="nowrap"><input id="Insurance2Lookup" name="Insurance2Lookup" type="button" value="Company" onclick="window.open('/modules/collections/collectionsInsuranceAccountsList.php?insname=<?php echo urlencode($_POST['cainsname2']); ?>','InsuranceDetail','type=fullWindow,fullscreen,scrollbars=yes');" <?php echo $insurance2disabled; ?> /></td>
														<td colspan="7"><input type="hidden" id="cainsname2" name="cainsname2" value="<?php echo $_POST['cainsname2']; ?>" /><select name="cainsname2select" type="text" size="1" maxlength="30" value="" />
															<?php echo $insurancelistoptions2; ?>
															</select></td>
													</tr>
													<tr>
														<td nowrap="nowrap" align="right"><input id="Adjuster2Lookup" name="Adjuster2Lookup" type="button" value="Adjuster" onclick="window.open('/modules/collections/collectionsAdjusterAccountsList.php?adjuster=<?php echo urlencode($_POST['caadjuster2']); ?>','AdjusterDetail','type=fullWindow,fullscreen,scrollbars=yes');" <?php echo $adjuster2disabled; ?> /></td>
														<td><input id="caadjuster2" name="caadjuster2" size="32" maxlength="64" value="<?php echo $_POST['caadjuster2']; ?>" /></td>
														<td nowrap="nowrap" align="right">Ph #</td>
														<td><input id="caadjuster2phone" name="caadjuster2phone" size="15" maxlength="20" value="<?php echo $_POST['caadjuster2phone']; ?>" />
															<input id="caadjuster2ext" name="caadjuster2ext" size="6" maxlength="8" value="<?php echo $_POST['caadjuster2ext']; ?>" /></td>
														<td nowrap="nowrap" align="right">Fax #</td>
														<td><input id="caadjuster2fax" name="caadjuster2fax" size="15" maxlength="20" value="<?php echo $_POST['caadjuster2fax']; ?>" /></td>
														<td nowrap="nowrap" align="right">E-mail</td>
														<td><input id="caadjuster2email" name="caadjuster2email" size="15" maxlength="64" value="<?php echo $_POST['caadjuster2email']; ?>" /></td>
													</tr>
													<!--                          <tr>
                            <td nowrap="nowrap" align="right">Bill Review</td>
                            <td><input id="billreview2" name="billreview2" size="32" maxlength="64" value="<?php echo $billreview2; ?>" /></td>
                            <td nowrap="nowrap" align="right">Ph #</td>
                            <td><input id="billreview2phone" name="billreview2phone" size="15" maxlength="20" value="<?php echo $billreview2phone; ?>" /></td>
                            <td nowrap="nowrap" align="right">Fax #</td>
                            <td><input id="billreview2fax" name="billreview2fax" size="15" maxlength="20" value="<?php echo $billreview2fax; ?>" /></td>
                            <td nowrap="nowrap" align="right">E-mail</td>
                            <td><input id="billreview2email" name="billreview2email" size="15" maxlength="64" value="<?php echo $billreview2email; ?>" /></td>
                          </tr>
-->
													<tr>
														<td nowrap="nowrap" align="right">Claim #</td>
														<td><input id="caclaimnumber2" name="caclaimnumber2" size="32" maxlength="64" value="<?php echo $_POST['caclaimnumber2']; ?>" /></td>
														<td nowrap="nowrap" align="right">Venue</td>
														<td><input id="cavenue2" name="cavenue2" size="15" maxlength="20" value="<?php echo $_POST['cavenue2']; ?>" /></td>
														<td nowrap="nowrap" align="right">WCAB #</td>
														<td colspan="3"><input id="cawcab2" name="cawcab2" size="15" maxlength="20" value="<?php echo $_POST['cawcab2']; ?>" /></td>
													</tr>
												</table></td>
											<td valign="top">&nbsp;</td>
										</tr>
										<tr>
											<td><table class="subtablesection" width="100%" >
													<tr>
														<th colspan="10">Attorney Information:</th>
													</tr>
													<tr>

														<td nowrap="nowrap" align="right"><input id="Attorney1Lookup" name="Attorney1Lookup" type="button" value="Def.Atty" onclick="window.open('/modules/collections/collectionsAttorneyAccountsList.php?attyname=<?php echo urlencode($_POST['caattorneyname1']); ?>','AttorneyDetail','type=fullWindow,fullscreen,scrollbars=yes');" <?php echo $attorney1disabled; ?> /></td>

														<td><input id="caattorneyname1" name="caattorneyname1" size="32" maxlength="64" value="<?php echo $_POST['caattorneyname1']; ?>" /></td>
														<td nowrap="nowrap" align="right">Ph #</td>
														<td><input id="caattorneyphone1" name="caattorneyphone1" size="15" maxlength="20" value="<?php echo $_POST['caattorneyphone1']; ?>" />
															<input id="caattorneyext1" name="caattorneyext1" size="6" maxlength="8" value="<?php echo $_POST['caattorneyext1']; ?>" /></td>
														<td nowrap="nowrap" align="right">Fax #</td>
														<td><input id="caattorneyfax1" name="caattorneyfax1" size="15" maxlength="20" value="<?php echo $_POST['caattorneyfax1']; ?>" /></td>
														<td nowrap="nowrap" align="right">E-mail</td>
														<td><input id="caattorneyemail1" name="caattorneyemail1" size="15" maxlength="64" value="<?php echo $_POST['caattorneyemail1']; ?>" /></td>
													</tr>
													<tr>

														<td nowrap="nowrap" align="right"><input id="Attorney2Lookup" name="Attorney2Lookup" type="button" value="App.Atty" onclick="window.open('/modules/collections/collectionsAttorneyAccountsList.php?attyname=<?php echo urlencode($_POST['caattorneyname2']); ?>','AttorneyDetail','type=fullWindow,fullscreen,scrollbars=yes');" <?php echo $attorney2disabled; ?> /></td>

														<td><input id="caattorneyname2" name="caattorneyname2" size="32" maxlength="64" value="<?php echo $_POST['caattorneyname2']; ?>" /></td>
														<td nowrap="nowrap" align="right">Ph #</td>
														<td><input id="caattorneyphone2" name="caattorneyphone2" size="15" maxlength="20" value="<?php echo $_POST['caattorneyphone2']; ?>" />
															<input id="caattorneyext2" name="caattorneyext2" size="6" maxlength="8" value="<?php echo $_POST['caattorneyext2']; ?>" /></td>
														<td nowrap="nowrap" align="right">Fax #</td>
														<td><input id="caattorneyfax2" name="caattorneyfax2" size="15" maxlength="20" value="<?php echo $_POST['caattorneyfax2']; ?>" /></td>
														<td nowrap="nowrap" align="right">E-mail</td>
														<td><input id="caattorneyemail2" name="caattorneyemail2" size="15" maxlength="20" value="<?php echo $_POST['caattorneyemail2']; ?>" /></td>
													</tr>
												</table></td>
											<td valign="top"><input id="AtyDtl" name="AtyDtl" type=button value="AtyDtl" disabled="disabled" /></td>
										</tr>
										<tr>
											<td align="center"><input type="submit" name="update" id="update" value="Update" /></td>
										</tr>
										<tr>
											<td><table width="100%">
													<tr>
														<th colspan="10">Functions:</th>
													</tr>
													<?php
$lienbuttonid="";
$lienbuttonname="";
$lienbuttondisabled=' disabled="disabled" ';
if($group=='20LEGA')
	$islegalgroup=true;
else
	$islegalgroup=false;

if($_POST['calienstatus']=='L') {
	$lienbuttonid="FileLien";
	$lienbuttonname="Amend Lien";
	$lienbuttondisabled="";
}
else {
	if($_POST['calienstatus']=='RL') {
		if($islegalgroup) {
			$lienbuttonid="FiledLien";
			$lienbuttonname="Lien Done";
			$lienbuttondisabled="";
		}
		else {
			$lienbuttonid="x";
			$lienbuttonname="Lien Requested";
			$lienbuttondisabled=' disabled="disabled" ';
		}
	}
	else {
// Allow everyone to request a lien because the DOR Status may have happened before the lien
		if(!$islegalgroup) {
			$lienbuttonid="FileLien";
			$lienbuttonname="Request Lien";
			$lienbuttondisabled="";
		}
		else {
			if($_POST['cadorstatus']=='D' || $_POST['cadorstatus']=='RD' ) {
				$lienbuttonid="FiledLien";
				$lienbuttonname="Update Lien";
				$lienbuttondisabled='';
			}
			else {
				$lienbuttonid="FileLien";
				$lienbuttonname="Request Lien";
				$lienbuttondisabled='';
			}
		}
	}
}

$dorbuttonid="";
$dorbuttonname="";
$dorbuttondisabled=' disabled="disabled" ';
if($_POST['cadorstatus']=='D') {
	$dorbuttonid="x";
	$dorbuttonname="DOR is Filed";
	$dorbuttondisabled=' disabled="disabled" ';
	if($_POST['calienstatus']!='L') {
		info("000","This account needs Lien information updated due to DOR status. Please request a Lien for LEGAL to research and update.");
	}
}
else {
	if($_POST['cadorstatus']=='RD') {
		if($_POST['calienstatus']!='L') {
			info("000","This account needs Lien information updated due to DOR status. Please request a Lien for LEGAL to research and update.");
		}
		if($islegalgroup) {
			$dorbuttonid="DORDone";
			$dorbuttonname="DOR Done";
			$dorbuttondisabled="";
		}
		else {
			$dorbuttonid="x";
			$dorbuttonname="DOR Requested";
			$dorbuttondisabled=' disabled="disabled" ';
		}
	}
	else {
// Allow everyone to request DOR
		if(!$islegalgroup) {
			$dorbuttonid="DOR";
			$dorbuttonname="Request DOR";
			$dorbuttondisabled="";
		}
		else {
			$dorbuttonid="DOR";
			$dorbuttonname="Update DOR";
			$dorbuttondisabled="";
		}
	}
}

$buttonarray=array();
$buttonarray[]=array(
"id"=>"LM",
"name"=>"Left Message",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>"CallBack",
"name"=>"Call Back",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>"Notes",
"name"=>"Notes",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>"SettlementStatus",
"name"=>"Settlement Status",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>"Mail",
"name"=>"Mail",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>"DemandOffer",
"name"=>"Demand Letter",
"disabled"=>""
);
$buttonarray[]=array(
    'id' => 'AppealLetter',
    'name' => 'AppealLetter',
    //'disabled' => 'disabled'
    'disabled' => ''
);
$buttonarray[]=array(
"id"=>"ConfirmationLetter",
"name"=>"Confirmation Letter",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>"ReconsiderationLetter",
"name"=>"Reconsideration Letter",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>"PendingCase",
"name"=>"Pending Case",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>"ActivelyTreating",
"name"=>"Actively Treating",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>"ProofOfService",
"name"=>"Proof of Service",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>"Resubmit",
"name"=>"Resubmit",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>$lienbuttonid,
"name"=>$lienbuttonname,
"disabled"=>$lienbuttondisabled
);
$buttonarray[]=array(
"id"=>$dorbuttonid,
"name"=>$dorbuttonname,
"disabled"=>$dorbuttondisabled
);
$buttonarray[]=array(
"id"=>"Application",
"name"=>"Application",
"disabled"=>""
);
$buttonarray[]=array(
"id"=>"NonAppearanceLetter",
"name"=>"Non-Appearance",
"disabled"=>""
);

$buttonarray[]=array(
"id"=>"EdexRequest",
"name"=>"Edex Request",
"disabled"=>""
);

if(count($buttonarray)>0)
	echo("<tr>");

foreach($buttonarray as $index=>$buttonarray) {
	$urlencodename=urlencode($name);
	$button=$buttonarray['id'];
	$buttonname=$buttonarray['name'];
	$disabled=$buttonarray['disabled'];
	unset($url);
	unset($title);
	unset($width);
	unset($onclick);
	if(empty($disabled)) {
		$url = "'/modules/collections/collections".$button.".php?app=$app&appid=$appid&bnum=$bnum&pnum=$pnum&button=$button&patientname=$urlencodename'";
		$title="'$button'";
		$width="'height=600px,width=1024px,scrollbars=yes'";
		$onclick='onclick="window.open('.$url.','.$title.','.$width.');"';
		$disabled='';
	}
	else
		$disabled='disabled="disabled"';

	$input='<input style="width:12em;height:20px;" name="collections'.$button.'" type="button" value="'.$buttonname.'" '.$onclick.' '.$disabled.' />';

	if((($index) % 4)==0)
		echo("</tr>
<tr>
	<td>
		<div id='$button' align='center'>
			$input
		</div>
	</td>
");
	else
		echo("	<td>
		<div id='$button' align='center'>
			$input
		</div>
	</td>
");
}

echo("</tr>");
?>

<tr>
    <td colspan="4" align="center"><input type="submit" name="exit" id="exit" value="<?php echo $exitvalue; ?>" <?php echo $exitdisabled; ?> /></td>
</tr>

<?php
if(infocount()>0) {
	echo('<tr><td colspan="4">');
	displayinfo();
	echo('</td></tr>');
}
?>
												</table>
                                            </td>
											<td>&nbsp;</td>
										</tr>
                                        <tr>
                                            <th style="width:100%">Status</th>
                                        </tr>
                                        <tr>
                                            <td style="width:100%">
                                                <input name="overallstatus" type="text" maxlength="140" style="width:100%" value="<?php echo $_POST['overallstatus']; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
											<td align="center"><input type="submit" name="update" id="update" value="Update" /></td>
										</tr>
										<tr>
											<td>
                                                <?php
					  			$notewidth=75;
								$notelimit=5;
								$notehidecount=1;
					  			require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsNotesList.php'); ?></td>
											<td valign="top"><input style="height:20px;" id="AuthDtl" name="AuthDtl" type="button" value="AuthDtl" onclick="window.open('/modules/collections/collectionsAuthDtl.php?bnum=<?php echo $bnum; ?>&pnum=<?php echo $pnum; ?>','AuthorizationNotesDetail','scrollbars=yes');" />
												<br />
												<input style="height:20px;" id="NotesDtl" name="NotesDtl" type="button" value="NotesDtl" onclick="window.open('/modules/collections/collectionsNotesDtl.php?bnum=<?php echo $bnum; ?>&pnum=<?php echo $pnum; ?>','NotesDetail','scrollbars=yes');" /></td>
										</tr>
									</table></td>
							</tr>
						</table></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
<?php
//unset($_SESSION['button']);

function convertDate($value) {
    //Convert Dates
    $time = strtotime($value);
    if ($time  && trim($value)) {
        return "'".date('Y-m-d h:i:s', $time)."'";
    } else {
        return 'NULL';
    }
}

?>
