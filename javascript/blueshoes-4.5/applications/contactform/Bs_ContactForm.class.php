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
require_once($_SERVER["DOCUMENT_ROOT"]       . '../global.conf.php');require_once($GLOBALS['APP']['path']['core'] . 'Bs_MagicClass.class.php');require_once($GLOBALS['APP']['path']['core'] . 'util/Bs_Array.class.php');require_once($GLOBALS['APP']['path']['core'] . 'html/form/Bs_FormItAble.class.php');require_once($GLOBALS['APP']['path']['core'] . 'storage/objectpersister/Bs_SimpleObjPersister.class.php');class Bs_ContactForm extends Bs_MagicClass {var $Bs_Array;var $_objP;var $_dbTableName;var $_formObj;var $_language;var $gender;var $firstname;var $lastname;var $email;var $comments;var $addDatetime;function Bs_ContactForm($loadFormHints=TRUE) {parent::Bs_MagicClass(FALSE, $loadFormHints);$this->Bs_Array = &$GLOBALS['Bs_Array'];}
function setLanguage($language) {$this->_language = $language;if (isSet($this->formProps)) {$this->formProps['language'] = $language;}
}
function setSaveByDsn($tableName, $dsn) {if (isEx($dbAgent = &getDbObject($dsn))) {return FALSE;}
$this->_objP =& new Bs_SimpleObjPersister();$this->_objP->setDbObject($dbAgent);$this->_dbTableName = $tableName;$this->_loadPersistHints();return TRUE;}
function setSaveByDb($tableName, &$bsDb) {$this->_objP =& new Bs_SimpleObjPersister();$this->_objP->setDbAgent($bsDb);$this->_dbTableName = $tableName;$this->_loadPersistHints();return TRUE;}
function setSaveByPersister($tableName, &$objP) {$this->_objP = &$objP;$this->_dbTableName = $tableName;$this->_loadPersistHints();return TRUE;}
function doItYourself() {$fia =& new Bs_FormItAble();$status = &$fia->doItYourself($this, TRUE);if (is_array($status)) {$this->_formObj = &$status['formObj'];return $status;} else {$this->_formObj = &$status;return TRUE;}
}
function &getFormObj() {if (isSet($this->_formObj)) return $this->_formObj;return FALSE;}
function store() {if (!isSet($this->_objP)) return FALSE;if (!$status = $this->_objP->store($this)) Bs_Error::setError('See privious error.', 'WARNING');return $status;}
function emailInternal($email) {$dataStr = $this->getSubmittedDataAsEmailString();if ($dataStr === FALSE) return FALSE; $infoHash = $this->_formObj->getInfo();$infoHash['dateTime'] = gmdate('Y-m-d H:i:s', $infoHash['startTimestamp']);$infoHash['usedTime'] .= ' seconds';$infoHash['ip']       = $_SERVER['REMOTE_ADDR'];$infoHash['host']     = $_SERVER['REMOTE_HOST'];$infoHash['browser']  = $_SERVER['HTTP_USER_AGENT'];$infoStr  = $this->Bs_Array->arrayToText($infoHash);$msg = $dataStr . "\n\n" . $infoStr;$status = @mail($email, 'Contact Form', $msg);return (bool)$status;}
function emailExternal($email) {$dataStr = $this->getSubmittedDataAsEmailString();if ($dataStr === FALSE) return FALSE; $status = @mail($email, 'Contact Form', $dataStr);return (bool)$status;}
function getSubmittedDataAsEmailString() {if (!isSet($this->_formObj)) return FALSE;$values = $this->_formObj->getValuesArray(TRUE, 'valueDisplay', TRUE, null, TRUE);$dataStr = $this->Bs_Array->arrayToText($values, 75, ': ', TRUE);return $dataStr;}
function _loadPersistHints() {$this->persistTable = array(
'name' => $this->_dbTableName, 
);$this->persistDebug = array(
'checkHintSyntax' => TRUE, 
'checkClassVars'  => FALSE, 
);$this->persistFieldsUsed = array(
'gender'      => TRUE, 
'firstname'   => TRUE, 
'lastname'    => TRUE, 
'email'       => TRUE, 
'comments'    => TRUE, 
'addDatetime' => TRUE, 
);$this->persistPrimary = array(
'uniqueKey' => array('name'=>'ID', 'type'=>'auto_increment'), 
);$this->persistFields = array(
'gender'        => array('name'=>'gender',        'metaType'=>'integer', 'size'=>'20'), 
'firstname'     => array('name'=>'firstname',     'metaType'=>'string',  'size'=>'20'), 
'lastname'      => array('name'=>'lastname',      'metaType'=>'string',  'size'=>'20'), 
'email'         => array('name'=>'email',         'metaType'=>'string',  'size'=>'60'), 
'comments'      => array('name'=>'comments',      'metaType'=>'blob',    'size'=>'60'), 
'addDatetime'   => array('name'=>'addDatetime',   'metaType'=>'string',  'size'=>'19'), 
);}
function loadFormHints() {$this->formFieldsUsed = array(
'gender'      => TRUE, 
'firstname'   => TRUE, 
'lastname'    => TRUE, 
'email'       => TRUE, 
'comments'    => TRUE, 
'addDatetime' => TRUE, 
);$this->formGroups = array(
'grpMessage' => array(
'caption' => array('en'=>'Message', 'de'=>'Mitteilung'), 
), 
);$accessRightsRestricted = array(
'user'  => 'omit', 
'admin'  => array(
'add'   => 'omit', 
'edit'  => 'show', 
), 
);$this->formFields = array(
'gender' => array(
'group'           => 'grpMessage', 
'fieldType'       => 'Bs_FormFieldSex', 
'must'            => FALSE, 
'editability'     => 'always', 
'firstnameField'  => 'firstname', 
), 
'firstname' => array(
'group'           => 'grpMessage', 
'fieldType'       => 'Bs_FormFieldFirstname', 
'must'            => FALSE, 
'editability'     => 'always', 
'sexField'        => 'gender', 
'lastnameField'   => 'lastname', 
), 
'lastname' => array(
'group'           => 'grpMessage', 
'fieldType'       => 'Bs_FormFieldLastname', 
'must'            => FALSE, 
'editability'     => 'always', 
'firstnameField'  => 'firstname', 
), 
'email' => array(
'group'           => 'grpMessage', 
'fieldType'       => 'Bs_FormFieldText', 
'must'            => TRUE, 
'caption'         => array('en'=>'Email', 'de'=>'E-Mail'), 
'editability'     => 'always', 
'valueDefault'    => '', 
'bsDataType'      => 'email', 
'bsDataInfo'      => 1, 
), 
'comments' => array(
'group'           => 'grpMessage', 
'fieldType'       => 'Bs_FormFieldTextarea', 
'must'            => TRUE, 
'minLength'       => 10, 
'caption'         => array('en'=>'Message', 'de'=>'Mitteilung'), 
'editability'     => 'always', 
'valueDefault'    => '', 
), 
'addDatetime' => array(
'group'           => 'grpMessage', 
'fieldType'       => 'Bs_FormFieldText', 
'must'            => TRUE, 
'caption'         => array('en'=>'Add Date Time', 'de'=>'Hinzugefügt Datum Uhrzeit'), 
'editability'     => 'never', 
'accessRights'    => $accessRightsRestricted, 
'valueDefault'    => '', 
'bsDataType'      => 'datetime', 
'bsDataInfo'      => 3, 
'minLength'       => 10, 
'maxLength'       => 19, 
), 
);$this->formProps = array(
'internalName' => 'bsMlForm', 
'name'         => 'bsMlForm', 
'mode'         => 'add', 
'language'     => (isSet($this->_language)) ? $this->_language : 'en', 
'buttons'      => array(
'add'       => array(
'save'      => array('en'=>'Send',      'de'=>'Senden'), 
), 
), 
'user'         => 'user', 
);}
}
?>