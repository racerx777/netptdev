<?php
/**
* @package    core_auth
* @subpackage examples
*/

include './cug.inc.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
</head>

<body>

hello <?php echo $cug->bsSession->getVar('user');?>
<br><br><br><br>

<a href="<?php echo $_SERVER['PHP_SELF'];?>?logout=1">logout</a><br>

</body>
</html>
