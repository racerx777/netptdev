<?php
$_SESSION['dump']=1;

function dumpsession() {
	dump("SESSION:", $_SESSION);
}

function dumppost() {
	dump("POST:", $_POST);
}
function dump($title, $var) {
	if($_SESSION['dump']==1) {
		echo("<h1>$title</h1>");
		echo("<pre>");
			var_dump($var);
		echo("</pre>");
	}
}
function dumpcode($title, $var) {
	if($_SESSION['dump']==1) {
		echo("<h1>$title</h1>");
		var_dump($var);
	}
}
?>