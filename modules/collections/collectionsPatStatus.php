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

    $_POST['casedenialdate'] = convertDate($_POST['casedenialdate']);
    $_POST['accepteddate'] = convertDate($_POST['accepteddate']);
    $_POST['pqmedate'] = convertDate($_POST['pqmedate']);



    echo("<script>");
	echo("window.opener.location.href = window.opener.location.href;");
	echo("if (window.opener.progressWindow) window.opener.progressWindow.close();");
	echo("window.close();");
	echo("</script>");

}

$infoSQL = "SELECT * FROM PTOS_Patients WHERE bnum = '$bnum' and pnum='$pnum'";
$infoResult = mysqli_query($dbhandle,$infoSQL);
$info = mysqli_fetch_assoc($infoResult);

$selectSQL = "SELECT
                psid,
                cicstatus,
                DATE_FORMAT(cicdate, '%m/%d/%Y') as cicdate,
                surgeon,
                DATE_FORMAT(surgerydate1, '%m/%d/%Y') as surgerydate1,
                surgerybodypart,
                refdoctor1,
                refdoctor2,
                refdoctor3,
                DATE_FORMAT(casedenialdate, '%m/%d/%Y') as casedenialdate,
                reasonfordenial,
                acceptedbodypart,
                DATE_FORMAT(accepteddate, '%m/%d/%Y') as accepteddate,
                DATE_FORMAT(pqmedate, '%m/%d/%Y') as pqmedate,
                cabto,
                wcabhearing,
                medlegaldoctor
            FROM patients_status WHERE bnum = '$bnum' and pnum='$pnum'";
$selectResult = mysqli_query($dbhandle,$selectSQL);
$currentData = mysqli_fetch_assoc($selectResult);
//print_r($currentData);
$psid = $currentData['psid'];

