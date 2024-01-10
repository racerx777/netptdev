<?php
if(strtolower($_SERVER["SERVER_NAME"]) == 'netpt.wsptn.com') 
	$http = "https://" . $_SERVER["SERVER_NAME"];
else {
	if(strtolower($_SERVER["SERVER_NAME"]) == '208.57.67.221') {
		$http = "http://" . $_SERVER["SERVER_NAME"].":31433";
	}
	else
		$http = "http://" . $_SERVER["SERVER_NAME"];
}
header("Location: $http");
exit();
?>