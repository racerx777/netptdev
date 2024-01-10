<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
if(empty($_POST['pnum']) && empty($_REQUEST['pnum']) && empty($_REQUEST['caid'])) {
	echo "ERROR";
dump("REQUEST",$_REQUEST);
dumppost();
dump("SESSION-navigation",$_SESSION['navigation']);
dump("SESSION-navigationid",$_SESSION['navigationid']);
dump("SESSION-button",$_SESSION['button']);
dump("SESSION-id",$_SESSION['id']);
	exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

// Get PTOS_Patients record
if(isset($_POST['pnum'])) {
	$pnum=$_POST['pnum'];
	$bnum=$_POST['bnum'];
}
elseif(isset($_REQUEST['pnum'])) {
	$pnum=$_REQUEST['pnum'];
	$bnum=$_REQUEST['bnum'];
}
$app=$_POST['app'];
$appid=$_POST['appid'];
$button=$_POST['button'];

if(empty($app) || empty($appid)) {
	if(empty($bnum) || empty($pnum)) {
		error('999', "Required parameter missing. (app:$app && appid:$appid) (bnum:$bnum && pnum:$pnum)<br>".mysqli_error($dbhandle));
		exit();
	}
	else
		$where = "bnum='$bnum' and pnum='$pnum'";
}
else
	$where = "caid='$appid'";
$query = "
SELECT *
FROM PTOS_Patients p
LEFT JOIN collection_accounts ca
ON bnum=cabnum and pnum=capnum
WHERE $where
";
$result = mysqli_query($dbhandle,$query);
if($result) {
	$numrows=mysqli_num_rows($result);
	if($numrows==0) {
		notify("000","No Patients or Collections Accounts Found for $bnum $pnum. No Row Fetched.");
		exit();
	}
	if($numrows > 1)
		notify("000","Multiple Records Found for $bnum $pnum. First Row Fetched.");
	$row = mysqli_fetch_assoc($result);
	if($row) {
		if(empty($row['caid'])) {
			$accttype='??';
			$acctsubtype='??';
			$lienstatus='??';
			$acctinfoarray = getAccountXref( $row['acctype'] );
				$accttype=$acctinfoarray['accttype'];
				$acctsubtype=$acctinfoarray['acctsubtype'];
				$lienstatus=$acctinfoarray['acctlientype'];
			$acctstatus='NEW';
			$auditfields = getauditfields();
			$crtuser = "'" . mysqli_real_escape_string($dbhandle,$auditfields['user']) . "'";
			$crtdate = "'" . mysqli_real_escape_string($dbhandle,$auditfields['date']) . "'";
			$crtprog = "'" . mysqli_real_escape_string($dbhandle,$auditfields['prog']) . "'";

			$caquery = "
				INSERT INTO collection_accounts (cabnum, cacnum, capnum, caaccttype, caacctsubtype, caacctstatus, calienstatus, crtdate, crtuser, crtprog) VALUES('$bnum', '$cnum', '$pnum', '$accttype', '$acctsubtype', '$acctstatus', '$lienstatus', $crtdate, $crtuser, $crtprog);
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
					if($numrows == 1)
						echo("One Record Found for $bnum $pnum. Row Fetched.");
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
		if($row) {
			foreach($row as $fieldname=>$fieldvalue) {
				if(!empty($fieldvalue))
					$_POST[$fieldname] = $fieldvalue;
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

if(errorcount()==0) {
// Display Output
	$name = $_POST['lname'] . ", " . $_POST['fname'];
	$dob = displayDate($_POST['birth']);
	$ssn = displaySsnAll($_POST['ssn']);
	$doi = displayDate($_POST['injury']);
	$office = $_POST['cnum'];
	$therapist = $_POST['therap'];
	$emp = $_POST['emp'];
	$accttype = $_POST['caaccttype'];
	$acctsubtype = $_POST['caacctsubtype'];
	$acctstatus = $_POST['caacctstatus'];
	$lienstatus = $_POST['calienstatus'];
	$charges=$_POST['charges'];
	$adjust = $_POST['adjust'];
	$payments = $_POST['payments'];
	$balance = $charges-$payments;
	$balanceadjust = $charges-$payments+$adjust;
	$lvisit = displayDate($_POST['lvisit']);
	$visits = $_POST['visits'];

	// Need to calculate from transv
	$paidvis = $_POST['paidvis'];

	$authvis = $_POST['authvis'];
	$authdate = displayDate($_POST['authdate']);
	$payor = $_POST['payor'];
	$adjuster1 = $_POST['padjust'];
	$adjuster1phone = $_POST['pphone'];

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
?>

<div class="centerFieldset" style="margin-top:50px;">
  <form action="" method="post" name="editForm">
    <input type="hidden" name="pnum" value="<?php echo $_POST['pnum']; ?>">
    <?php echo $emp; ?>
    <fieldset style="text-align:center;">
      <legend>Collections - Insurance Detail Record #<?php echo $_POST['pnum']; ?></legend>
      <?php echo $_POST['fname']; ?><?php echo $_POST['lname']; ?>
      <table width="760px" style="text-align:left;">
        <tr>
          <td><table width="100%">
              <tr>
                <td nowrap="nowrap" align="right">Insurance:</td>
                <td><?php echo $_POST['cnum']; ?></td>
                <td nowrap="nowrap" align="right">Insured Name:</td>
                <td><?php echo $_POST['acctype']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Adjuster:</td>
                <td><?php echo $_POST['unbilled']; ?></td>
                <td nowrap="nowrap" align="right">Address::</td>
                <td><?php echo $_POST['lastcode']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Group #: </td>
                <td><?php echo $_POST['charges']; ?></td>
                <td nowrap="nowrap" align="right">City State:</td>
                <td><?php echo $_POST['lvisit']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Id #: </td>
                <td><?php echo $_POST['payments']; ?></td>
                <td nowrap="nowrap" align="right">Zip Phone:</td>
                <td><?php echo $_POST['tbal']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Employer:</td>
                <td><?php echo $_POST['adjust']; ?></td>
                <td nowrap="nowrap" align="right">Accept Assignment:</td>
                <td><?php echo $_POST['ibal']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Related to Insured:</td>
                <td><?php echo $_POST['ipaid']; ?></td>
                <td nowrap="nowrap" align="right">Birth Date:</td>
                <td><?php echo $_POST['pbal']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Gender:</td>
                <td><?php echo $_POST['ppaid']; ?></td>
                <td nowrap="nowrap" align="right">Current:</td>
                <td><?php echo $_POST['tbal']; ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><input name="Exit" type="button" value="Exit" onclick="javascript:window.close()" /></td>
        </tr>
      </table>
    </fieldset>
  </form>
</div>
