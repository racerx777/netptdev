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
define('BS_FORMFIELDEMAIL_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldText.class.php');class Bs_FormFieldEmail extends Bs_FormFieldText {var $checkOnChange;var $checkOnChangeUrl;function Bs_FormFieldEmail() {$this->Bs_FormFieldTxt(); $this->caption          = array('en'=>'E-Mail', 'de'=>'E-Mail');$this->minLength        = 7;$this->maxLength        = 60;$this->bsDataType       = 'email';$this->bsDataInfo       = 1;$this->trim             = 'both';$this->persisterVarSettings['checkOnChange']     = array('mode'=>'stream');$this->persisterVarSettings['checkOnChangeUrl']  = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function disallowFreemail() {$this->additionalCheck = '
$ev =& new Bs_EmailValidator();if (!$ev->usesFreemailProvider($v)) return TRUE;return $this->errorMessage = array(
"en" => "No freemail providers accepted, sorry.", 
"de" => "Kostenlose E-Mail Provider sind nicht zugelassen, sorry."
);';}
function &getField($explodeKey=NULL, $addEnforceCheckbox=TRUE) {if ($this->getVisibility() != 'normal') {return parent::getField($explodeKey, $addEnforceCheckbox);}
$ret = '';$fieldName = $this->_getFieldNameForHtml($this->name);$this->_markAsUsed();$ret .= "<nobr><input type=\"{$this->fieldType}\"";if (!is_null($explodeKey)) {$ret .= " name=\"{$fieldName}[{$explodeKey}]\"";} else {$ret .= " name=\"{$fieldName}\"";}
$ret .= " value=\"" . $this->_getTagStringValue($explodeKey) . "\"";if (!empty($this->size)) $ret .= " size=\"{$this->size}\"";if (!empty($this->direction)) $ret .= " dir=\"{$this->direction}\"";$ret .= $this->_getTagStringStyles();if ($this->checkOnChange) {$this->events = $this->_Bs_Array->hashKeysToLower($this->events);$t = "bsFormCheckMail('{$this->checkOnChangeUrl}', this, {$this->checkOnChange});";if (isSet($this->events['onchange'])) {$this->events['onchange'] .= ' ' . $t;} else {$this->events['onchange'] = $t;}
}
$ret .= $this->_getTagStringEvents();if (!is_null($t = $this->_getMaxLength())) $ret .= " maxlength=\"{$t}\"";$ret .= $this->_getTagStringAdditionalTags();$ret .= '>';if ($this->checkOnChange) {$ret .= '<iframe src="' . $this->checkOnChangeUrl . '" name="bsMailCheck' . $fieldName . '" id="bsMailCheck' . $fieldName . '" width="20" height="20" marginwidth="0" marginheight="0" hspace="0" vspace="0" align="left" scrolling="no" frameborder="0"></iframe>';}
$ret .= '</nobr>'; if ($addEnforceCheckbox) $ret .= $this->addEnforceCheckbox();return $this->_doElementStringFormat($ret);}
}
?>