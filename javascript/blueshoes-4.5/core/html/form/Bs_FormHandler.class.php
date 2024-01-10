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
define('BS_FORMHANDLER_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_Form.class.php');class Bs_FormHandler extends Bs_Object {var $_bsDb;var $form;var $_postVars;var $_formNameId;var $_language;var $_mode;function Bs_FormHandler($formNameId, $bsDb=NULL) {parent::Bs_Object(); if (is_null($bsDb)) {$this->_bsDb = &$GLOBALS['bsDb'];} else {$this->_bsDb = &$bsDb;}
$this->_postVars = &$GLOBALS['HTTP_POST_VARS'];$this->_formNameId = &$formNameId;}
function go($doLoadForm=TRUE) {if ($doLoadForm) {$status = $this->loadForm();if (isEx($status)) {$status->stackTrace('was here in go()', __FILE__, __LINE__);return $status;}
}
if ((isSet($this->_postVars['bs_form']['step'])) && ($this->_postVars['bs_form']['step'] == '2')) {$this->form->setReceivedValues($this->_postVars);$isOk = $this->form->validate();if ($isOk) {$status = $this->form->saveToDb();if (isEx($status)) {$status->stackTrace('was here in go()', __FILE__, __LINE__);return $status;}
$ret .= $this->form->getForm();} else {$ret .= $this->form->getForm();}
} else {if ($this->_postVars['bs_form']['mode'] == 'edit') {$ret .= $this->form->getForm();} else { $ret .= $this->form->getForm();}
}
return $ret;}
function loadForm() {$this->form =& new Bs_Form();if (is_numeric($this->_formNameId)) {$this->form->persisterID = $this->_formNameId;$status = $this->form->unPersist();} else { $status = $this->form->unPersist($this->_formNameId);}
if (isEx($status)) {$status->stackTrace('was here in loadForm()', __FILE__, __LINE__);return $status;}
if ($status === FALSE) {return new Bs_Exception("was not able to unPersist() the form you wanted. formNameId was: {$this->_formNameId}.", __FILE__, __LINE__, '', 'fatal');}
if (isSet($this->_language)) $this->form->language = $this->_language;if (isSet($this->_mode))     $this->form->mode     = $this->_mode;return TRUE;}
function setLanguage($lang='en') {$this->_language = $lang;if (isSet($this->form)) $this->form->language = $lang;}
function setMode($mode='') {$this->_mode = $mode;if (isSet($this->form)) $this->form->mode = $mode;}
}
?>