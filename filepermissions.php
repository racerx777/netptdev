<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Wolfcast CHMOD Scriptlet Version 1.0">
<meta name="author" content="Alexandre Valiquette (www.wolfcast.com)">
<title>Wolfcast CHMOD Scriptlet</title>
<style type="text/css">
div#logtext {
	width: 875px;
	height: 300px;
	overflow: auto;
	border: solid 1px black;
}
div#logtext p {
	white-space: nowrap;
	margin: 0px;
}
div#logtext p.success {
	color: #008000;
}
div#logtext p.error {
	color: #ff0000;
}
</style>
</head>
<body>
<p> <big><b>Wolfcast CHMOD Scriptlet</b></big><br>
	<small>Version 1.0<br>
	<a href="http://www.wolfcast.com">www.wolfcast.com</a><br>
	</small> </p>
<!-- START - PHP generated output -->

<?php
function getFiles($directory, &$exempt, &$files) {
	$handle = opendir($directory);
	while($resource = readdir($handle)) {
		$r=strtolower($resource);
		if(!in_array($r, $exempt)) {
			if(is_dir($directory.'/'.$resource)) {
				array_merge($files, getFiles($directory.'/'.$resource,$exempt,$files));
			}
			else {
				$files[] = $directory.'/'.$resource;
			}
		}
	}
	closedir($handle);
	return $files;
} 

function getfileperms($perms) {
if (($perms & 0xC000) == 0xC000) {
    // Socket
    $info = 's';
} elseif (($perms & 0xA000) == 0xA000) {
    // Symbolic Link
    $info = 'l';
} elseif (($perms & 0x8000) == 0x8000) {
    // Regular
    $info = '-';
} elseif (($perms & 0x6000) == 0x6000) {
    // Block special
    $info = 'b';
} elseif (($perms & 0x4000) == 0x4000) {
    // Directory
    $info = 'd';
} elseif (($perms & 0x2000) == 0x2000) {
    // Character special
    $info = 'c';
} elseif (($perms & 0x1000) == 0x1000) {
    // FIFO pipe
    $info = 'p';
} else {
    // Unknown
    $info = 'u';
}

// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));

// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));

// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));
$info .= "($perms)";
return($info);
}

$root=$_SERVER['DOCUMENT_ROOT']; // /home/wsptn/public_html/netpt
//$root=$root."/common";

$exemptarray=array('.','..','.ds_store','.svn','error_log');
$filearray=array();
$files=getFiles($root, $exemptarray, $filearray);

echo(count($files)." Results:<br>");
foreach($files as $index=>$filename) {
	unset($before);
	unset($after);
//	$before = getfileperms(fileperms($filename));
	chmod($filename, 0644);
//	$after = getfileperms(fileperms($filename));
//	if($before!=$after)
//		echo "$before to $after $filename<br>";
}
echo "<p style=\"margin-top: 0px\"><b>";
echo "</b></p>\r\n";

?>
<!-- END - PHP generated output -->

<p> <img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Strict" height="31" width="88"> </p>
</body>
</html>
