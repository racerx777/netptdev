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
require_once($APP['path']['core'] . 'text/Bs_TextUtil.class.php');require_once($APP['path']['core'] . 'util/Bs_StopWatch.class.php');Class Bs_Is_Searcher extends Bs_Object {var $Bs_Is_IndexServer;var $Bs_TextUtil;var $_profile;var $_bsDb;var $lang;var $hintString = '';var $hintWords;var $searchTime;var $parsedQuery;var $queryData;var $stopWatch;function Bs_Is_Searcher(&$Bs_Is_IndexServer, &$profile, &$bsDb) {parent::Bs_Object();  $this->Bs_Is_IndexServer = &$Bs_Is_IndexServer;$this->Bs_TextUtil      = &$GLOBALS['Bs_TextUtil'];$this->_profile         = &$profile;$this->_bsDb            = &$bsDb;$this->stopWatch =& new Bs_StopWatch();$this->stopWatch->takeTime('Bs_Is_Searcher start');}
function search($searchString, $lang=NULL, $features=NULL) {$this->stopWatch->takeTime('search()');$this->hintString  = '';$this->searchTime  = NULL;$searchTimeStart   = microtime();$this->parsedQuery = NULL;$this->queryData   = NULL;if (is_null($features)) {$features = array(
'part'      => FALSE, 
'stemming'  => FALSE, 
'metaphone' => FALSE, 
'soundex'   => FALSE, 
'synonyme'  => FALSE, 
'caching'   => FALSE, 
'hints'     => 'auto', 
);}
$parsedQuery = $this->Bs_TextUtil->parseSearchQuery($searchString);$this->parsedQuery = $parsedQuery;$this->stopWatch->takeTime('parseSearchQuery() done.');$queryData = $this->_parsedQueryToQueryData($parsedQuery, $lang, $features);$this->stopWatch->takeTime('_parsedQueryToQueryData() done.');$this->_findWordIDs($queryData, $lang, $features);$this->stopWatch->takeTime('_findWordIDs() done.');$this->_searchAddRelations($queryData, $lang, $features);$this->stopWatch->takeTime('_searchAddRelations() done.');$maxWordUseCount = (int)$this->_bsDb->getOne("SELECT MAX(useCount) FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words");if ($maxWordUseCount < 1) $maxWordUseCount = 1; $ignoreMatches = array();reset($queryData);while (list($k) = each($queryData)) {$queryData[$k]['match'] = array();reset($queryData[$k]['tokens']);if (sizeOf($queryData[$k]['tokens']) > 1) {do {$tmpArr             = array();$smallestArraySize  = 0;$smallestArrayIndex = 0;$i = 0;while (list($k2) = each($queryData[$k]['tokens'])) {if ($queryData[$k]['tokens'][$k2]['ignored']) continue; if (!@is_array($queryData[$k]['tokens'][$k2]['match'])) break 2; reset($queryData[$k]['tokens'][$k2]['match']);$wordID = key($queryData[$k]['tokens'][$k2]['match']);if (!is_array($queryData[$k]['tokens'][$k2]['match'][$wordID]['relation'])) break 2; $tmpArr[$i] = $queryData[$k]['tokens'][$k2]['match'][$wordID]['relation'];if (($i == 0) || (sizeOf($tmpArr[$i]) < $smallestArraySize)) {$smallestArrayIndex = $i;$smallestArraySize  = sizeOf($tmpArr[$i]);}
$i++;}
if (is_array(@$tmpArr[$smallestArrayIndex])) {while (list($sourceID) = each($tmpArr[$smallestArrayIndex])) {for ($j=0; $j<$i; $j++) {if ($j == $smallestArrayIndex) continue;if (!isSet($tmpArr[$j][$sourceID])) {unset($tmpArr[$smallestArrayIndex][$sourceID]);continue;} else {$tmpArr[$smallestArrayIndex][$sourceID] += $tmpArr[$j][$sourceID];}
}
if (isSet($tmpArr[$smallestArrayIndex][$sourceID])) { $queryData[$k]['match'][$sourceID] = $tmpArr[$smallestArrayIndex][$sourceID] / $i;}
}
}
} while (FALSE);} else {while (list($k2) = each($queryData[$k]['tokens'])) {if (!isSet($queryData[$k]['tokens'][$k2]['match']) || !is_array($queryData[$k]['tokens'][$k2]['match'])) continue;reset($queryData[$k]['tokens'][$k2]['match']);while (list($wordID) = each($queryData[$k]['tokens'][$k2]['match'])) {if (!isSet($queryData[$k]['tokens'][$k2]['match'][$wordID]['relation'])) continue;reset($queryData[$k]['tokens'][$k2]['match'][$wordID]['relation']);while (list($sourceID, $ranking) = each($queryData[$k]['tokens'][$k2]['match'][$wordID]['relation'])) {if (isSet($ignoreMatches[$sourceID])) continue;if ($queryData[$k]['operator'] === '!') {$ignoreMatches[$sourceID] = TRUE;continue;}
if ($queryData[$k]['operator'] != '!') { if ($queryData[$k]['operator'] == '|') {$ranking = $ranking / 10 * 7; }
switch ($queryData[$k]['tokens'][$k2]['match'][$wordID]['type']) {case 'direct':
case 'part':
$ranking = $ranking / 10 * 6; break;case 'stem':
$ranking /= 2; break;case 'metaphone':
$ranking = $ranking / 100 * 35; break;case 'soundex':
$ranking = $ranking / 10 * 3; break;}
$wordUseCount = (int)$queryData[$k]['tokens'][$k2]['match'][$wordID]['wordInfo']['useCount'];$wordLength   = strlen($queryData[$k]['tokens'][$k2]['wordInternal']);$wordWeight   = $this->_wordRankForRelation($wordUseCount, $wordLength, $maxWordUseCount);$endWordRank = $ranking * $wordWeight / 100;}
if (isSet($queryData[$k]['match'][$sourceID])) {$endWordRank /= 3; $queryData[$k]['match'][$sourceID] += $endWordRank;} else {$queryData[$k]['match'][$sourceID] = $endWordRank;}
}
}
}
}
}
$this->stopWatch->takeTime('here on line: ' . __LINE__);$endRecords      = array();$hasMustWords    = FALSE;reset($queryData);while (list($k) = each($queryData)) {if ($queryData[$k]['operator'] !== '&') continue; reset($endRecords);while (list($sourceID) = each($endRecords)) {if (!isSet($queryData[$k]['match'][$sourceID])) {unset($endRecords[$sourceID]);}
}
reset($queryData[$k]['match']);while (list($sourceID) = each($queryData[$k]['match'])) {if (!isSet($endRecords[$sourceID])) {if ($hasMustWords) continue; $endRecords[$sourceID]  = $queryData[$k]['match'][$sourceID];} else {$endRecords[$sourceID] += $queryData[$k]['match'][$sourceID];}
}
$hasMustWords = TRUE;}
$this->stopWatch->takeTime('here on line: ' . __LINE__);reset($endRecords);while (list($sourceID) = each($endRecords)) {if (isSet($ignoreMatches[$sourceID])) unset($endRecords[$sourceID]);}
$this->stopWatch->takeTime('here on line: ' . __LINE__);reset($queryData);while (list($k) = each($queryData)) {if ($queryData[$k]['operator'] !== '|') continue; reset($queryData[$k]['match']);while (list($sourceID) = each($queryData[$k]['match'])) {if (isSet($ignoreMatches[$sourceID])) continue;if (!isSet($endRecords[$sourceID])) {if ($hasMustWords) continue; $endRecords[$sourceID]  = $queryData[$k]['match'][$sourceID];} else {$endRecords[$sourceID] += $queryData[$k]['match'][$sourceID];}
}
}
$this->stopWatch->takeTime('here on line: ' . __LINE__);arsort($endRecords);$this->searchTime = microtime() - $searchTimeStart;$this->queryData  = $queryData;$this->stopWatch->takeTime('here on line: ' . __LINE__);return $endRecords;}
function search2($searchString) {$this->stopWatch->takeTime('search2()');$this->hintString  = '';$this->searchTime  = NULL;$searchTimeStart   = microtime();$this->parsedQuery = NULL;$this->queryData   = NULL;$parsedQuery = $this->Bs_TextUtil->parseSearchQuery2($searchString);$this->parsedQuery = $parsedQuery;$this->stopWatch->takeTime('parseSearchQuery2() done.');$this->_findWordIDs2($parsedQuery);$sourceData = array();$sourceIDs  = array();$status = $this->_search2sql($parsedQuery, $sourceData, $sourceIDs, '&');$ret = array();while (list($sourceID) = each($sourceData)) {$sourceData[$sourceID]['_points'] = 0;while (list($wordID, $ranking) = each($sourceData[$sourceID])) {if ($wordID === '_points') continue;$sourceData[$sourceID]['_points'] += $ranking;}
$ret[$sourceID] = $sourceData[$sourceID]['_points'];}
arsort($ret, SORT_NUMERIC);return $ret;}
function _search2sql(&$parsedQuery, &$sourceData, &$sourceIDs, $preOperator='&') {if ($preOperator === '!') {$backupSourceData = $sourceData;$backupSourceIDs  = $sourceIDs;}
$order = array();reset($parsedQuery);while (list($k) = each($parsedQuery)) {$orderElm = array();$orderElm['key']      = $k;$orderElm['operator'] = $parsedQuery[$k]['operator'];$orderElm['useCount'] = 100;if (isSet($parsedQuery[$k]['words']) && sizeOf($parsedQuery[$k]['words']) > 1) $orderElm['useCount'] += 100;if (!empty($orderElm['list'])) $orderElm['useCount'] += 1000; $order[$parsedQuery[$k]['operator']][] = $orderElm;}
if (isSet($order['&'])) {$sqlNot = '';foreach ($order['&'] as $orderElm) {$baseSql     = "SELECT sourceID, wordID, ranking";$baseSql    .= " FROM Bs_Is_" . $this->_profile->getProfileName() . "_WordToSource";$baseSql    .= " WHERE 1=1";$matchLoop    = 0; $matchLoopAll = 0; if (isSet($parsedQuery[$orderElm['key']]['list'])) {$this->_search2sql($parsedQuery[$orderElm['key']]['list'], $sourceData, $sourceIDs, '&');continue;}
foreach ($parsedQuery[$orderElm['key']]['words'] as $wordNumber => $wordArr) {$matchLoopAll++;$sql = $baseSql;if ($wordArr['ignored'] !== FALSE) continue;if (!empty($sourceIDs)) {$sql  .= " AND sourceID IN ('" . join("','", $sourceIDs) . "')";}
$orStack = array();if (empty($wordArr['match']) || !is_array($wordArr['match'])) {$sourceData = array();$sourceIDs  = array();return FALSE;}
foreach ($wordArr['match'] as $wordIDs) {if (getType($wordIDs) != 'array') {$wordIDs = array($wordIDs);}
if (is_numeric(@$parsedQuery[$orderElm['key']]['near'])) {$sqlCollo  = "SELECT COUNT(*) FROM Bs_Is_" . $this->_profile->getProfileName() . "_Collocations";$sqlCollo .= " WHERE sourceID='%' AND ";foreach ($wordIDs as $wordID) {$parsedQuery[$orderElm['key']]['near'] = 0; foreach ($parsedQuery[$parsedQuery[$orderElm['key']]['near']]['words'][0]['match'] as $colloWordIDs) {if (getType($colloWordIDs) != 'array') {$colloWordIDs = array($colloWordIDs);}
foreach ($colloWordIDs as $colloWordID) {if ($colloWordID < $wordID) {$wordID1 = $colloWordID;$wordID2 = $wordID;} else {$wordID1 = $wordID;$wordID2 = $colloWordID;}
$sqlCollo2[] = "(first_wordID={$wordID1} AND second_wordID={$wordID2})";}
}
}
$sqlCollo .= join(' OR ', $sqlCollo2);$colloData = $this->_bsDb->getAll($sqlCollo);if (empty($colloData) || !is_array($colloData)) continue;}
$orStack[] = "wordID IN (" . join(',', $wordIDs) . ")";}
$sql .= " AND (" . join(' OR ', $orStack) . ")";if ($parsedQuery[$orderElm['key']]['neighbor']) {do {if (!isSet($parsedQuery[$orderElm['key']]['words'][$wordNumber +1])) break;$nextWordArr = $parsedQuery[$orderElm['key']]['words'][$wordNumber +1];$neighborOrStack = array();if (!isSet($nextWordArr['match']) || !is_array($nextWordArr['match'])) break;foreach ($nextWordArr['match'] as $nextWordIDs) {if (getType($nextWordIDs) != 'array') {$nextWordIDs = array($nextWordIDs);}
foreach ($nextWordIDs as $nextWordID) {$neighborOrStack[] = "rnbs_wordIDs LIKE '%,{$nextWordID},%'";}
}
$sql .= " AND (" . join(' OR ', $neighborOrStack) . ")";} while (FALSE);}
$data = $this->_bsDb->getAssoc($sql, TRUE, FALSE);if (empty($data) || !is_array($data)) {$sourceData = array();$sourceIDs  = array();return FALSE;} else {$sourceDataCopy = $sourceData;$sourceData     = array();foreach ($data as $sourceID => $wordData) {if (!empty($sourceDataCopy)) {$sourceData[$sourceID] = $sourceDataCopy[$sourceID];}
$sourceData[$sourceID][$wordData[0]] = $wordData[1];}
}
$sourceIDs = array_keys($data);$matchLoop++;}
}
}
if (isSet($order['|'])) {if (!isSet($sourceIDs)) $sourceIDs = array();foreach ($order['|'] as $orderElm) {$baseSql     = "SELECT sourceID, wordID, ranking";$baseSql    .= " FROM Bs_Is_" . $this->_profile->getProfileName() . "_WordToSource";$baseSql    .= " WHERE 1=1";$matchLoop    = 0; $matchLoopAll = 0; if (isSet($parsedQuery[$orderElm['key']]['list'])) {$this->_search2sql($parsedQuery[$orderElm['key']]['list'], $sourceData, $sourceIDs, '|');continue;}
$phraseSourceIDs = array();foreach ($parsedQuery[$orderElm['key']]['words'] as $wordNumber => $wordArr) {$matchLoopAll++;if ($wordArr['ignored'] !== FALSE) continue;if (empty($wordArr['match']) || !is_array($wordArr['match'])) continue;if (isSet($order['&']) && !empty($sourceIDs)) {$baseSql  .= " AND sourceID IN ('" . join("','", $sourceIDs) . "')";} elseif (!empty($phraseSourceIDs)) {$baseSql  .= " AND sourceID IN ('" . join(',', $phraseSourceIDs) . "')";}
$orStack = array();foreach ($wordArr['match'] as $wordIDs) {if (getType($wordIDs) != 'array') {$wordIDs = array($wordIDs);}
$orStack[] = "wordID IN (" . join(',', $wordIDs) . ")";}
$sql  = $baseSql . " AND (" . join(' OR ', $orStack) . ")";if ($parsedQuery[$orderElm['key']]['neighbor']) {do {if (!isSet($parsedQuery[$orderElm['key']]['words'][$wordNumber +1])) break;$nextWordArr = $parsedQuery[$orderElm['key']]['words'][$wordNumber +1];$neighborOrStack = array();foreach ($nextWordArr['match'] as $nextWordIDs) {if (getType($nextWordIDs) != 'array') {$nextWordIDs = array($nextWordIDs);}
foreach ($nextWordIDs as $nextWordID) {$neighborOrStack[] = "rnbs_wordIDs LIKE '%,{$nextWordID},%'";}
}
$sql .= " AND (" . join(' OR ', $neighborOrStack) . ")";} while (FALSE);}
$data = $this->_bsDb->getAssoc($sql, FALSE, FALSE);if (empty($data) || !is_array($data)) {} else {$sourceDataCopy = $sourceData;$sourceData     = array();foreach ($data as $sourceID => $wordData) {if (!empty($sourceDataCopy) && isSet($sourceDataCopy[$sourceID])) {$sourceData[$sourceID] = $sourceDataCopy[$sourceID];}
$sourceData[$sourceID][$wordData[0]] = $wordData[1];}
}
if (isSet($order['&']) && !empty($sourceIDs)) {$sourceIDs = array_keys($data);} else {$phraseSourceIDs = array_keys($data);}
$matchLoop++;}
}
}
if (isSet($order['!'])) { foreach ($order['!'] as $orderElm) {$baseSql     = "SELECT sourceID"; $baseSql    .= " FROM Bs_Is_" . $this->_profile->getProfileName() . "_WordToSource";$baseSql    .= " WHERE 1=1";$matchLoop    = 0; $matchLoopAll = 0; if (isSet($parsedQuery[$orderElm['key']]['list'])) {$this->_search2sql($parsedQuery[$orderElm['key']]['list'], $sourceData, $sourceIDs, '!');continue;}
foreach ($parsedQuery[$orderElm['key']]['words'] as $wordNumber => $wordArr) {$matchLoopAll++;if (!empty($sourceIDs)) {$baseSql  .= " AND sourceID IN ('" . join("','", $sourceIDs) . "')";}
if (empty($wordArr['match']) || !is_array($wordArr['match'])) continue;$orStack = array();foreach ($wordArr['match'] as $wordIDs) {if (getType($wordIDs) != 'array') {$wordIDs = array($wordIDs);}
$orStack[] = "wordID IN (" . join(',', $wordIDs) . ")";}
$sql  = $baseSql . " AND (" . join(' OR ', $orStack) . ")";if ($parsedQuery[$orderElm['key']]['neighbor']) {do {if (!isSet($parsedQuery[$orderElm['key']]['words'][$wordNumber +1])) break;$nextWordArr = $parsedQuery[$orderElm['key']]['words'][$wordNumber +1];$neighborOrStack = array();foreach ($nextWordArr['match'] as $nextWordIDs) {if (getType($nextWordIDs) != 'array') {$nextWordIDs = array($nextWordIDs);}
foreach ($nextWordIDs as $nextWordID) {$neighborOrStack[] = "rnbs_wordIDs LIKE '%,{$nextWordID},%'";}
}
$sql .= " AND (" . join(' OR ', $neighborOrStack) . ")";} while (FALSE);}
$data = $this->_bsDb->getCol($sql);if (empty($data) || !is_array($data)) {} else {foreach ($data as $sourceID) {if (isSet($sourceData[$sourceID])) {unset($sourceData[$sourceID]);}
}
}
$matchLoop++;}
}
}
if ($preOperator === '!') {foreach ($sourceData as $sourceID => $dev0) {unset($backupSourceData[$sourceID]);}
$sourceData = $backupSourceData;$sourceIDs  = array_keys($sourceData);}
return TRUE;}
function _findWordIDs2(&$parsedQuery) {while (list($k) = each($parsedQuery)) {if (isSet($parsedQuery[$k]['list'])) {$this->_findWordIDs2($parsedQuery[$k]['list']);continue;}
if (!isSet($parsedQuery[$k]['words'])) continue;while (list($kk) = each($parsedQuery[$k]['words'])) {$ignored      = FALSE;$wordInternal = $this->Bs_Is_IndexServer->cleanWord($parsedQuery[$k]['words'][$kk]['word'], $this->_profile->minWordLength(), $this->_profile->maxWordLength(), TRUE);if (is_array($wordInternal)) {$ignored      = $wordInternal[0];$wordInternal = '';} else {if ($this->Bs_Is_IndexServer->isStopWord($wordInternal)) $ignored = 'noise';}
$parsedQuery[$k]['words'][$kk]['ignored']      = $ignored;$parsedQuery[$k]['words'][$kk]['wordInternal'] = $wordInternal;if ($ignored === FALSE) {if ($parsedQuery[$k]['words'][$kk]['fuzzy']) {} elseif ($parsedQuery[$k]['words'][$kk]['part']) {$word = str_replace('*', '%', $parsedQuery[$k]['words'][$kk]['wordInternal']);$sql  = "SELECT * FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE ";if (substr($word, 0, 1) === '%') {$sql .= "noitpac LIKE '" . addSlashes(strrev($word));} else {$sql .= "caption LIKE '" . addSlashes($word);}
$sql .= "'";$wordRecords = $this->_bsDb->getAssoc($sql, TRUE, TRUE);if (!is_null($wordRecords)) {$parsedQuery[$k]['words'][$kk]['match']['part'] = array_keys($wordRecords);}
} elseif ($parsedQuery[$k]['words'][$kk]['stem']) {} else {$sql = "SELECT * FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE caption = '" . addSlashes($parsedQuery[$k]['words'][$kk]['wordInternal']) . "'";$wordRecord = $this->_bsDb->getAssoc($sql, TRUE, TRUE);if (!empty($wordRecord)) {$parsedQuery[$k]['words'][$kk]['match']['direct'] = key($wordRecord);}
}
$this->_addHintForWord($wordInternal, $parsedQuery[$k]['words'][$kk]['word'], empty($wordRecord));}
}
}
}
function recommendWords() {$this->stopWatch->takeTime(__FUNCTION__ . ' on line ' . __LINE__);$wordIDs = array();foreach ($this->queryData as $hash) {if ($hash['operator'] == '!') continue; foreach ($hash['tokens'] as $hash) {if (!@is_array($hash['match'])) continue;while (list($wordID) = each($hash['match'])) {if ($hash['match'][$wordID]['type'] !== 'direct') continue;$this->stopWatch->takeTime(__FUNCTION__ . ' on line ' . __LINE__);$sql    = "SELECT second_wordID, ranking FROM Bs_Is_" . $this->_profile->getProfileName() . "_Collocations WHERE first_wordID={$wordID}  ORDER BY ranking DESC LIMIT 10";$collo  = $this->_bsDb->getAssoc($sql);$sql    = "SELECT first_wordID,  ranking FROM Bs_Is_" . $this->_profile->getProfileName() . "_Collocations WHERE second_wordID={$wordID} ORDER BY ranking DESC LIMIT 10";$collo2 = $this->_bsDb->getAssoc($sql);foreach ($collo2 as $colloID => $colloRanking) {$collo[$colloID] = $colloRanking;}
arsort($collo, SORT_NUMERIC);$newCollo = array();if (!empty($collo)) {for ($i=0; $i<10; $i++) {$newCollo[key($collo)] = current($collo);if (next($collo) == FALSE) break;}
}
foreach ($newCollo as $colloID => $colloRanking) {@$wordIDs[$colloID] += $colloRanking;}
}
continue;}
}
$this->stopWatch->takeTime(__FUNCTION__ . ' on line ' . __LINE__);$ret = array();if (!empty($wordIDs)) {arsort($wordIDs, SORT_NUMERIC);$limitedWordIDs = array();for ($i=0; $i<10; $i++) {$limitedWordIDs[key($wordIDs)] = current($wordIDs);if (next($wordIDs) == FALSE) break;}
foreach ($limitedWordIDs as $wordID => $ranking) {$sql = "SELECT caption FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE ID ={$wordID}";$wordCaption = $this->_bsDb->getOne($sql);$ret[$wordCaption] = array('ID'=>$wordID, 'ranking'=>$ranking);}
}
return $ret;}
function getQueryWordsForHighlight() {$ret = array();foreach ($this->queryData as $hash) {foreach ($hash['tokens'] as $hash) {if (!@is_array($hash['match'])) continue; while (list($wordID) = each($hash['match'])) {$ret[$hash['match'][$wordID]['wordInfo']['caption']] = TRUE;}
}
}
return $ret;}
function _addHintForWord($wordInternal, $wordOrig, $wasEmpty) {$stemWords    = $this->findStemWords($wordInternal, 'IDs', 20);$partWords    = $this->findPartWords($wordInternal, 'both', 'IDs', 20);$soundexWords = $this->findSoundexWords($wordInternal, 'IDs', 10);if (!is_array($stemWords))    $stemWords    = array();if (!is_array($partWords))    $partWords    = array();if (!is_array($soundexWords)) $soundexWords = array();$words          = array($stemWords, $partWords, $soundexWords);$foundWordAssoc = array();foreach($words as $wordIdArray) {if (!empty($wordIdArray)) {$sql  = "SELECT ID, caption FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words";$sql .= " WHERE ID IN (" . join(',', $wordIdArray) . ")";$sql .= " AND caption <> '{$wordInternal}' AND caption <> '$wordOrig'";$sql .= " ORDER BY useCount DESC LIMIT 10";$foundWordAssoc[] = $this->_bsDb->getAssoc($sql);} else {$foundWordAssoc[] = null;}
}
$this->hintWords[$wordOrig] = array(
'stem'    => $foundWordAssoc[0], 
'part'    => $foundWordAssoc[1], 
'soundex' => $foundWordAssoc[2], 
);$t = array_unique(array_slice(array_merge($foundWordAssoc[0], $foundWordAssoc[1], $foundWordAssoc[2]), 0, 10)); if ($wasEmpty) {if (empty($t)) {if ($this->lang === 'de') {$this->hintString .= "Nicht gefunden: '" . $wordOrig . "'.<br/>\n";} else { $this->hintString .= "Not found: '" . $wordOrig . "'.<br/>\n";}
} else {if ($this->lang === 'de') {$this->hintString .= "Nicht gefunden: '" . $wordOrig . "'. Meinten Sie: " . join(', ', $t) . "<br/>\n";} else { $this->hintString .= "Not found: '" . $wordOrig . "'. Did you mean: " . join(', ', $t) . "<br/>\n";}
}
}
}
function findSynonyms($word, $lang=null) {}
function findPartWords($word, $extend='both', $returnType='simple', $limit=1000, $lang=null) {$wordInternal = $this->Bs_Is_IndexServer->cleanWord($word, $this->_profile->minWordLength(), $this->_profile->maxWordLength());if ($wordInternal === FALSE) return FALSE;if ((substr($wordInternal, 0, 1) != '%') && (($extend == 'both') || ($extend == 'left'))) {$wordInternal = '%' . $wordInternal;}
if ((substr($wordInternal, -1) != '%') && (($extend == 'both') || ($extend == 'right'))) {$wordInternal .= '%';}
$wordInternal = str_replace('*', '%', $wordInternal); $sql = "SELECT * FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE caption LIKE '" . addSlashes($wordInternal) . "' ORDER BY useCount DESC LIMIT {$limit}";return $this->_findWordsHelper($sql, $returnType, 'part');}
function findStemWords($word, $return='simple', $limit=1000, $lang=null) {$wordInternal = $this->Bs_Is_IndexServer->cleanWord($word, $this->_profile->minWordLength(), $this->_profile->maxWordLength());if ($wordInternal === FALSE) return FALSE;if ((strpos($wordInternal, '%') !== FALSE) || (strpos($wordInternal, '*') !== FALSE)) return FALSE; $stem = $this->Bs_Is_IndexServer->getStem($wordInternal, $lang);if (empty($stem)) return FALSE;$sql = "SELECT * FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE stem = '" . addSlashes($stem) . "' ORDER BY useCount DESC LIMIT {$limit}";return $this->_findWordsHelper($sql, $returnType, 'stem');}
function findMetaphoneWords($word) {}
function findSoundexWords($word, $returnType='simple', $limit=1000, $lang=null) {$wordInternal = $this->Bs_Is_IndexServer->cleanWord($word, $this->_profile->minWordLength(), $this->_profile->maxWordLength());if ($wordInternal === FALSE) return FALSE;if ((strpos($wordInternal, '%') !== FALSE) || (strpos($wordInternal, '*') !== FALSE)) return FALSE; $sql = "SELECT * FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE soundx = SOUNDEX('" . addSlashes($wordInternal) . "') ORDER BY useCount DESC LIMIT {$limit}";return $this->_findWordsHelper($sql, $returnType, 'soundex');}
function _findWordsHelper($sql, $returnType='simple', $wordType='') {$wordRecords = $this->_bsDb->getAll($sql);if (is_null($wordRecords) || (!is_array($wordRecords)) || empty($wordRecords)) return FALSE;$ret = array();if ($returnType == 'structure') {while (list(,$wordRecord) = each($wordRecords)) {$ret[$wordRecord['ID']] = array(
'wordInfo' => $wordRecord, 
'type'     => $wordType, 
);}
} elseif ($returnType == 'IDs') {while (list(,$wordRecord) = each($wordRecords)) {$ret[] = $wordRecord['ID'];}
} else {while (list(,$wordRecord) = each($wordRecords)) {$ret[$wordRecord['ID']] = $wordRecord['caption'];}
}
return $ret;}
function _wordRankForRelation($wordUseCount, $wordLength, $maxWordUseCount) {$z1 = (pow($wordUseCount, 0.2)    / $maxWordUseCount)  *10000;$z3 = (pow($maxWordUseCount, 0.2) / $maxWordUseCount)  *10000;$wordWeightUse = 100 - ($z1 / $z3 * 100); $wordWeightLength = $wordLength / $this->_profile->maxWordLength() * 100;if ($maxWordUseCount < 5) {$wordWeightForRecord = ($wordWeightUse + $wordWeightLength) /2;       } elseif ($maxWordUseCount < 10) {$wordWeightForRecord = ((2 * $wordWeightUse) + $wordWeightLength) /3; } elseif ($maxWordUseCount < 20) {$wordWeightForRecord = ((3 * $wordWeightUse) + $wordWeightLength) /4; } else {$wordWeightForRecord = ((5 * $wordWeightUse) + $wordWeightLength) /6; }
return $wordWeightForRecord;}
function _DEPRECATED_wordRankForRelation($k, $wordID, $sourceID, &$queryData, $maxWordUseCount, $wordLength) {$wordUseCount    = (int)$queryData[$k]['match'][$wordID]['wordInfo']['useCount'];$z1 = (pow($wordUseCount, 0.2)    / $maxWordUseCount)  *10000;$z3 = (pow($maxWordUseCount, 0.2) / $maxWordUseCount)  *10000;$wordWeightUse = 100 - ($z1 / $z3 * 100); $wordWeightLength = $wordLength / $this->_profile->maxWordLength() * 100;if ($maxWordUseCount < 5) {$wordWeightForRecord = ($wordWeightUse + $wordWeightLength) /2;       } elseif ($maxWordUseCount < 10) {$wordWeightForRecord = ((2 * $wordWeightUse) + $wordWeightLength) /3; } elseif ($maxWordUseCount < 20) {$wordWeightForRecord = ((3 * $wordWeightUse) + $wordWeightLength) /4; } else {$wordWeightForRecord = ((5 * $wordWeightUse) + $wordWeightLength) /6; }
$endWordRank = $queryData[$k]['match'][$wordID]['relation'][$sourceID] * $wordWeightForRecord /100;return $endWordRank;}
function DEPRECATED_searchXXX($searchInput="", $preWhere="") {$this->cacheStopWords(); unset($this->searchStopWords); $this->realNumRows = 0;        $searchInput["keywords"] = " " . $searchInput["keywords"]; $wordArray = $this->parseSearchInput($searchInput["keywords"]);while(list($k, $v) = each($wordArray)) {$this->realWordIdString = "";if ($searchInput["mode"] == "soundex") {$sqlQ1 = "SELECT ID FROM cmtIndexServer.realWord WHERE SOUNDEX(caption) = SOUNDEX('"  . $wordArray[$k][0] . "')";  } else {$sqlQ1 = "SELECT ID FROM cmtIndexServer.realWord WHERE caption = '"  . $wordArray[$k][0] . "'";  }
$wordArray[$k][2] = $this->searchQueryHelper($sqlQ1, $points=100);if ($v[1] != "!" AND $searchInput["mode"] != "soundex") {$sqlQ2  = "SELECT ID FROM cmtIndexServer.realWord WHERE caption LIKE '"  . $wordArray[$k][0] . "%'";if ($this->realWordIdString != "") $sqlQ2 .= " AND ID NOT IN ({$this->realWordIdString})"; $wordArray[$k][2] = array_merge($wordArray[$k][2], $this->searchQueryHelper($sqlQ2, $points=30));$sqlQ3 = "SELECT ID FROM cmtIndexServer.realWord WHERE caption LIKE '%" . $wordArray[$k][0] . "'";if ($this->realWordIdString != "") $sqlQ3 .= "  AND ID NOT IN ({$this->realWordIdString})"; $wordArray[$k][2] = array_merge($wordArray[$k][2], $this->searchQueryHelper($sqlQ3, $points=20));$sqlQ4 = "SELECT ID FROM cmtIndexServer.realWord WHERE caption LIKE '%" . $wordArray[$k][0] . "%'";if ($this->realWordIdString != "") $sqlQ4 .= " AND ID NOT IN ({$this->realWordIdString})"; $wordArray[$k][2] = array_merge($wordArray[$k][2], $this->searchQueryHelper($sqlQ4, $points=10));}
}
reset($wordArray);while(list($k, $v) = each($wordArray)) {if ($v[1] == "!") {if ($v[2][0]["ID"] > 0) { $forbiddenWordIDs[] = $v[2][0]["ID"];}
}
}
if (is_array($forbiddenWordIDs)) {$sqlQ = "SELECT record_id FROM word2record WHERE realWordID IN (" . join(", ", $forbiddenWordIDs) . ") GROUP BY record_id";$result = $this->dbIndex->Query($sqlQ);$errorMsg = "fehler beim auslesen der forbiddenRecordIDs";$this->dbIndex->checkDbError($errorMsg);while ($rs = $this->dbIndex->FArray($result)) {$forbiddenRecordIDs[] = $rs["record_id"];}
}
reset($wordArray);$i = 0;while(list($k, $v) = each($wordArray)) {if ($v[1] == "&") {while(list($k2, $v2) = each($v[2])) {$mustWordIDs[$i][] = $v2["ID"];}
$i++;}
}
if (is_array($mustWordIDs)) {$i=0;while(list($k, $v) = each($mustWordIDs)) {$sqlQ = "SELECT record_id FROM word2record WHERE realWordID IN (" . join(", ", $v) . ") GROUP BY record_id";$result = $this->dbIndex->Query($sqlQ);$errorMsg = "fehler beim auslesen der mustRecordIDs";$this->dbIndex->checkDbError($errorMsg);while ($rs = $this->dbIndex->FArray($result)) {$mustRecordIDs[$i][] = $rs["record_id"];}
$i++;}
}
$i = 0;reset($wordArray);while(list($k, $v) = each($wordArray)) {if ($v[1] != "!") {switch ($v[1]) {case "&":
$operatorPoints = 10;break;case "|":
$operatorPoints = 6;break;default:
$operatorPoints = 6;}
$inArray = array();$caseString = " (CASE realWordID ";while(list($k2, $v2) = each($v[2])) {$caseString .= "WHEN " . $v2["ID"] . " THEN " . $v2["points"] . " ";$inArray[]   = $v2["ID"];}
$caseString .= "END) ";if ($i == 0) {$sqlC = "CREATE TABLE cmtIndexServer.heapShit TYPE=HEAP ";} else {$sqlC = "INSERT INTO cmtIndexServer.heapShit ";}
$sqlC .= "SELECT realWordID, record_id, ";$sqlC .= "SUM(ranking * {$operatorPoints} * {$caseString} ) AS rankingSum ";$sqlC .= "FROM word2record ";$sqlC .= "WHERE dbName = '{$searchInput['dbName']}' ";$sqlC .= "AND dbTable = '{$searchInput['dbTable']}' ";$sqlC .= "AND (realWordID IN (" . join(", ", $inArray) . ")) ";if (is_array($mustRecordIDs)) {while(list($mustK, $mustV) = each($mustRecordIDs)) {$sqlC .= "AND (record_id IN     (" . join(", ", $mustV) . ")) ";}
}
if (is_array($forbiddenRecordIDs)) $sqlC .= "AND (record_id NOT IN (" . join(", ", $forbiddenRecordIDs) . ")) ";$sqlC .= "GROUP BY record_id";$dev0 = $this->dbIndex->executeSql($sqlC);$i++;}
}
if (! $this->dbIndex->tableExists("heapShit", "cmtIndexServer")) {return array();}
$sqlQPart1  = "SELECT ";$wantedSelectFields = explode(', ', $searchInput["dbField"]);while(list($k, $v) = each($wantedSelectFields)) {$sqlQPart1 .= "{$searchInput["dbName"]}.{$searchInput["dbTable"]}.{$v}, ";}
$sqlQPart1 .= "SUM(cmtIndexServer.heapShit.rankingSum) AS internal_rankingSum ";$sqlQPart2  = "FROM cmtIndexServer.heapShit, {$searchInput["dbName"]}.{$searchInput["dbTable"]} ";if ($preWhere != "") {$sqlQPart2 .= $preWhere;$sqlQPart2 .= " AND ";} else {$sqlQPart2 .= " WHERE ";}
$sqlQPart2 .= "cmtIndexServer.heapShit.record_id = {$searchInput["dbName"]}.{$searchInput["dbTable"]}.ID ";$sqlQPart2 .= "GROUP BY cmtIndexServer.heapShit.record_id ";$sqlQPart3 .= "ORDER BY internal_rankingSum DESC ";$searchInput["offset"]  = ($searchInput["offset"]  > 0) ? $searchInput["offset"]  : 0;$searchInput["numRows"] = ($searchInput["numRows"] > 0) ? $searchInput["numRows"] : -1;$sqlQPart3 .= "LIMIT {$searchInput["offset"]}, {$searchInput["numRows"]}";$sqlQ = $sqlQPart1 . $sqlQPart2 . $sqlQPart3;$sqlQCount .= "SELECT count(*) AS rowcount ";$sqlQCount .= $sqlQPart2;$this->realNumRows = $this->dbIndex->getDatabaseNumRecords($sqlQCount);$result = $this->dbIndex->Query($sqlQ);$errorMsg = "fehler in search()";$this->dbIndex->checkDbError($errorMsg);while ($rs = $this->dbIndex->FArray($result)) {$ret[] = $rs;}
$sqlD = "DROP TABLE cmtIndexServer.heapShit";$dev0 = $this->dbIndex->executeSql($sqlD);return $ret;}
function searchQueryHelper($query, $points) {$ret = array();$result   = $this->dbIndex->Query($query);$errorMsg = "Fehler in searchQueryHelper()";$this->dbIndex->checkDbError($errorMsg);$i = 0;while ($rs = $this->dbIndex->FArray($result)) {$ret[$i]["ID"]     = $rs["ID"];$ret[$i]["points"] = $points;if ($this->realWordIdString != "") $this->realWordIdString .= ", ";$this->realWordIdString .= $rs["ID"];$i++;}
return $ret;}
function _parsedQueryToQueryData($parsedQuery, $lang, $features) {$queryData = array();while (list(,$arr) = each($parsedQuery)) {$t = array();$t['phrase']   = $arr['phrase'];$t['operator'] = $arr['operator'];$t['fuzzy']    = $arr['fuzzy'];while (list(,$token) = each($arr['words'])) {$ignored      = FALSE;$wordInternal = $this->Bs_Is_IndexServer->cleanWord($token, $this->_profile->minWordLength(), $this->_profile->maxWordLength(), TRUE);if (is_array($wordInternal)) {$ignored      = $wordInternal[0];$wordInternal = '';} else {if ($this->Bs_Is_IndexServer->isStopWord($wordInternal)) $ignored = 'noise';}
$t['tokens'][] = array('wordOrig'=>$token, 'wordInternal'=>$wordInternal, 'ignored'=>$ignored);}
$queryData[] = $t;}
return $queryData;}
function _findWordIDs(&$queryData, $lang, $origFeatures) {while (list($k) = each($queryData)) {$features = $origFeatures;if ($queryData[$k]['fuzzy']) {$features['part']       = TRUE;$features['stemming']   = TRUE;$features['metaphone']  = TRUE;$features['soundex']    = TRUE;}
while (list($k2) = each($queryData[$k]['tokens'])) {$ref = &$queryData[$k]['tokens'][$k2];if ($ref['ignored'] !== FALSE) continue;$word = str_replace('*', '%', $ref['wordInternal']);if (strpos($word, '%') === FALSE) {$sql = "SELECT * FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE caption = '" . addSlashes($ref['wordInternal']) . "'";$wordRecord = $this->_bsDb->getRow($sql);if (!is_null($wordRecord)) {$ref['match'][$wordRecord['ID']] = array(
'wordInfo' => $wordRecord, 
'type'     => 'direct', 
);} else {if ($features['hints'] === 'auto') {$this->_addHintForWord($queryData[$k]['tokens'][$k2]['wordInternal'], $queryData[$k]['tokens'][$k2]['wordOrig']);}
}
} else {$sql  = "SELECT * FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE ";if (substr($word, 0, 1) === '%') {$sql .= "noitpac LIKE '" . addSlashes(strrev($word));} else {$sql .= "caption LIKE '" . addSlashes($word);}
$sql .= "'";$wordRecords = $this->_bsDb->getAll($sql);if (!is_null($wordRecords)) {while (list(,$wordRecord) = each($wordRecords)) {if (isSet($ref['match'][$wordRecord['ID']])) continue; $ref['match'][$wordRecord['ID']] = array(
'wordInfo' => $wordRecord, 
'type'     => 'part', 
);}
}
}
if (!isSet($ref['partSearch']) || !$ref['partSearch']) {if (@$features['part']) {$sql = "SELECT * FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE caption LIKE '" . addSlashes($word) . "%'";$wordRecords = $this->_bsDb->getAll($sql);if (!is_null($wordRecords)) {while (list(,$wordRecord) = each($wordRecords)) {if (isSet($ref['match'][$wordRecord['ID']])) continue; $ref['match'][$wordRecord['ID']] = array(
'wordInfo' => $wordRecord, 
'type'     => 'part', 
);}
}
$sql = "SELECT * FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE noitpac LIKE '" . addSlashes(strrev($word)) . "%'";$wordRecords = $this->_bsDb->getAll($sql);if (!is_null($wordRecords)) {while (list(,$wordRecord) = each($wordRecords)) {if (isSet($ref['match'][$wordRecord['ID']])) continue; $ref['match'][$wordRecord['ID']] = array(
'wordInfo' => $wordRecord, 
'type'     => 'part', 
);}
}
}
}
if (($queryData[$k]['operator'] != '!') && (sizeOf($queryData[$k]['tokens']) == 1) && ($features['stemming'] === TRUE)) {if (@$features['stemming']) {$lang = (isSet($this->lang)) ? $this->lang : 'en';$stem = $this->Bs_Is_IndexServer->getStem($ref['wordInternal'], $lang);if (!empty($stem)) {$sql = "SELECT * FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE stem = '" . addSlashes($stem) . "'";$wordRecords = $this->_bsDb->getAll($sql);if (!is_null($wordRecords)) {while (list(,$wordRecord) = each($wordRecords)) {if (isSet($ref['match'][$wordRecord['ID']])) continue; $ref['match'][$wordRecord['ID']] = array(
'wordInfo' => $wordRecord, 
'type'     => 'stem', 
);}
}
}
}
}
if (($queryData[$k]['operator'] != '!') && (sizeOf($queryData[$k]['tokens']) == 1) && ($features['soundex'] === TRUE)) {if (@$features['soundex']) {$sql = "SELECT * FROM Bs_Is_" . $this->_profile->getProfileName() . "_Words WHERE soundx = SOUNDEX('" . addSlashes($ref['wordInternal']) . "')";$wordRecords = $this->_bsDb->getAll($sql);if (!is_null($wordRecords)) {while (list(,$wordRecord) = each($wordRecords)) {if (isSet($ref['match'][$wordRecord['ID']])) continue; $ref['match'][$wordRecord['ID']] = array(
'wordInfo' => $wordRecord, 
'type'     => 'soundex', 
);}
}
}
}
}
}
}
function _searchAddRelations(&$queryData, $lang, $features) {$profileName = $this->_profile->getProfileName();reset($queryData);while (list($k) = each($queryData)) {reset($queryData[$k]['tokens']);while (list($k2) = each($queryData[$k]['tokens'])) {if (!isSet($queryData[$k]['tokens'][$k2]['match']) || !is_array($queryData[$k]['tokens'][$k2]['match'])) continue;$wordIDs = array_keys($queryData[$k]['tokens'][$k2]['match']);$sql = "SELECT wordID, sourceID, ranking FROM Bs_Is_{$profileName}_WordToSource WHERE wordID IN (" . join(',', $wordIDs) . ")";$data = $this->_bsDb->getAll($sql);foreach ($data as $arr) {$queryData[$k]['tokens'][$k2]['match'][$arr['wordID']]['relation'][$arr['sourceID']] = $arr['ranking'];}
}
}
}
function showWordInfo($searchString) {$features = array(
'part'      => TRUE, 
'stemming'  => TRUE, 
'metaphone' => TRUE, 
'soundex'   => TRUE, 
'synonyme'  => TRUE, 
'caching'   => FALSE, 
'hints'     => 'auto', 
);$parsedQuery = $this->Bs_TextUtil->parseSearchQuery($searchString);$queryData = $this->_parsedQueryToQueryData($parsedQuery, $lang, $features);$this->_findWordIDs($queryData, $lang, $features);return dump($queryData, TRUE);}
function listRightNeighbors($wordID) {$status = $this->_bsDb->getOne("SELECT rnbs_wordIDs FROM Bs_Is_" . $this->_profile->getProfileName() . "_WordToSource WHERE wordID = {$wordID}");if (isEx($status)) return $status;if (strlen($status) < 3) return 'Reight Neighbors: None';$rnbs = str_replace(';', ',', substr($status, 1, -1)); $status = $this->_bsDb->getAll("SELECT ID, caption FROM Bs_Is_" . $this->_profile->getProfileName() . "_words WHERE ID IN ({$rnbs})");return dump($status, TRUE);}
function listCollocations($wordID) {}
}
?>