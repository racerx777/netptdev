<?php
/**
* @package    applications_contactform
* @subpackage examples
*/


$GLOBAL_CONF_TINY = TRUE;
require_once($_SERVER["DOCUMENT_ROOT"]       . '../global.conf.php');
require_once($GLOBALS['APP']['path']['core']         . 'db/Bs_Db.class.php');
require_once($GLOBALS['APP']['path']['applications'] . 'contactform/Bs_ContactForm.class.php');


$cf =& new Bs_ContactForm();
$cf->setLanguage('en');
$dsn = array('name'=>'test', 'host'=>'localhost', 'port'=>'3306', 'socket'=>'',
             'user'=>'root', 'pass'=>'', 'syntax'=>'mysql', 'type'=>'mysql');
$cf->setSaveByDsn('foo', $dsn);
$cfOut = $cf->doItYourself();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>BlueShoes Contact Form Example 1</title>
<?php
if (is_array($cfOut)) {
  if (isSet($cfOut['onLoad'])) {
    ?>
    <script language='JavaScript' type='text/javascript'>
    <!--
    onload=function() {
    <?php
    echo $cfOut['onLoad'];
    ?>
    }
    //-->
    </script>
    <?php
  }
  if (is_array($cfOut['include'])) {
    foreach($cfOut['include'] as $dev0 => $src) {
      echo "<script language='JavaScript' src='{$src}'></script>\n";
    }
  }
  //echo $cfOut['head'];
}
?>
</head>

<body>

<h1>BlueShoes Contact Form Example 1</h1>

<?php
if (is_array($cfOut)) {
  if (isSet($cfOut['errors'])) echo $cfOut['errors'];
  echo $cfOut['form'];
} else { //TRUE
  $status = $cf->store();
  $status = $cf->emailInternal('user@domain.com');
  echo 'Thank you.';
}
?>

</body>
</html>