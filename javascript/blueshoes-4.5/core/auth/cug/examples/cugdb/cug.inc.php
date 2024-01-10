<?php
/**
* @package    core_auth
* @subpackage examples
*/

require_once($_SERVER["DOCUMENT_ROOT"]   . "../global.conf.php");
require_once($APP['path']['core']        . 'auth/cug/Bs_CugDb.class.php');
require_once($APP['path']['core']        . 'db/Bs_Db.class.php');
$cug =& new Bs_CugDb('myExampleCug');
$cug->language      = 'en';
$cug->userDbName    = 'test';
$cug->userTableName = 'ExampleCug';
$cug->logDbName     = 'test';
$cug->logTableName  = 'ExampleCugLoginLog';
$cug->formTarget    = '_self';

$dsn = array(
	'name'=>'test', 'host'=>'localhost', 'port'=>'3306', 'socket'=>'', 
	'user'=>'root', 'pass'=>'', 'syntax'=>'mysql', 'type'=>'mysql'
);
if (isEx($bsDb =& getDbObject($dsn))) $dbAgent->stackDump('die');
$cug->setDbObject($bsDb);

if (isSet($_GET['logout'])) {
  $cug->logout();
  $outMsg = "<font color='green'><b>You have logged out successfully.</b></font>";
}

if (isSet($_GET['logout']) || !$cug->letMeIn()) {
  $loginForm = $cug->treatLoginForm();
  if ($loginForm === TRUE) {
    //oki doki, logged in successfully, register some vars.
		//getFieldValue returns a vector with exactly one element.
    $t        = $cug->form->getFieldValue('username');
    $username = $t[0];
    $cug->bsSession->register('user', $username);
    redirect('http://developer.blueshoes.org/index.php'); //script ends here.
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>BlueShoes - Closed User Group</title>
  <link href="http://www.blueshoes.org/default.css" rel="stylesheet">
</head>

<body leftmargin="0" topmargin="0">

<table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%'>
  <tr><td bgcolor='#212B43' height='20'>&nbsp;</td></tr>
  <tr><td bgcolor='white' height='1'></td></tr>
  <tr><td bgcolor='#2E3C5E' height='50'>&nbsp;</td></tr>
  <tr><td bgcolor='white' height='1'></td></tr>
  <tr>
    <td align="center" valign="middle">
      <table width='350'><tr><td>
        <h2>closed user group</h2>
        <?php
        if (isSet($outMsg)) {
          echo $outMsg . "<br><br>";
        }
				echo $loginForm;
        ?>
      </td></tr></table>
    </td>
  </tr>
  <tr><td bgcolor='white' height='1'></td></tr>
  <tr><td bgcolor='#2E3C5E' height='50'>&nbsp;</td></tr>
  <tr><td bgcolor='white' height='1'></td></tr>
  <tr><td bgcolor='#212B43' height='50'>&nbsp;</td></tr>
</table>

</body>
</html>
<?php
  exit;
}
?>