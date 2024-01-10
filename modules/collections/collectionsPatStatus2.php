<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();


$bnum = null;
$pnum = null;

if (isset($_GET['bnum'])) {
    $bnum = $_GET['bnum'];
}

if (isset($_GET['pnum'])) {
    $pnum = $_GET['pnum'];
}

if (isset($_POST['save'])) {

    //Convert Dates
    $_POST['cicdate'] = convertDate($_POST['cicdate']);
    $_POST['surgerydate1'] = convertDate($_POST['surgerydate1']);
    //$_POST['surgery-date-2'] = convertDate($_POST['surgery-date-2']);

    $_POST['case-denial-date'] = convertDate($_POST['case-denial-date']);
    $_POST['accepted-date'] = convertDate($_POST['accepted-date']);
    $_POST['pqme-date'] = convertDate($_POST['pqme-date']);

    $_POST['body-part']['back'] = (isset($_POST['body-part']['back'])) ? 1 : 0;
    $_POST['body-part']['elbow'] = (isset($_POST['body-part']['elbow'])) ? 1 : 0;
    $_POST['body-part']['foot'] = (isset($_POST['body-part']['foot'])) ? 1 : 0;
    $_POST['body-part']['hand'] = (isset($_POST['body-part']['hand'])) ? 1 : 0;
    $_POST['body-part']['hip'] = (isset($_POST['body-part']['hip'])) ? 1 : 0;
    $_POST['body-part']['knee'] = (isset($_POST['body-part']['knee'])) ? 1 : 0;
    $_POST['body-part']['neck'] = (isset($_POST['body-part']['neck'])) ? 1 : 0;
    $_POST['body-part']['shoulder'] = (isset($_POST['body-part']['shoulder'])) ? 1 : 0;
    $_POST['body-part']['wrist'] = (isset($_POST['body-part']['wrist'])) ? 1 : 0;

    if ($_POST['psid']) {
        $sql = "UPDATE patients_status
                SET
                    cicstatus = '".mysql_escape_string($dbhandle,$_POST['cicstatus'])."',
                    cicdate = ".$_POST['cicdate'].",
                    surgeon = '".mysql_escape_string($dbhandle,$_POST['surgeon'])."',
                    surgerydate1 = ".$_POST['surgery-date-1'].",

                surgery_date_2 = ".$_POST['surgery-date-2'].",
                doctor = '".mysql_escape_string($dbhandle,$_POST['doctor'])."',
                body_part_back = ". $_POST['body-part']['back'] .",
                body_part_elbow = ". $_POST['body-part']['elbow'] .",
                body_part_foot = ". $_POST['body-part']['foot'] .",
                body_part_hand = ". $_POST['body-part']['hand'] .",
                body_part_hip = ". $_POST['body-part']['hip'] .",
                body_part_knee = ". $_POST['body-part']['knee'] .",
                body_part_neck = ". $_POST['body-part']['neck'] .",
                body_part_shoulder = ". $_POST['body-part']['shoulder'] .",
                body_part_wrist = ". $_POST['body-part']['wrist'] .",
                referring_doctor = '".mysql_escape_string($dbhandle,$_POST['referring-doctor'])."',
                case_denial_date = ".$_POST['case-denial-date'].",
                reason_for_denial = '".mysql_escape_string($dbhandle,$_POST['reason-for-denial'])."',
                accepted_date = ".$_POST['accepted-date'].",
                accepted_body_part = '".mysql_escape_string($dbhandle,$_POST['accepted-body-part'])."',
                cabto = '".mysql_escape_string($dbhandle,$_POST['cabto'])."',
                pqme_date = ".$_POST['pqme-date'].",
                doctor2 = '".mysql_escape_string($dbhandle,$_POST['doctor2'])."',
                exp_hearing = '".mysql_escape_string($dbhandle,$_POST['exp-hearing'])."',
                lien_conf = '".mysql_escape_string($dbhandle,$_POST['lien-conf'])."',
                lien_trial = '".mysql_escape_string($dbhandle,$_POST['lien-trial'])."',
                msc = '".mysql_escape_string($dbhandle,$_POST['msc'])."',
                status_conf = '".mysql_escape_string($dbhandle,$_POST['status-conf'])."',
                note = '".mysql_escape_string($dbhandle,$_POST['note'])."'

                WHERE psid = ".$_POST['psid'];
        mysqli_query($dbhandle,$sql);
        if (mysqli_errno($dbhandle)) {
            echo mysqli_error($dbhandle);exit;
        }
    } else {
        $sql = "INSERT INTO patients_status
                (
                    bnum,
                    pnum,
                    cicstatus,
                    cicdate,
                    surgeon
                    surgerydate1,


                    surgery_date_2,
                    doctor, body_part_back, body_part_elbow, body_part_foot,
                    body_part_hand, body_part_hip, body_part_knee, body_part_neck,
                    body_part_shoulder, body_part_wrist, referring_doctor, case_denial_date,
                    reason_for_denial, accepted_date, accepted_body_part,
                    cabto, pqme_date, doctor2, exp_hearing, lien_conf,
                    lien_trial, msc, status_conf, note
                )
                VALUES (
                    '".$_POST['bnum']."',
                    '".$_POST['pnum']."',
                    '".mysql_escape_string($dbhandle,$_POST['cicstatus'])."',
                    ".$_POST['cicdate'].",


                    ".$_POST['surgery-date-1'].",
                    ".$_POST['surgery-date-2'].",
                    '".mysql_escape_string($dbhandle,$_POST['doctor'])."',
                    '".mysql_escape_string($dbhandle,$_POST['body-part']['back'])."',
                    '".mysql_escape_string($dbhandle,$_POST['body-part']['elbow'])."',
                    '".mysql_escape_string($dbhandle,$_POST['body-part']['foot'])."',
                    '".mysql_escape_string($dbhandle,$_POST['body-part']['hand'])."',
                    '".mysql_escape_string($dbhandle,$_POST['body-part']['hip'])."',
                    '".mysql_escape_string($dbhandle,$_POST['body-part']['knee'])."',
                    '".mysql_escape_string($dbhandle,$_POST['body-part']['neck'])."',
                    '".mysql_escape_string($dbhandle,$_POST['body-part']['shoulder'])."',
                    '".mysql_escape_string($dbhandle,$_POST['body-part']['wrist'])."',
                    '".mysql_escape_string($dbhandle,$_POST['referring-doctor'])."',
                    ".$_POST['case-denial-date'].",
                    '".mysql_escape_string($dbhandle,$_POST['reason-for-denial'])."',
                    ".$_POST['accepted-date'].",
                    '".mysql_escape_string($dbhandle,$_POST['accepted-body-part'])."',
                    '".mysql_escape_string($dbhandle,$_POST['cabto'])."',
                    ".$_POST['pqme-date'].",
                    '".mysql_escape_string($dbhandle,$_POST['doctor2'])."',
                    '".mysql_escape_string($dbhandle,$_POST['exp-hearing'])."',
                    '".mysql_escape_string($dbhandle,$_POST['lien-conf'])."',
                    '".mysql_escape_string($dbhandle,$_POST['lien_trial'])."',
                    '".mysql_escape_string($dbhandle,$_POST['msc'])."',
                    '".mysql_escape_string($dbhandle,$_POST['status_conf'])."',
                    '".mysql_escape_string($dbhandle,$_POST['note'])."'
                )";
        mysqli_query($dbhandle,$sql);
        if (mysqli_errno($dbhandle)) {
            echo mysqli_error($dbhandle);exit;
        }
    }
}

