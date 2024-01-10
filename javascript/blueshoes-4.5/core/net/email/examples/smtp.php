<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['core'] . 'net/email/Bs_Smtp.class.php');

$mail =& new Bs_Smtp();
$mail->host = 'mail.digitalsolutions.ch';
$mail->addFrom('foo@blueshoes.org', 'foo');
$mail->addTo('andrej@blueshoes.org', 'andrej');
$mail->subject = 'test mail from core/net/email/examples/smtp.php';
$mail->message = 'foo';
$status = $mail->send();
if ($status[0] === 'all') {
	echo '<font color="green"><b>Thank you for your message.</b></font><br><br>';
} else {
	echo '<font color="red"><b>Sending message failed.</b></font><br><br>';
	if (isEx($status)) $status->stackDump('echo');
}
?>