<?php
/**
* @package    plugins_indexserver
* @subpackage examples
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['plugins']   . 'indexserver/Bs_Is_IndexServer.class.php');

set_time_limit(600);



$data = array(
	'1' => array(
		'foo'    => "hello world", 
		'bar'    => "tom jones", 
		'hello'  => "very cool", 
		'lalala' => "foo + 13 * bar / hello = world;", 
		'file'   => "schätzung.doc", 
		'db'     => 1, 
		'url'    => 'http://www.blueshoes.org/', //'c:/del.html', 
	),
	'2' => array(
		'foo'    => "hello world", 
		'bar'    => "ballade tom jones", 
		'hello'  => "very cool ballade", 
		'lalala' => "foo + 13 * bar / hello = world;", 
		'file'   => "sm.pdf", 
		'db'     => 2, 
		'url'    => '', 
	),
	'3' => array(
		'foo'    => "excelsius", 
		'bar'    => "ex cel", 
		'hello'  => "excellent", 
		'lalala' => "excelsior", 
		'file'   => "excel.xls", 
		'db'     => 2, 
		'url'    => '', 
	),
);

$props = array(
	'foo' => array(
		'type'         => 'text', 
		'weight'       => 100, 
		'lang'         => 'en', 
	),
	'bar' => array(
		'type'         => 'text', 
		'weight'       => 60, 
	),
	'hello' => array(
		'type'         => 'text', 
		'weight'       => 80, 
		'lang'         => 'en', 
	),
	'lalala' => array(
		'type'         => 'text', 
		'weight'       => 10, 
		'lang'         => 'en', 
	),
	'file' => array(
		'type'         => 'path', 
    'pathPrefix'   => 'C:/del/', 
		'weight'       => 100, 
		'lang'         => 'en', 
		'path'         => array(
			'path'         => array(
				'weight'       => 80, 
			),
			'file'         => array(
				'index'        => FALSE,
				'weight'       => 100,
			),
			'content'      => array(
				'weight'       => 60,
			),
		),
	),
  /*
	'db' => array(
		'index'      => FALSE, 
		'type'       => 'key', 
		'weight'     => 100, 
		'lang'       => 'en', 
		'key'        => array(
			//'dsn'      => 
			//'db'       => 
			'table'      => 'chartcomment', 
			'field'      => 'ID', 
			//'fieldSet' => 
		),
	),
  */
  /*
	'url' => array(
		'type'         => 'url', 
		'weight'       => 100, 
		'lang'         => 'en', 
		'url'          => array(
			'path'         => array(
				'weight'       => 80, 
			),
			'file'         => array(
				'weight'       => 100,
			),
			'content'      => array(
				'weight'       => 100,
			),
		),
	),
  */
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
$profileName      = 'dbExample';

$out = '';

if (@$_REQUEST['reindex'] == 1) {
	$out .= "<h2>creating profile...</h2>\n";
	
	$profile =& new Bs_Is_Profile();
	$status = $profile->setDbByDsn($dsn);
	if (isEx($status)) {
		$status->stackDump('die');
	}
	$profile->drop($profileName);
	$profile->create($profileName, $profileXml='');
	$profile->load($profileName);
  
	$is =& new Bs_Is_IndexServer();
	$is->setProfile($profile);
	
	$indexer  = &$is->getIndexer($profileName);
	if (isEx($indexer)) {
		$indexer->stackDump($indexer, 'die');
	}
  
	$out .= "<h2>indexing ...</h2>\n";
  foreach ($data as $key => $val) {
  	dump($indexer->index($key, $val, $props));
  }
} else {
	$profile =& new Bs_Is_Profile();
	$status = $profile->setDbByDsn($dsn);
	if (isEx($status)) {
		$status->stackDump('die');
	}
	$profile->load($profileName);
	
	$is =& new Bs_Is_IndexServer();
	$is->setProfile($profile);
}


if (!empty($_REQUEST['query'])) {
	$out = "<h2>performing search...</h2>\n";
	$searcher = &$is->getSearcher($profileName);
	$searchResult = $searcher->search($_REQUEST['query']);
	$x = $searcher->recommendWords();
	dump($x);
	$out .= "<b>Number of matches: " . sizeOf($searchResult) . "</b><br><br>\n";
  dump($searchResult);
	foreach ($searchResult as $ID => $points) {
	}
}

echo $out;

?>

<br><br><br>
DONE!