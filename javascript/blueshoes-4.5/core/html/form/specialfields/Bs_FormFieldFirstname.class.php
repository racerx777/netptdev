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
define('BS_FORMFIELDFIRSTNAME_VERSION',      '4.5.$Revision: 1.5 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldText.class.php');class Bs_FormFieldFirstname extends Bs_FormFieldText {var $lastnameField;var $sexField;var $communicatorUrl;function Bs_FormFieldFirstname() {$this->Bs_FormFieldText(); $this->caption          = array(
'en'=>'First name', 
'de'=>'Vorname', 
'fr'=>'Prénom', 
'it'=>'Nome', 
);$this->minLength        = 2;$this->maxLength        = 30;$this->bsDataType       = 'firstname';$this->trim             = 'both';$this->persisterVarSettings['lastnameField']     = array('mode'=>'stream');$this->persisterVarSettings['sexField']          = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {if ($this->getVisibility() != 'normal') {return parent::getField($explodeKey, $addEnforceCheckbox);}
$ret = '';$fieldName     = $this->_getFieldNameForHtml($this->name);$onoClientName = $this->getOnoClientName();$this->_markAsUsed();$ret .= "<input type=\"{$this->fieldType}\"";if (!is_null($explodeKey)) {$ret .= " name=\"{$fieldName}[{$explodeKey}]\"";} else {$ret .= " name=\"{$fieldName}\"";}
$ret .= " value=\"" . $this->_getTagStringValue($explodeKey) . "\"";if (!empty($this->size)) $ret .= " size=\"{$this->size}\"";if (!empty($this->direction)) $ret .= " dir=\"{$this->direction}\"";$ret .= $this->_getTagStringStyles();if (TRUE) {$this->events = $this->_Bs_Array->hashKeysToLower($this->events);$t  = "try {" . $onoClientName . ".queryGender(this); } catch (e) {} \n";$t .= "try {" . $onoClientName . ".queryMix(this); } catch (e) {}\n";if (isSet($this->events['onchange'])) {$this->events['onchange'] .= ' ' . $t;} else {$this->events['onchange'] = $t;}
}
$ret .= $this->_getTagStringEvents();if (!is_null($t = $this->_getMaxLength())) $ret .= " maxlength=\"{$t}\"";$ret .= $this->_getTagStringAdditionalTags();$ret .= '>';if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();$this->_form->addIncludeOnce('/_bsJavascript/plugins/jsrs/JsrsCore.lib.js');$this->_form->addIncludeOnce('/_bsJavascript/plugins/onomastics/OnomasticsClient.class.js');$aolc  = ''; $aolc .= "{$onoClientName} = new OnomasticsClient();\n";if (isSet($this->communicatorUrl)) {$aolc .= "{$onoClientName}.communicatorUrl = '{$this->communicatorUrl}';\n";}
$aolc .= "{$onoClientName}.language = '{$this->_form->language}';\n";$aolc .= "{$onoClientName}.init('{$onoClientName}', '{$this->_form->name}', '{$fieldName}', '{$this->lastnameField}', '{$this->sexField}');\n";$this->_form->addOnLoadCode($aolc);return $this->_doElementStringFormat($ret);}
function getOnoClientName() {$fieldName = $this->_getFieldNameForHtml($this->name);return $fieldName . 'OnoClient';}
}
?>