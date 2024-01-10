<?php
/**
* @package    plugins_indexserver
* @subpackage examples_lyrics
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['plugins']   . 'indexserver/Bs_Is_IndexServer.class.php');

set_time_limit(0); //6000000



$props = array(
	'artist' => array(
		'type'         => 'text', 
		'weight'       => 80, 
		'lang'         => 'en', 
	),
	'title' => array(
		'type'         => 'text', 
		'weight'       => 100, 
		'lang'         => 'en', 
	),
	'lyric' => array(
		'type'         => 'text', 
		'weight'       => 60, 
		'lang'         => 'en', 
	),
);


$dsn = array(
	'name'   => 'test', 
	'host'   => 'localhost', 
	'port'   => '3306', 
	'user'   => 'root', 
	'pass'   => '', 
	'syntax' => 'mysql', 
	'type'   => 'mysql', 
);
$profileName      = 'lyricsExample';

$out = '';
$out .= "<h2>creating profile...</h2>\n";

$profile =& new Bs_Is_Profile();
$status = $profile->setDbByDsn($dsn);
if (isEx($status)) {
	$status->stackDump('die');
}
//$profile->drop($profileName);
$status = $profile->load($profileName);
if ($status !== TRUE) {
	$profile->create($profileName, $profileXml='');
	$status = $profile->load($profileName);
}

$is =& new Bs_Is_IndexServer();
$is->setProfile($profile);

$indexer  = &$is->getIndexer($profileName);
//$indexer->doCollocations = FALSE;
if (isEx($indexer)) {
	$indexer->stackDump($indexer, 'die');
}

$out .= "<h2>indexing ...</h2>\n";

$bsDb = &getDbObject($dsn);
/*
$highestId = $bsDb->getOne("SELECT max(CONVERT(sourceID, SIGNED)) FROM Bs_Is_lyricsExample_WordToSource");
dump($highestId);
*/

$data = $bsDb->getAssoc("SELECT ID, artist, title, lyric FROM exampleLyrics LIMIT 92500, 30000", FALSE, TRUE);
foreach ($data as $key => $val) {
	dump($key);
 	dump($indexer->index($key, $val, $props));
  //dump($val);
}
$indexer->stopWatch->takeTime('userland LINE: ' . __LINE__);
echo $indexer->stopWatch->toHtml();
?>

<br><br><br>
DONE!