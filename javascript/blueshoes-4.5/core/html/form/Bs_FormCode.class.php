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
define('BS_FORMCODE_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormElement.class.php');class Bs_FormCode extends Bs_FormElement {var $code;function Bs_FormCode() {parent::Bs_FormElement(); $this->elementType = 'code';$tempArray = array('mode'=>'stream');$this->persisterVarSettings['code']   = &$tempArray;$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getElement() {$code = $this->getLanguageDependentValue($this->code);if (is_string($code)) {$t = evalWrap($code, 'low');if (is_string($t)) $ret = $t;} else {$ret = '';}
return $this->_doElementStringFormat($ret);}
}
?>