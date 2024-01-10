<?php
define('BS_HTTPCLIENT_VERSION',      '4.5.$Revision: 1.1.1.1 $');

//require dependencies
$GLOBAL_CONF_TINY = TRUE;
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['core'] . 'net/http/Bs_HttpClient.class.php');
require_once($APP['path']['core'] . 'util/Bs_StopWatch.class.php');

$url = 'http://www.blueshoes.org/';

$httpClient =& new Bs_HttpClient();
//$httpClient->stopWatch =& new Bs_StopWatch();
//$httpClient->sendProtocolVersion = 'HTTP/1.1';
$httpClient->parseHeader = TRUE;
$html = $httpClient->fetchPage($url, $host=NULL, $port=NULL, $postData=NULL, $method=NULL, $tryReconnect=TRUE);
if (isEx($html)) {
	$html->stackDump('die');
} else {
	echo $httpClient->responseCode;
	dump($httpClient->headerRaw);
	dump($httpClient->headerParsed);
}
//$httpClient->stopWatch->takeTime('fetchPage() ' . __FILE__ . ':' . __LINE__);
//echo $httpClient->stopWatch->toHtml();
?>