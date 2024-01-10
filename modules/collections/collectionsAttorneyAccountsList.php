<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(33);
//dumppost();
if( empty($_REQUEST['attyname']) ) {
	echo "ERROR MISSING Attorney Name";
	dump("REQUEST",$_REQUEST);
	dumppost();
	dump("SESSION-navigation",$_SESSION['navigation']);
	dump("SESSION-navigationid",$_SESSION['navigationid']);
	dump("SESSION-button",$_SESSION['button']);
	dump("SESSION-id",$_SESSION['id']);
	exit();
}

if(isset($_POST['attyname']))
	$attyname=$_POST['attyname'];
else {
	if(isset($_REQUEST['attyname']))
		$attyname=$_REQUEST['attyname'];
}

if( empty($attyname) ) {
		error('999', "Required parameter missing. (attyname:$attyname)<br>".mysqli_error($dbhandle));
		exit();
}

$likeattyname = substr($attyname,0,5);

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
    <input type="hidden" name="insname" value="<?php echo $attyname; ?>">
    <fieldset style="text-align:center;">
      <legend>Collections - Account List for Attorney <?php echo $attyname; ?></legend>
		<table class="subtablesection" cellpadding="5" cellspacing="1">
			<tr>
				<th colspan="8">Attorney Account List by Attorney and Last Visit</th>
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
	WHERE (attorney='$attyname' or attorney like '%$likeattyname%') and tbal>0
	ORDER BY attorney, lvisit
";
// dump("query",$query);
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
<th nowrap="nowrap" >ATTORNEY</th>
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
			$default="UNKNOWN ERROR";
			$attorney1=$default;

			if( !empty($row['attorney']) ) {
				$attorney1=$row['attorney'];
			}

			$bnum = $row['cabnum'];
			$cnum = $row['cacnum'];
			$pnum = $row['capnum'];
			$name = $row['lname'] . ", " . $row['fname'];
			$tbal = $row['tbal'];
			$fvisit = displayDate($row['fvisit']);
			$lvisit = displayDate($row['lvisit']);
?>
<tr>
	<td nowrap="nowrap" width="15%"><?php echo($attorney1); ?></td>
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
else {
	error("001","collectionsInsuranceAccountList: SELECT Error. $query<br>".mysqli_error($dbhandle));
	displaysitemessages();
}
?>
		<tr><th colspan="8">
		END OF LIST - <?php echo($numrows); ?> records listed</th>
        </tr>
      </table>
    </fieldset>
  </form>
</div>