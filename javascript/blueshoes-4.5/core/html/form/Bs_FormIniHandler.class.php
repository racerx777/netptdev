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
define('BS_FORMINIHANDLER_VERSION',      '4.5.$Revision: 1.3 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_Form.class.php');require_once($APP['path']['core'] . 'util/Bs_IniHandler.class.php');class Bs_FormIniHandler extends Bs_Object {var $_IniHandler;var $_form;var $mayAddFields = FALSE;var $acceptEmpty  = TRUE;var $globalGroupName = '_globalGroup_';function Bs_FormIniHandler() {parent::Bs_Object(); $this->_IniHandler =& new Bs_IniHandler();}
function setFormObj(&$form) {$this->_form = &$form;}
function &getFormObj() {return $this->_form;}
function createFormObj() {$this->_form =& new Bs_Form();$this->_form->internalName         = 'FormIniHandler';$this->_form->name                 = 'FormIniHandler';$this->_form->mustFieldsVisualMode = 'none';$this->_form->useAccessKeys        = TRUE;$this->_form->useJsFile            = TRUE;$this->_form->language             = 'en';$this->_form->mode                 = 'add';$this->_form->onEnter              = 'tab';$this->_form->buttons['add']['save'] = array('en'=>'Save',  'de'=>'Speichern');}
function doItYourself($fileFullPath) {if (!file_exists($fileFullPath) || !is_readable($fileFullPath)) return FALSE;$this->generateFormFieldsByFullPath($fileFullPath);if (@$_POST['bs_form']['step'] == '2') {$status = $this->_form->doItYourself();if (is_array($status)) return $status;} else {return $this->_form->getAll();}
$status = $this->_IniHandler->loadFile($fileFullPath);$formData = $this->_form->getValuesArray(TRUE, 'valueInternal');while (list($varName, $value) = each($formData)) {$t = explode('_', $varName);$groupName = $t[0];$varName   = $t[1];if ($groupName == $this->globalGroupName) $groupName = '';$this->_IniHandler->set($groupName, $varName, $value);}
$status = $this->_IniHandler->saveFile($fileFullPath);return TRUE;}
function doItYourselfWithPage($fileFullPath) {$formOut = $this->doItYourself($fileFullPath);$ret  = '';$ret .= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';$ret .= "<html><head><title>BlueShoes FormIniHandler</title>\n";if (is_array($formOut)) {if (isSet($formOut['include'])) {foreach($formOut['include'] as $url) {$ret .= "<script language='JavaScript' src='{$url}' type='text/javascript'></script>\n";}
}
if (isSet($formOut['onLoad'])) {$ret .= "<script language='JavaScript' type='text/javascript'>\n";$ret .= "<!--\n";$ret .= "onload=function() {\n";$ret .= $formOut['onLoad'];$ret .= "}\n";$ret .= "// -->\n";$ret .= "</script>\n";}
if (isSet($formOut['head'])) {$ret .= $formOut['head'];}
}
$ret .= '</head><body>';if (is_array($formOut)) {if (isSet($formOut['errors'])) $ret .= $formOut['errors'];$ret .= $formOut['form'];} elseif ($formOut === TRUE) {$ret .= 'saved successfully.';} else { $ret .= 'error: no such file, or file not readable.';}
$ret .= '</body></html>';return $ret;}
function generateFormFieldsByFullPath($fileFullPath) {if (!isSet($this->_form)) $this->createFormObj();$status = $this->_IniHandler->loadFile($fileFullPath);$data   = $this->_IniHandler->get();foreach($data as $group => $arr) {unset($container);$container =& new Bs_FormContainer();$container->name    = $group;$container->caption = $group;$this->_form->elementContainer->addElement($container);foreach($arr as $key => $value) {unset($field);if ($group == '') {$fieldName = $this->globalGroupName . '_' . $key;} else {$fieldName = $group . '_' . $key;}
$field =& new Bs_FormFieldText();$field->name         = $fieldName;$field->caption      = $key;$field->valueDefault = $value;if (!$this->acceptEmpty) {$field->must      = TRUE;$field->minLength = 1;}
$container->addElement($field);}
}
return TRUE;}
}
$fih =& new Bs_FormIniHandler();?>