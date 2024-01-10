<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(11); 
if(empty($_REQUEST['crid'])) {
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


$crid=$_REQUEST['crid'];
$button=$_REQUEST['button'];

if(empty($crid)) 
	error('999', "Required parameter missing. (crid:$crid)<br>".mysqli_error($dbhandle));

if(errorcount()==0) {
	$notehtmlrows=array();
	$notenumrows=0;
	unset($notequery);
	$notequery = "
		SELECT * 
		FROM cases
		LEFT JOIN case_prescriptions
		ON crid=cpcrid
		LEFT JOIN case_prescriptions_history
		ON cpid=cphcpid
		LEFT JOIN patients
		ON crpaid=paid
		WHERE crid='$crid'
		ORDER BY cphdate desc
		";
	if(!empty($notequery)) {
		if($noteresult = mysqli_query($dbhandle,$notequery)) {
			$notenumrows=mysqli_num_rows($noteresult);
			if($notenumrows > 0) {
				$notehtmlrows[]="<tr><th>Date Added</th><th>Rx Date</th><th>Notes</th><th>Added by User</th></tr>";
				while($noterow=mysqli_fetch_assoc($noteresult)) {
					$notedate=displayDate($noterow['cphdate']) . " " . displayTime($noterow['cphdate']);
					$notebutton=$noterow['cpdate'];
					$notedescription=strtoupper($noterow['cphhistory']);
					$noteuser=strtoupper($noterow['cphuser']);
					$notehtmlrows[]="<tr><td>$notedate</td><td>$notebutton</td><td>$notedescription</td><td>$noteuser</td></tr>";
				}
			}
			if(empty($notehidecount)) {
				if($notenumrows == 0)
					$notehtmlrows[]="<tr><th colspan='3'>No notes found.</th></tr>";
				if($notenumrows == 1)
					$notehtmlrows[]="<tr><th colspan='3'>1 note found.</th></tr>";
				if($notenumrows > 1)
					$notehtmlrows[]="<tr><th colspan='3'>$notenumrows notes found.</th></tr>";
			}
			else
				$notehtmlrows[]="<tr><th colspan='3'>last $notelimit notes shown.</th></tr>";
		}
		else 
			error("001","collectionsNotes: SELECT Error. $notequery<br>".mysqli_error($dbhandle));
		mysqli_close($dbhandle);
	}
	else
		error("002","Missing required identifier. notequery:$notequery");
$pnum=$noterow['crpnum'];
$lname=$noterow['crlname'];
$fname=$noterow['crfname'];

	if(count($notehtmlrows) > 0) 
		$notehtml=implode("", $notehtmlrows);
?>

<div class="centerFieldset" style="margin-top:50px;">
  <form action="" method="post" name="editForm">
    <input type="hidden" name="crid" value="<?php echo $_REQUEST['crid']; ?>">
    <fieldset style="text-align:center;">
      <legend>Cases - Notes Detail Patient #<?php echo "$pnum:$fname $lname $crid"; ?> </legend>
      <table width="100%" style="text-align:left;">
        <tr>
        <td colspan="4" align="center"><input name="Exit" type="button" value="Exit" onclick="javascript:window.close()" /></td>
        </tr>
          <?php echo $notehtml; ?>
        <tr>
        <td colspan="4" align="center"><input name="Exit" type="button" value="Exit" onclick="javascript:window.close()" /></td>
        </tr>      </table>
    </fieldset>
  </form>
</div>
<?php
}
//unset($_SESSION['button']);
?>
