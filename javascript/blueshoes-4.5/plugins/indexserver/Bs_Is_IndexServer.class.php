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
define('BS_IS_INDEXSERVER_VERSION',      '4.5.$Revision: 1.5 $');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_Profile.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_Indexer.class.php');require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_Searcher.class.php');require_once($APP['path']['core']    . 'util/Bs_String.class.php');require_once($APP['path']['core']    . 'html/Bs_HtmlUtil.class.php');Class Bs_Is_IndexServer extends Bs_Object {var $Bs_String;var $Bs_HtmlUtil;var $_clhProfile;var $_clhIndexer;var $_clhSearcher;var $_stopWords;function Bs_Is_IndexServer() {if (isSet($GLOBALS['Bs_Is_IndexServer'])) return $GLOBALS['Bs_Is_IndexServer'];$GLOBALS['Bs_Is_IndexServer'] = &$this;parent::Bs_Object(); $this->Bs_String = &$GLOBALS['Bs_String'];$this->Bs_HtmlUtil = &$GLOBALS['Bs_HtmlUtil'];}
function setProfile(&$profile) {$this->_clhProfile[$profile->profileName] = &$profile;if (!isSet($this->_bsDb)) {$this->_bsDb = &$profile->_bsDb;$this->cacheStopWords();}
}
function &getIndexer($profileName) {if (isSet($this->_clhIndexer[$profileName])) {return $this->_clhIndexer[$profileName];}
if (!isSet($this->_clhProfile[$profileName])) return FALSE;$indexer =& new Bs_Is_Indexer($this, $this->_clhProfile[$profileName], $this->_clhProfile[$profileName]->getIndexDbObj());$this->_clhIndexer[$profileName] = &$indexer;return $indexer;}
function &getSearcher($profileName) {if (isSet($this->_clhSearcher[$profileName])) {return $this->_clhSearcher[$profileName];}
if (!isSet($this->_clhProfile[$profileName])) return FALSE;$searcher =& new Bs_Is_Searcher($this, $this->_clhProfile[$profileName], $this->_clhProfile[$profileName]->getIndexDbObj());$this->_clhSearcher[$profileName] = &$searcher;return $searcher;}
function &getProfile($profileName) {if (isSet($this->_clhProfile[$profileName])) {return $this->_clhProfile[$profileName];}
return FALSE;}
function cacheStopWords($lang=null) {if (is_null($lang)) {$lang = array('en', 'de');} elseif (is_string($lang)) {$lang = array($lang);}
if (is_array($lang)) {while (list(,$myLang) = each($lang)) {$sql  = "SELECT caption, 1 FROM BsKb.NoiseWords" . ucFirst($myLang) . " ORDER BY caption";$data = $this->_bsDb->getAssoc($sql, FALSE);if (isEx($data)) {$data->stackTrace("been here in cacheStopWords for language: '" . $myLang . "'.", __FILE__, __LINE__);$data->stackDump('die');} else {$this->_stopWords[$myLang] = $data;}
}
}
}
function isStopWord($word, $lang=null) {if (is_null($lang)) {if (@is_array($this->_stopWords)) {reset($this->_stopWords);while (list($currentLang) = each($this->_stopWords)) {if (isSet($this->_stopWords[$currentLang][$word])) return TRUE;}
}
} elseif (isSet($this->_stopWords[$lang])) {return (isSet($this->_stopWords[$lang][$word]));}
return FALSE;}
function getStem($word, $lang='') {if (!function_exists('stem')) return ''; switch ($lang) {case 'en': return stem($word, STEM_ENGLISH);case 'fr': return stem($word, STEM_FRENCH);case 'es': return stem($word, STEM_SPANISH);case 'nl': return stem($word, STEM_DUTCH);case 'dk': return stem_danish($word);case 'de': return stem_german($word);case 'it': return stem_italian($word);case 'no': return stem_norwegian($word);case 'pt': return stem_portuguese($word);case 'se': return stem_swedish($word);default:
return '';}
}
function cleanString($string) {if (empty($string)) return '';$string = strToLower($string);$string = ereg_replace('-<br>[ \t\n\r]*', '', $string);$string = str_replace("<", " <", $string); $string = strip_tags($string);             $string = $this->Bs_HtmlUtil->htmlEntitiesUndo($string);$string = $this->Bs_String->normalize($string);$string = str_replace('-', '',  $string);$string = str_replace('&', ' ', $string);$string = str_replace('/', ' ', $string);$string = str_replace('(', ' ', $string);$string = str_replace(')', ' ', $string);$allowedSpecialCharsArray = array(32);$string = $this->_treatSpecialChars($string, ' ', $allowedSpecialCharsArray);return $string;}
function cleanStringChunkSentence($string) {$string = strToLower($string);$string = preg_replace('/\r/',  '',  $string);$string = preg_replace('/-\n/', '',  $string);$string = preg_replace('/\n/',  ' ', $string);$string = str_replace("/</", " <", $string); $string = strip_tags($string);             $string = $this->Bs_HtmlUtil->htmlEntitiesUndo($string);$string = $this->Bs_String->normalize($string);$string = str_replace('-', '',  $string);$string = str_replace('&', ' ', $string);$string = str_replace('/', ' ', $string);$string = str_replace('(', ' ', $string);$string = str_replace(')', ' ', $string);$allowedSpecialCharsArray = array(32, 9, 33, 46, 58, 59, 63);$string = $this->_treatSpecialChars($string, ' ', $allowedSpecialCharsArray);$array = preg_split("/[\t\.\!\?\:\;]+/", $string);return $array;}
function cleanWord($word, $minLength=3, $maxLength=30, $returnError=FALSE) {$errReason = 'unknown';do {$word = trim($word);if (strlen($word) < $minLength) {$errReason = 'length'; break; }
if (strlen($word) > $maxLength) {$word = substr($word, 0, $maxLength);}
return $word;} while (FALSE);if ($returnError) {return array($errReason);} else {return FALSE;}
}
function _treatSpecialChars($string='', $replaceWith=' ', $allowedSpecialCharsArray='') {$ret = '';$len = strlen($string);for ($i = 0; $i < $len; $i++) {$allowedChar = FALSE;$x = ord($string[$i]);if (($x >= 48 AND $x <= 57) OR ($x >= 65 AND $x <= 90) OR ($x >= 97 AND $x <= 122)) {$allowedChar = TRUE;} else {if (is_array($allowedSpecialCharsArray)) {reset($allowedSpecialCharsArray);while(list($k, $v) = each($allowedSpecialCharsArray)) {if ($x == $v) {$allowedChar = TRUE;break;}
}
} else {$allowedChar = FALSE;}
}
if ($allowedChar) {$ret .= $string[$i];} else {$ret .= $replaceWith;}
}
return $ret;}
}
?>