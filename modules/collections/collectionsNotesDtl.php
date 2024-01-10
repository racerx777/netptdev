<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16); 
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


if(empty($app))
	$app=$_REQUEST['app'];
if(empty($appid))
	$appid=$_REQUEST['appid'];
if(empty($bnum))
	$bnum=$_REQUEST['bnum'];
if(empty($pnum))
	$pnum=$_REQUEST['pnum'];
if(empty($button))
	$button=$_REQUEST['button'];

if(empty($app) || empty($appid)) {
	if(empty($bnum) || empty($pnum)) {
		error('959', "Required parameter missing. (app:$app && appid:$appid) (bnum:$bnum && pnum:$pnum)<br>".mysqli_error($dbhandle));
		exit();
	}
	else 
		$where = "bnum='$bnum' and pnum='$pnum'";
}
else 
	$where = "caid='$appid'";

if(errorcount()==0) {
?>

<div class="centerFieldset" style="margin-top:50px;">
  <form action="" method="post" name="editForm">
    <input type="hidden" name="pnum" value="<?php echo $_POST['pnum']; ?>">
    <fieldset style="text-align:center;">
      <legend>Collections - Notes Detail Patient #<?php echo "$pnum:$fname $lname"; ?> </legend>
      <table style="text-align:left;">
        <tr>
          <td align="center"><input name="Exit" type="button" value="Exit" onclick="javascript:window.close()" /></td>
        </tr>
        <tr>
          <td><?php 
				$notewidth=75;
				$notelimit=0;
				$notehidecount=0; 
				require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/collections/collectionsNotesList.php'); ?></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><input name="Exit" type="button" value="Exit" onclick="javascript:window.close()" /></td>
        </tr>
      </table>
    </fieldset>
  </form>
</div>
<?php
}
//unset($_SESSION['button']);
?>
