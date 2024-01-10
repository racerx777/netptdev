<?php
/**
* @package    plugins_indexserver
* @subpackage examples
*/


//require dependencies
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_IndexServer.class.php');
require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_WebSearchEngine.class.php');


set_time_limit(100000);

//createProfile();

$is =& new Bs_IndexServer();


$wse =& new Bs_Is_WebSearchEngine();
$wse->init('zend');
$wse->waitAfterIndex = 5;
//dump($wse->prune());
index($wse);

echo $wse->search('andrej');



function index(&$wse) {
	$wse->queryStringUrlLimit = 100;
	$wse->limitDomains = array('www.zend.com');
	$wse->ignoreUrls = array(
		array('value'=>'go_link.php',  'type'=>'file'), 
		array('value'=>'click.php3',   'type'=>'file'), 
	);
	$wse->allowUrls = array(
		array('value'=>'apps.php',        'type'=>'file'), 
		array('value'=>'codex.php',       'type'=>'file'), 
		array('value'=>'comm_person.php', 'type'=>'file'), 
		array('value'=>'press.php', 'type'=>'file'), 
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
	dump($wse->index('http://www.zend.com', TRUE));
	echo '<h1>done!</h1>';
	echo $wse->stopWatch->toHtml();
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
dump($profile->drop('zend'));
dump($profile->create('zend', $xmlStr));
}
?>