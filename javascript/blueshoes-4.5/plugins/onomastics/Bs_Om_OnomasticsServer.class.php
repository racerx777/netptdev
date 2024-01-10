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
require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");require_once($APP['path']['core']         . 'util/Bs_String.class.php');Class Bs_Om_OnomasticsServer extends Bs_Object {var $Bs_String;var $_bsDb;var $title  = array('dr', 'rev', 'haj', 'sri', 'col');var $suffix = array('aldin' , 'oglu', 'skii', 'skaya');var $prefix = array("o'", 'de la', "de l'", "d'", 'd', 'de', 'di', 'dos', 'la', 'le', 'abdul', 'abd', 'al', 'von', 'van', 'vi', 'dal', 'da', 'del', 'lo', 'mc', 'mac', 'ait', 'aid', 'el', 'ed', 'ben', 'y');var $qualifier = array('jr', 'sr', 'mr', 'ms', 'miss', 'fils', 'neto', 'sobrinho', 'ph.d', 'nat');var $pronounce = array();var $_similarityFirstnameCache;var $_similarityFirstnameRelationCache;var $_similarityLastnameCache;function Bs_Om_OnomasticsServer() {parent::Bs_Object(); $this->Bs_String = &$GLOBALS['Bs_String'];if (isSet($GLOBALS['bsDb'])) $this->_bsDb = &$GLOBALS['bsDb'];}
function setDbByObj(&$bsDb) {unset($this->_bsDb);$this->_bsDb = &$bsDb;}
function setDbByDsn($dsn) {bs_lazyLoadClass('db/Bs_Db.class.php');$bsDb = &getDbObject($dsn);if (isEx($bsDb)) {$bsDb->stackTrace('was here in setDbByDsn()', __FILE__, __LINE__, 'fatal');return $bsDb;}
unset($this->_bsDb);$this->_bsDb = &$bsDb;return TRUE;}
function findFirstname($name, $gender=0, $strict=FALSE, $returnData=FALSE) {if ($returnData) {$sql = "SELECT * FROM BsOnomastics.Firstname WHERE caption LIKE '" . addSlashes($name) . "'";if (($gender == 1) || ($gender == 2)) {$sql .= " AND sex = {$gender}";}
$t = $this->_bsDb->getAssoc($sql, TRUE, TRUE);} else {$sql = "SELECT ID FROM BsOnomastics.Firstname WHERE caption LIKE '" . addSlashes($name) . "'";if (($gender == 1) || ($gender == 2)) {$sql .= " AND sex = {$gender}";}
$t = $this->_bsDb->getCol($sql);}
return $t;}
function cleanLastname($lastname) {$regExp = "[\\.| ]";if ((strpos($lastname, ' ') !== FALSE) || (strpos($lastname, "'") !== FALSE)) {reset($this->title);while (list(,$junk) = each($this->title)) {$lastname = preg_replace($junk . '[\\.| ]'   , '', $lastname, 1);$lastname = preg_replace(' ' . $junk . '\\.?', '', $lastname, 1);}
reset($this->suffix);while (list(,$junk) = each($this->suffix)) {$lastname = preg_replace(' ' . $junk, '', $lastname, 1);}
reset($this->prefix);while (list(,$junk) = each($this->prefix)) {if (substr($junk, -1) == "'") {$lastname = preg_replace($junk      , '', $lastname, 1);} else {$lastname = preg_replace($junk . ' ', '', $lastname, 1);}
$lastname = preg_replace(' ' . $junk, '', $lastname, 1);}
reset($this->qualifier);while (list(,$junk) = each($this->qualifier)) {$lastname = preg_replace($junk . '[\\.| ]'   , '', $lastname, 1);$lastname = preg_replace(' ' . $junk . '\\.?', '', $lastname, 1);}
}
$lastname = trim($lastname);return $lastname;}
function getNicknames($firstname) {return array();}
function getVariations($firstname) {return array();}
function getTranslations($firstname, $toLang=null, $strict=FALSE) {return array();}
function getGender($firstname, $strict=FALSE, $returnPlainText=FALSE) {$breakLevel = 1;do {$firstname = trim($firstname);$sql = "SELECT ID, caption, sex FROM BsOnomastics.Firstname WHERE caption LIKE '" . addSlashes($firstname) . "'";$data = $this->_bsDb->getAll($sql);if (is_null($data) || empty($data)) {if (strpos($firstname, ' ')) {$firstArr = explode(' ', $firstname);$breakLevel++;while (list(,$firstname) = each($firstArr)) {$ret = $this->getGender($firstname, $strict);break $breakLevel;}
$breakLevel--;$ret = FALSE; break $breakLevel;} elseif (strpos($firstname, '-')) {$firstArr = explode('-', $firstname);$breakLevel++;while (list(,$firstname) = each($firstArr)) {$ret = $this->getGender($firstname, $strict);break $breakLevel;}
$breakLevel--;$ret = FALSE; }
}
if (isEx($data)) {$ret = FALSE; break $breakLevel;} elseif (is_null($data)) {$ret = FALSE; break $breakLevel;} else {$ret = FALSE;$breakLevel++;while (list($k) = each($data)) {if (($strict) && (strToLower($data[$k]['caption']) !== strToLower($firstname))) continue;if ($ret === FALSE) {if ($data[$k]['sex'] == 1) {$ret = 2; } elseif ($data[$k]['sex'] == 2) {$ret = -2; } } else {if ($data[$k]['sex'] == 1) {if ($ret < 0) {$ret = 0;} } elseif ($data[$k]['sex'] == 2) {if ($ret > 0) {$ret = 0;} } }
}
$breakLevel--;break $breakLevel;}
} while (FALSE);if ($returnPlainText) {switch ($ret) {case -2:
return 'female';case -1:
return 'more female';case 0:
return 'both';case 1:
return 'more male';case 2:
return 'male';default:
return 'unknown';}
} else {return $ret;}
}
function isOrderOk($firstname, $lastname) {$firstname = trim($firstname);$lastname  = trim($lastname);$sql = "SELECT ID, caption, sex FROM BsOnomastics.Firstname WHERE caption LIKE '" . addSlashes($firstname) . "'";$numFound = $this->_bsDb->countRead($sql);if (is_numeric($numFound) && ($numFound > 0)) return 2;$pos  = FALSE;$pos1 = strpos($firstname, ' ');$pos2 = strpos($firstname, '-');if ($pos1 !== FALSE) $pos = $pos1;if (($pos2 !== FALSE) && ($pos2 < $pos)) $pos = $pos2;if ($pos) {$firstnameFirstJunk = substr($firstname, 0, $pos);$sql = "SELECT ID, caption, sex FROM BsOnomastics.Firstname WHERE caption LIKE '" . addSlashes($firstnameFirstJunk) . "'";$numFound = $this->_bsDb->countRead($sql);if (is_numeric($numFound) && ($numFound > 0)) return 2;}
$sql = "SELECT ID, caption, sex FROM BsOnomastics.Firstname WHERE caption LIKE '" . addSlashes($lastname) . "'";$numFound = $this->_bsDb->countRead($sql);if (is_numeric($numFound) && ($numFound > 0)) return 0;$pos  = FALSE;$pos1 = strpos($lastname, ' ');$pos2 = strpos($lastname, '-');if ($pos1 !== FALSE) $pos = $pos1;if (($pos2 !== FALSE) && ($pos2 < $pos)) $pos = $pos2;if ($pos) {$lastnameFirstJunk = substr($lastname, 0, $pos);$sql = "SELECT ID, caption, sex FROM BsOnomastics.Firstname WHERE caption LIKE '" . addSlashes($lastnameFirstJunk) . "'";$numFound = $this->_bsDb->countRead($sql);if (is_numeric($numFound) && ($numFound > 0)) return 0;}
$sql = "SELECT ID, caption, FROM BsOnomastics.Lastname WHERE caption LIKE '" . addSlashes($firstname) . "'";$numFound = $this->_bsDb->countRead($sql);if (is_numeric($numFound) && ($numFound > 0)) return 0;$sql = "SELECT ID, caption, FROM BsOnomastics.Lastname WHERE caption LIKE '" . addSlashes($lastname) . "'";$numFound = $this->_bsDb->countRead($sql);if (is_numeric($numFound) && ($numFound > 0)) return 2;return 1;}
function translateFirstname($firstname, $fromLang, $toLang=null) {return FALSE;}
function calcSimilarityFirstname($nameOne, $nameTwo, $pointsPercent=100) {$ret = 0;do {$cacheKey = $nameOne . '/' . $nameTwo;if (isSet($this->_similarityFirstnameCache[$cacheKey])) {$ret = $this->_similarityFirstnameCache[$cacheKey];break;}
$nameOne = strToLower(trim($nameOne));$nameTwo = strToLower(trim($nameTwo));if ($nameOne == $nameTwo) {$ret = 100;break;}
$nameOneNorm = $this->Bs_String->normalize($nameOne, TRUE);$nameTwoNorm = $this->Bs_String->normalize($nameTwo, TRUE);if ($nameOneNorm == $nameTwoNorm) {$ret = 98;break;}
$nameOneLength = strlen($nameOne);$nameTwoLength = strlen($nameTwo);if ($nameTwoLength == 0) { $ret = 0;break;}
if ($nameOneLength == 0) { $ret = 0;break;}
if ($nameOneLength > $nameTwoLength) {if ($this->Bs_String->startsWith($nameOne, $nameTwo, FALSE)) {$ret = 80;break;}
if ($this->Bs_String->endsWith($nameOne, $nameTwo, FALSE)) {$ret = 75;break;}
} else {if ($this->Bs_String->startsWith($nameTwo, $nameOne, FALSE)) {$ret = 80;break;}
if ($this->Bs_String->endsWith($nameTwo, $nameOne, FALSE)) {$ret = 75;break;}
}
if (strpos($nameOne, $nameTwo) !== FALSE) {$ret = 70;break;}
if (strpos($nameTwo, $nameOne) !== FALSE) {$ret = 70;break;}
$nameOneNormLength = strlen($nameOneNorm);$nameTwoNormLength = strlen($nameTwoNorm);if ($nameOneNormLength > $nameTwoNormLength) {if ($this->Bs_String->startsWith($nameOneNorm, $nameTwoNorm, FALSE)) {$ret = 78;break;}
if ($this->Bs_String->endsWith($nameOneNorm, $nameTwoNorm, FALSE)) {$ret = 73;break;}
} else {if ($this->Bs_String->startsWith($nameTwoNorm, $nameOneNorm, FALSE)) {$ret = 78;break;}
if ($this->Bs_String->endsWith($nameTwoNorm, $nameOneNorm, FALSE)) {$ret = 73;break;}
}
if (strpos($nameOneNorm, $nameTwoNorm) !== FALSE) {$ret = 70;break;}
if (strpos($nameTwoNorm, $nameOneNorm) !== FALSE) {$ret = 70;break;}
if (isSet($this->_similarityFirstnameRelationCache[$nameOne])) {$allWords = $this->_similarityFirstnameRelationCache[$nameOne];} else {$nameIDs1 = $this->findFirstname($nameOne, null, TRUE);$nameIDs  = $nameIDs1;$allWords = NULL;if (!empty($nameIDs)) {if (is_array($nameIDs) && !empty($nameIDs)) {$relationWordIDs   = array();$relationIDs       = array();$allWords          = $nameIDs; $this->findRelationsLimit($nameIDs, $relationWordIDs, $relationIDs, $allWords, 2);}
}
$this->_similarityFirstnameRelationCache[$nameOne] = $allWords;}
if (is_array($allWords) && !empty($allWords)) {$sql = "SELECT * FROM BsOnomastics.Firstname WHERE ID IN (" . join(',', array_keys($allWords)) . ") 
AND (caption LIKE '" . addSlashes($nameTwo) . "' OR caption LIKE '" . addSlashes($nameTwoNorm) . "')";$numCount = $this->_bsDb->countRead($sql);if (isEx($sql)) {$numCount->stackDump('die');} elseif (is_int($numCount) && ($numCount > 0)) {$ret = 70;break;}
}
$levPercent = levenshteinPercent($nameOne, $nameTwo);if ($levPercent < 35) {$t = 100 - $levPercent;$t /= 2;$ret = $t;break;}
$nameOneSoundex = soundex($nameOne);$nameTwoSoundex = soundex($nameTwo);if ($nameOneSoundex == $nameTwoSoundex) {$ret = 20;break;}
} while (FALSE);if (($pointsPercent != 100) && ($ret > 0)) {$ret = (int)($ret * $pointsPercent / 100);}
return $this->_similarityFirstnameCache[$cacheKey] = $ret;}
function calcSimilarityLastname($nameOne, $nameTwo, $pointsPercent=100) {$ret = 0;do {$cacheKey = $nameOne . '/' . $nameTwo;if (isSet($this->_similarityLastnameCache[$cacheKey])) {$ret = $this->_similarityLastnameCache[$cacheKey];break;}
$nameOne = strToLower(trim($nameOne));$nameTwo = strToLower(trim($nameTwo));if ($nameOne == $nameTwo) {$ret = 100;break;}
$nameOneNorm = $this->Bs_String->normalize($nameOne, TRUE);$nameTwoNorm = $this->Bs_String->normalize($nameTwo, TRUE);if ($nameOneNorm == $nameTwoNorm) {$ret = 98;break;}
$nameOneLength = strlen($nameOne);$nameTwoLength = strlen($nameTwo);if ($nameTwoLength == 0) {$ret = 0; break;}
if ($nameOneLength > $nameTwoLength) {if ($this->Bs_String->startsWith($nameOne, $nameTwo, FALSE)) {$ret = 80;break;}
if ($this->Bs_String->endsWith($nameOne, $nameTwo, FALSE)) {$ret = 75;break;}
} else {if ($this->Bs_String->startsWith($nameTwo, $nameOne, FALSE)) {$ret = 80;break;}
if ($this->Bs_String->endsWith($nameTwo, $nameOne, FALSE)) {$ret = 75;break;}
}
if (strpos($nameOne, $nameTwo) !== FALSE) {$ret = 70;break;}
if (strpos($nameTwo, $nameOne) !== FALSE) {$ret = 70;break;}
$nameOneNormLength = strlen($nameOneNorm);$nameTwoNormLength = strlen($nameTwoNorm);if ($nameOneNormLength > $nameTwoNormLength) {if ($this->Bs_String->startsWith($nameOneNorm, $nameTwoNorm, FALSE)) {$ret = 75;break;}
if ($this->Bs_String->endsWith($nameOneNorm, $nameTwoNorm, FALSE)) {$ret = 73;break;}
} else {if ($this->Bs_String->startsWith($nameTwoNorm, $nameOneNorm, FALSE)) {$ret = 78;break;}
if ($this->Bs_String->endsWith($nameTwoNorm, $nameOneNorm, FALSE)) {$ret = 73;break;}
}
if (strpos($nameOneNorm, $nameTwoNorm) !== FALSE) {$ret = 70;break;}
if (strpos($nameTwoNorm, $nameOneNorm) !== FALSE) {$ret = 70;break;}
$levPercent = levenshteinPercent($nameOne, $nameTwo);if ($levPercent < 35) {$t = 100 - $levPercent;$t /= 2;return $this->_similarityLastnameCache[$cacheKey] = $t;}
$nameOneSoundex = soundex($nameOne);$nameTwoSoundex = soundex($nameTwo);if ($nameOneSoundex == $nameTwoSoundex) {$ret = 20;break;}
} while (FALSE);if (($pointsPercent != 100) && ($ret > 0)) {$ret = (int)($ret * $pointsPercent / 100);}
return $this->_similarityLastnameCache[$cacheKey] = $ret;}
function calcSimilarityFirstnameMultiple($nameOne, $nameTwo) {$points = $this->calcSimilarityFirstname($nameOne, $nameTwo);if ($points) return $points;$nameOneArr = split("[ -/]", $nameOne);$nameTwoArr = split("[ -/]", $nameTwo);if ((sizeOf($nameOneArr) > 1) && (sizeOf($nameTwoArr) > 1)) {while (list($k) = each($nameOneArr)) {reset($nameTwoArr);while (list($k2) = each($nameTwoArr)) {$points = $this->calcSimilarityFirstname($nameOneArr[$k], $nameTwoArr[$k2]);if ($points) return $points;}
}
} elseif (sizeOf($nameOneArr) > 1) {while (list($k) = each($nameOneArr)) {$points = $this->calcSimilarityFirstname($nameOneArr[$k], $nameTwoArr[0]);if ($points) return $points;}
} elseif (sizeOf($nameTwoArr) > 1) {while (list($k) = each($nameTwoArr)) {$points = $this->calcSimilarityFirstname($nameOneArr[0], $nameTwoArr[$k]);if ($points) return $points;}
}
return $this->_similarityFirstnameCache[$cacheKey] = 0;}
function calcSimilarityLastnameMultiple($nameOne, $nameTwo) {$points = $this->calcSimilarityLastname($nameOne, $nameTwo);if ($points) return $points;$nameOneArr = split("[ -/]", $nameOne);$nameTwoArr = split("[ -/]", $nameTwo);if ((sizeOf($nameOneArr) > 1) && (sizeOf($nameTwoArr) > 1)) {$pointsPercent = 90;while (list($k) = each($nameOneArr)) {reset($nameTwoArr);while (list($k2) = each($nameTwoArr)) {$pointsPercent2 = $pointsPercent;$points = $this->calcSimilarityLastname($nameOneArr[$k], $nameTwoArr[$k2], $pointsPercent2);if ($points) return $points;$pointsPercent2 -= 10;}
$pointsPercent -= 10;}
} elseif (sizeOf($nameOneArr) > 1) {$pointsPercent = 90;while (list($k) = each($nameOneArr)) {$points = $this->calcSimilarityLastname($nameOneArr[$k], $nameTwoArr[0], $pointsPercent);if ($points) return $points;$pointsPercent -= 10;}
} elseif (sizeOf($nameTwoArr) > 1) {$pointsPercent = 90;while (list($k) = each($nameTwoArr)) {$points = $this->calcSimilarityLastname($nameOneArr[0], $nameTwoArr[$k], $pointsPercent);if ($points) return $points;$pointsPercent -= 10;}
}
return $this->_similarityLastnameCache[$cacheKey] = 0;}
function findRelations($forID, &$relationWordIDs, &$relationIDs, &$allWordIDs) {if (empty($relationIDs)) {$relationIdString = '';} else {$relationIdString = 'and ID not in (' . join(',', $relationIDs) . ')';}
$sql = "SELECT * from BsOnomastics.Firstname2RelationType where 
(first_FirstnameID={$forID} or second_FirstnameID={$forID} or third_FirstnameID={$forID}) 
{$relationIdString}"; $relData = $this->_bsDb->getAll($sql);if (isEx($relData)) {$relData->stackDump('exit');}
while (list(,$relRecord) = each($relData)) {$relationIDs[] = $relRecord['ID'];$wordID = ($relRecord['first_FirstnameID'] == $forID) ? $relRecord['second_FirstnameID'] : $relRecord['first_FirstnameID'];$allWordIDs[] = $wordID;if ($forID < $wordID) {$one = $forID;$two = $wordID;} else {$one = $wordID;$two = $forID;}
$relationWordIDs[] = array('wordOneID'=>$one, 'wordTwoID'=>$two, 'relationTypeID'=>$relRecord['RelationTypeID']);}
}
function findRelationsLimit($forID, &$relationWordIDs, &$relationIDs, &$allWordIDs, $limit=2) {$ddd = $forID;if (is_array($forID)) {$todoWordIDs        = $forID;} else {$todoWordIDs        = array($forID);}
$doneWordIDs        = array();$relationWordIDs    = array();$relationIDs        = array();$i=0;do {if (!is_array($todoWordIDs) || empty($todoWordIDs)) break;if ((sizeOf($todoWordIDs) == 1) && (isSet($todoWordIDs[1])) && ($todoWordIDs[1] === NULL)) break; reset($todoWordIDs);while (list(,$nextWordID) = each($todoWordIDs)) {if (!is_null($nextWordID)) {$this->findRelations($nextWordID, $relationWordIDs, $relationIDs, $allWordIDs);$t = array_search($nextWordID, $todoWordIDs); unset($todoWordIDs[$t]);}
$doneWordIDs[] = $nextWordID;}
reset($allWordIDs);while (list(,$wordID) = each($allWordIDs)) {if (!in_array($wordID, $doneWordIDs) && !in_array($wordID, $todoWordIDs)) {if ($i < ($limit-1)) {$todoWordIDs[] = $wordID;}
}
}
$i++;} while (TRUE);$allWordIDs = array_flip($allWordIDs); }
function addRelation($relationTypeID, $first_FirstnameID, $second_FirstnameID, $third_FirstnameID=0) {$sql = "insert into BsOnomastics.Firstname2RelationType 
(RelationTypeID, first_FirstnameID, second_FirstnameID, third_FirstnameID) 
VALUES ({$relationTypeID}, {$first_FirstnameID}, {$second_FirstnameID}, {$third_FirstnameID})";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in addRelation()', __FILE__, __LINE__);return $status;}
return TRUE;}
function deleteRelation($relationTypeID, $first_FirstnameID, $second_FirstnameID) {$sql = "DELETE FROM BsOnomastics.Firstname2RelationType 
WHERE RelationTypeID='{$relationTypeID}' 
AND (first_FirstnameID='{$first_FirstnameID}'  AND second_FirstnameID='{$second_FirstnameID}') 
OR  (first_FirstnameID='{$second_FirstnameID}' AND second_FirstnameID='{$first_FirstnameID}')";$status = $this->_bsDb->write($sql);if (isEx($status)) {$status->stackTrace('was here in deleteRelation()', __FILE__, __LINE__);return $status;}
return TRUE;}
}
$GLOBALS['Bs_Om_OnomasticsServer'] =& new Bs_Om_OnomasticsServer();if (basename($_SERVER['PHP_SELF']) == 'Bs_Om_OnomasticsServer.class.php') {$ono = &$GLOBALS['Bs_Om_OnomasticsServer'];$status = $ono->isOrderOk('tom', 'jones');switch ($status) {case 2: case 1: case 0: }
dump($status);$status = $ono->isOrderOk('jones', 'tom');switch ($status) {case 2: case 1: case 0: }
dump($status);}
?>