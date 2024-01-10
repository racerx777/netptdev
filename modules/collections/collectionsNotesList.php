<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/session.php');
securitylevel(16);
function my_wordwrap($str, $width=75, $break="<br>/n", $cut=true) {
	$fullstr=trim($str);
	$linearray=array();
	while(strlen($fullstr) > 0) {
		$fullstrlen = strlen($fullstr);
		if(strlen($fullstr) > $width) {
			$mystr=substr($fullstr, 0, $width);
			$lastpos = strrpos( $mystr , " ");
			if($lastpos > 0) 
				$line=trim(substr($mystr, 0, $lastpos));
			else
				$line=trim(substr($mystr, 0, $width-1));
			$linestrlen = strlen($line);
			$linearray[]=$line;
			$newstr = substr($fullstr, $linestrlen+1, ($fullstrlen-$linestrlen) );
			$fullstr=trim($newstr);
		}
		else {
			$linearray[]=trim($fullstr);
			unset($fullstr);
		}
	}
	return implode($break, $linearray);
}

$notehtmlrows=array();
$notenumrows=0;
if(empty($notelimit)) {
	if(empty($_POST['notelimit']))
		unset($limit);
	else
		$limit="LIMIT ".$_POST['notelimit'];
}
else
	$limit="LIMIT ".$notelimit;

if(empty($notewidth)) {
	if(empty($_POST['notewidth']))
		$notewidth="50";
	else
		$notewidth=$_POST['notewidth'];
}

if(empty($likenotebutton)) {
	if(empty($_POST['likenotebutton']))
		unset($likenotebutton);
	else
		$likenotebutton=$_POST['likenotebutton'];
}
if(!empty($likenotebutton)) {
	$likenotebutton = " AND nobutton LIKE '%$likenotebutton%'";
}
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/mysql/wsptn_db.php');
$dbhandle = dbconnect();

if(!empty($noid)) {
	$notequery = "
		SELECT * 
		FROM notes
		WHERE noid='$noid' $likenotebutton
		ORDER BY crtdate desc
		$limit
	";
}
if(!empty($app) && !empty($appid)) {
	$notequery = "
		SELECT * 
		FROM notes
		WHERE noapp='$app' and noappid='$appid' $likenotebutton
		ORDER BY crtdate desc
		$limit
	";
}
if(!empty($bnum) && !empty($pnum)) {
	$notequery = "
		SELECT * 
		FROM notes
		WHERE nobnum='$bnum' and nopnum='$pnum' $likenotebutton
		ORDER BY crtdate desc
		$limit
	";
}
if(!empty($notequery)) {
	if($noteresult = mysqli_query($dbhandle,$notequery)) {
		$notenumrows=mysqli_num_rows($noteresult);
		if($notenumrows > 0) {
			$notehtmlrows[]="<tr><th>Date Added</th><th>Button</th><th>Notes</th><th>Added by User</th></tr>";
			while($noterow=mysqli_fetch_assoc($noteresult)) {
				$notedate=displayDate($noterow['crtdate']) . " " . displayTime($noterow['crtdate']);
				$notebutton=$noterow['nobutton'];
				$notedescription=my_wordwrap(strtoupper($noterow['nonote']),$notewidth,"<br>\n", true);
				$noteuser=strtoupper($noterow['crtuser']);
				$notehtmlrows[]='<tr><td valign="top" nowrap="nowrap">'.$notedate.'</td><td valign="top" nowrap="nowrap">'.$notebutton.'</td><td valign="top" width="99%">'.$notedescription.'</td><td valign="top" nowrap="nowrap">'.$noteuser.'</td><td valign="top">'.$functions.'</td></tr>';
			}
		}
		if(empty($notehidecount)) {
			if($notenumrows == 0)
				$notehtmlrows[]="<tr><th colspan='4'>No notes found.</th></tr>";
			if($notenumrows == 1)
				$notehtmlrows[]="<tr><th colspan='4'>1 note found.</th></tr>";
			if($notenumrows > 1)
				$notehtmlrows[]="<tr><th colspan='4'>$notenumrows notes found.</th></tr>";
		}
		else
			$notehtmlrows[]="<tr><th colspan='4'>last $notelimit notes shown.</th></tr>";
	}
	else 
		error("001","collectionsNotes: SELECT Error. $notequery<br>".mysqli_error($dbhandle));
	mysqli_close($dbhandle);
}
else
	error("002","Missing required identifier. (noid:$noid) (app:$app & appid:$appid) (bnum:$bnum & pnum:$pnum) button:$button notequery:$notequery");

if(count($notehtmlrows) > 0) 
	$notehtml=implode("", $notehtmlrows);
$notehtml='<table width="100%">'.$notehtml.'</table>';
displaysitemessages();
// output a table with notes listed
echo($notehtml);
?>