$infoSQL = "SELECT * FROM PTOS_Patients WHERE bnum = '$bnum' and pnum='$pnum'";
$infoResult = mysqli_query($dbhandle,$infoSQL);
$info = mysqli_fetch_assoc($infoResult);
$selectSQL = "SELECT * FROM patients_status WHERE bnum = '$bnum' and pnum='$pnum'";
$selectResult = mysqli_query($dbhandle,$selectSQL);
echo mysqli_error($dbhandle);
$currentData = mysqli_fetch_assoc($selectResult);
$psid = $currentData['psid'];

?>
<html>
    <head>
        <title>Patient Status</title>
        <style>
            .status-block {
                width: 47%;
                display: inline-block;
                border: 1px solid black;
                margin: 5px;
                padding: 5px;
                vertical-align: top;
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
    </head>
    <body>
    <fieldset>
        <legend>Patient Status - <?php echo $info['fname'] . " " . $info['lname']; ?></legend>
        <form name="collectionStatus" method="POST">
            <input type="hidden" name="bnum" value="<?php echo $bnum; ?>" />
            <input type="hidden" name="pnum" value="<?php echo $pnum; ?>" />
            <input type="hidden" name="psid" value="<?php echo $psid; ?>" />
            <div class='status-block'>
                <label for="cicstatus">CIC Status</label>
                <select id="cicstatus" name="cicstatus">
                    <option value=""></option>
                    <option <?php if ($currentData['cic_status'] == 'cr') { echo "selected=true"; } ?> value="cr">C & R</option>
                    <option <?php if ($currentData['cic_status'] == 'dismissed') { echo "selected=true"; } ?> value="dismissed">Dismissed</option>
                    <option <?php if ($currentData['cic_status'] == 'fa') { echo "selected=true"; } ?> value="fa">F & A</option>
                    <option <?php if ($currentData['cic_status'] == 'fo') { echo "selected=true"; } ?> value="fo">F & O</option>
                    <option <?php if ($currentData['cic_status'] == 'sa') { echo "selected=true"; } ?> value="sa">S & A</option>
                </select>
                <input id="cicdate" name="cicdate" onchange="validateDate(this.id)" type="text" value="<?php if ($currentData['cicdate']) { echo $currentData['cicdate']; } ?>" placeholder="CIC Date"  />
                <img id="cicdateimg" align="absmiddle" onclick="cal.select(document.collectionStatus.cicdate,'cicdateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="cicdateimg">
            </div>
            <div class='status-block'>
                <label for="surgeon">Surgeon</label>
                <input type="text" name="surgeon" value="<?php if ($currentData['surgeon']) { echo $currentData['surgeon']; } ?>" placeholder="Surgeon" />
                <input id="surgerydate1" name="surgerydate1" type="text" value="<?php if ($currentData['surgerydate1']) { echo $currentData['surgerydate1']; } ?>" placeholder="Surgery Date" />
                <img id="surgerydate1img" align="absmiddle" onclick="cal.select(document.collectionStatus.surgerydate1,'surgerydate1img','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="surgerydate1img">
                <?php
                    /*<input id="surgerydate2" name="surgerydate2" type="text" value="<?php if ($currentData['surgerydate2']) { echo $currentData['surgerydate2']; } ?>" />
                      <img id="surgerydate2img" align="absmiddle" onclick="cal.select(document.collectionStatus.surgerydate2,'surgerydate2img','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="surgerydate2img">
                        */
                ?>
            </div>
            <div class='status-block'>
                <label for="doctor">Doctor</label> <br />
                <input type="text" name="refdoctor1" <?php if ($currentData['refdoctor1']) { echo $currentData['refdoctor1']; } ?> placeholder="Doctor 1" /> <br />
                <input type="text" name="refdoctor2" <?php if ($currentData['refdoctor2']) { echo $currentData['refdoctor2']; } ?> placeholder="Doctor 2" /> <br />
                <input type="text" name="refdoctor3" <?php if ($currentData['refdoctor3']) { echo $currentData['refdoctor3']; } ?> placeholder="Doctor 3" /> <br />
            </div>
            <div class='status-block'>
                <label for="casedenialdate">Case Denial Date</label>
                <input type="text" name="casedenialdate" value="<?php if ($currentData['casedenialdate']) { echo $currentData['casedenialdate']; } ?>" placeholder="Case Denial Date" />
                <img id="casedenialdateimg" align="absmiddle" onclick="cal.select(document.collectionStatus.casedenialdate,'casedenialdateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="casedenialdateimg">
            </div>
            <div class='status-block'>
                <label for="reason-for-denial">Reason For Denial</label>
                <select name="reason-for-denial">
                    <option value=""><option>
                    <option <?php if ($currentData['reason_for_denial'] == 'aoecoe') { echo "selected=true"; } ?> value="aoecoe">AOE/COE<option>
                    <option <?php if ($currentData['reason_for_denial'] == 'coverage') { echo "selected=true"; } ?> value="coverage">Coverage<option>
                    <option <?php if ($currentData['reason_for_denial'] == 'post-term') { echo "selected=true"; } ?> value="post-term">Post-Term<option>
                </select>
            </div>
            <div class='status-block'>
                <label for="accepteddate">Accepted Date</label>
                <input type="text" name="accepteddate" value="<?php if ($currentData['accepteddate']) { echo $currentData['accepteddate']; } ?>" placeholder="Accepted Date" />
                <img id="accepteddateimg" align="absmiddle" onclick="cal.select(document.collectionStatus.accepteddate,'accepteddateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="accepteddateimg">
            </div>
            <div class='status-block'>
                <label for="acceptedbodypart">Accepted Body Part</label>
                <input type="text" name="acceptedbodypart" value="<?php if ($currentData['accepteddate']) { echo $currentData['accepteddate']; } ?>" placeholder="Accepted Body Part">
            </div>
            <div class='status-block'>
                <label for="cabto">Claim accepted but tx objected</label>
                <select name="cabto">
                    <option value=""></option>
                    <option <?php if ($currentData['cabto'] == 'body-part') { echo "selected=true"; } ?> value="body-part">Body Part</option>
                    <option <?php if ($currentData['cabto'] == 'co-def') { echo "selected=true"; } ?> value="co-def">Co Def</option>
                    <option <?php if ($currentData['cabto'] == 'medical-only') { echo "selected=true"; } ?> value="medical-only">Medical Only</option>
                    <option <?php if ($currentData['cabto'] == 'non-mpn') { echo "selected=true"; } ?> value="non-mpn">Non-MPN</option>
                    <option <?php if ($currentData['cabto'] == 'not-ptp') { echo "selected=true"; } ?> value="not-ptp">Not PtP</option>
                    <option <?php if ($currentData['cabto'] == 'over-24') { echo "selected=true"; } ?> value="over-24">Over 24</option>
                    <option <?php if ($currentData['cabto'] == 'prior-p-s') { echo "selected=true"; } ?> value="prior-p-s">Prior P&S</option>
                    <option <?php if ($currentData['cabto'] == 'ur-denial') { echo "selected=true"; } ?> value="ur-denial">UR Denial</option>
                </select>
            </div>
            <div class='status-block'>
                <label for="pqmedate">PQME/AME DATE</label>
                <input type="text" name="pqmedate" value="<?php if ($currentData['pqme_date']) { echo $currentData['pqme_date']; } ?>" placeholder="PQME AME Date" />
                <img id="accepteddateimg" align="absmiddle" onclick="cal.select(document.collectionStatus.accepteddate,'accepteddateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="accepteddateimg">
            </div>
            <div class='status-block'>
                <label for="medlegaldoctor">Med Legal Doctor</label>
                <input type="text" name="medlegaldoctor" placeholder="Med Leagal Doctor" value="<?php if ($currentData['medlegaldoctor']) { echo $currentData['medlegaldoctor']; } ?>" />
            </div>
            <div class='status-block'>
                <label for="wcabhearing">WCAB Hearing</label>
                <input name="wcabhearing" type="text" max-length="255" value="<?php if ($currentData['wcabhearing']) { echo $currentData['wcabhearing']; } ?>" placeholder="WCAB Hearing" />
            </div>
            <div>
                <input type="submit" name="save" value="Save Information" />
            </div>
        </form>
    </fieldset>


    <script>
        var cal = new CalendarPopup();
    </script>
    </body>
</html>
<?php

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
