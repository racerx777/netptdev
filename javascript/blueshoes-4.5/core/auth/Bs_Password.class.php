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
define("BS_PASSWORD_VERSION",      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_Password extends Bs_Object {var $_bsDb = NULL;function Bs_Password() {parent::Bs_Object(); if (is_object($GLOBALS['bsDb'])) $this->_bsDb = &$GLOBALS['bsDb'];}
function createPronounceablePassword($length=8, $charType='lnn', $confuseSafe=FALSE, $startWith='random') {$ret          = '';if ($confuseSafe) {$vowel        = 'aeou';$vowelLen     = 4;$consonant    = 'bcdfghkmnpqrstvwxyz';$consonantLen = 19;} else {$vowel        = 'aeiou';$vowelLen     = 5;$consonant    = 'bcdfghjklmnpqrstvwxyz';$consonantLen = 21;}
mt_srand((double)microtime() * 1000000);if ($startWith == 'random') {$startWith = (mt_rand(0, 1)) ? 'wovel' : 'consonant';}
$base = ($startWith == 'consonant') ? FALSE : TRUE;for ($x=0; $x<$length; $x++) {if ($base) { $char = substr($vowel, mt_rand(0, $vowelLen -1), 1);} else {$char = substr($consonant, mt_rand(0, $consonantLen -1), 1);}
switch ($charType[0]) {case 'u':
$char = strToUpper($char);break;case 'b':
$char = (mt_rand(0, 1)) ? strToUpper($char) : $char;break;}
$ret .= $char;$base = !$base;}
return $ret;}
function looksLikeHack($password, $username=null) {if (!is_object($this->_bsDb)) {return new Bs_Exception('no db connection available in looksLikeHack().', __FILE__, __LINE__, null, '');}
$looksLikeHack = TRUE;do { if (!empty($username)) {if (strToLower($username) == strToLower($password)) break;}
if (empty($username)) {$sqlQ   = "SELECT * FROM BsKb.AuthDictWorst WHERE LCASE(caption)=LCASE('{$password}'))";} else {$sqlQ   = "SELECT * FROM BsKb.AuthDictWorst WHERE LCASE(caption)=LCASE('{$username}') OR LCASE(caption)=LCASE('{$password}')";}
$status = $this->_bsDb->countRead($sqlQ);if (isEx($status)) {$status->stackTrace('was here in looksLikeHack()', __FILE__, __LINE__);} elseif ($status > 0) {break; }
$looksLikeHack = FALSE;} while (FALSE);return $looksLikeHack;}
function isDictionaryWord($word) {if (!is_object($this->_bsDb)) {return new Bs_Exception('no db connection available in isDictionaryWord().', __FILE__, __LINE__, null, '');}
$sqlQ   = "SELECT * FROM BsKb.DictEnSimple WHERE LCASE(word)=LCASE('{$word}')) LIMIT 1";$status = $this->_bsDb->countRead($sqlQ);if (isEx($status)) {$status->stackTrace('was here in isDictionaryWord()', __FILE__, __LINE__);return $status;} else {return (bool)$status;}
}
function isBoyName($name) {if (!is_object($this->_bsDb)) {return new Bs_Exception('no db connection available in isBoyName().', __FILE__, __LINE__, null, '');}
$sqlQ   = "SELECT * FROM BsKb.BoyName WHERE LCASE(caption)=LCASE('{$name}')) LIMIT 1";$status = $this->_bsDb->countRead($sqlQ);if (isEx($status)) {$status->stackTrace('was here in isBoyName()', __FILE__, __LINE__);return $status;} else {return (bool)$status;}
}
function isGirlName($name) {if (!is_object($this->_bsDb)) {return new Bs_Exception('no db connection available in isGirlName().', __FILE__, __LINE__, null, '');}
$sqlQ   = "SELECT * FROM BsKb.GirlName WHERE LCASE(caption)=LCASE('{$name}')) LIMIT 1";$status = $this->_bsDb->countRead($sqlQ);if (isEx($status)) {$status->stackTrace('was here in isGirlName()', __FILE__, __LINE__);return $status;} else {return (bool)$status;}
}
function isFirstName($name) {$status = $this->isBoyName($name);if ($status === TRUE) return TRUE;$status2 = $this->isGirlName($name);if ($status2 === TRUE) return TRUE;if (isEx($status)) {$status->stackTrace('was here in isFirstName()', __FILE__, __LINE__);return $status;} elseif (isEx($status2)) {$status2->stackTrace('was here in isFirstName()', __FILE__, __LINE__);return $status;}
return FALSE;}
function isSilly($password) {return FALSE;}
function isBadPassword($password) {return FALSE;}
} ?>