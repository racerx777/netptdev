<?php
$instructions=array();
$_SESSION['headerspace']="";

if(isset($instructions[$_SESSION['button']]))
	$_SESSION['headerspace'] = $instructions[$_SESSION['button']];
else
	$_SESSION['headerspace'] = $instructions['default'];

$_SESSION['init']['ar']=1;
?>