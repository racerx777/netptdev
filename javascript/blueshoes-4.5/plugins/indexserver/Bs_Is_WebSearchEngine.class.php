<?php/********************************************************************************************
* BlueShoes Framework; This file is part of the php application framework.
* NOTE: This code is stripped (obfuscated). To get the clean documented code goto 
*       www.blueshoes.org and register for the free open source *DEVELOPER* version or 
*       buy the commercial version.
*       
*       In case you've already got the developer version, then this is one of the few 
*       packages/classes that is only available to *PAYING* customers.
*       To get it go to www.blueshoes.org and buy a commercial version.
* 
* @copyright www.blueshoes.org
* @author    Samuel Blume <sam at blueshoes dot org>
* @author    Andrej Arn <andrej at blueshoes dot org>
*/?><?php
define('BS_IS_WEBSEARCHENGINE_VERSION',      '4.5.$Revision: 1.2 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'net/Bs_Url.class.php');require_once($APP['path']['core'] . 'net/http/Bs_HttpClient.class.php');require_once($APP['path']['core'] . 'util/Bs_StopWatch.class.php');require_once($APP['path']['core'] . 'lang/Bs_Logger.class.php');require_once($APP['path']['core'] . 'file/Bs_File.class.php');require_once($APP['path']['core'] . 'file/converter/Bs_FileConverterPdf.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_IndexServer.class.php');class Bs_Is_WebSearchEngine extends Bs_Object {var $Bs_Url;var $_bsDb;var $_indexServer;var $_indexer;var $_searcher;var $_httpClient;var $_profileName;var $_profile;var $stopWatch;var $indexIframes = 1;var $detectDublicatePages = TRUE;var $ignoreUrlsWithUser = TRUE;var $ignoreUrlsWithPass = TRUE;var $queryStringUrlLimit = 10;var $reindexIfUnchanged = 30;var $refetchAfter = 10;var $limitDomains;var $ignoreUrls = array();var $allowUrls = array();var $ignoreFileExtensions = array('zip', 'tar', 'tgz', 'gz', 'bz2', 'pdb', 'chm');var $searchStyleHead = '__NUM_RESULTS_TOTAL__ Seiten gefunden.<br><br>__HINTS_STRING__<br><ol>';var $searchStyleBody = '<li>__LINK_TITLE__<br>__DESCRIPTION__<br>__LINK_URL__<hr size=1 noshade></li>';var $searchStyleFoot = '</ol>';var $weightProperties = array(
'domain'      => array('weight' => 50), 
'path'        => array('weight' => 100), 
'file'        => array('weight' => 100), 
'queryString' => array('weight' => 80), 
'title'       => array('weight' => 100), 
'description' => array('weight' => 60), 
'keywords'    => array('weight' => 40), 
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
);var $waitAfterIndex = 0;var $_crawlUrlStack   = array();var $_crawlUrlDone    = array();var $_crawlUrlIgnored = array();var $_crawlUrlIgnoredQueryLimit = array();var $_crawlMd5Done    = array();var $registeredIndexCallback;function Bs_Is_WebSearchEngine() {parent::Bs_Object();  $this->Bs_Url = &$GLOBALS['Bs_Url'];}
function init($profileName) {$this->stopWatch =& new Bs_StopWatch();$this->stopWatch->takeTime('WebSearchEngine start');$this->_profileName = $profileName;if (!isSet($this->_bsDb) && isSet($GLOBALS['bsDb'])) {$this->setDbByObj($GLOBALS['bsDb']);}
$this->_indexServer =& new Bs_IndexServer();if (isSet($this->_bsDb)) {$this->_indexServer->setDbByObj($this->_bsDb); }
$this->_profile =& new Bs_Is_Profile($this->_bsDb);$status = $this->_profile->load($profileName);if (isEx($status)) {$status->stackTrace('was here in &getProfile()', __FILE__, __LINE__);return $status;}
$this->_indexer  = &$this->_indexServer->getIndexer($profileName);if (isEx($this->_indexer)) {$this->_indexer->stackDump('die');}
$this->_httpClient =& new Bs_HttpClient();$this->_httpClient->parseHeader = TRUE;}
function setDbByObj(&$bsDb) {unset($this->_bsDb);$this->_bsDb = &$bsDb;$this->_createDbTables();}
function setDbByDsn($dsn) {bs_lazyLoadClass('db/Bs_Db.class.php');$bsDb = &getDbObject($dsn);if (isEx($bsDb)) {$bsDb->stackTrace('was here in setDbByDsn()', __FILE__, __LINE__, 'fatal');return $bsDb;}
$this->_bsDb = &$bsDb;$this->_createDbTables();return TRUE;}
function search($searchString, $limit=10, $offset=0) {if (!isSet($this->_searcher)) {$this->_searcher =& new Bs_Is_Searcher($this->_indexServer, $this->_profile, $this->_bsDb);}
$results = $this->_searcher->search($searchString);$searchStyleHead = $this->searchStyleHead;$searchStyleHead = str_replace('__TIME_TAKEN__',        $this->_searcher->searchTime, $searchStyleHead);$searchStyleHead = str_replace('__NUM_RESULTS_TOTAL__', sizeOf($results),             $searchStyleHead);$searchStyleHead = str_replace('__HINTS_STRING__',      $this->_searcher->hintString, $searchStyleHead);$ret = $searchStyleHead;$i = 0;foreach($results as $url => $points) {$record = $this->fetchPageInfoByUrl($url);if (is_array($record)) { $searchStyleBody = $this->searchStyleBody;$searchStyleBody = str_replace('__LINK_TITLE__',  '<a href="__URL__">__TITLE__</a>', $searchStyleBody);$searchStyleBody = str_replace('__LINK_URL__',    '<a href="__URL__">__URL__</a>',   $searchStyleBody);$searchStyleBody = str_replace('__DESCRIPTION__', $record['description'], $searchStyleBody);$searchStyleBody = str_replace('__TITLE__',       $record['title'],       $searchStyleBody);$searchStyleBody = str_replace('__URL__',         $record['url'],         $searchStyleBody);$ret .= $searchStyleBody;$i++;}
if ($i >= $limit) break;}
$ret .= $this->searchStyleFoot;return $ret;}
function index($url, $follow=FALSE) {$this->stopWatch->takeTime("index() with url $url");$this->_logMessage($url);if (!empty($this->registeredIndexCallback)) {$t = $this->registeredIndexCallback;$t($this);}
do {$urlParsed = $this->Bs_Url->parseUrlExtended($url);if (is_array($this->limitDomains)) {if (!in_array(strToLower($urlParsed['host']), $this->limitDomains)) break;} else {$this->limitDomains = array($urlParsed['host']);}
if (isSet($this->_crawlUrlDone[$url])) break; if (isSet($this->_crawlUrlIgnored[$url])) break; if ($this->isIgnoredUrl($url, TRUE)) {$this->_logMessage('url ignored: ' . $url);$this->_crawlUrlIgnored[$url] = TRUE;break;}
$currentRecord = $this->fetchPageInfoByUrl($url);if (is_array($currentRecord) && isSet($currentRecord['lastIndexDatetime'])) {$compareDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d')-$this->refetchAfter, date('Y')));if ($compareDate < $currentRecord['lastIndexDatetime']) {$this->_logMessage('url done lately: ' . $url);$this->_crawlUrlIgnored[$url] = TRUE;break;}
}
$this->stopWatch->takeTime("in index() before _fetchPage()");$fetched = $this->_fetchPage($url);if (isEx($fetched)) {return $fetched;}
if ($fetched['code'] !== 200) break; switch ($fetched['mime']) {case 'image/gif':
case 'image/jpg':
case 'image/jpeg':
case 'image/png':
continue; }
$content = $fetched['content'];if ($this->detectDublicatePages) {$md5 = md5($content);$this->stopWatch->takeTime("created md5.");if (isSet($this->_crawlMd5Done[$md5])) {$this->_logMessage('content of ' . $url . ' equals ' . $this->_crawlMd5Done[$md5] . ' and is ignored.');$this->_crawlUrlIgnored[$url] = TRUE;break; }
}
$this->_logMessage('indexing: ' . $url);if ($this->detectDublicatePages) $this->_crawlMd5Done[$md5] = $url;$this->_crawlUrlDone[$url] = TRUE;if (($fetched['mime'] === '') || (empty($fetched['mime']) && (strToLower(@$urlParsed['extension']) === 'pdf'))) {if (isSet($GLOBALS['APP']['path']['pdftohtml'])) {$pdfFullPath = getTmp() . md5($url) . '.pdf';$file =& new Bs_File();$file->onewayWrite($content, $pdfFullPath);$pdfConv =& new Bs_FileConverterPdf();$content = $pdfConv->pdfToHtmlString($pdfFullPath);} else {break; }
}
$hi =& new Bs_HtmlInfo();$hi->initByString($content);$sizeBytes = strlen($content);$status = $this->_indexUrl($url, $urlParsed, $md5, $sizeBytes, $hi);if (isEx($status)) {$status->stackTrace('was here in index()', __FILE__, __LINE__);return $status;}
$this->stopWatch->takeTime("indexed the page content.");$this->_logMessage('status of indexing: ' . boolToString($status));$links = $hi->fetchLinks(1);foreach ($links as $foundUrl) {$foundUrl['href'] = $this->_fixUrl($foundUrl['href'], $url);$this->_dropLinksFromTo($url, $foundUrl['href']);$foundUrlParsed = parse_url($foundUrl['href']);if (in_array(strToLower($foundUrlParsed['host']), $this->limitDomains)) {$this->_indexLinkFromTo($url, $foundUrl['href'], $foundUrl['caption'], FALSE);if (!isSet($this->_crawlUrlDone[$foundUrl['href']]) && !isSet($this->_crawlUrlIgnored[$foundUrl['href']]) && !in_array($foundUrl['href'], $this->_crawlUrlStack) && !$this->isIgnoredUrl($foundUrl['href'], FALSE)) {$this->_crawlUrlStack[] = $foundUrl['href'];}
} else {$this->_indexLinkFromTo($url, $foundUrl['href'], $foundUrl['caption'], TRUE);}
}
$this->stopWatch->takeTime("fetched links from html file. finished indexing $url");if ($this->waitAfterIndex > 0) sleep($this->waitAfterIndex);} while (FALSE);if ($follow) {$nextUrl = $this->_getNextUrlFromStack();if ($nextUrl) $this->index($nextUrl, $follow);}
return TRUE;}
function _indexUrl($url, $urlParsed, $md5, $sizeBytes, &$hi) {$this->stopWatch->takeTime("_indexUrl() with url $url");$linkArr  = array();$linkData = $this->fetchLinksToPageByUrl($url);if (is_array($linkData)) {foreach ($linkData as $arr) {$linkArr[] = $arr['caption'];}
}
$linkString = join(' ', $linkArr);$bodyArray = array();if ($this->indexIframes === 1) {$iframeUrls = $hi->fetchIframeUrls(FALSE);if (!empty($iframeUrls)) {foreach($iframeUrls as $iframeUrl) {$iframeUrl     = $this->_fixUrl($iframeUrl, $url);$iframeFetched = $this->_fetchPage($iframeUrl);if (isEx($iframeFetched)) {continue; }
if ($iframeFetched['code'] !== 200) continue; $iframeContent = $iframeFetched['content'];switch ($iframeFetched['mime']) {case 'image/gif':
case 'image/jpg':
case 'image/jpeg':
case 'image/png':
continue; }
$hiIframe =& new Bs_HtmlInfo();$hiIframe->initByString($iframeContent);$bodyArray[] = $hiIframe->fetchBody();}
}
}
$bodyArray[] = $hi->fetchBody();$data = array(
'domain'      => $urlParsed['host'], 
'path'        => (string)@$urlParsed['path'],  'file'        => (string)@basename($urlParsed['path']), 
'queryString' => (string)@$urlParsed['query'], 
'title'       => $hi->fetchTitle(), 
'description' => $hi->fetchDescription(), 
'keywords'    => $hi->fetchKeywords(), 
'links'       => $linkString, 
'h1'          => join(' ', $hi->fetchStringsByTagNameStupid('h1')), 
'h2'          => join(' ', $hi->fetchStringsByTagNameStupid('h2')), 
'h3'          => join(' ', $hi->fetchStringsByTagNameStupid('h3')), 
'h4'          => join(' ', $hi->fetchStringsByTagNameStupid('h4')), 
'h5'          => join(' ', $hi->fetchStringsByTagNameStupid('h5')), 
'h6'          => join(' ', $hi->fetchStringsByTagNameStupid('h6')), 
'h7'          => join(' ', $hi->fetchStringsByTagNameStupid('h7')), 
'h8'          => join(' ', $hi->fetchStringsByTagNameStupid('h8')), 
'b'           => join(' ', $hi->fetchStringsByTagNamesStupid(array('b', 'strong'))), 
'i'           => join(' ', $hi->fetchStringsByTagNameStupid('i')), 
'u'           => join(' ', $hi->fetchStringsByTagNameStupid('u')), 
'body'        => join(' ', $bodyArray), 
);$pageDescription = (!empty($data['description'])) ? $data['description'] : substr($data['body'], 0, 255);$status = $this->_indexPageInfo($url, $data['title'], $pageDescription, $data['body'], '', 'text/html', $md5, $sizeBytes);if (isEx($status)) {$status->stackTrace('was here in _indexUrl()', __FILE__, __LINE__);return $status;}
return $this->_indexer->index($url, $data, $this->weightProperties);}
function _fetchPage($url) {$this->stopWatch->takeTime("_fetchPage() with url $url");$content = $this->_httpClient->fetchPage($url);if (isEx($content)) {return $content;}
$ret = array(
'code'    => $this->_httpClient->responseCode, 
'mime'    => $this->_httpClient->headerParsed['content-type'], 
'content' => $content, 
);return $ret;}
function loadTodoStack() {$sql  = "SELECT sourceID FROM Bs_Is_{$this->_profileName}_Queue WHERE todo = 'a'";$status = $this->_bsDb->getCol($sql);if (isEx($status)) {$status->stackTrace('was here in loadTodoStack()', __FILE__, __LINE__);return $status;}
if (is_array($status)) {$this->_crawlUrlStack = array_merge($this->_crawlUrlStack, $status);$this->_crawlUrlStack = array_unique($this->_crawlUrlStack); }
return TRUE;}
function persistTodoStack() {foreach($this->_crawlUrlStack as $url) {$sql  = "INSERT INTO Bs_Is_{$this->_profileName}_Queue (sourceID, todo) VALUES ('" . $this->_bsDb->escapeString($url) . "', 'a')"; $status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in persistStack()', __FILE__, __LINE__);return $status;}
}
return TRUE;}
function dropTodoStack() {$sql  = "DELETE FROM Bs_Is_{$this->_profileName}_Queue";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in dropTodoStack()', __FILE__, __LINE__);return $status;}
return TRUE;}
function _getNextUrlFromStack() {if (!empty($this->_crawlUrlStack)) {reset($this->_crawlUrlStack);while (list($k,$url) = each($this->_crawlUrlStack)) {unset($this->_crawlUrlStack[$k]);return $url;}
}
return FALSE;}
function _logMessage($msg) {bs_logIt($msg, '', __LINE__, '', __FILE__);echo $msg . "<br>\n";}
function getPageID($url) {$sql = "SELECT ID FROM Bs_Is_{$this->_profileName}_WebPages WHERE url LIKE '" . $this->_bsDb->escapeString($url) . "'";$status = $this->_bsDb->getOne($sql);if (isEx($status)) {$status->stackTrace('was here in getPageID()', __FILE__, __LINE__);return $status;}
return $status;}
function getPageUrl($pageID) {$sql = "SELECT url FROM Bs_Is_{$this->_profileName}_WebPages WHERE ID = {$pageID}";$status = $this->_bsDb->getOne($sql);if (isEx($status)) {$status->stackTrace('was here in getPageUrl()', __FILE__, __LINE__);return $status;}
return $status;}
function fetchPageInfoByUrl($url) {$sql = "SELECT * FROM Bs_Is_{$this->_profileName}_WebPages WHERE url LIKE '" . $this->_bsDb->escapeString($url) . "'";$status = $this->_bsDb->getRow($sql);if (isEx($status)) {$status->stackTrace('was here in fetchPageInfoByUrl()', __FILE__, __LINE__);return $status;}
return $status;}
function fetchPageInfoById($pageID) {$sql = "SELECT * FROM Bs_Is_{$this->_profileName}_WebPages WHERE ID = {$pageID}";$status = $this->_bsDb->getRow($sql);if (isEx($status)) {$status->stackTrace('was here in fetchPageInfoById()', __FILE__, __LINE__);return $status;}
return $status;}
function fetchPageList() {$sql = "SELECT ID, url FROM Bs_Is_{$this->_profileName}_WebPages";$status = $this->_bsDb->getAssoc($sql);if (isEx($status)) {$status->stackTrace('was here in fetchPageList()', __FILE__, __LINE__);return $status;}
return $status;}
function _indexPageInfo($url, $title, $description, $contentSnapshot, $language, $mimeType, $contentMd5, $sizeBytes) {$currentRecord = $this->fetchPageInfoByUrl($url);if (isEx($currentRecord)) {$this->_createDbTables();$currentRecord = $this->fetchPageInfoByUrl($url);}
if (isEx($currentRecord)) {$currentRecord->stackTrace('was here in _indexPageInfo()', __FILE__, __LINE__);return $currentRecord;} elseif (is_array($currentRecord)) {if ($currentRecord['contentMd5'] === $contentMd5) {}
}
$lastIndexDatetime = gmdate('Y-m-d H:i:s');$dataArr = array(
'url'                => $url, 
'title'              => $title, 
'description'        => $description, 
'contentSnapshot'    => $contentSnapshot, 
'language'           => $language, 
'mimeType'           => $mimeType, 
'lastIndexDatetime'  => $lastIndexDatetime, 
'changeFrequency'    => '', 
'contentMd5'         => $contentMd5, 
'sizeBytes'          => $sizeBytes, 
);if (is_array($currentRecord)) {$sql = "UPDATE ";} else {$dataArr['firstIndexDatetime'] = $lastIndexDatetime;$sql = "INSERT INTO ";}
$sql .= "Bs_Is_{$this->_profileName}_WebPages ";$sql .= " SET ";$sql .= $this->_bsDb->quoteArgs($dataArr);if (is_array($currentRecord)) {$sql .= " WHERE ID = {$currentRecord['ID']}";}
$status = $this->_bsDb->countWrite($sql);if (isEx($status)) {$this->_createDbTables(); $status = $this->_bsDb->countWrite($sql);}
if (isEx($status)) {$status->stackTrace('was here in _indexPageInfo()', __FILE__, __LINE__);return $status;} elseif ($status === 0) {return TRUE;}
return TRUE;}
function isIgnoredUrl($url, $forIndexing=TRUE) {$urlParsed = $this->Bs_Url->parseUrlExtended($url);if (!empty($urlParsed['extension'])) {static $ife;$ife = array(
'gif', 'jpg', 'jpeg', 'png', 'bmp', 'tiff', 'tif', 'eps', 'ico', 
'midi', 'mdi', 'wav', 'mp3', 'avi', 'mov', 
'jar', 'java', 
'js', 'css', 'vbs'
);if (in_array($urlParsed['extension'], $ife)) return TRUE;}
if (!empty($urlParsed['extension'])) {if (in_array($urlParsed['extension'], $this->ignoreFileExtensions)) return TRUE;}
if (isSet($this->allowUrls) && is_array($this->allowUrls)) {foreach($this->allowUrls as $arr) {switch (@$arr['type']) {case 'preg':
break;case 'ereg':
break;case 'file':
$t1 = (string)$arr['value'];$t2 = (string)@$urlParsed['file'];if ($t1 === $t2) return FALSE;break;default: if (strpos($url, $arr['value']) !== FALSE) return FALSE;}
}
}
if (!empty($urlParsed['query'])) {if (($this->queryStringUrlLimit !== -1) && (!empty($urlParsed['query']))) {if ($this->queryStringUrlLimit === 0) return TRUE;if ($forIndexing) {$urlLimited = $this->Bs_Url->getUrlJunk('suPhOp', $url); $t = (isSet($this->_crawlUrlIgnoredQueryLimit[$urlLimited])) ? $this->_crawlUrlIgnoredQueryLimit[$urlLimited] : 0;$this->_crawlUrlIgnoredQueryLimit[$urlLimited] = $t + 1;if ($this->_crawlUrlIgnoredQueryLimit[$urlLimited] > $this->queryStringUrlLimit) return TRUE;}
}
}
if (isSet($this->ignoreUrls) && is_array($this->ignoreUrls)) {foreach($this->ignoreUrls as $arr) {switch (@$arr['type']) {case 'preg':
break;case 'ereg':
break;case 'file':
$t1 = (string)$arr['value'];$t2 = (string)@$urlParsed['file'];if ($t1 === $t2) return TRUE;break;default: if (strpos($url, $arr['value']) !== FALSE) return TRUE;}
}
}
return FALSE;}
function fetchLinksToPageByUrl($url) {$sql  = "SELECT urlFrom as href, caption FROM Bs_Is_{$this->_profileName}_WebLinks WHERE urlTo LIKE '";$sql .= $this->_bsDb->escapeString($url) . "'";$status = $this->_bsDb->getAll($sql);if (isEx($status)) {$status->stackTrace('was here in fetchLinksToPageByUrl()', __FILE__, __LINE__);return $status;}
return $status;}
function fetchLinksFromPageByUrl($url) {$sql  = "SELECT urlTo as href, caption FROM Bs_Is_{$this->_profileName}_WebLinks WHERE urlFrom LIKE '";$sql .= $this->_bsDb->escapeString($url) . "'";$status = $this->_bsDb->getAll($sql);if (isEx($status)) {$status->stackTrace('was here in fetchLinksFromPageByUrl()', __FILE__, __LINE__);return $status;}
return $status;}
function getExternalLinks() {$sql  = "SELECT ID, urlFrom, urlTo, caption FROM Bs_Is_{$this->_profileName}_WebLinks WHERE isExternal = 1";$status = $this->_bsDb->getAssoc($sql);if (isEx($status)) {$status->stackTrace('was here in getExternalLinks()', __FILE__, __LINE__);return $status;}
return $status;}
function _indexLinkFromTo($urlFrom, $urlTo, $caption, $isExternal=FALSE) {$sql  = "INSERT INTO Bs_Is_{$this->_profileName}_WebLinks (urlFrom, urlTo, caption, isExternal) VALUES ('";$sql .= $this->_bsDb->escapeString($urlFrom) . "', '";$sql .= $this->_bsDb->escapeString($urlTo)   . "', '";$sql .= $this->_bsDb->escapeString($caption) . "', ";$sql .= (int)$isExternal . ")";$status = $this->_bsDb->write($sql);if (isEx($status)) {$this->_createDbTables(); $status = $this->_bsDb->write($sql);}
if (isEx($status)) {$status->stackTrace('was here in _indexLinkFromTo()', __FILE__, __LINE__);return $status;}
return TRUE;}
function _dropLinksFromTo($urlFrom, $urlTo) {$sql  = "DELETE FROM Bs_Is_{$this->_profileName}_WebLinks WHERE ";$sql .= "urlFrom LIKE '" . $this->_bsDb->escapeString($urlFrom) . "' ";$sql .= "AND urlTo LIKE '" . $this->_bsDb->escapeString($urlTo) . "'";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _dropLinksFromTo()', __FILE__, __LINE__);return $status;}
return TRUE;}
function _createDbTables() {$sql = "
CREATE TABLE IF NOT EXISTS Bs_Is_{$this->_profileName}_WebPages (
ID                     int not null default 0 auto_increment, 
url                    varchar(255) not null default '', 
title                  varchar(255) not null default '', 
description            varchar(255) not null default '', 
contentSnapshot        blob not null, 
language               char(5) not null default '', 
mimeType               varchar(10)  not null default 'text/html', 
firstIndexDatetime     datetime not null default '0000-00-00 00:00:00', 
lastIndexDatetime      datetime not null default '0000-00-00 00:00:00', 
changeFrequency        tinyint not null default 0, 
contentMd5             CHAR(40) NOT NULL DEFAULT '', 
sizeBytes              int not null default 0, 
PRIMARY KEY  ID         (ID), 
KEY          url        (url), 
KEY          contentMd5 (contentMd5)
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _createDbTables()', __FILE__, __LINE__);return $status;}
$sql = "
CREATE TABLE IF NOT EXISTS Bs_Is_{$this->_profileName}_WebLinks (
ID           int not null default 0 auto_increment, 
urlFrom      varchar(255) not null default '', 
urlTo        varchar(255) not null default '', 
caption      varchar(255) not null default '', 
isExternal   tinyint      not null default 0, 
PRIMARY KEY  ID (ID), 
KEY urlFrom  (urlFrom), 
KEY urlTo    (urlTo)
)
";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _createDbTables()', __FILE__, __LINE__);return $status;}
return TRUE;}
function validateExternalLinks() {$eLinks = $this->getExternalLinks();if (isEx($eLinks)) {$eLinks->stackTrace('was here in validateExternalLinks()', __FILE__, __LINE__);return $eLinks;}
$ret = '';foreach($eLinks as $arr) {$ret .= "checking url: {$arr['urlTo']} ... ";$content = $this->_httpClient->fetchPage($url, NULL, NULL, NULL, 'HEAD', TRUE);$responseCode = $this->_httpClient->responseCode;if (($responseCode !== 200) && ($responseCode !== 404)) {sleep(2);$content = $this->_httpClient->fetchPage($url, NULL, NULL, NULL, 'HEAD', TRUE);$responseCode = $this->_httpClient->responseCode;}
if ($responseCode !== 200) {$codeInfo = $this->_httpClient->responseCodeInfo($responseCode);$ret .= "checking url: {$arr['urlTo']} ...";} else {}
}
}
function fetchWordsForPageByID($pageID, $order='caption') {$url = $this->getPageUrl($pageID);$tblRel  = "Bs_Is_{$this->_profileName}_WordToSource";$tblWord = "Bs_Is_{$this->_profileName}_Words";$sql  = "SELECT {$tblWord}.caption, {$tblRel}.wordID, {$tblRel}.ranking FROM {$tblRel} left join {$tblWord} ON {$tblRel}.wordID={$tblWord}.ID WHERE sourceID LIKE '$url'";if ($order === 'ranking') {$sql .= " ORDER BY {$tblWord}.ranking";} else { $sql .= " ORDER BY {$tblWord}.caption";}
$status = $this->_bsDb->getAll($sql);if (isEx($status)) {$status->stackTrace('was here in fetchWordsForPageByID()', __FILE__, __LINE__);return $status;}
return $status;}
function prune() {do {$status = $this->_profile->prune($this->_profileName);if (isEx($status)) break;$sql  = "DELETE FROM Bs_Is_{$this->_profileName}_WebLinks";$status = $this->_bsDb->write($sql);if (isEx($status)) break;$sql  = "DELETE FROM Bs_Is_{$this->_profileName}_WebPages";$status = $this->_bsDb->write($sql);if (isEx($status)) break;return TRUE;} while (FALSE);$status->stackTrace('was here in prune()', __FILE__, __LINE__);return $status;}
function _fixUrl($newUrl, $oldUrl) {if (!strpos($newUrl, '://')) {if (substr($newUrl, 0, 1) === '/') {$newUrl = $this->Bs_Url->getUrlJunk('suPhO', $oldUrl)  . $newUrl;} else {$newUrl = $this->Bs_Url->getUrlJunk('suPhOi', $oldUrl) . $newUrl;}
}
return $this->Bs_Url->realUrl($newUrl);}
}
if (basename($_SERVER['PHP_SELF']) == 'Bs_Is_WebSearchEngine.class.php') {set_time_limit(600);$is =& new Bs_IndexServer();$wse =& new Bs_Is_WebSearchEngine();$wse->setDbByObj($bsDb);$wse->init('foo');echo $wse->search('klingnau'); }
?>