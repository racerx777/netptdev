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

if(errorcount()!=0) {
displaysitemessages();
?>

<input name="Exit" type="button" value="Exit" onclick="javascript:window.close()" />
<?php
exit();
}
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
?>
<style type="text/css">
.subtablesection {
	background-color:#DDD;
}

.readonlytext {
	background-color: #DDD;
}
</style>
<div class="centerFieldset" style="margin-top:50px;">
  <form action="" method="post" name="editForm">
    <input type="hidden" name="pnum" value="<?php echo $_POST['pnum']; ?>">
    <?php echo $emp; ?>
    <fieldset style="text-align:center;">
      <legend>Collections - Account Detail Record #<?php echo $_POST['pnum']; ?></legend>
      <?php echo $_POST['fname']; ?><?php echo $_POST['lname']; ?>
      <table class="subtablesection" width="100%">
        <tr>
          <td><table width="100%">
              <tr>
                <td nowrap="nowrap" align="right">Office:</td>
                <td><?php echo $_POST['cnum']; ?></td>
                <td nowrap="nowrap" align="right">Account Type:</td>
                <td><?php echo $_POST['acctype']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Ins Unbilled:</td>
                <td><?php echo $_POST['unbilled']; ?></td>
                <td nowrap="nowrap" align="right">Last Code:</td>
                <td><?php echo $_POST['lastcode']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Charges: </td>
                <td><?php echo $_POST['charges']; ?></td>
                <td nowrap="nowrap" align="right">Last Tx:</td>
                <td><?php echo $_POST['lvisit']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Payments: </td>
                <td><?php echo $_POST['payments']; ?></td>
                <td nowrap="nowrap" align="right">Balance:</td>
                <td><?php echo $_POST['tbal']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Adjustments:</td>
                <td><?php echo $_POST['adjust']; ?></td>
                <td nowrap="nowrap" align="right">Ins Bal:</td>
                <td><?php echo $_POST['ibal']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Ins Paid:</td>
                <td><?php echo $_POST['ipaid']; ?></td>
                <td nowrap="nowrap" align="right">Pat Bal:</td>
                <td><?php echo $_POST['pbal']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Pat Paid</td>
                <td><?php echo $_POST['ppaid']; ?></td>
                <td nowrap="nowrap" align="right">Current:</td>
                <td><?php echo $_POST['tcurr']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Ins Billed:</td>
                <td><?php echo $_POST['ibilled']; ?></td>
                <td nowrap="nowrap" align="right">30-60</td>
                <td><?php echo $_POST['t30']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Pat Billed</td>
                <td><?php echo $_POST['pbilled']; ?></td>
                <td nowrap="nowrap" align="right">60-90:</td>
                <td><?php echo $_POST['t60']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">1st Visit:</td>
                <td><?php echo $_POST['fvisit']; ?></td>
                <td nowrap="nowrap" align="right">90-120</td>
                <td><?php echo $_POST['t90']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Visits:</td>
                <td><?php echo $_POST['visits']; ?></td>
                <td nowrap="nowrap" align="right">120+</td>
                <td><?php echo $_POST['t120']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Auth Visits:</td>
                <td><?php echo $_POST['authvisits']; ?></td>
                <td nowrap="nowrap" align="right">Remaining:</td>
                <td><?php echo $_POST['remaimimg']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="right">Auth Date:</td>
                <td><?php echo $_POST['authdate']; ?></td>
                <td nowrap="nowrap" align="right">Max. Charges:</td>
                <td><?php echo $_POST['maxcharge']; ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><input name="Exit" type="button" value="Exit" onclick="javascript:window.close()" /></td>
        </tr>
        <tr>
          <td><table class="subtablesection" cellpadding="5" cellspacing="1">
              <tr>
                <td> Transactions
                  <?php
