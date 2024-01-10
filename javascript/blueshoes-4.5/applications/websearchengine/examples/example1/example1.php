<?php
/**
* @package    applications_websearchengine
* @subpackage examples
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['applications'] . 'websearchengine/Bs_Wse_WebSearchEngine.class.php');


set_time_limit(120);

$profileName = 'wseExample1';

$dsn = array(
	'name'   => 'bs_site_www_bs_org', 
	'host'   => 'localhost', 
	'port'   => '3306', 
	'user'   => 'root', 
	'pass'   => '', 
	'syntax' => 'mysql', 
	'type'   => 'mysql', 
);

$wseProfile =& new Bs_Wse_Profile();
$status = $wseProfile->setDbByDsn($dsn);
if (isEx($status)) {
	$status->stackDump('die');
}
$wseProfile->drop($profileName);
$status = $wseProfile->load($profileName);
if (isEx($status) || ($status === FALSE)) {
	//most probably: tables don't exist yet.
	$status = $wseProfile->create($profileName, $wseXml='', $isXml='');
}
$status = $wseProfile->load($profileName);
if (isEx($status)) {
	$status->stackDump('die');
} elseif ($status === FALSE) {
	die('could not load profile.');
}

$wse =& new Bs_Wse_WebSearchEngine();
$wse->setProfile($wseProfile);
$walker = &$wse->getWalker($profileName);

dump(index($wse, $walker, $wseProfile));

$searcher = &$wse->getSearcher($profileName);
echo $searcher->search('doc');


function index(&$wse, &$walker, &$profile) {
	$profile->limitDomains = array('www.blueshoes.ws');
	$profile->ignoreUrls = array(
		array('value'=>'phoneBook.php',  'type'=>'file'), 
	);
	//$profile->allowUrls = array(
	//	array('value'=>'showinterpret.asp',  'type'=>'file'), 
	//);
	dump($walker->index('http://www.blueshoes.ws/_bsApplications/websearchengine/examples/example1/index.html', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}


?>