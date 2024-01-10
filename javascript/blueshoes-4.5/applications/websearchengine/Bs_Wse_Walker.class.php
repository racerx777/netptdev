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
define('BS_WSE_WALKER_VERSION',      '4.5.$Revision: 1.5 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'net/Bs_Url.class.php');require_once($APP['path']['core'] . 'net/http/Bs_HttpClient.class.php');require_once($APP['path']['core'] . 'util/Bs_StopWatch.class.php');require_once($APP['path']['core'] . 'lang/Bs_Logger.class.php');require_once($APP['path']['core'] . 'file/Bs_File.class.php');require_once($APP['path']['core'] . 'file/converter/Bs_FileConverterPdf.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_IndexServer.class.php');class Bs_Wse_Walker extends Bs_Object {var $Bs_Wse_WebSearchEngine;var $Bs_Url;var $_bsDb;var $_indexer;var $_httpClient;var $_profile;var $stopWatch;var $_crawlUrlStack   = array();var $_crawlUrlDone    = array();var $_crawlUrlIgnored = array();var $_crawlUrlIgnoredQueryLimit = array();var $_crawlMd5Done    = array();var $_descriptionMd5 = array();var $_descriptionFrontpage;var $_keywordsMd5 = array();var $_keywordsFrontpage;var $registeredIndexCallback;function Bs_Wse_Walker(&$Bs_Wse_WebSearchEngine, &$profile, &$bsDb) {parent::Bs_Object();  $this->stopWatch =& new Bs_StopWatch();$this->stopWatch->takeTime('WebSearchEngine start');$this->Bs_Wse_WebSearchEngine = &$Bs_Wse_WebSearchEngine;$this->_profile               = &$profile;$this->_bsDb                  = &$bsDb;$this->Bs_Url = &$GLOBALS['Bs_Url'];$this->Bs_Wse_WebSearchEngine->Bs_Is_IndexServer->setProfile($profile->_isProfile); $this->_indexer = &$this->Bs_Wse_WebSearchEngine->Bs_Is_IndexServer->getIndexer($profile->profileName);if (isEx($this->_indexer)) {$this->_indexer->stackDump('die');} elseif ($this->_indexer === FALSE) {die('could not create indexer in ' . __FILE__ . ' on line ' . __LINE__);}
$this->_httpClient    =& new Bs_HttpClient();$this->_httpClient->parseHeader    = TRUE;$this->_httpClient->followRedirect = 0;}
function setDbByObj(&$bsDb) {unset($this->_bsDb);$this->_bsDb = &$bsDb;$this->_createDbTables();}
function setDbByDsn($dsn) {bs_lazyLoadClass('db/Bs_Db.class.php');$bsDb = &getDbObject($dsn);if (isEx($bsDb)) {$bsDb->stackTrace('was here in setDbByDsn()', __FILE__, __LINE__, 'fatal');return $bsDb;}
$this->_bsDb = &$bsDb;$this->_createDbTables();return TRUE;}
function index($url, $follow=FALSE) {$this->stopWatch->takeTime("index() with url $url");$this->_logMessage($url);if (!empty($this->registeredIndexCallback)) {$t = $this->registeredIndexCallback;$t($this);}
do {$urlParsed = $this->Bs_Url->parseUrlExtended($url);if (is_array($this->_profile->limitDomains)) {if (!in_array(strToLower($urlParsed['host']), $this->_profile->limitDomains)) break;} else {$this->_profile->limitDomains = array($urlParsed['host']);}
if (isSet($this->_crawlUrlDone[$url])) break; if (isSet($this->_crawlUrlIgnored[$url])) break; if ($this->isIgnoredUrl($url, TRUE)) {$this->_logMessage('url ignored: ' . $url);$this->_crawlUrlIgnored[strToLower($url)] = TRUE; break;}
$currentRecord = $this->fetchPageInfoByUrl($url);if (is_array($currentRecord) && isSet($currentRecord['lastIndexDatetime'])) {if ((int)$currentRecord['changeFrequency'] >= 100) {} else {$compareDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d')-$this->_profile->refetchAfter, date('Y')));if ($compareDate < $currentRecord['lastIndexDatetime']) {$this->_logMessage('url done lately: ' . $url);$this->_crawlUrlIgnored[$url] = TRUE;break;}
}
}
$this->stopWatch->takeTime("in index() before _fetchPage()");$fetched = $this->_fetchPage($url);if (isEx($fetched)) {return $fetched;}
if ($fetched['code'] !== 200) {break;}
switch ($fetched['mime']) {case 'image/gif':
case 'image/jpg':
case 'image/jpeg':
case 'image/png':
continue; }
if ($this->_profile->detectDublicatePages) {$md5 = md5($fetched['content']);$this->stopWatch->takeTime("created md5.");if (isSet($this->_crawlMd5Done[$md5])) {$this->_logMessage('content of ' . $url . ' equals ' . $this->_crawlMd5Done[$md5] . ' and is ignored.');$this->_crawlUrlIgnored[$url] = TRUE;break; }
}
$this->_logMessage('indexing: ' . $url);if ($this->_profile->detectDublicatePages) $this->_crawlMd5Done[$md5] = $url;$this->_crawlUrlDone[strToLower($url)] = TRUE; $sizeBytes = strlen($fetched['content']);if ($fetched['mime'] === 'text/html') {$content = $fetched['content'];} elseif ($fetched['mime'] === 'text/plain') {$content = $fetched['content'];} else {$isOk = FALSE;do {$obj = &$this->_indexer->getMimeTypeHandler($fetched['mime']);if (!is_object($obj)) {break;}
if (method_exists($obj, 'stringToHtmlString')) {$status = $obj->stringToHtmlString($fetched['content']);if ($status === FALSE) break;if (isEx($status)) {break;}
$newMime = 'text/html';$content = $status;} elseif (method_exists($obj, 'streamToHtmlString')) {$status = $obj->streamToHtmlString($url);if ($status === FALSE) break;if (isEx($status)) {break;}
$newMime = 'text/html';$content = $status;} elseif (method_exists($obj, 'streamToArray')) {$status = $obj->stringToArray($fetched['content']);if ($status === FALSE) break;if (isEx($status)) {break;}
$content = $status['values'];} elseif (method_exists($obj, 'streamToTextString')) {$status = $obj->stringToTextString($fetched['content']);if ($status === FALSE) break;} else {break; $content = $status['values'];}
$isOk    = TRUE;} while (FALSE);if (!$isOk) {$content = '';}
}
if (($fetched['mime'] === 'text/html') || (@$newMime == 'text/html')) {$hi =& new Bs_HtmlInfo();$hi->initByString($content);$status = $this->_indexUrlHtml($url, $urlParsed, $md5, $sizeBytes, $hi);} else {if (is_array($content)) {$title       = (isSet($content['title']))       ? $content['title']       : '';$description = (isSet($content['description'])) ? $content['description'] : '';$body        = (isSet($content['body']))        ? $content['body']        : '';} else {$title       = '';$description = '';$body        = '';}
$status = $this->_indexUrl($url, $urlParsed, $md5, $sizeBytes, $fetched['mime'], $title, $description, $body, $content, $weightProperties=array());}
if (isEx($status)) {$status->stackTrace('was here in index()', __FILE__, __LINE__);return $status;}
$this->stopWatch->takeTime("indexed the page content.");$this->_logMessage('status of indexing: ' . boolToString($status));if ($fetched['mime'] === 'text/html') {$links = $hi->fetchLinks(1);foreach ($links as $foundUrl) {$foundUrl['href'] = $this->_fixUrl($foundUrl['href'], $url);$this->_dropLinksFromTo($url, $foundUrl['href']);$foundUrlParsed = parse_url($foundUrl['href']);if (in_array(strToLower($foundUrlParsed['host']), $this->_profile->limitDomains)) {$this->_indexLinkFromTo($url, $foundUrl['href'], $foundUrl['caption'], FALSE);$tmpFoundUrl = strToLower($foundUrl['href']); if (!isSet($this->_crawlUrlDone[$tmpFoundUrl]) && !isSet($this->_crawlUrlIgnored[$tmpFoundUrl]) && !in_array($tmpFoundUrl, $this->_crawlUrlStack) && !$this->isIgnoredUrl($foundUrl['href'], FALSE)) {$this->_crawlUrlStack[] = $foundUrl['href'];}
} else {$this->_indexLinkFromTo($url, $foundUrl['href'], $foundUrl['caption'], TRUE);}
}
$this->stopWatch->takeTime("fetched links from html file. finished indexing $url");}
if ($this->_profile->waitAfterIndex > 0) sleep($this->_profile->waitAfterIndex);} while (FALSE);if ($follow) {$nextUrl = $this->_getNextUrlFromStack();if ($nextUrl) $this->index($nextUrl, $follow);}
return TRUE;}
function _fetchIframeContent(&$htmlInfo, $url) {$bodyArray = array();$iframeUrls = $htmlInfo->fetchIframeUrls(FALSE);if (!empty($iframeUrls)) {foreach($iframeUrls as $iframeUrl) {$iframeUrl     = $this->_fixUrl($iframeUrl, $url);$iframeFetched = $this->_fetchPage($iframeUrl);if (isEx($iframeFetched)) {continue; }
if ($iframeFetched['code'] !== 200) continue; $iframeContent = $iframeFetched['content'];if ($iframeFetched['mime'] !== 'text/html') continue;$hiIframe =& new Bs_HtmlInfo();$hiIframe->initByString($iframeContent);$bodyArray[] = $hiIframe->fetchBody();}
}
return $bodyArray;}
function _indexUrl($url, $urlParsed, $md5, $sizeBytes, $mimeType, $title, $description, $body, $data, $weightProperties) {$linkArr  = array();$linkData = $this->fetchLinksToPageByUrl($url);if (is_array($linkData)) {foreach ($linkData as $arr) {$linkArr[] = $arr['caption'];}
}
$linkString = join(' ', $linkArr);if (!is_array($data)) {$data = array('body'=>$data);}
$data['domain']      = $urlParsed['host'];$data['path']        = (string)@$urlParsed['path'];  $data['file']        = (string)@basename($urlParsed['path']);$data['queryString'] = (string)@$urlParsed['query'];$data['links']       = $linkString;$status = $this->_indexPageInfo($url, $urlParsed, $title, $description, $body, '', $mimeType, $md5, $sizeBytes);if (isEx($status)) {$status->stackTrace('was here in _indexUrl()', __FILE__, __LINE__);return $status;}
return $this->_indexer->index($url, $data, $weightProperties);}
function _indexUrlHtml($url, $urlParsed, $md5, $sizeBytes, &$hi) {$this->stopWatch->takeTime("_indexUrl() with url $url");if ($this->_profile->indexIframes === 1) {$bodyArray = $this->_fetchIframeContent($hi, $url);} else {$bodyArray = array();}
$realBody       = $hi->fetchBody(TRUE);$realBodyNoJunk = $realBody;$realBodyNoJunk = preg_replace('/(\<bs:nav\>)(.*?)(\<\/bs:nav\>)/is', '', $realBodyNoJunk); $realBody       = $hi->htmlToText($realBodyNoJunk);array_unshift($bodyArray, $realBody); $description = $this->_getDescription($url, $hi->fetchDescription());$keywords    = $this->_getKeywords($url, $hi->fetchKeywords());$data = array(
'title'       => $hi->fetchTitle(), 
'description' => $description, 
'keywords'    => $keywords, 
);$hiNoJunk = $hi; $hiNoJunk->initByString($realBodyNoJunk);$data['h1']          = join(' ', $hiNoJunk->fetchStringsByTagNameStupid('h1'));$data['h2']          = join(' ', $hiNoJunk->fetchStringsByTagNameStupid('h2'));$data['h3']          = join(' ', $hiNoJunk->fetchStringsByTagNameStupid('h3'));$data['h4']          = join(' ', $hiNoJunk->fetchStringsByTagNameStupid('h4'));$data['h5']          = join(' ', $hiNoJunk->fetchStringsByTagNameStupid('h5'));$data['h6']          = join(' ', $hiNoJunk->fetchStringsByTagNameStupid('h6'));$data['h7']          = join(' ', $hiNoJunk->fetchStringsByTagNameStupid('h7'));$data['h8']          = join(' ', $hiNoJunk->fetchStringsByTagNameStupid('h8'));$data['b']           = join(' ', $hiNoJunk->fetchStringsByTagNamesStupid(array('b', 'strong')));$data['i']           = join(' ', $hiNoJunk->fetchStringsByTagNameStupid('i'));$data['u']           = join(' ', $hiNoJunk->fetchStringsByTagNameStupid('u'));$data['body']        = join(' ', $bodyArray);$data['image']       = $hiNoJunk->fetchImageTexts('string');$pageDescription = (!empty($data['description'])) ? $data['description'] : substr($data['body'], 0, 255);return $this->_indexUrl($url, $urlParsed, $md5, $sizeBytes, 'text/html', $data['title'], $pageDescription, $data['body'], $data, $this->_profile->weightProperties);}
function _fetchPage($url) {$this->stopWatch->takeTime("_fetchPage() with url $url");$content = $this->_httpClient->fetchPage($url);if (isEx($content)) {return $content;}
$contentType = explode(';', $this->_httpClient->headerParsed['content-type']);$ret = array(
'code'    => $this->_httpClient->responseCode, 
'mime'    => trim($contentType[0]), 
'content' => $content, 
);if (isSet($contentType[1])) {$ret['charset'] = $contentType[1];}
return $ret;}
function loadTodoStack() {$sql  = "SELECT sourceID FROM Bs_Wse_{$this->_profile->profileName}_Queue WHERE todo = 'a'";$status = $this->_bsDb->getCol($sql);if (isEx($status)) {$status->stackTrace('was here in loadTodoStack()', __FILE__, __LINE__);return $status;}
if (is_array($status)) {$this->_crawlUrlStack = array_merge($this->_crawlUrlStack, $status);$this->_crawlUrlStack = array_unique($this->_crawlUrlStack); }
return TRUE;}
function persistTodoStack() {foreach($this->_crawlUrlStack as $url) {$sql  = "INSERT INTO Bs_Wse_{$this->_profile->profileName}_Queue (sourceID, todo) VALUES ('" . $this->_bsDb->escapeString($url) . "', 'a')"; $status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in persistStack()', __FILE__, __LINE__);return $status;}
}
return TRUE;}
function dropTodoStack() {$sql  = "DELETE FROM Bs_Wse_{$this->_profile->profileName}_Queue";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in dropTodoStack()', __FILE__, __LINE__);return $status;}
return TRUE;}
function _getNextUrlFromStack() {if (!empty($this->_crawlUrlStack)) {reset($this->_crawlUrlStack);while (list($k,$url) = each($this->_crawlUrlStack)) {unset($this->_crawlUrlStack[$k]);return $url;}
}
return FALSE;}
function _logMessage($msg) {bs_logIt($msg, '', __LINE__, '_logMessage', __FILE__);echo $msg . "<br>\n";}
function getPageID($url) {$sql = "SELECT ID FROM Bs_Wse_{$this->_profile->profileName}_Pages WHERE url LIKE '" . $this->_bsDb->escapeString($url) . "'";$status = $this->_bsDb->getOne($sql);if (isEx($status)) {$status->stackTrace('was here in getPageID()', __FILE__, __LINE__);return $status;}
return $status;}
function getPageUrl($pageID) {$sql = "SELECT url FROM Bs_Wse_{$this->_profile->profileName}_Pages WHERE ID = {$pageID}";$status = $this->_bsDb->getOne($sql);if (isEx($status)) {$status->stackTrace('was here in getPageUrl()', __FILE__, __LINE__);return $status;}
return $status;}
function fetchPageInfoByUrl($url) {$sql = "SELECT * FROM Bs_Wse_{$this->_profile->profileName}_Pages WHERE url LIKE '" . $this->_bsDb->escapeString($url) . "'";$status = $this->_bsDb->getRow($sql);if (isEx($status)) {$status->stackTrace('was here in fetchPageInfoByUrl()', __FILE__, __LINE__);return $status;}
return $status;}
function fetchPageInfoById($pageID) {$sql = "SELECT * FROM Bs_Wse_{$this->_profile->profileName}_Pages WHERE ID = {$pageID}";$status = $this->_bsDb->getRow($sql);if (isEx($status)) {$status->stackTrace('was here in fetchPageInfoById()', __FILE__, __LINE__);return $status;}
return $status;}
function fetchPageList() {$sql = "SELECT ID, url FROM Bs_Wse_{$this->_profile->profileName}_Pages";$status = $this->_bsDb->getAssoc($sql);if (isEx($status)) {$status->stackTrace('was here in fetchPageList()', __FILE__, __LINE__);return $status;}
return $status;}
function _indexPageInfo($url, $urlParsed, $title, $description, $contentSnapshot, $language, $mimeType, $contentMd5, $sizeBytes) {$currentRecord = $this->fetchPageInfoByUrl($url);if (isEx($currentRecord)) {$this->_createDbTables();$currentRecord = $this->fetchPageInfoByUrl($url);}
if (isEx($currentRecord)) {$currentRecord->stackTrace('was here in _indexPageInfo()', __FILE__, __LINE__);return $currentRecord;} elseif (is_array($currentRecord)) {if ($currentRecord['contentMd5'] === $contentMd5) {}
}
$lastIndexDatetime = gmdate('Y-m-d H:i:s');$dataArr = array(
'url'                => $url, 
'category'           => $this->_profile->getCategoryForUrl($url, $urlParsed), 
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
$sql .= "Bs_Wse_{$this->_profile->profileName}_Pages ";$sql .= " SET ";$sql .= $this->_bsDb->quoteArgs($dataArr);if (is_array($currentRecord)) {$sql .= " WHERE ID = {$currentRecord['ID']}";}
$status = $this->_bsDb->countWrite($sql);if (isEx($status)) {$this->_createDbTables(); $status = $this->_bsDb->countWrite($sql);}
if (isEx($status)) {$status->stackTrace('was here in _indexPageInfo()', __FILE__, __LINE__);return $status;} elseif ($status === 0) {return TRUE;}
return TRUE;}
function isIgnoredUrl($url, $forIndexing=TRUE) {$urlParsed = $this->Bs_Url->parseUrlExtended($url);if (!empty($urlParsed['extension'])) {static $ife;$ife = array(
'gif', 'jpg', 'jpeg', 'png', 'bmp', 'tiff', 'tif', 'eps', 'ico', 
'midi', 'mdi', 'wav', 'mp3', 'avi', 'mov', 
'jar', 'java', 
'js', 'css', 'vbs'
);if (in_array(strToLower($urlParsed['extension']), $ife)) return TRUE;}
if (!empty($urlParsed['extension'])) {if (in_array(strToLower($urlParsed['extension']), $this->_profile->ignoreFileExtensions)) return TRUE;}
if (isSet($this->_profile->allowIgnore) && is_array($this->_profile->allowIgnore)) {foreach($this->_profile->allowIgnore as $arr) {switch (@$arr['type']) {case 'preg':
break;case 'ereg':
break;case 'file':
$t1 = (string)$arr['value'];$t2 = (string)@$urlParsed['file'];if ($t1 === $t2) return $arr['ignore'];break;case 'parti':
if (strpos(strToLower($url), strToLower($arr['value'])) !== FALSE) return $arr['ignore'];break;default: if (strpos($url, $arr['value']) !== FALSE) return $arr['ignore'];}
}
}
if (isSet($this->_profile->allowUrls) && is_array($this->_profile->allowUrls)) {foreach($this->_profile->allowUrls as $arr) {switch (@$arr['type']) {case 'preg':
break;case 'ereg':
break;case 'file':
$t1 = (string)$arr['value'];$t2 = (string)@$urlParsed['file'];if ($t1 === $t2) return FALSE;break;default: if (strpos($url, $arr['value']) !== FALSE) return FALSE;}
}
}
if (!empty($urlParsed['query'])) {if (($this->_profile->queryStringUrlLimit !== -1) && (!empty($urlParsed['query']))) {if ($this->_profile->queryStringUrlLimit === 0) return TRUE;if ($forIndexing) {$urlLimited = $this->Bs_Url->getUrlJunk('suPhOp', $url); $t = (isSet($this->_crawlUrlIgnoredQueryLimit[$urlLimited])) ? $this->_crawlUrlIgnoredQueryLimit[$urlLimited] : 0;$this->_crawlUrlIgnoredQueryLimit[$urlLimited] = $t + 1;if ($this->_crawlUrlIgnoredQueryLimit[$urlLimited] > $this->_profile->queryStringUrlLimit) return TRUE;}
}
}
if (isSet($this->_profile->ignoreUrls) && is_array($this->_profile->ignoreUrls)) {foreach($this->_profile->ignoreUrls as $arr) {switch (@$arr['type']) {case 'preg':
break;case 'ereg':
break;case 'file':
$t1 = (string)$arr['value'];$t2 = (string)@$urlParsed['file'];if ($t1 === $t2) return TRUE;break;default: if (strpos($url, $arr['value']) !== FALSE) return TRUE;}
}
}
return FALSE;}
function _getDescription($url, $description) {if (empty($description)) return ''; switch ($this->_profile->useDescription) {case 0:
return '';break;case 1:
$descMd5     = md5($description);$urlLower    = strToLower($url);foreach($this->_descriptionMd5 as $md5Url => $md5Key) {if (($descMd5 === $md5Key) && ($urlLower !== $md5Url)) {return '';break;}
}
$this->_descriptionMd5[$urlLower] = $descMd5;break;case 2:
if (strToLower($description) === @strToLower($this->_descriptionFrontpage)) {return '';break;}
break;case 3:
break;}
return $description;}
function _getKeywords($url, $keywords) {if (empty($keywords)) return ''; switch ($this->_profile->useKeywords) {case 0:
return '';break;case 1:
$descMd5     = md5($keywords);$urlLower    = strToLower($url);foreach($this->_keywordsMd5 as $md5Url => $md5Key) {if (($descMd5 === $md5Key) && ($urlLower !== $md5Url)) {return '';break;}
}
$this->_keywordsMd5[$urlLower] = $descMd5;break;case 2:
if (strToLower($keywords) === @strToLower($this->_keywordsFrontpage)) {return '';break;}
break;case 3:
break;}
return $keywords;}
function fetchLinksToPageByUrl($url) {$sql  = "SELECT urlFrom as href, caption FROM Bs_Wse_{$this->_profile->profileName}_Links WHERE urlTo LIKE '";$sql .= $this->_bsDb->escapeString($url) . "'";$status = $this->_bsDb->getAll($sql);if (isEx($status)) {$status->stackTrace('was here in fetchLinksToPageByUrl()', __FILE__, __LINE__);return $status;}
return $status;}
function fetchLinksFromPageByUrl($url) {$sql  = "SELECT urlTo as href, caption FROM Bs_Wse_{$this->_profile->profileName}_Links WHERE urlFrom LIKE '";$sql .= $this->_bsDb->escapeString($url) . "'";$status = $this->_bsDb->getAll($sql);if (isEx($status)) {$status->stackTrace('was here in fetchLinksFromPageByUrl()', __FILE__, __LINE__);return $status;}
return $status;}
function getExternalLinks() {$sql  = "SELECT ID, urlFrom, urlTo, caption FROM Bs_Wse_{$this->_profile->profileName}_Links WHERE isExternal = 1";$status = $this->_bsDb->getAssoc($sql);if (isEx($status)) {$status->stackTrace('was here in getExternalLinks()', __FILE__, __LINE__);return $status;}
return $status;}
function _indexLinkFromTo($urlFrom, $urlTo, $caption, $isExternal=FALSE) {$sql  = "INSERT INTO Bs_Wse_{$this->_profile->profileName}_Links (urlFrom, urlTo, caption, isExternal) VALUES ('";$sql .= $this->_bsDb->escapeString($urlFrom) . "', '";$sql .= $this->_bsDb->escapeString($urlTo)   . "', '";$sql .= $this->_bsDb->escapeString($caption) . "', ";$sql .= (int)$isExternal . ")";$status = $this->_bsDb->write($sql);if (isEx($status)) {$this->_createDbTables(); $status = $this->_bsDb->write($sql);}
if (isEx($status)) {$status->stackTrace('was here in _indexLinkFromTo()', __FILE__, __LINE__);return $status;}
return TRUE;}
function _dropLinksFromTo($urlFrom, $urlTo) {$sql  = "DELETE FROM Bs_Wse_{$this->_profile->profileName}_Links WHERE ";$sql .= "urlFrom LIKE '" . $this->_bsDb->escapeString($urlFrom) . "' ";$sql .= "AND urlTo LIKE '" . $this->_bsDb->escapeString($urlTo) . "'";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in _dropLinksFromTo()', __FILE__, __LINE__);return $status;}
return TRUE;}
function validateExternalLinks() {$eLinks = $this->getExternalLinks();if (isEx($eLinks)) {$eLinks->stackTrace('was here in validateExternalLinks()', __FILE__, __LINE__);return $eLinks;}
$ret = '';foreach($eLinks as $arr) {$ret .= "checking url: {$arr['urlTo']} ... ";$content = $this->_httpClient->fetchPage($url, NULL, NULL, NULL, 'HEAD', TRUE);$responseCode = $this->_httpClient->responseCode;if (($responseCode !== 200) && ($responseCode !== 404)) {sleep(2);$content = $this->_httpClient->fetchPage($url, NULL, NULL, NULL, 'HEAD', TRUE);$responseCode = $this->_httpClient->responseCode;}
if ($responseCode !== 200) {$codeInfo = $this->_httpClient->responseCodeInfo($responseCode);$ret .= "checking url: {$arr['urlTo']} ...";} else {}
}
}
function fetchWordsForPageByID($pageID, $order='caption') {$url = $this->getPageUrl($pageID);$tblRel  = "Bs_Is_{$this->_profile->profileName}_WordToSource";$tblWord = "Bs_Is_{$this->_profile->profileName}_Words";$sql  = "SELECT {$tblWord}.caption, {$tblRel}.wordID, {$tblRel}.ranking FROM {$tblRel} left join {$tblWord} ON {$tblRel}.wordID={$tblWord}.ID WHERE sourceID LIKE '$url'";if ($order === 'ranking') {$sql .= " ORDER BY {$tblWord}.ranking";} else { $sql .= " ORDER BY {$tblWord}.caption";}
$status = $this->_bsDb->getAll($sql);if (isEx($status)) {$status->stackTrace('was here in fetchWordsForPageByID()', __FILE__, __LINE__);return $status;}
return $status;}
function _fixUrl($newUrl, $oldUrl) {if (!strpos($newUrl, '://')) {if (substr($newUrl, 0, 1) === '/') { $newUrl = $this->Bs_Url->getUrlJunk('suPhO', $oldUrl)  . $newUrl;} else {$newUrl = $this->Bs_Url->getUrlJunk('suPhOi', $oldUrl) . $newUrl;}
}
return $this->Bs_Url->realUrl($newUrl);}
}
if (basename($_SERVER['PHP_SELF']) == 'Bs_Wse_Walker.class.php') {}
?>