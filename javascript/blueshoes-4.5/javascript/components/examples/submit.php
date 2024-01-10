<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
//include_once($APP['path']['core'] . 'util/Bs_Array.class.php');
//echo $Bs_Array->arrayToText($_POST, $maxWidth=75, $stringSeparator=': ', $struct2=FALSE);
$postDump = dump($_POST, TRUE);

require_once($APP['path']['core'] . 'net/email/Bs_Smtp.class.php');
$smtp =& new Bs_Smtp();
$smtp->host = $APP['smtp']['host'];
$smtp->subject = "complex frontend example; feedback";
$smtp->message = $postDump;
$smtp->addFrom('andrej@blueshoes.org', "andrej");
$smtp->addTo($mailTo='andrej@blueshoes.org');
$status = $smtp->send();
if (isEx($status)) {
	echo "<font color=red>Transmission to our email address {$mailTo} failed!</font>";
} else {
	echo "<font color=green>Thank youf or your feedback.</font>";
}
?>