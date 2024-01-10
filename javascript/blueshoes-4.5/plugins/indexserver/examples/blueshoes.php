<?php
/**
* @package    plugins_indexserver
* @subpackage examples
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['applications'] . 'websearchengine/Bs_Wse_WebSearchEngine.class.php');


set_time_limit(600);

$profileName = 'blueshoes';

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
//$wseProfile->drop($profileName);
$status = $wseProfile->load($profileName);
if (isEx($status)) {
	//most probably: tables don't exist yet.
	$wseProfile->create($profileName, $wseXml='', $isXml='');
}
$status = $wseProfile->load($profileName);
if (isEx($status)) {
	$status->stackDump('die');
}

$wse =& new Bs_Wse_WebSearchEngine();
$wse->setProfile($wseProfile);
$walker = &$wse->getWalker($profileName);

die('here');

dump(index($wse, $walker, $wseProfile));

echo $wse->search('andrej');


function index(&$wse, &$walker, &$profile) {
	$profile->limitDomains = array('www.blueshoes.vv', 'developer.blueshoes.v');
	$profile->ignoreUrls = array(
		array('value'=>'phoneBook.php',  'type'=>'file'), 
	);
	//$profile->allowUrls = array(
	//	array('value'=>'showinterpret.asp',  'type'=>'file'), 
	//);
	$profile->weightProperties = array(
		'domain'      => array('weight' => 50), 
		'path'        => array('weight' => 100), 
		'file'        => array('weight' => 100), 
		'queryString' => array('weight' => 80), 
		'title'       => array('weight' => 100), 
		'description' => array('weight' => 60), 
		'keywords'    => array('weight' => 20), 
		'links'       => array('weight' => 100), 
		'h1'          => array('weight' => 80), 
		'h2'          => array('weight' => 70), 
		'h3'          => array('weight' => 60), 
		'h4'          => array('weight' => 50), 
		'h5'          => array('weight' => 40), 
		'h6'          => array('weight' => 30), 
		'h7'          => array('weight' => 20), 
		'h8'          => array('weight' => 10), 
		'b'           => array('weight' => 10), 
		'i'           => array('weight' => 8), 
		'u'           => array('weight' => 8), 
		'body'        => array('weight' => 5), 
	);
	dump($walker->index('http://www.blueshoes.ws', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}


?>