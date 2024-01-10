<?php
/**
* @package    plugins_indexserver
* @subpackage examples_lyrics
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
//require_once($APP['path']['plugins']   . 'indexserver/Bs_Is_IndexServer.class.php');
require_once($APP['path']['core']   . 'db/Bs_Db.class.php');

set_time_limit(600);

$dsn = array(
	'name'   => 'test', 
	'host'   => 'localhost', 
	'port'   => '3306', 
	'user'   => 'root', 
	'pass'   => '', 
	'syntax' => 'mysql', 
	'type'   => 'mysql', 
);
$bsDb = &getDbObject($dsn);

$sql = "DROP TABLE IF EXISTS exampleLyrics";
$bsDb->write($sql);
$sql = "
	CREATE TABLE IF NOT EXISTS exampleLyrics (
		ID       INT NOT NULL DEFAULT 0 AUTO_INCREMENT, 
		artist   VARCHAR(100) NOT NULL DEFAULT '', 
		title    VARCHAR(100) NOT NULL DEFAULT '', 
		lyric    BLOB NOT NULL DEFAULT '', 
		PRIMARY KEY ID (ID), 
	)
";
$bsDb->write($sql);


$conn = new COM("ADODB.Connection") or die("Cannot start ADO");
//$conn->Open("Provider=Microsoft,.Jet.OLEDB.4.0; Data Source=c:\\media\\music\\lyrics.mdb");
$conn->Open("DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=c:\\media\\music\\lyrics.mdb");
$rs = $conn->Execute("Select Artist, Title, Lyric FROM lyrics");
while (!$rs->EOF) {
	//echo $rs->Fields("Artist") . '<br>';
	$aObj   = $rs->Fields("Artist");
	$artist = $aObj->value;
	$tObj   = $rs->Fields("Title");
	$title  = $tObj->value;
	$lObj   = $rs->Fields("Lyric");
	$lyric  = $lObj->value;
	$sql = "INSERT INTO exampleLyrics (artist, title, lyric) VALUES ('" . $bsDb->escapeString($artist) . "', '" . $bsDb->escapeString($title) . "', '" . $bsDb->escapeString($lyric) . "')";
	$bsDb->write($sql);
	$rs->MoveNext();
}
$rs->Close();
?>

<h1>done!</h1>