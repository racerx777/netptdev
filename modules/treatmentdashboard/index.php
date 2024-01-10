<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(20); 
// Process button press
switch($_SESSION['button']):
endswitch;
displaysitemessages();
require_once('dashboardForm.php');
?>