?>
<html>
    <head>
        <title>Patient Status</title>
        <style>
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
    </head>
    <body>
    <fieldset>
        <legend>Patient Status - <?php echo $info['fname'] . " " . $info['lname']; ?></legend>
        <form name="collectionStatus" method="POST">
            <input type="hidden" name="bnum" value="<?php echo $bnum; ?>" />
            <input type="hidden" name="pnum" value="<?php echo $pnum; ?>" />
            <input type="hidden" name="psid" value="<?php echo $psid; ?>" />
            <div id="left-column">

                <div class='status-block'>
                    <label for="cicstatus">CIC Status</label>
                    <select id="cicstatus" name="cicstatus">
                        <option value=""></option>
                        <option <?php if ($currentData['cicstatus'] == 'cr') { echo "selected=true"; } ?> value="cr">C & R</option>
                        <option <?php if ($currentData['cicstatus'] == 'dismissed') { echo "selected=true"; } ?> value="dismissed">Dismissed</option>
                        <option <?php if ($currentData['cicstatus'] == 'fa') { echo "selected=true"; } ?> value="fa">F & A</option>
                        <option <?php if ($currentData['cicstatus'] == 'fo') { echo "selected=true"; } ?> value="fo">F & O</option>
                        <option <?php if ($currentData['cicstatus'] == 'sa') { echo "selected=true"; } ?> value="sa">S & A</option>
                    </select>
                    <input id="cicdate" name="cicdate" onchange="validateDate(this.id)" type="text" value="<?php if ($currentData['cicdate']) { echo $currentData['cicdate']; } ?>" placeholder="CIC Date"  />
                    <img id="cicdateimg" align="absmiddle" onclick="cal.select(document.collectionStatus.cicdate,'cicdateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="cicdateimg">
                </div>

                <div class='status-block'>
                    <label for="refdoctor1">1st Referral Dr</label>
                    <input type="text" name="refdoctor1" value="<?php if ($currentData['refdoctor1']) { echo $currentData['refdoctor1']; } ?>" placeholder="1st Referral Dr" /> <br />
                    <label for="refdoctor2">2nd Referral Dr</label>
                    <input type="text" name="refdoctor2" value="<?php if ($currentData['refdoctor2']) { echo $currentData['refdoctor2']; } ?>" placeholder="2nd Referral Dr" /> <br />
                    <label for="refdoctor3">3rd Referral Dr</label>
                    <input type="text" name="refdoctor3" value="<?php if ($currentData['refdoctor3']) { echo $currentData['refdoctor3']; } ?>" placeholder="3rd Referral Dr" /> <br />
                </div>

                <div class='status-block'>
                    <label for="acceptedbodypart">Accepted Body Part</label>
                    <input type="text" name="acceptedbodypart" value="<?php if ($currentData['acceptedbodypart']) { echo $currentData['acceptedbodypart']; } ?>" placeholder="Accepted Body Part">
                </div>

                <div class='status-block'>
                    <label for="pqmedate">PQME/AME DATE</label>
                    <input type="text" name="pqmedate" value="<?php if ($currentData['pqmedate']) { echo $currentData['pqmedate']; } ?>" placeholder="PQME AME Date" />
                    <img id="pqmedateimg" align="absmiddle" onclick="cal.select(document.collectionStatus.pqmedate,'pqmedateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="pqmedateimg">
                </div>

                <div class='status-block'>
                    <label for="wcabhearing">WCAB Hearing</label>
                    <input name="wcabhearing" type="text" max-length="255" value="<?php if ($currentData['wcabhearing']) { echo $currentData['wcabhearing']; } ?>" placeholder="WCAB Hearing" />
                </div>
            </div>
            <div id="right-column">

                <div class='status-block'>
                    <label for="surgeon">Surgeon</label>
                    <input type="text" name="surgeon" value="<?php if ($currentData['surgeon']) { echo $currentData['surgeon']; } ?>" placeholder="Surgeon" />
                    <input id="surgerydate1" name="surgerydate1" type="text" value="<?php if ($currentData['surgerydate1']) { echo $currentData['surgerydate1']; } ?>" placeholder="Surgery Date" />
                    <img id="surgerydate1img" align="absmiddle" onclick="cal.select(document.collectionStatus.surgerydate1,'surgerydate1img','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="surgerydate1img">
                    <br />
                    <label for="surgerybodypart">Surgery Body Part
                    <input type="text" id="surgerybodypart" name="surgerybodypart" type="text" value="<?php if ($currentData['surgerybodypart']) { echo $currentData['surgerybodypart']; } ?>" placeholder="Surgery Body Part" />
                </div>

                <div class='status-block'>
                    <label for="casedenialdate">Case Denial Date</label>
                    <input type="text" name="casedenialdate" value="<?php if ($currentData['casedenialdate']) { echo $currentData['casedenialdate']; } ?>" placeholder="Case Denial Date" />
                    <img id="casedenialdateimg" align="absmiddle" onclick="cal.select(document.collectionStatus.casedenialdate,'casedenialdateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="casedenialdateimg">
                </div>

                <div class='status-block'>
                    <label for="reasonfordenial">Reason For Denial</label>
                    <select name="reasonfordenial">
                    <option value=""></option>
                    <option <?php if ($currentData['reasonfordenial'] == 'aoecoe') { echo "selected=true"; } ?> value="aoecoe">AOE/COE</option>
                    <option <?php if ($currentData['reasonfordenial'] == 'coverage') { echo "selected=true"; } ?> value="coverage">Coverage</option>
                    <option <?php if ($currentData['reasonfordenial'] == 'post-term') { echo "selected=true"; } ?> value="post-term">Post-Term</option>
                    </select>
                </div>


                <div class='status-block'>
                    <label for="accepteddate">Accepted Date</label>
                    <input type="text" name="accepteddate" value="<?php if ($currentData['accepteddate']) { echo $currentData['accepteddate']; } ?>" placeholder="Accepted Date" />
                    <img id="accepteddateimg" align="absmiddle" onclick="cal.select(document.collectionStatus.accepteddate,'accepteddateimg','MM/dd/yyyy'); return false;" src="/img/calendar.gif" name="accepteddateimg">
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
                    <label for="medlegaldoctor">Med Legal Doctor</label>
                    <input type="text" name="medlegaldoctor" placeholder="Med Leagal Doctor" value="<?php if ($currentData['medlegaldoctor']) { echo $currentData['medlegaldoctor']; } ?>" />
                </div>
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
