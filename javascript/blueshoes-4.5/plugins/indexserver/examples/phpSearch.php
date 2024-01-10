<?php
/**
* @package    plugins_indexserver
* @subpackage examples
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_IndexServer.class.php');
require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_WebSearchEngine.class.php');
require_once($APP['path']['core']    . 'file/Bs_File.class.php');


set_time_limit(0);

//createProfile();

$is =& new Bs_IndexServer();


$wse =& new Bs_Is_WebSearchEngine();
$wse->init('phpSearch');
$wse->waitAfterIndex = 1;
//dump($wse->prune());
index($wse);

echo $wse->search('andrej');


function index(&$wse) {
	bs_registerShutdownMethod(__LINE__, __FILE__, $wse, 'persistTodoStack');
	
	//$wse->queryStringUrlLimit = 100;
	$wse->limitDomains = array(
		'www.php.net', 
		/*
		'www.zend.com', 
		'www.phpbuilder.com', 
		'www.phpdeveloper.org', 
		'www.devnetwork.net', 
		'www.evilwalrus.com', 
		'www.phpfreaks.com', 
		'www.phpcomplete.com', 
		'www.phpclasses.org', 
		'www.phpadvisory.com', 
		'www.phpmac.com', 
		'www.php-resource.de', 
		'www.phpwelt.de', 
		'www.phparchiv.de', 
		'www.phpfinder.de', 
		//'p2p.wrox.com/php/', 
		*/
	);
	$wse->ignoreUrls = array(
		array('value'=>'go_link.php',  'type'=>'file'), 
		array('value'=>'click.php3',   'type'=>'file'), 
		array('value'=>'click.php',    'type'=>'file'), 
		array('value'=>'link.php',     'type'=>'file'), 
		array('value'=>'add_user.php', 'type'=>'file'), 
		array('value'=>'www.php.net/source.php', 'type'=>'part'), 
		array('value'=>'www.php.net/search.php', 'type'=>'part'), 
		array('value'=>'www.php.net/get_download.php', 'type'=>'part'), 
		array('value'=>'www.php.net/do_download.php',  'type'=>'part'), 
		array('value'=>'/print/', 'type'=>'part'), 
		array('value'=>'www.php.net/manual/add-note.php', 'type'=>'part'), 
		array('value'=>'/manual/add-note.php',            'type'=>'part'), 
	);
	$wse->allowUrls = array(
		array('value'=>'www.zend.com/apps.php',        'type'=>'part'), 
		array('value'=>'www.zend.com/codex.php',       'type'=>'part'), 
		array('value'=>'www.zend.com/comm_person.php', 'type'=>'part'), 
		array('value'=>'www.zend.com/press.php',       'type'=>'part'), 
		array('value'=>'www.php.net/cal.php',          'type'=>'part'), 
	);
	$wse->weightProperties = array(
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
	
	/*
	$wse->_crawlUrlStack[] = 'http://www.php.net/';
	$wse->_crawlUrlStack[] = 'http://www.phpbuilder.com/';
	$wse->_crawlUrlStack[] = 'http://www.phpdeveloper.org/';
	$wse->_crawlUrlStack[] = 'http://www.devnetwork.net/';
	$wse->_crawlUrlStack[] = 'http://www.evilwalrus.com/';
	$wse->_crawlUrlStack[] = 'http://www.phpfreaks.com/';
	$wse->_crawlUrlStack[] = 'http://www.phpcomplete.com/';
	$wse->_crawlUrlStack[] = 'http://www.phpclasses.org/';
	$wse->_crawlUrlStack[] = 'http://www.phpadvisory.com/';
	$wse->_crawlUrlStack[] = 'http://www.phpmac.com/';
	$wse->_crawlUrlStack[] = 'http://www.php-resource.de/';
	$wse->_crawlUrlStack[] = 'http://www.phpwelt.de/';
	$wse->_crawlUrlStack[] = 'http://www.phparchiv.de/';
	$wse->_crawlUrlStack[] = 'http://www.phpfinder.de/';
	*/
	$wse->loadTodoStack();
	
	$wse->registeredIndexCallback = 'indexCallback';
	//dump($wse->index('http://www.zend.com', TRUE));
	dump($wse->index('http://www.php.net', TRUE));
	echo '<h1>done!</h1>';
	echo $wse->stopWatch->toHtml();
}

function indexCallback(&$wse) {
	static $i = 0;
	$i++;
	
	if (connection_aborted()) {
		$wse->dropTodoStack();
		$wse->persistTodoStack();
		die('user aborted');
	} else {
		if ($i > 20) {
			$wse->dropTodoStack();
			$wse->persistTodoStack();
			$i = 0;
		}
	}
}


function createProfile() {
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
$profile =& new Bs_Is_Profile($GLOBALS['bsDb']);
dump($profile->drop('phpSearch'));
dump($profile->create('phpSearch', $xmlStr));
}
?>