<?php
# include the configuration file.
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['core'] . 'net/http/session/Bs_SimpleSession.class.php');
  
  ################################################################################
  # Start of the session
  $session =& $GLOBALS['Bs_SimpleSession']; // Use the per ref operator '=&' <-- !!
  
  ################################################################################
  # Sample 1
  # --------
  # Simple use. (No parallel sessions and using default property settings).
  //$session->unregister('my_var');
  $aVariable=0;
  $ok = $session->register('my_var', $aVariable);
	$output = '';
  if (!$ok) XR_dump($session->getErrors(), __LINE__, '', __FILE__);
  $output .= "Simple Counter: <B>" . $aVariable++ . "</B><br>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Example 1 of Bs_SimpleSession</title>
</head>

<body>
<H2>Bs_SimpleSession Sample 1</H2>

<hr>
<?php
  echo $output;
?>
<br><br>

<a href="#" onClick="javascript:var block=document.getElementById('sessProps'); block.style.visibility='hidden'">Hide Session Properties</a>
<div id="sessProps" style="visibility : visible;">
<a name="x" href="x"></a>
<?php
  echo $session->toHtml();
?>
</div>

</body>
</html>
