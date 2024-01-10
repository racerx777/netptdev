<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(30); 
session_set_cookie_params(8*60*60);
$cookie=session_get_cookie_params();
// ACTIONS
switch($_SESSION['button']):

endswitch;

// Output begins here
displaysitemessages();

switch($_SESSION['button']):

endswitch;
?>
<div style="clear:both">&nbsp;</div>