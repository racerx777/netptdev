<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(15); 
$script = 'editFormPatientSelect';
$table = 'patients';
$keyfield = 'paid';
echo "select patient form";
?>