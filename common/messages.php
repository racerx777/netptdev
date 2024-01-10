<?php
function displayerror() {
// Output Error Messages here.
	if( isset($_SESSION['error']) && count($_SESSION['error']) > 0) {
		echo('<fieldset style="background-color:#DC143C; color:#FFFFFF;">');
		echo('<legend style="background-color:#000000; color:#FFFFFF;">Error Message Notifications</legend>');
		foreach($_SESSION['error'] as $num=>$msg)
			echo("<div id=err" . $num . "> $msg </div>");
		echo("</fieldset>");
		$_SESSION['error'] = array();
	}
}

function displayinfo() {
	// Output informational Messages here.
	if(isset($_SESSION['info']) && count($_SESSION['info']) > 0) {
		echo('<fieldset style="background-color:#4682B4; color:#FFFFFF;">');
		echo('<legend style="background-color:#000000; color:#FFFFFF;">Notification Messages</legend>');
		foreach($_SESSION['info'] as $num=>$msg)
			echo('<div id="info"' . $num . "> $msg </div>");
		echo("</fieldset>");
		$_SESSION['info'] = array();
	}
}

function displaynotify() {
	// Output Notification Messages here.
	if( isset($_SESSION['notify']) && count($_SESSION['notify']) > 0) {
		echo('<fieldset style="background-color:#4682B4; color:#FFFFFF;">');
		echo('<legend style="background-color:#000000; color:#FFFFFF;">Notification Messages</legend>');
		foreach($_SESSION['notify'] as $num=>$msg)
			echo('<div id="notify"' . $num . "> $msg </div>");
		echo("</fieldset>");
		$_SESSION['notify'] = array();
	}
}

function displaymessages() {
	displayinfo();
	displaynotify();
	displayerror();
}
?>