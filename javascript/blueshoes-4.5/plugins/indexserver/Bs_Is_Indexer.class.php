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
require_once($APP['path']['core']    . 'util/Bs_String.class.php');require_once($APP['path']['core']    . 'html/Bs_HtmlUtil.class.php');require_once($APP['path']['core']    . 'html/Bs_HtmlInfo.class.php');require_once($APP['path']['core']    . 'file/Bs_Dir.class.php');require_once($APP['path']['core']    . 'net/http/Bs_HttpClient.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_ConverterHtml.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_ConverterPdf.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_ConverterWord.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_ConverterExcel.class.php');require_once($APP['path']['core']    . 'util/Bs_StopWatch.class.php');Class Bs_Is_Indexer extends Bs_Object {var $Bs_Is_IndexServer;var $_profile;var $_bsDb;var $_httpClient;var $debug    = FALSE;var $debugOut = '';var $_cacheWordID;var $_mimeTypeHandlers = array(
'mimeTypes'   => array(), 
'fileEndings' => array(), 
);var $doCollocations = FALSE;var $stopWatch;function Bs_Is_Indexer(&$Bs_Is_IndexServer, &$profile, &$bsDb) {parent::Bs_Object();  $this->Bs_Is_IndexServer = &$Bs_Is_IndexServer;$this->_profile          = &$profile;$this->_bsDb             = &$bsDb;$this->_httpClient    =& new Bs_HttpClient();$this->_httpClient->parseHeader = TRUE;$Bs_Is_ConverterHtml =& new Bs_Is_ConverterHtml();$this->registerMimeTypeHandler($Bs_Is_ConverterHtml, 'text/html', 'html');$this->registerMimeTypeHandler($Bs_Is_ConverterHtml, NULL, 'htm');$Bs_Is_ConverterPdf =& new Bs_Is_ConverterPdf();$this->registerMimeTypeHandler($Bs_Is_ConverterPdf, 'application/pdf', 'pdf');$Bs_Is_ConverterWord =& new Bs_Is_ConverterWord();$this->registerMimeTypeHandler($Bs_Is_ConverterWord, 'application/msword', 'doc');$Bs_Is_ConverterExcel =& new Bs_Is_ConverterExcel();$this->registerMimeTypeHandler($Bs_Is_ConverterExcel, 'application/vnd.ms-excel', 'xls');$this->stopWatch =& new Bs_StopWatch();$this->stopWatch->takeTime('Bs_Is_Indexer start');}
function registerMimeTypeHandler(&$obj, $mimeType=NULL, $fileEnding=NULL) {if (!is_null($mimeType)) {$this->_mimeTypeHandlers['mimeTypes'][$mimeType]     = &$obj;}
if (!is_null($fileEnding)) {$this->_mimeTypeHandlers['fileEndings'][$fileEnding] = &$obj;}
return TRUE;}
function &getMimeTypeHandler($mime, $type='mimeTypes') {if (isSet($this->_mimeTypeHandlers[$type][$mime])) {return $this->_mimeTypeHandlers[$type][$mime];}
return FALSE;}
function index($key, $data, $props=NULL) {$this->stopWatch->takeTime('index()');$wordRank = $this->_getWordRank($data, $props);if ($this->debug) $this->debugOut .= "compiled wordRank: " . dump($wordRank, TRUE);return $this->indexRankedData($key, $wordRank);}
function indexByUrl($url, $key=NULL, $props=NULL) {if (is_null($props)) {$props = array(
'path'    => array('index'=>TRUE, 'type'=>'text', 'weight'=>100), 
'file'    => array('index'=>TRUE, 'type'=>'text', 'weight'=>100), 
'content' => array('index'=>TRUE, 'type'=>'text', 'weight'=>100), 
);}
$content = $this->_fetchPage($url);if (isEx($content))           return FALSE; if ($content['code'] !== 200) return FALSE; $isOk = FALSE;do {if (isSet($this->_mimeTypeHandlers['mimeTypes'][$content['mime']])) {$obj = &$this->_mimeTypeHandlers['mimeTypes'][$content['mime']];if (method_exists($obj, 'stringToArray')) {$status = $obj->stringToArray($content['content']);if ($status === FALSE) break;if (isEx($status)) {break;}
} elseif (method_exists($obj, 'stringToTextString')) {$status = $obj->stringToTextString($content['content']);if ($status === FALSE) break;} else {break; }
$fileContent = $status['values'];$isOk        = TRUE;if (is_array($fileContent) && !isSet($props['content']['fieldSet'])) {$props['content']['fieldSet'] = $status['weights'];}
}
} while (FALSE);if (!$isOk) {$fileContent = $content['content'];}
$data = array(
'path'    => substr($fileFullPath, 0, strlen($fileFullPath) - strlen(basename($fileFullPath))), 
'file'    => basename($fileFullPath), 
'content' => $fileContent, 
);if (is_null($key)) $key = $url;return $this->index($key, $data, $props);}
function indexByPath($fileFullPath, $key=NULL, $props=NULL) {if (!file_exists($fileFullPath)) return FALSE;if (!is_readable($fileFullPath)) return FALSE;if (is_null($props)) {$props = array(
'path'    => array('index'=>TRUE, 'type'=>'text', 'weight'=>100), 
'file'    => array('index'=>TRUE, 'type'=>'text', 'weight'=>100), 
'content' => array('index'=>TRUE, 'type'=>'text', 'weight'=>100), 
);}
$dir =& new Bs_Dir();$fileExtension = strToLower($dir->getFileExtension($fileFullPath));$isOk = FALSE;do {if (isSet($this->_mimeTypeHandlers['fileEndings'][$fileExtension])) {$obj = &$this->_mimeTypeHandlers['fileEndings'][$fileExtension];if (method_exists($obj, 'streamToArray')) {$status = $obj->streamToArray($fileFullPath);if ($status === FALSE) break;if (isEx($status)) {break;}
} elseif (method_exists($obj, 'streamToTextString')) {$status = $obj->streamToTextString($fileFullPath);if ($status === FALSE) break;} else {break; }
$fileContent = $status['values'];$isOk        = TRUE;if (is_array($fileContent) && !isSet($props['content']['fieldSet'])) {$props['content']['fieldSet'] = $status['weights'];}
}
} while (FALSE);if (!$isOk) {$fileContent = join('', file($fileFullPath));}
$data = array(
'path'    => substr($fileFullPath, 0, strlen($fileFullPath) - strlen(basename($fileFullPath))), 
'file'    => basename($fileFullPath), 
'content' => $fileContent, 
);if (is_null($key)) $key = $fileFullPath;return $this->index($key, $data, $props);}
function indexBySqlWhere($where) {}
function indexRankedData($key, $rankedData) {$this->stopWatch->takeTime('indexRankedData()');$this->unindex($key);$this->stopWatch->takeTime('indexRankedData() LINE: ' . __LINE__);reset($rankedData);while (list($word) = each($rankedData)) {$rank   = $rankedData[$word]['rank'];$lang   = (isSet($rankedData[$word]['lang'])) ? $rankedData[$word]['lang'] : '';$wordID = $this->getWordID($word);if ($wordID === FALSE) {if (is_array($lang) && (sizeOf($lang) > 1)) {$wordID = $this->_indexWord($word, $lang[0]);} elseif (is_array($lang) && (sizeOf($lang) == 1)) {$wordID = $this->_indexWord($word, $lang[0]);} else {$wordID = $this->_indexWord($word);}
if (isEx($wordID)) {$wordID->stackDump('echo');continue; }
} elseif (isEx($wordID)) {$wordID->stackDump('echo'); continue; } else {$sql = "UPDATE Bs_Is_" . $this->_profile->getProfileName() . "_words SET useCount = (useCount +1) WHERE ID = {$wordID}";$status = $this->_bsDb->write($sql);}
$this->stopWatch->takeTime('indexRankedData() LINE: ' . __LINE__);$rnbs = array();$rankedData[$word]['rnbs'] = array_unique($rankedData[$word]['rnbs']); while (list(,$rnbsWordString) = each($rankedData[$word]['rnbs'])) {$rnbsWordID = $this->getWordID($rnbsWordString);if ($rnbsWordID === FALSE) {$rnbsWordID = $this->_indexWord($rnbsWordString, @$rankedData[$rnbsWordString]['lang'], 0);}
if (isEx($rnbsWordID)) {} else {$rnbs[] = $rnbsWordID;}
}
$rnbsString = join(',', $rnbs);if (!empty($rnbsString)) $rnbsString = ',' . $rnbsString . ',';$sql = "INSERT INTO Bs_Is_" . $this->_profile->getProfileName() . "_WordToSource SET wordID={$wordID}, sourceID='{$key}', ranking={$rank}, rnbs_wordIDs='{$rnbsString}'";$status = $this->_bsDb->write($sql);}
$this->stopWatch->takeTime('indexRankedData() LINE: ' . __LINE__);if ($this->doCollocations) {reset($rankedData);while (list($word) = each($rankedData)) {$wordID = $this->getWordID($word);foreach ($rankedData[$word]['collo'] as $colloWord => $colloPoints) {$colloWordID = $this->getWordID($colloWord);if ($wordID > $colloWordID) {$sql = "SELECT ID FROM Bs_Is_" . $this->_profile->getProfileName() . "_Collocations WHERE sourceID='{$key}' AND (first_wordID='{$colloWordID}' AND second_wordID='{$wordID}')";} else {$sql = "SELECT ID FROM Bs_Is_" . $this->_profile->getProfileName() . "_Collocations WHERE sourceID='{$key}' AND (first_wordID='{$wordID}' AND second_wordID='{$colloWordID}')";}
$colloRecordID = $this->_bsDb->getOne($sql);if (is_numeric($colloRecordID)) {$sql = "UPDATE Bs_Is_" . $this->_profile->getProfileName() . "_Collocations SET ranking=ranking+{$colloPoints} WHERE ID={$colloRecordID}";$status = $this->_bsDb->write($sql);} else {if ($wordID > $colloWordID) {$sql = "INSERT INTO Bs_Is_" . $this->_profile->getProfileName() . "_Collocations (sourceID, first_wordID, second_wordID, useCount, ranking) VALUES ('{$key}', {$colloWordID}, {$wordID}, 0, {$colloPoints})";} else {$sql = "INSERT INTO Bs_Is_" . $this->_profile->getProfileName() . "_Collocations (sourceID, first_wordID, second_wordID, useCount, ranking) VALUES ('{$key}', {$wordID}, {$colloWordID}, 0, {$colloPoints})";}
$status = $this->_bsDb->write($sql);}
}
}
}
$this->stopWatch->takeTime('indexRankedData() LINE: ' . __LINE__);return TRUE;}
function _assignLanguage(&$wordList, $lang) {reset($wordList['words']);while (list($wordString) = each($wordList['words'])) {$wordList['words'][$wordString]['lang'] = $lang;}
}
function unindexRecord($recordID) {return $this->unindex($recordID);}
function unindex($key) {$sql = "SELECT wordID FROM Bs_Is_" . $this->_profile->getProfileName() . "_WordToSource WHERE sourceID = '{$key}'";$idList = $this->_bsDb->getCol($sql);if (is_array($idList) && !empty($idList)) {$sql = "UPDATE Bs_Is_" . $this->_profile->getProfileName() . "_words SET useCount = (useCount -1) WHERE ID IN (" . join(',', $idList) . ")";$status = $this->_bsDb->write($sql);$sql = "DELETE FROM Bs_Is_" . $this->_profile->getProfileName() . "_words WHERE useCount = 0";$status = $this->_bsDb->write($sql);}
$sql = "DELETE FROM Bs_Is_" . $this->_profile->getProfileName() . "_WordToSource WHERE sourceID = '{$key}'";$status = $this->_bsDb->write($sql);return TRUE;}
function getWordID($word) {if (isSet($this->_cacheWordID[$word])) return $this->_cacheWordID[$word]; $sql = "SELECT ID FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE caption='{$word}'";$wordID = $this->_bsDb->getOne($sql);if (isEx($wordID)) {$wordID->stackTrace('was here in getWordID()', __FILE__, __LINE__);return $wordID;}
if (is_numeric($wordID) && ($wordID > 0)) {$this->_cacheWordID[$word] = $wordID;return $wordID;}
return FALSE;}
function _indexWord($word, $lang='', $useCount=1) {$noitpac = strrev($word);$stem = $this->Bs_Is_IndexServer->getStem($word, $lang);if ($word === $stem) $stem = ''; $sql = "INSERT INTO Bs_Is_" . $this->_profile->getProfileName() . "_Words SET caption='" . addSlashes($word) . "', noitpac='" . addSlashes($noitpac) . "', soundx=soundex('" . addSlashes($word) . "'), stem='" . $stem . "', languages='" . $lang . "', useCount={$useCount}";$wordID = $this->_bsDb->idWrite($sql);if (isEx($wordID)) {$wordID->stackTrace('was here in getWordID()', __FILE__, __LINE__);return $wordID;}
return $wordID;}
function _rankWordData($wordData) {$ret = array();reset($wordData);while (list($k) = each($wordData)) {reset($wordData[$k]['words']);while (list($word) = each($wordData[$k]['words'])) {$useCount = (isSet($wordData[$k]['words'][$word]['amount'])) ? $wordData[$k]['words'][$word]['amount'] : 0;if (is_null($wordData[$k]['size'])) {$useCount = $wordData[$k]['words'][$word]['rank'];$rankPercent = $useCount / 1500 * 100;$rankPercent = 100 - $rankPercent;if ($this->debug) $this->debugOut .= "the word '{$word}' comes from a linked source (foreign key, external file or so) and thus has an inherited ranking of {$useCount}. the rank here is (100 - {$rankPercent})%.<br>";} else {$rankPercent = sqrt($wordData[$k]['size'] / $useCount) *10;  if ($this->debug) $this->debugOut .= "the word '{$word}' has been found {$useCount} times out of {$wordData[$k]['size']} words and gets (100 - {$rankPercent})%.<br>";}
$rankReal    = (int)($wordData[$k]['fieldWeight'] / $rankPercent *100);if (isSet($ret[$word])) {$ret[$word]['rank'] += $rankReal;$ret[$word]['lang']  = array_merge($ret[$word]['lang'],  $wordData[$k]['words'][$word]['lang']);$ret[$word]['rnbs']  = array_merge($ret[$word]['rnbs'],  $wordData[$k]['words'][$word]['rnbs']);if ($this->doCollocations) {$ret[$word]['collo'] = array_merge($ret[$word]['collo'], $wordData[$k]['words'][$word]['collo']);} else {$ret[$word]['collo'] = array();}
} else {$ret[$word]['rank']  = $rankReal;$ret[$word]['lang']  = $wordData[$k]['words'][$word]['lang'];$ret[$word]['rnbs']  = $wordData[$k]['words'][$word]['rnbs'];if ($this->doCollocations) {$ret[$word]['collo'] = $wordData[$k]['words'][$word]['collo'];} else {$ret[$word]['collo'] = array();}
}
}
}
return $ret;}
function _getWordList($string, $flipWordList=FALSE) {$ret = array('size'=>0, 'words'=>array());$array = $this->Bs_Is_IndexServer->cleanStringChunkSentence($string);foreach ($array as $sentence) {if (empty($sentence)) continue;$collocations = array();unset($rnbs);$wordArray = explode(' ', $sentence);while(list(,$word) = each($wordArray)) {if (empty($word)) continue;$word = $this->Bs_Is_IndexServer->cleanWord($word, $this->_profile->minWordLength(), $this->_profile->maxWordLength());if (($word === FALSE) || $this->Bs_Is_IndexServer->isStopWord($word)) {$collocations[] = '';} else {$collocations[] = $word;if (isSet($ret['words'][$word])) {$tAmount = $ret['words'][$word]['amount'];$tRnbs   = $ret['words'][$word]['rnbs'];unset($ret['words'][$word]);$ret['words'][$word]['amount'] = $tAmount + 1;$ret['words'][$word]['rnbs']   = $tRnbs;} else {$ret['words'][$word]['amount'] = 1;$ret['words'][$word]['rnbs']   = array();}
if ($this->doCollocations) {$ret['words'][$word]['collo'] = array();$colloSize   = sizeOf($collocations);$colloPoints = 7;for ($colloI=$colloSize-2; $colloI>=0; $colloI--) {if ($colloPoints <= 0) break;$colloWord = $collocations[$colloI];if (empty($colloWord)) {$colloPoints--;continue;}
if ($word !== $colloWord) { if (isSet($ret['words'][$word]['collo'][$colloWord])) {$ret['words'][$word]['collo'][$colloWord] += $colloPoints;} else {$ret['words'][$word]['collo'][$colloWord] = $colloPoints;}
}
$colloPoints -= 2;}
}
$rnbs[] = $word;unset($rnbs); $rnbs = &$ret['words'][$word]['rnbs'];}
}
}
if ($flipWordList) {$ret['words'] = array_reverse($ret['words']);}
$ret['size'] = sizeOf($ret['words']);return $ret;}
function _fetchPlainTextContent($data, $props) {$type = $props['type'];if ($props['type'] === 'path') {$fileFullPath = $data;if (!empty($props['pathPrefix'])) $fileFullPath = $props['pathPrefix'] . $fileFullPath;if ((empty($data) && ($data != '0')) || !file_exists($fileFullPath) || !is_readable($fileFullPath)) {return '';} else {$dir =& new Bs_Dir();$type = $dir->getFileExtension($data);$data = join('', file($fileFullPath));}
} elseif ($props['type'] === 'url') {$dir =& new Bs_Dir();$type = $dir->getFileExtension($data);$data = join('', file($data));}
$isOk = FALSE;do {if (isSet($this->_mimeTypeHandlers['fileEndings'][$type])) {$obj = &$this->_mimeTypeHandlers['fileEndings'][$type];if (method_exists($obj, 'streamToArray')) {$status = $obj->streamToArray($fileFullPath);if ($status === FALSE) break;if (isEx($status)) {break;}
} elseif (method_exists($obj, 'streamToTextString')) {$status = $obj->streamToTextString($fileFullPath);if ($status === FALSE) break;} elseif (method_exists($obj, 'fileToString')) {$status = $obj->fileToString($fileFullPath);if ($status === FALSE) break;} else {break; }
$fileContent = $status['values'];$isOk        = TRUE;if (is_array($fileContent) && !isSet($props['content']['fieldSet'])) {$props['content']['fieldSet'] = $status['weights'];}
}
} while (FALSE);if (!$isOk) {$fileContent = join('', file($fileFullPath));}
return $fileContent;}
function _fetchDataByType($data, $props) {switch (@$props['type']) {case 'text':
return $data;case 'html':
case 'xml':
case 'pdf':
case 'doc':
case 'xls':
return $this->_fetchPlainTextContent($data, $props);case 'path':
$dir =& new Bs_Dir();$ret = array();$ret['path']    = $dir->getPathStem($data);$ret['file']    = $dir->basename($data);$ret['content'] = $this->_fetchPlainTextContent($data, $props);return $ret;case 'url':
$dir =& new Bs_Dir();$ret = array();$ret['path']    = $dir->getPathStem($data);$ret['file']    = $dir->basename($data);$ret['content'] = $this->_fetchPlainTextContent($data, $props);return $ret;case 'key':
$dbTableString = '';if (!empty($props['key']['db'])) $dbTableString .= $props['key']['db'] . '.';$dbTableString .= $props['key']['table'];$sql  = "SELECT * FROM {$dbTableString} WHERE ID = '{$data}'";$status = $this->_bsDb->getRow($sql);if (is_array($status)) return $status;return ''; default:
if (TRUE) {return $data;} else {return '';}
}
}
function _getWordRank($data, $props=NULL) {$this->stopWatch->takeTime('_getWordRank()');if (!is_array($data)) {$data  = array($data);$props = array($props);}
if (is_array($data) && (sizeOf($data) == 2) && isSet($data['type']) && isSet($data['values'])) {switch ($data['type']) {case 'html':
$props = array(
'title'       => array('weight'=>100), 
'description' => array('weight'=>40), 
'keywords'    => array('weight'=>5), 
'links'       => array('weight'=>10), 
'h1'          => array('weight'=>80), 
'h2'          => array('weight'=>70), 
'h3'          => array('weight'=>60), 
'h4'          => array('weight'=>50), 
'h5'          => array('weight'=>40), 
'h6'          => array('weight'=>30), 
'h7'          => array('weight'=>20), 
'h8'          => array('weight'=>10), 
'b'           => array('weight'=>10), 'i'           => array('weight'=>8), 
'u'           => array('weight'=>8), 
'body'        => array('weight'=>5), 
);break;default:
}
$data = $data['values'];}
$wordData = array();reset($data);while (list($fieldName) = each($data)) {if ($this->debug) $this->debugOut .= "in _getWordRank for field: {$fieldName}<br>\n";$fieldProps = (isSet($props[$fieldName])) ? $props[$fieldName] : array('type'=>'text', 'weight'=>50, 'lang'=>'');if (@$fieldProps['index'] === FALSE) continue;$fieldData  = $this->_fetchDataByType($data[$fieldName], $fieldProps);if (is_array($fieldData)) {if ($this->debug) $this->debugOut .= "got an array, calling myself recursively.<br>\n";if (isSet($fieldProps['path'])) {$subFieldProps = $fieldProps['path'];} elseif (isSet($fieldProps['url'])) {$subFieldProps = $fieldProps['url'];} elseif (isSet($fieldProps['key']['fieldSet'])) {$subFieldProps = $fieldProps['key']['fieldSet'];} else {$subFieldProps = NULL;}
$wordRank = $this->_getWordRank($fieldData, $subFieldProps);$wordList = array();$wordList['words'] = $wordRank;$wordList['size']  = NULL;if ($this->debug) $this->debugOut .= "got the following wordRank: " . dump($wordRank, TRUE) . "<br>\n";} else {$wordList = $this->_getWordList($fieldData, TRUE);$this->_assignLanguage($wordList, @$fieldProps['lang']); if ($this->debug) $this->debugOut .= "got the following wordList: " . dump($wordList, TRUE) . "<br>\n";}
$wordData[] = array(
'words'       => $wordList['words'], 
'size'        => $wordList['size'], 
'fieldWeight' => $fieldProps['weight'], 
);}
$wordRank = $this->_rankWordData($wordData);if ($this->debug) $this->debugOut .= "got the following wordRank: " . dump($wordRank, TRUE) . "<br>\n";return $wordRank;}
function _fetchPage($url) {$content = $this->_httpClient->fetchPage($url);if (isEx($content)) {return $content;}
$contentType = explode(';', $this->_httpClient->headerParsed['content-type']);$ret = array(
'code'    => $this->_httpClient->responseCode, 
'mime'    => trim($contentType[0]), 
'content' => $content, 
);return $ret;}
}
?>