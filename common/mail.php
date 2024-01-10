<?php
function sendSubmissionNotification($clinic) {
	if(strtolower($_SERVER['SERVER_NAME'])=='netpt.wsptn.com')
		$to = 'Nancy Villa <nancyv@apmi.net>';
	else
		$to = 'NetPT Info <netpt.errors@freshims.com>';
	$from = 'Nancy Villa <nancyv@apmi.net>';
	$subject = 'A submission has been received for clinic "' . $clinic . '" on the NetPt site ' . $_SERVER['SERVER_NAME'];
	$headers = 'From: ' . $from . '
X-Mailer: PHP/' . phpversion();
	$message = wordwrap('A submission has been received for clinic "' . $clinic . '" on the NetPt site ' . $_SERVER['SERVER_NAME'], 70);
	$error = mail($to, $subject, $message, $headers);

    //@todo remove this logging when done.
    error_log(date('Y-m-d H:i:s').": $clinic $error".PHP_EOL, 3, '/home/wsptn/public_html/netpt/email.log');

}
function sendEMailNotification($msg) {
	$to = 'Nancy Villa <nancyv@apmi.net>';
	$from = 'NetPT Info <netpt.errors@freshims.com>';
	$subject = 'A notification message has been received on the NetPt site ' . $_SERVER['SERVER_NAME'];
	$headers = 'From: ' . $from . '
X-Mailer: PHP/' . phpversion();
	$message = wordwrap($msg, 70);
	return(mail($to, $subject, $message, $headers));
}
?>