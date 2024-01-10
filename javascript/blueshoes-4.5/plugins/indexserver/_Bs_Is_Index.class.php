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
require_once($APP['path']['XPath'] . 'XPath.class.php');Class Bs_Is_Index extends Bs_Object {var $_xPath;var $_bsDb;var $_bsDbSpecial;var $_xml;var $_name;function Bs_Is_Index() {parent::Bs_Object();  $this->_bsDb  =& $GLOBALS['bsDb'];$this->_xPath =& new XPath(); }
function load($name) {if (!empty($this->_name)) return FALSE;$sqlQ = "SELECT * FROM bs_indexServer.Indexes WHERE lcase(caption) = lcase('{$name}') LIMIT 1";$result = &$this->_bsDb->getRow($sqlQ);if (isEx($result)) {$result->stackTrace('was here in load()', __FILE__, __LINE__);return $result;}
$this->settingsXmlToVar($result['xml']);$this->_name = $name;}
function settingsXmlToVar(&$xml) {$this->_xPath->importFromString(&$xml);$xpvIndex  = $this->_xPath->match('/blueshoes[1]/bs:index[1]');$prop['index']  = $this->_xPath->getAttributes($xpvIndex[0]);$xpvSource = $this->_xPath->match('/blueshoes[1]/bs:index[1]/bs:source/descendant-or-self::*');var_dump($xpvSource);exit;while (list($k) = each($xpvSource)) {$x = $this->_xPath->getAttributes($xpvIndex[$k]);}
$xpvIndex  = $this->_xPath->match('/blueshoes/bs:source');}
function settingsVarToXml() {}
function save() {}
function reset() {$this->_xPath->reset();unset($this->_name);}
function create($name, $optimized='disk') {$sqlQ = "SELECT * FROM bs_indexServer.Indexes WHERE lcase(caption) = lcase('{$name}')";if ($this->_bsDb->countRead($sqlQ) > 0) {$e =& new Bs_Exception('there is already an index with this name.', __FILE__, __LINE__, BS_DB_ERROR_ALREADY_EXISTS);return $e;}
if (
($this->_bsDb->tableExists("index_{$name}_relation", 'bs_indexServer', FALSE) === TRUE) OR 
($this->_bsDb->tableExists("index_{$name}_word", 'bs_indexServer', FALSE) === TRUE)) 
{$e =& new Bs_Exception('table already exists, clean up first.', __FILE__, __LINE__, BS_DB_ERROR_ALREADY_EXISTS);return $e;}
$sqlI = "INSERT INTO bs_indexServer.Indexes SET caption='{$name}', xml='" . addSlashes($this->_xml) . "'";$status = $this->_bsDb->write($sqlI);if (isEx($status)) {$status->stackTrace('was here in createIndex(). maybe there is already an index with this name?', __FILE__, __LINE__);return $status;}
$charField = ($optimized=='disk') ? 'VARCHAR' : 'CHAR';$sqlC = "
CREATE TABLE bs_indexServer.Index_{$name}_relation (
ID                  int not null default 0 auto_increment, 
wordID              int not null default 0, 
recordID            int not null default 0, 
ranking             smallint not null default 0, 
primary key(ID), 
key wordID(wordID), 
key recordID(recordID), 
key(ranking)
)
";$status = $this->_bsDb->write($sqlC);if (isEx($status)) {$status->stackTrace('was here in createIndex()', __FILE__, __LINE__);return $status;}
$sqlC = "
CREATE TABLE bs_indexServer.Index_{$name}_word (
caption             {$charField}(30) not null default '', 
soundex             {$charField}(10) not null default '', 
length              tinyint not null default 0, 
languages           {$charField}(30) NOT NULL default '', 
useCount            int not null default 0, 
searchCount         int not null default 0, 
primary key(caption), 
unique(caption)
)
";$status = $this->_bsDb->write($sqlC);if (isEx($status)) {$sqlD    = "DROP TABLE IF EXISTS bs_indexServer.index_{$name}_relation";$status2 = $this->_bsDb->write($sqlD);if (isEx($status2)) {$status->stackTrace("was here in createIndex(). failed in the middle of the process. the first db table (bs_indexServer.index_{$name}_relation) was created and could not be removed.", __FILE__, __LINE__);return $status;} else {$status->stackTrace('was here in createIndex(). we have been able to clean up things.', __FILE__, __LINE__);return $status;}
}
return TRUE;}
function isReservedName($name) {switch strToLower($name) {case 'indexes':
case 'queue':
return TRUE;break;default:
if ((strToLower(substr($name, 0, 10))) == 'noisewords') return TRUE; }
return FALSE;}
function setXml(&$xml) {$this->_xml =&$xml;return TRUE;}
function &getXml(&$xml) {return $this->_xml;}
function drop() {}
function dropIndex($name) {$sqlD = "DELETE FROM bs_indexServer.Queue WHERE IndexesCaption = '{$name}'";$this->_bsDb->write($sqlI); $sqlD = "DELETE FROM bs_indexServer.Indexes WHERE caption='{$name}'";$status = $this->_bsDb->countWrite($sqlI);if (isEx($status)) {$status->stackTrace('was not able to delete the index record.', __FILE__, __LINE__);return $status;} elseif (is_int($status) && ($status == 0)) {$e =& new Bs_Exception('no record found with this name. there is already an index with this name.', __FILE__, __LINE__, BS_DB_ERROR_ALREADY_EXISTS);return $e;}
$sqlD = "DROP TABLE IF EXISTS bs_indexServer.index_{$name}_relation";$status = $this->_bsDb->write($sqlD);if (isEx($status)) {$status->stackTrace("was here in dropIndex(). failed, none of the 2 tables has been removed.", __FILE__, __LINE__);return $status;}
$sqlD = "DROP TABLE IF EXISTS bs_indexServer.index_{$name}_word";$status = $this->_bsDb->write($sqlD);if (isEx($status)) {$status->stackTrace("was here in dropIndex(). failed, the first table worked while the 2nd table bs_indexServer.index_{$name}_word still exists.", __FILE__, __LINE__);return $status;}
return TRUE;}
function queueAdd($name, $sourceRecordID, $todo='a') {$sqlQ   = "SELECT ID, todo FROM bs_indexServer.Queue WHERE IndexesCaption='{$name}' AND recordID={$sourceRecordID}";$result = $this->_bsDb->getAll($sqlQ);if (isEx($result)) {return FALSE; }
$remove = FALSE; $accept = FALSE; switch (sizeOf($result)) {case 0:
$accept = TRUE;break;case 1:
if (($result[0]['todo'] == 'a') && ($todo == 'r')) {$remove = TRUE;$accept = TRUE;} elseif (($result[0]['todo'] == 'r') && ($todo == 'a')) {$accept = TRUE;}
break;case 2:
if (($todo == 'r')) {$remove = TRUE;$accept = TRUE;}
break;}
if ($remove) {$idInString = (sizeOf($result) == 1) ? $result[0]['ID'] : $result[0]['ID'] . ', ' . $result[1]['ID'];$sqlD = "DELETE FROM bs_indexServer.Queue WHERE ID IN ({$idInString})";$status = $this->_bsDb->countWrite($sqlI);}
if ($accept) {$sqlI = "INSERT INTO bs_indexServer.Queue SET IndexesCaption='{$name}', recordID={$sourceRecordID}, todo='{$todo}'";$status = $this->_bsDb->write($sqlI);if (!isEx($status)) {return TRUE;}
}
return FALSE;}
function queueRemove($queueRecordID) {if (is_array($queueRecordID)) {$queueRecordID = join(',', $queueRecordID);}
$sqlD = "DELETE FROM bs_indexServer.Queue WHERE ID IN ({$queueRecordID})";$status = $this->_bsDb->countWrite($sqlI);if (isEx($status)) {$status->stackTrace('was here in queueRemove()', __FILE__, __LINE__);}
return $status;}
function &_fetchDataForIndex($name, $sourceRecordID) {$sqlQ = "SELECT * FROM bs_indexServer.Indexes WHERE caption='{$name}' LIMIT 1";$result = $this->_bsDb->getRow($sqlD);if (isEx($result)) {$result->stackTrace('was here in _fetchDataForIndex()', __FILE__, __LINE__);return $result;}
$xp =& new XPath();}
function &_fetchDataForIndexUsingQuery($query) {$result = &$this->_bsDb->getRow($query);if (isEx($result)) {$result->stackTrace('was here in _fetchDataForIndexUsingQuery()', __FILE__, __LINE__);return $result;}
return $result;}
function search($searchInput="", $preWhere="") {$this->cacheStopWords(); unset($this->searchStopWords); $this->realNumRows = 0;        $searchInput["keywords"] = " " . $searchInput["keywords"]; $wordArray = $this->parseSearchInput($searchInput["keywords"]);while(list($k, $v) = each($wordArray)) {$this->realWordIdString = "";if ($searchInput["mode"] == "soundex") {$sqlQ1 = "SELECT ID FROM cmtIndexServer.realWord WHERE SOUNDEX(caption) = SOUNDEX('"  . $wordArray[$k][0] . "')";  } else {$sqlQ1 = "SELECT ID FROM cmtIndexServer.realWord WHERE caption = '"  . $wordArray[$k][0] . "'";  }
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
$operatorPoints = "10";break;case "|":
$operatorPoints = "6";break;default:
$operatorPoints = "6";}
$inArray = array();$caseString = " (CASE realWordID ";while(list($k2, $v2) = each($v[2])) {$caseString .= "WHEN " . $v2["ID"] . " THEN " . $v2["points"] . " ";$inArray[]   = $v2["ID"];}
$caseString .= "END) ";if ($i == 0) {$sqlC = "CREATE TABLE cmtIndexServer.heapShit TYPE=HEAP ";} else {$sqlC = "INSERT INTO cmtIndexServer.heapShit ";}
$sqlC .= "SELECT realWordID, record_id, ";$sqlC .= "SUM(ranking * $operatorPoints * $caseString ) AS rankingSum ";$sqlC .= "FROM word2record ";$sqlC .= "WHERE dbName = '{$searchInput["dbName"]}' ";$sqlC .= "AND dbTable = '{$searchInput["dbTable"]}' ";$sqlC .= "AND (realWordID IN (" . join(", ", $inArray) . ")) ";if (is_array($mustRecordIDs)) {while(list($mustK, $mustV) = each($mustRecordIDs)) {$sqlC .= "AND (record_id IN     (" . join(", ", $mustV) . ")) ";}
}
if (is_array($forbiddenRecordIDs)) $sqlC .= "AND (record_id NOT IN (" . join(", ", $forbiddenRecordIDs) . ")) ";$sqlC .= "GROUP BY record_id";$dev0 = $this->dbIndex->executeSql($sqlC);$i++;}
}
if (! $this->dbIndex->tableExists("heapShit", "cmtIndexServer")) {return array();}
$sqlQPart1  = "SELECT ";$wantedSelectFields = explode(", ", $searchInput["dbField"]);while(list($k, $v) = each($wantedSelectFields)) {$sqlQPart1 .= "{$searchInput["dbName"]}.{$searchInput["dbTable"]}.{$v}, ";}
$sqlQPart1 .= "SUM(cmtIndexServer.heapShit.rankingSum) AS internal_rankingSum ";$sqlQPart2  = "FROM cmtIndexServer.heapShit, {$searchInput["dbName"]}.{$searchInput["dbTable"]} ";if ($preWhere != "") {$sqlQPart2 .= $preWhere;$sqlQPart2 .= " AND ";} else {$sqlQPart2 .= " WHERE ";}
$sqlQPart2 .= "cmtIndexServer.heapShit.record_id = {$searchInput["dbName"]}.{$searchInput["dbTable"]}.ID ";$sqlQPart2 .= "GROUP BY cmtIndexServer.heapShit.record_id ";$sqlQPart3 .= "ORDER BY internal_rankingSum DESC ";$searchInput["offset"]  = ($searchInput["offset"]  > 0) ? $searchInput["offset"]  : 0;$searchInput["numRows"] = ($searchInput["numRows"] > 0) ? $searchInput["numRows"] : -1;$sqlQPart3 .= "LIMIT {$searchInput["offset"]}, {$searchInput["numRows"]}";$sqlQ = $sqlQPart1 . $sqlQPart2 . $sqlQPart3;$sqlQCount .= "SELECT count(*) AS rowcount ";$sqlQCount .= $sqlQPart2;$this->realNumRows = $this->dbIndex->getDatabaseNumRecords($sqlQCount);$result = $this->dbIndex->Query($sqlQ);$errorMsg = "fehler in search()";$this->dbIndex->checkDbError($errorMsg);while ($rs = $this->dbIndex->FArray($result)) {$ret[] = $rs;}
$sqlD = "DROP TABLE cmtIndexServer.heapShit";$dev0 = $this->dbIndex->executeSql($sqlD);return $ret;}
function searchQueryHelper($query, $points) {$ret = array();$result   = $this->dbIndex->Query($query);$errorMsg = "Fehler in searchQueryHelper()";$this->dbIndex->checkDbError($errorMsg);$i = 0;while ($rs = $this->dbIndex->FArray($result)) {$ret[$i]["ID"]     = $rs["ID"];$ret[$i]["points"] = $points;if ($this->realWordIdString != "") $this->realWordIdString .= ", ";$this->realWordIdString .= $rs["ID"];$i++;}
return $ret;}
function indexRecord($name, $recordID) {}
function removeRecordIndex($name, $recordID) {}
function indexRecord($dbName="", $tableName="", $recordID=0) {if ($dbName == "" OR $tableName == "" OR $recordID == 0) {return FALSE;}
if (! is_array($this->stopWord)) $this->cacheStopWords();$SFP  = new SysFieldProperty($dbName, $tableName);$fieldArray = $SFP->getRecords();foreach($fieldArray as $key => $fieldPropertyArray) {if ($fieldPropertyArray["addProperties"]["doIndex"]) {$wantedFields[$key]["indexWeight"] = explode(",", $fieldPropertyArray["addProperties"]["indexWeight"]);$wantedFields[$key]["dataType"]    = &$fieldPropertyArray["dataType"];$tmpSpitterArray[$key] = "";$tmpWeight = 0;foreach($wantedFields[$key]["indexWeight"] as $k => $v) {$tmpWeight += $v;}
$totalWeight += $tmpWeight;}
}
$myDbSpit = new DbSpit();$settings["realData"]  = TRUE;$settings["dbName"]    = $dbName;$settings["dbTable"]   = $tableName;$settings["whereCond"] = "WHERE ID = $recordID";$settings["dbFieldsShow"][$tableName] = $tmpSpitterArray; $myDbSpit->init($settings);$dataArray = $myDbSpit->getDataArray();reset($wantedFields);foreach($wantedFields as $key => $dev0) {$wantedFields[$key]["origValue"] = $dataArray["0"][$key];if ($wantedFields[$key]["dataType"] == "file" OR $wantedFields[$key]["dataType"] == "url") {$wantedFields[$key]["fileValue"] .= " " . $this->Util->readTextFromFile($wantedFields[$key]["origValue"], $wantedFields[$key]["dataType"], $this);}
}
reset($wantedFields);foreach($wantedFields as $key => $dev0) {$wantedFields[$key]["wordArray"]  = $this->getWordArrayFromString($wantedFields[$key]["origValue"]);if ($wantedFields[$key]["dataType"] == "file" OR $wantedFields[$key]["dataType"] == "url") {$wantedFields[$key]["wordArrayFile"] = $this->getWordArrayFromString($wantedFields[$key]["fileValue"]);$wantedFields[$key]["wordArray"] = array_merge($wantedFields[$key]["wordArray"], $wantedFields[$key]["wordArrayFile"]);}
}
reset($wantedFields);foreach($wantedFields as $key => $array) {foreach($array["wordArray"] as $word => $wordArray) {$wantedFields[$key]["wordArray"][$word]["indexWeight"]   = $array["indexWeight"];$wantedFields[$key]["wordArray"][$word]["weightPercent"] = $wantedFields[$key]["wordArray"][$word]["weightPoints"] / $totalWeight * 100;$pointsForWord = 0;for ($i = 0; $i < $wordArray["findCount"]; $i++) {$pointsForWord += $array["indexWeight"][$i];}
$wantedFields[$key]["wordArray"][$word]["weightPoints"]  = $pointsForWord;}
}
reset($wantedFields);foreach($wantedFields as $key => $array) {foreach($array["wordArray"] as $word => $wordArray) {$finalWordArray[$word]["weightPoints"]  += $wordArray["weightPoints"];}
}
$sqlD  = "DELETE FROM cmtIndexServer.word2record WHERE dbName = '$dbName' AND dbTable = '$tableName' AND record_id = $recordID";$this->dbIndex->executeSql($sqlD);foreach($finalWordArray as $word => $dev0) {$finalWordArray[$word]["weightPercent"] = $finalWordArray[$word]["weightPoints"] / $totalWeight * 100;$wordProp = $this->getWordProperties($word);$finalWordArray[$word]["wordID"]        = $wordProp["ID"];$finalWordArray[$word]["popularity"]    = $wordProp["popularity"];$tmpPop = 1000 - $finalWordArray[$word]["popularity"];$finalWordArray[$word]["ranking"]       = (int) (($finalWordArray[$word]["weightPercent"] * 10 * 9) + $tmpPop) / 10;$sqlI  = "INSERT INTO cmtIndexServer.word2record SET realWordID = " . $finalWordArray[$word]["wordID"] . ", ";$sqlI .= "dbName = '$dbName', dbTable = '$tableName', record_id = $recordID, ranking = " . $finalWordArray[$word]["ranking"];$this->dbIndex->executeSql($sqlI);}
return TRUE;}
function connectDb($param) {switch ($param) {case "IndexServer":
$dbName = $this->Application["dbname_indexserver"];$dbVar  = "dbIndex";break;case "PageSys":
$dbName = $this->Application["dbname_system"];$dbVar  = "dbSys";break;default:
$dbName = $param;$dbVar  = $param;}
$this->$dbVar = new dbClass($this->Application["dbserver"], $this->Application["dbuser"], $this->Application["dbpassword"], $persistent=TRUE, $this->Application["dbport"], $this->Application["dbpathtosocket"]);$this->$dbVar->setDbName($dbName);$this->$dbVar->Connect();return TRUE;}
function getWordProperties($word) {$wordProp["len"] = strlen($word);$sqlQ = "SELECT ID, popularity FROM cmtIndexServer.realWord WHERE caption = '" . $word . "' LIMIT 1";$result = $this->dbIndex->Query($sqlQ);$errorMsg = "fehler in getWordProperties() 1";$this->dbIndex->checkDbError($errorMsg);if ($this->dbIndex->NumRows($result) == 1) {$rs = $this->dbIndex->FArray($result);$wordProp["ID"]         = $rs["ID"];$wordProp["popularity"] = $rs["popularity"];} else {$sqlQ = "SELECT useCount FROM cmtIndexServer.word WHERE caption = '" . $word . "' LIMIT 1";$result = $this->dbIndex->Query($sqlQ);$errorMsg = "fehler in getWordProperties() 2";$this->dbIndex->checkDbError($errorMsg);if ($this->dbIndex->NumRows($result) == 1) {$rs = $this->dbIndex->FArray($result);$wordProp["useCount"] = $rs["useCount"];} else {$wordProp["useCount"] = 0;}
$lenPoints = (30 - $wordProp["len"]) * 33;$wordProp["popularity"] = ($lenPoints + (3 * $wordProp["useCount"])) / 4;$sqlI  = "INSERT INTO cmtIndexServer.realWord SET caption = '$word', soundex = soundex('$word'), popularity=" . $wordProp["popularity"] . ", ";$sqlI .= "len = " . $wordProp["len"] . ", origUseCount = " . $wordProp["useCount"];$this->dbIndex->executeSql($sqlI);$wordProp["ID"] = $this->dbIndex->InsertID();}
return $wordProp;}
function getWordID($word) {$ret = -1; $sqlQ = "SELECT ID FROM cmtIndexServer.realWord WHERE caption = '" . $word . "' LIMIT 1";$result = $this->dbIndex->Query($sqlQ);$errorMsg = "fehler in getWordID()";$this->dbIndex->checkDbError($errorMsg);if ($this->dbIndex->NumRows($result) == 1) {$rs = $this->dbIndex->FArray($result);$ret = $rs["ID"];}
return $ret;}
function cacheStopWords() {$sqlQ = "SELECT caption FROM stopWord ORDER BY caption";$result = $this->dbIndex->Query($sqlQ);$errorMsg = "fehler in cacheStopWords()";$this->dbIndex->checkDbError($errorMsg);while($rs = $this->dbIndex->FArray($result)) {$tmp[$rs["caption"]] = TRUE;}
$this->stopWord = &$tmp;}
function isStopWord($word) {$ret = FALSE;if ($this->stopWord[$word]) $ret = TRUE;return $ret;}
function getWordArrayFromString($string) {$ret = array();$string = $this->getCleanedWordString($string);$explodeChar = " ";$tmpArray = explode($explodeChar, $string);while(list($k) = each($tmpArray)) {$tmpWord = $this->cleanWord($tmpArray[$k]);if ($tmpWord != "") {if (is_array($ret[$tmpWord])) {$ret[$tmpWord]["findCount"]++;   } else {$ret[$tmpWord]["findCount"]  = 1; $ret[$tmpWord]["wordString"] = $tmpWord;}
}
}
return $ret;}
function treatSpecialChars($string="", $replaceWith=" ", $allowedSpecialCharsArray="") {$len = strlen($string);for ($i = 0; $i < $len; $i++) {$allowedChar = FALSE;$x = ord($string[$i]);if (($x >= 48 AND $x <= 57) OR ($x >= 65 AND $x <= 90) OR ($x >= 97 AND $x <= 122)) {$allowedChar = TRUE;} else {if (is_array($allowedSpecialCharsArray)) {reset($allowedSpecialCharsArray);while(list($k, $v) = each($allowedSpecialCharsArray)) {if ($x == $v) {$allowedChar = TRUE;break;}
}
} else {$allowedChar = FALSE;}
}
if ($allowedChar) {$ret .= $string[$i];} else {$ret .= $replaceWith;}
}
return $ret;}
function cleanWord($word, $rememberSearchStopWords=FALSE) {$word = trim($word);$wordStrLen = strlen($word);if ($wordStrLen <= 1) {if ($wordStrLen > 0 AND $rememberSearchStopWords) $this->searchStopWords[] = $word;return "";} else if ($wordStrLen > 30) {$word = substr($word, 0, 30);}
if ($this->isStopWord($word)) {if ($rememberSearchStopWords) $this->searchStopWords[] = $word;return "";}
return $word;}
function getCleanedWordString($string, $parseSearch=FALSE) {if ($this->debug) echo "1: $string <br>\n"; if ($string == "") return "";$string = strToLower($string);$string = ereg_replace('-<br>[ \t\n\r]*', '', $string);$string = str_replace("<", " <", $string); $string = strip_tags($string);             if ($this->debug) echo "2: $string <br>\n"; $string = $this->Util->htmlValueToRealValue($string);if ($this->debug) echo "3: $string <br>\n"; $string = $this->Util->foreignToInternalChar($string);if ($this->debug) echo "4: $string <br>\n"; if (! $parseSearch) {$string = str_replace("-", "", $string);if ($this->debug) echo "5: $string <br>\n"; $allowedSpecialCharsArray = array(32);$string = $this->treatSpecialChars($string, " ", $allowedSpecialCharsArray);if ($this->debug) echo "6: $string <br>\n"; }
return $string;}
function parseSearchInput($string) {$ret = array();if ($string == " ") return $ret;$string = $this->getCleanedWordString($string, TRUE);$string = str_replace(" and ",   " &", $string); $string = str_replace(" und ",   " &", $string); $string = str_replace(" + ",     " &", $string); $string = str_replace(" +",      " &", $string); $string = str_replace(" or ",    " |", $string); $string = str_replace(" oder ",  " |", $string); $string = str_replace(" not ",   " !", $string); $string = str_replace(" nicht ", " !", $string); $string = str_replace(" - ",     " !", $string); $string = str_replace(" -",      " !", $string); $myWord = str_replace("-", "", $myWord);$allowedSpecialCharsArray = array(32, 38, 124, 33); $string = $this->treatSpecialChars($string, " ", $allowedSpecialCharsArray);if ($debug) echo "6: $string <br>\n"; $searchArray  = explode(" ", $string);while(list($k, $v) = each($searchArray)) {$myWord = $v;if ($this->Util->startsWith($myWord, "&")) {$myOperator = "&";$myWord = str_replace("&", "", $myWord);} elseif ($this->Util->startsWith($myWord, "!")) {$myOperator = "!";$myWord = str_replace("!", "", $myWord);} else {$myOperator = "|";$myWord = str_replace("|", "", $myWord);}
$myWord = $this->cleanWord($myWord, TRUE);if ($myWord != "") {$ret[] = array($myWord, $myOperator);}
}
return $ret;}  
function uniqueArray($array) {return array_flip(array_flip($array));} 
}
?>