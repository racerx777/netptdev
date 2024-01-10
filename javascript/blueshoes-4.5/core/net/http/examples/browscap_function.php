<?php
//require dependencies
$GLOBAL_CONF_TINY = TRUE;
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
if (!isSet($GLOBALS['bsSession'])) {
  $APP['sess']['maxStandbyTime'] = 60; //minutes
	$APP['sess']['path']           = $_SERVER['DOCUMENT_ROOT']  . '../session/';
	require_once($APP['path']['core'] . 'net/http/session/Bs_SimpleSession.class.php');
	$GLOBALS['bsSession'] =& $GLOBALS['Bs_SimpleSession'];
	$GLOBALS['bsSession']->start();
}

bsSessionBrowscap();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head><title>BlueShoes Browscap Example</title></head>
<body>

<a href="<?php echo $_SERVER['PHP_SELF'];?>">link to this page.</a><br>
If you click this link (again and again) you can see that the redirection is not done again 
since the session already exists.
<br><br><hr><br>

<?php
dump($GLOBALS['Bs_Browscap']->data);
?>

</body>
</html>
