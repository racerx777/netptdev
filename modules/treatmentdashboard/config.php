<?php
$instructions=array();
$instructions['default']="&nbsp;";

if(isset($instructions[$_SESSION['button']]))
	$_SESSION['headerspace'] = $instructions[$_SESSION['button']];
else
	$_SESSION['headerspace'] = $instructions['default'];;

if(!isset($_POST['workingDate']) || empty($_POST['workingDate'])) 
	$_POST['workingDate'] = date('m/d/Y');

if(isset($_POST['workingDate'])) 
	$_SESSION['workingDate'] = $_POST['workingDate'];

$functions['functions'] = array('add', 'update', 'delete', 'search');
$_SESSION['module']['treatmentdashboard']=$functions;
$_SESSION['init']['dashboard']=1;
?>