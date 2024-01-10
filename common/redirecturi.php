<?php
if (strtolower($_SERVER["SERVER_NAME"]) == 'netpt.wsptn.com') {
	if ($_SERVER["HTTPS"] != "on") {
		header("HTTP/1.1 301 Moved Permanently");
		header('Location: https://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
		exit();
	}
	// else would be what we want for netpt
} else {
	if (strtolower($_SERVER["SERVER_NAME"]) == 'development.wsptn.com' || strtolower($_SERVER["SERVER_NAME"]) == 'netptdev.wsptn.com') {
		if ($_SERVER["HTTPS"] == "on") {
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
			exit();
		}
		// else would be what we want for development
	} else {
		if (strtolower($_SERVER["SERVER_NAME"]) == '208.57.67.221') {
			if (isset($_SERVER['HTTPS'])) {
				if ($_SERVER["HTTPS"] == "on") {
					header("HTTP/1.1 301 Moved Permanently");
					header('Location: http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
					exit();
				}
			}
			// else would be what we want for development
		} else {
			echo ("huh? " . strtolower($_SERVER["SERVER_NAME"]));
			exit();

		}
	}
}
?>