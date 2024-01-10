<?php
# include the configuration file.
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['core'] . 'net/http/session/Bs_SimpleSession.class.php');
  
  ################################################################################
  # Start of the session
  $session =& $GLOBALS['Bs_SimpleSession']; // Use the per ref operator '=&' <-- !!
  
  ################################################################################
  # Sample 3
  # --------
  # Chage some properties
  $prop = array (
     'path'           => 'c:/',
     'maxStandbyTime' => 0.2, // <- How long should the session stay valid (in min) if not used. 0 means until browser end.
     'maxLifeTime'    => 0.5  // <- Independent of use, invalidat after this time (im min).  0 means until browser end.
    );
  $ok = $session->setProperty($prop, 'shortTimeSession');   // <----- Optional. Set property here if needed
  if (!$ok) XR_dump($session->getErrors(), __LINE__, '', __FILE__);
  
	$output  = '';
  $output .= "Setting Session Property to ";
  $output .= "maxLifetime: {$prop['maxLifeTime']}min, maxStandbyTime: {$prop['maxStandbyTime']}min\n";
  $output .= "<hr>";
  $aVariable=0;
  
  if (!$session->isRegistered('my_var', 'shortTimeSession')) {
    $output .= "<span style='color: Red; font-weight: bold;'>- SESSION TIMED OUT ! - </span><br>\n";
  } else {
    $output .= "<span style='color: Green; font-weight: bold;'> SESSION Running !</span><br>\n";
  }
  $ok = $session->register('my_var', $aVariable, 'shortTimeSession');
  if (!$ok) XR_dump($session->getErrors(), __LINE__, '', __FILE__);
  $output .= "Simple Counter: <B>" . $aVariable++ . "</B><br>";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Example 3 of Bs_SimpleSession</title>
</head>

<body>
<H2>Bs_SimpleSession Sample 3</H2>

<hr>
<?php
  echo $output;
?>
<br><br>

<a href="#" onClick="javascript:var block=document.getElementById('sessProps'); block.style.visibility='hidden'">Hide Session Properties</a>
<div id="sessProps" style="visibility : visible;">
<a name="x" href="x"></a>
<?php
  echo $session->toHtml('shortTimeSession');
?>
</div>
</body>
</html>
