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
require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");Class Bs_Ih_InstantHelp extends Bs_Object {var $_bsDb;var $_currentDict;function Bs_Ih_InstantHelp() {parent::Bs_Object(); $this->_bsDb     = &$GLOBALS['bsDb'];}
function setDb($dbObj) {unset($this->_bsDb);$this->_bsDb = &$dbObj;}
function loadDict($dictName) {$this->_currentDict = $dictName;return TRUE;}
function createDict($dictName) {$sql = "
CREATE TABLE IF NOT EXISTS BsInstantHelp (
ID       INT         NOT NULL DEFAULT 0 AUTO_INCREMENT, 
dict     VARCHAR(40) NOT NULL DEFAULT '', 
strKey   CHAR(40)    NOT NULL DEFAULT '', 
lang     CHAR(5)     NOT NULL DEFAULT '', 
helpText BLOB        NOT NULL DEFAULT '', 
PRIMARY  KEY ID (ID), 
KEY dictStrKeyLang (dict, strKey, lang)
)";$status = $this->_bsDb->write($sql);if ($status === TRUE) {$this->_currentDict = $dictName;return TRUE;}
return FALSE;}
function getText($strKey, $lang=NULL, $dictName=NULL) {if (is_null($dictName)) $dictName = $this->_currentDict;$sql = "SELECT lang, helpText FROM BsInstantHelp WHERE dict LIKE '{$dictName}' AND strKey LIKE '{$strKey}'";$data = $this->_bsDb->getAssoc($sql, TRUE);if (is_array($data) && !empty($data)) {if (isSet($data[$lang][0])) return $data[$lang][0]; while (list($foundLang) = each($data)) {if (isSet($data[$foundLang][0])) return $data[$foundLang][0];}
}
return FALSE;}
function getHash($lang, $dictName) {$sql = "SELECT strKey, helpText FROM BsInstantHelp WHERE dict='{$dictName}' AND lang='{$lang}'";$data = $this->_bsDb->getAssoc($sql, FALSE);return $data;}
function setText($strKey, $lang='') {$sql = "REPLACE INTO BsInstantHelp (dict, strKey, lang, helpText) VALUES() WHERE dict='{$this->_currentDict}', strKey='{$strKey}', lang='{$lang}'";$status = $this->_bsDb->write($sql);if ($status === TRUE) return TRUE;return FALSE;}
}
?>