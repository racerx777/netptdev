<?php

//require dependencies
if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';
require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');
require_once($APP['path']['core'] . 'net/http/Bs_HttpClient.class.php');

$url = 'http://www.blueshoes.ws/';
$httpClient =& new Bs_HttpClient();
//$httpClient->sendProtocolVersion = 'HTTP/1.1';
$content = $httpClient->fetchPage($url);
if (isEx($content)) {
	$content->stackDump('die');
} else {
	//$content = $httpClient->fetchPage($url, NULL, NULL, NULL, 'HEAD', TRUE);
	echo "the response code was: " . $httpClient->responseCode . "<br>\n";
	$codeInfo = $httpClient->responseCodeInfo($httpClient->responseCode, $httpClient->sendProtocolVersion);
	dump($codeInfo);
	echo "that means: " . $codeInfo[0] . "<br>\n";
	if (!empty($codeInfo[1])) echo "that means: " . $codeInfo[1] . "<br>\n";
}

?>