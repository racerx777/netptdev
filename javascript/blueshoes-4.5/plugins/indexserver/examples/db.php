<?php
/**
* @package    plugins_indexserver
* @subpackage examples
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
//require_once($APP['path']['core'] . 'util/Bs_System.class.php');
require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_IndexServer.class.php');


set_time_limit(600);


$is =& new Bs_IndexServer();
//$is->profileDir = 'C:/cvs/bs-4.0/core/Plugins/IndexServer/';
//$profile  = &$is->getProfile('chartComment');

/*
$xmlStr = '
<blueshoes>
  <bs:index>
    
    <bs:source>
      <bs:type>db</bs:type>
      <bs:dsn>-default-</bs:dsn>
      <bs:database>-default-</bs:database>
      <bs:table>ChartComment</bs:table>
    </bs:source>
    
    <bs:minLength>3</bs:minLength>
    <bs:maxLength>30</bs:maxLength>
    
    <bs:fields>
      <bs:field name="ID">
        <bs:index>false</bs:index>
        <bs:weight>0</bs:weight>
      </bs:field>
      <bs:field name="textEn">
        <bs:index>true</bs:index>
        <bs:weight>60</bs:weight>
        <bs:lang>en</bs:lang>
      </bs:field>
      <bs:field name="textDe">
        <bs:index>true</bs:index>
        <bs:weight>60</bs:weight>
        <bs:lang>de</bs:lang>
      </bs:field>
    </bs:fields>
  
  </bs:index>
</blueshoes>';
$profile =& new Bs_Is_Profile();
dump($profile->drop('foo'));
dump($profile->create('foo', $xmlStr)); exit;
*/

/*
$indexer  = &$is->getIndexer('foo');
if (isEx($indexer)) {
	$indexer->stackDump($indexer, 'die');
}
$indexer->debug = TRUE;
for ($i=20; $i<21; $i++) {
	dump($i);
	dump($indexer->indexRecord($i));
	dump($indexer->debugOut);
	$indexer->debugOut = '';
}
*/

/*
$searcher = &$is->getSearcher('foo');
dump($searcher->search('britney'));
*/

//$data = "hello world foo bar";
$data = array(
	'1' => array(
		'foo'    => "hello world", 
		'bar'    => "tom jones", 
		'hello'  => "very cool", 
		'lalala' => "foo + 13 * bar / hello = world;", 
		'file'   => "c:/indexTest.txt", 
		'db'     => 1, 
		'url'    => 'http://www.blueshoes.org/', //'c:/del.html', 
	),
	'2' => array(
		'foo'    => "hello world", 
		'bar'    => "ballade tom jones", 
		'hello'  => "very cool ballade", 
		'lalala' => "foo + 13 * bar / hello = world;", 
		'file'   => "c:/schoggi.txt", 
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
);

///*
$indexer  = &$is->getIndexer('foo');
if (isEx($indexer)) {
	$indexer->stackDump('die');
}
$indexer->debug = TRUE;
foreach($data as $key => $val) {
	dump($indexer->index($key, $val, $props));
}
//dump($indexer->debugOut);
//*/


$searcher = &$is->getSearcher('foo');
//echo $searcher->showWordInfo('archives');
//echo $searcher->listRightNeighbors(13910);
dump($searcher->search('blueshoes'));
//dump($searcher->search('"Archives of EMULE"'));

?>