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
define('BS_FORMFIELDLASTNAME_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldText.class.php');class Bs_FormFieldLastname extends Bs_FormFieldText {var $firstnameField;function Bs_FormFieldLastname() {$this->Bs_FormFieldText(); $this->caption          = array(
'en'=>'Last name', 
'de'=>'Nachname', 
'fr'=>'Nom', 
'it'=>'Cognome', 
);$this->minLength        = 2;$this->maxLength        = 30;$this->bsDataType       = 'lastname';$this->trim             = 'both';$this->persisterVarSettings['firstnameField']     = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {if ($this->getVisibility() != 'normal') {return parent::getField($explodeKey, $addEnforceCheckbox);}
$ret = '';$fieldName     = $this->_getFieldNameForHtml($this->name);$this->_form->seedClearingHouse(); $useOno = FALSE;if (method_exists($this->_form->clearingHouse[$this->firstnameField], 'getOnoClientName')) {$useOno = TRUE;$onoClientName = $this->_form->clearingHouse[$this->firstnameField]->getOnoClientName();}
$this->_markAsUsed();$ret .= "<input type=\"{$this->fieldType}\"";if (!is_null($explodeKey)) {$ret .= " name=\"{$fieldName}[{$explodeKey}]\"";} else {$ret .= " name=\"{$fieldName}\"";}
$ret .= " value=\"" . $this->_getTagStringValue($explodeKey) . "\"";if (!empty($this->size)) $ret .= " size=\"{$this->size}\"";if (!empty($this->direction)) $ret .= " dir=\"{$this->direction}\"";$ret .= $this->_getTagStringStyles();if ($useOno) {$this->events = $this->_Bs_Array->hashKeysToLower($this->events);$t = $onoClientName . ".queryMix(this);\n";if (isSet($this->events['onchange'])) {$this->events['onchange'] .= ' ' . $t;} else {$this->events['onchange'] = $t;}
}
$ret .= $this->_getTagStringEvents();if (!is_null($t = $this->_getMaxLength())) $ret .= " maxlength=\"{$t}\"";$ret .= $this->_getTagStringAdditionalTags();$ret .= '>';if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
}
?>