$htmlrows=array();
$numrows=0;
unset($query);
if(!empty($bnum) && !empty($pnum)) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
	$dbhandle = dbconnect();
	
	$query = "
		SELECT bnum, cnum, pnum, date, code, descrip, amount, ipayed+ppayed as paid, credit
		FROM PTOS_Transactions
		WHERE bnum='$bnum' and pnum='$pnum'
		ORDER BY date
	";
	if($result = mysqli_query($dbhandle,$query)) {
		$numrows=mysqli_num_rows($result);
		if($numrows > 0) {
			$netbalance=0;
			$htmlrows[]="<tr><th>BU</th><th>OFC</th><th>DATE</th><th>CODE</th><th>DESCRIP</th><th>AMOUNT</th><th>PAID</th><th>CREDIT</th><th>TRANS BAL</th><th>NET BAL</th></tr>";
			while($row=mysqli_fetch_assoc($result)) {
				$bnum=$row['bnum'];
				$cnum=$row['cnum'];
				$pnum=$row['pnum'];
				$date=displayDate($row['date']);
				$code=$row['code'];
				$descrip=$row['descrip'];

				$amount=number_format($row['amount'],2,".","");
				$paid=number_format($row['paid'],2,".","");
				$credit=number_format($row['credit'],2,".","");

				$rowbal = round($row['amount'] - $row['paid'] - $row['credit'],2);
				$netbalance = round($netbalance + $row['amount'] - $row['paid'] - $row['credit'],2);
if(getuser()=='SunniSpoonx') {
	dump($amount, $row['amount']);
	dump($paid, $row['paid']);
	dump($credit, $row['credit']);
	dump("ROWBALANCE", $rowbal);
	dump("NETBALANCE", $netbalance);
}
				$amount=displayCurrency($amount);
				$paid=displayCurrency($paid);
				$credit=displayCurrency($credit);
				$rowbal=displayCurrency($rowbal);
				$netbal=displayCurrency($netbalance);

				$colspan4='"4"';
				$colspan5='"5"';
				$nowrap='"nowrap"';
				$right='"right"';
				if(empty($visitdate))
					$visitdate=$date;

				if($date != $visitdate) {
					$htmlrows[]="<tr>
	<td colspan='2' $nowrap><hr></td>
	<td $nowrap>$visitdate</td>
	<td colspan='2' align=$right $nowrap>Visit Total</td>
	<td align=$right>$visitamount</td>
	<td align=$right>$visitpaid</td>
	<td align=$right>$visitcredit</td>
	<td align=$right>$visitrowbal</td>
	</tr>";
					$visitdate = $date;
					$visitamount=0;
					$visitpaid=0;
					$visitcredit=0;
					$visitrowbal=0;
				}

					$visitamount=displayCurrency($visitamount+$amount);
					$visitpaid=displayCurrency($visitpaid+$paid);
					$visitcredit=displayCurrency($visitcredit+$credit);
					$visitrowbal=displayCurrency($visitrowbal+$rowbal);

				if($code=="D") {
					unset($visitdate);
					if(stristr($descrip,' cr.$'))
						$rowcolor="Khaki";
					else {
						if(stristr($descrip,' pd.$'))
							$rowcolor="MediumSeaGreen ";
						else
							if(stristr($descrip,' billed '))
								$rowcolor="DodgerBlue";
							else
								$rowcolor="white";
					}
					$htmlrows[]="<tr bgcolor='$rowcolor'>
	<td colspan='2' $nowrap>&nbsp;</td>
	<td>$date</td>
	<td colspan='7' $nowrap>$descrip</td>
</tr>";
				}
				else {
					if($rowbal==0)
						$rowcolor='';
					else
						$rowcolor='bgcolor="White"';
					$htmlrows[]="<tr $rowcolor>
	<td>$bnum</td>
	<td>$cnum</td>
	<td>$date</td>
	<td>$code</td>
	<td $nowrap>$descrip</td>
	<td align=$right>$amount</td>
	<td align=$right>$paid</td>
	<td align=$right>$credit</td>
	<td align=$right>$rowbal</td>
	<td align=$right>$netbal</td>
</tr>";
				}
			}
		}
	}
	else
		error("001","collectionsAcctDtl: SELECT Error. $query<br>".mysqli_error($dbhandle));
	mysqli_close($dbhandle);
}
else
	error("002","Missing required identifier. (bnum:$bnum & pnum:$pnum)");
if(count($htmlrows) > 0)
	$html=implode("", $htmlrows);
else
	$html="No Transactions found.<br>$query";
echo $html;
?></td>
              </tr>
            </table></td>
        </tr>
      </table>
    </fieldset>
  </form>
</div>
<?php
//require_once($_SERVER['DOCUMENT_ROOT'] . '/common/eamslist.php');
?>