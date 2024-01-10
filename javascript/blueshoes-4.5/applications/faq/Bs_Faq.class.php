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
require_once($APP['path']['applications'] . 'faq/Bs_FaqRecord.class.php');require_once($APP['path']['core']         . 'html/Bs_HeadCollector.class.php');require_once($APP['path']['core']         . 'html/form/Bs_FormItAble.class.php');require_once($APP['path']['core']         . 'lang/Bs_ToDo.lib.php');require_once($APP['path']['core']         . 'text/Bs_LanguageHandler.class.php');class Bs_Faq extends Bs_Object {var $_objPersister;var $layout = array(
'startTag'        => '<ol>', 
'endTag'          => '</ol>', 
'record'          => '<li><b>__QUESTION__</b><br>__ANSWER__</li><br><br>', 
'recordsPerPage'  => 10, 
'useQuestionList' => 'false', 
);var $user = 'user';var $language = 'en';var $todo;var $headCollector;var $_guiLangHash;function Bs_Faq() {}
function setObjPersister(&$objPersister) {$this->_objPersister = &$objPersister;}
function doItYourself() {if (!isSet($this->headCollector)) $this->headCollector = &$GLOBALS['Bs_HeadCollector'];$this->todo = $_REQUEST['bs_todo'];$arr = array('add', 'edit', 'delete');if (in_array($this->todo[BS_TODO_DATAHASH]['doWhat'], $arr)) {return $this->_buildFormGeneric('Bs_FaqRecord');} else {return $this->show();}
}
function show() {$ret = '';$objList = $this->_objPersister->loadAll('Bs_FaqRecord');if (empty($objList)) {$ret .= $this->getInterfaceText('noRecords');} else {if ($this->user === 'admin') {$ret .= '<table>';while (list($k) = each($objList)) {$obj = $objList[$k];$ret .= '<tr>';$ret .= '<td>' . $obj->ID . '</td>';$ret .= '<td>' . $obj->questionGroup . '</td>';$ret .= '<td>' . $obj->question . '</td>';$ret .= '<td>' . $obj->fromEmail . '</td>';$ret .= '<td>' . $obj->fromName . '</td>';$ret .= '<td>' . $obj->addDatetime . '</td>';$ret .= '<td>' . $obj->doShow . '</td>';$qs = bs_makeTodoQueryString($exitScreen='', $exitActions=array(), $nextScreen='', $nextActions=array(), $dataHash=array('doWhat'=>'edit', 'ID'=>$obj->ID));$ret .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?edit=1' . $qs . '">' . $this->getInterfaceText('EDIT') . '</a></td>';$qs = bs_makeTodoQueryString($exitScreen='', $exitActions=array(), $nextScreen='', $nextActions=array(), $dataHash=array('doWhat'=>'delete', 'ID'=>$obj->ID));$ret .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?edit=1' . $qs . '">' . $this->getInterfaceText('DELETE') . '</a></td>';$ret .= '</tr>';}
$ret .= '</table>';} else {$ret .= $this->layout['startTag'];while (list($k) = each($objList)) {$obj = $objList[$k];if (!$obj->doShow) continue;$lay = $this->layout['record'];$lay = str_replace('__QUESTION__',       $obj->question, $lay);$lay = str_replace('__ANSWER__',         $obj->answer,   $lay);$lay = str_replace('__FROM_EMAIL__',     $obj->fromEmail,   $lay);$lay = str_replace('__FROM_NAME__',      $obj->fromName,   $lay);$lay = str_replace('__ADD_DATETIME__',   $obj->addDatetime,   $lay);$ret .= $lay;}
$ret .= $this->layout['endTag'];}
}
$qs = bs_makeTodoQueryString($exitScreen='', $exitActions=array(), $nextScreen='', $nextActions=array(), $dataHash=array('doWhat'=>'add'));$ret .= '<a href="' . $_SERVER['PHP_SELF'] . '?edit=1' . $qs . '">' . $this->getInterfaceText('askQuestion') . '</a>';return $ret;}
function getInterfaceText($key, $lang=null) {do {if (is_null($lang)) {if (!isSet($this->language)) break; $lang = $this->language;}
if (!isSet($this->_guiLangHash[$lang])) {$status = $this->_loadInterfaceLanguage($lang);if (!$status) {break; }
}
if (!isSet($this->_guiLangHash[$lang]['default'][$key])) break; return $this->_guiLangHash[$lang]['default'][$key];} while (FALSE);return ''; }
function _loadInterfaceLanguage($wantLang=null) {if (is_null($wantLang)) {if (!isSet($this->language)) return FALSE; $wantLang = $this->language;}
if (isSet($this->_guiLangHash[$wantLang])) return TRUE; $Bs_LanguageHandler =& new Bs_LanguageHandler();$t = &$Bs_LanguageHandler->determineLanguage($GLOBALS['APP']['path']['applications'] . 'faq/lang/text', $wantLang);if (is_null($t)) return FALSE; list($lang, $path) = $t;if (isSet($this->_guiLangHash[$lang])) {if (!isSet($this->_guiLangHash[$wantLang])) {$this->_guiLangHash[$wantLang] = &$this->_guiLangHash[$lang];}
} else {$this->_guiLangHash[$lang] = &$Bs_LanguageHandler->readLanguage($path);$this->_guiLangHash[$wantLang] = &$this->_guiLangHash[$lang];}
return TRUE;}
function _buildFormGeneric($objToEdit, $screen='') {$_func_ = '_buildFormGeneric';$ret = '';$fia =& new Bs_FormItAble();if (is_string($objToEdit)) {$objToEdit =& new $objToEdit();}
if ($this->todo[BS_TODO_DATAHASH]['doWhat'] === 'add') {} else {if (empty($objToEdit->ID)) {$objID = FALSE;if (@$_REQUEST['ID']) {$objID = $_REQUEST['ID'];} elseif (@$this->todo[BS_TODO_DATAHASH]['ID']) {$objID = $this->todo[BS_TODO_DATAHASH]['ID'];}
if ($objID) {$objToEdit->ID = $objID;$status = $this->_objPersister->load($objToEdit);if ($status !== TRUE) {$className = get_class($objToEdit);Bs_Error::setError("Failed loading the object[{$className}] with id: " . $objID, 'ERROR', __LINE__, $_func_, __FILE__);return Bs_Error::getLastError();}
} else {$ret = 'No ID available, there has to be one. Line: ' . __LINE__ . ' File: ' . __FILE__;return $ret;}
}
}
$todoDataHash = $this->todo[BS_TODO_DATAHASH];$todoInfo = bs_makeHiddenToDoFields($screen, '', $screen, array('save'=>TRUE), $todoDataHash);if (is_object($t = &$fia->doItYourself($objToEdit, TRUE, array('user'=>$this->user, 'mode'=>$this->todo[BS_TODO_DATAHASH]['doWhat'])))) { $uploadInfo = array();foreach($t->clearingHouse as $k => $field) {if (($field->elementType === 'field') AND ($field->fieldType === 'file')) {$copyTo = empty($field->valueInternal) ? FALSE :  $field->diskStorage['path'] . $field->valueInternal;$uploadInfo[] = array(
'attrName'=>$field->name,
'attrValue'=>$field->valueInternal,
'copyFrom'=> $field->fileInfo['tmpFullPath'], 
'copyTo'=>$copyTo,
);}
}
$backupObj = FALSE;switch ($this->todo[BS_TODO_DATAHASH]['doWhat']) {case 'edit':
if (!empty($objToEdit->ID)) {$backupObj = $this->_objPersister->loadById(get_class($objToEdit), $objToEdit->ID);foreach($uploadInfo as $info) {if (empty($info['attrValue'])) {$objToEdit->$info['attrName'] = $backupObj->$info['attrName'];} else {if ($info['copyTo'] AND file_exists($info['copyTo'])) unlink($info['copyTo']);if ($info['copyTo'] AND file_exists($info['copyFrom'])) {copy($info['copyFrom'], $info['copyTo']);@unlink($info['copyFrom']);}
}
}
}
$status = $this->_objPersister->store($objToEdit);break;case 'add':
foreach($uploadInfo as $info) {if (empty($info['attrValue'])) {} else {if ($info['copyTo'] AND file_exists($info['copyTo'])) unlink($info['copyTo']);if ($info['copyTo'] AND file_exists($info['copyFrom'])) {copy($info['copyFrom'], $info['copyTo']);@unlink($info['copyFrom']);}
}
}
$status = $this->_objPersister->store($objToEdit);break;case 'delete':
foreach($uploadInfo as $info) {if ($info['copyTo'] AND file_exists($info['copyTo'])) unlink($info['copyTo']);}
$status = $this->_objPersister->delete($objToEdit);break;default:
XR_dump($this->todo[BS_TODO_DATAHASH], __FILE__, '', __LINE__);}
if ($status) {switch ($this->todo[BS_TODO_DATAHASH]['doWhat']) {case 'delete':
$ret .= '<div class="PaddingMain">' . $this->getInterfaceText('deletedSuccessfully') . '</div>';break;case 'add':
$ret .= '<div class="paddingMain">' . $this->getInterfaceText('addedSuccessfully') . '</div>';break;default: $ret .= '<div class="paddingMain">' . $this->getInterfaceText('savedSuccessfully') . '</div>';}
return $ret;} else {$ret .= "Error occured.<br>\n" . join("<br>\n", $this->_objPersister->getLastErrors());return $ret;}
} else {$this->headCollector->addIncludeOnce($t['include']);$this->headCollector->addOnLoadCode($t['onLoad']);if (!empty($t['errors'])) $ret .= $t['errors'];$ret .= str_replace('<bs_before_formclose_tag/>', $todoInfo, $t['form']);}
switch ($this->todo[BS_TODO_DATAHASH]['doWhat']) {case 'delete':
$ret = "<div class='paddingMain boldtxt redtxt'>" . $this->getInterfaceText('CONFIRM_DELETE') . "</div><br>\n" . $ret;break;case 'add':
$ret = "<div class='paddingMain boldtxt'>" . $this->getInterfaceText('ADD') . "</div><br>\n" . $ret;break;default: $ret = "<div class='paddingMain boldtxt'>" . $this->getInterfaceText('EDIT') . "</div><br>\n" . $ret;}
return $ret;}
}
?>