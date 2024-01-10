<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
if( empty($_POST['adjuster']) && empty($_REQUEST['adjuster']) ) {
	echo "ERROR MISSING Adjuster";
	dump("REQUEST",$_REQUEST);
	dumppost();
	dump("SESSION-navigation",$_SESSION['navigation']);
	dump("SESSION-navigationid",$_SESSION['navigationid']);
	dump("SESSION-button",$_SESSION['button']);
	dump("SESSION-id",$_SESSION['id']);
	exit();
}

if(isset($_POST['adjuster']))
	$adjuster=$_POST['adjuster'];
else {
	if(isset($_REQUEST['adjuster']))
		$adjuster=$_REQUEST['adjuster'];
}

if( empty($adjuster) ) {
		error('999', "Required parameter missing. (adjuster:$adjuster)<br>".mysqli_error($dbhandle));
		exit();
}
?>
<style type="text/css">
.subtablesection {
	background-color:#DDD;
}
.readonlytext {
	background-color: #DDD;
}
th {
	text-align: left;
}
</style>
<div class="centerFieldset" style="margin-top:50px;">
  <form action="" method="post" name="listForm">
    <input type="hidden" name="adjuster" value="<?php echo $adjuster; ?>">
    <fieldset style="text-align:center;">
      <legend>Collections - Account List for Adjuster <?php echo $adjuster; ?></legend>
		<table class="subtablesection" cellpadding="5" cellspacing="1">
			<tr>
				<th colspan="8">Adjuster Account List</th>
			</tr>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

// Get PTOS_Patients record
unset($query);
$numrows=0;
$query = "
	SELECT *
	FROM collection_accounts
	LEFT JOIN PTOS_Patients
	ON cabnum=bnum and capnum=pnum
	WHERE caadjuster1='$adjuster' or caadjuster2='$adjuster' or padjust='$adjuster' or sadjust= '$adjuster'
	ORDER BY caadjuster1, caadjuster2, padjust, sadjust, cainsname1, cainsname2, pinsurance, sinsurance, tbal
";
//dump("query",$query);
if($result = mysqli_query($dbhandle,$query)) {
	$numrows=mysqli_num_rows($result);
	if($numrows == 0) { ?>
		<tr>
		<th colspan="8">
		No Accounts Found!
		</th>
		</tr>
<?php
	}
	else { ?>
<tr>
<th nowrap="nowrap" >PRI ADJ(INS)</th>
<th nowrap="nowrap" >SEC ADJ(INS)</th>
<th nowrap="nowrap" >BU</th>
<th nowrap="nowrap" >CLINIC</th>
<th nowrap="nowrap" >PATIENT NUMBER</th>
<th nowrap="nowrap" >NAME</th>
<th nowrap="nowrap" >BALANCE</th>
<th nowrap="nowrap" >FIRST VISIT</th>
<th nowrap="nowrap" >LAST VISIT</th>
</tr>
<?php
		while($row=mysqli_fetch_assoc($result)) {
// Display Output
			$default="";
			$insname1=$default;
			$adjuster1=$default;
			$insname2=$default;
			$adjuster2=$default;

			if( !empty($row['pinsurance']) ) {
				$insname1=$row['pinsurance'];
				$adjuster1=$row['padjust'];
			}
			if( !empty($row['cainsname1']) ) {
				$insname1=$row['cainsname1'];
				$adjuster1=$row['caadjuster1'];
			}
			if( !empty($row['sinsurance']) ) {
				$insname2=$row['sinsurance'];
				$adjuster2=$row['sadjust'];
			}
			if( !empty($row['cainsname2']) ) {
				$insname2=$row['cainsname2'];
				$adjuster2=$row['caadjuster2'];
			}

			if(!empty($insname1))
				$insname1="($insname1)";
			if(!empty($insname2))
				$insname2="($insname2)";
			$bnum = $row['cabnum'];
			$cnum = $row['cacnum'];
			$pnum = $row['capnum'];
			$name = $row['lname'] . ", " . $row['fname'];
			$tbal = $row['tbal'];
			$fvisit = displayDate($row['fvisit']);
			$lvisit = displayDate($row['lvisit']);
?>
<tr>
	<td nowrap="nowrap" width="15%"><?php echo("$adjuster1 $insname1"); ?></td>
	<td nowrap="nowrap" width="15%"><?php echo("$adjuster2 $insname2"); ?></td>
	<td nowrap="nowrap" width="15%"><?php echo($bnum); ?></td>
	<td nowrap="nowrap" width="15%"><?php echo($cnum); ?></td>
	<td nowrap="nowrap" width="15%"><?php echo($pnum); ?></td>
	<td nowrap="nowrap" width="15%"><?php echo($name); ?></td>
	<td nowrap="nowrap" width="15%"><?php echo($tbal); ?></td>
	<td nowrap="nowrap" width="15%"><?php echo($fvisit); ?></td>
	<td nowrap="nowrap" width="15%"><?php echo($lvisit); ?></td>
</tr>
<?php
		} // End While
	} // End Else
}
else
	error("001","collectionsAjusterAccountList: SELECT Error. $query<br>".mysqli_error($dbhandle));
?>
		<tr><th colspan="8">
		END OF LIST - <?php echo($numrows); ?> records listed</th>
        </tr>
      </table>
    </fieldset>
  </form>
</div>