<?php
function ungzip($source, $target, $delete=false, $verbose=false) {
	if($verbose) echo("Attempting to uncompress $source to $target...");
	unset($result);
	$message = "FAILED.";
	if($fp=fopen($target,"w")) {
		if($gz=gzopen($source,"r")) {
			while($string = gzread($gz, 16384))
				$result = fwrite($fp, $string);
			$message = "SUCCESSFUL.";
			gzclose($gz);
			if($delete) unlink($source);
		}
		fclose($fp);
	}
	if($verbose) echo($message."<br>");
	return($result);
}

$root='/home/wsptn/public_html/netpt/collections/';

$source=$root.'ws/pat1.txt.gz';
$target=$root.'ws/pat1.txt';
ungzip($source, $target,true);

$source=$root.'ws/transv.txt.gz';
$target=$root.'ws/transv.txt';
ungzip($source, $target,true);
//shell_exec('gzip -d '.$source);

$source=$root.'net/pat1.txt.gz';
$target=$root.'net/pat1.txt';
ungzip($source, $target,true);

$source=$root.'net/transv.txt.gz';
$target=$root.'net/transv.txt';
ungzip($source, $target,true);
?>