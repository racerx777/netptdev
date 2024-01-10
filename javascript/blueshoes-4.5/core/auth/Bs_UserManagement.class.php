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
define("BS_USERMANAGEMENT_VERSION",      '4.5.$Revision: 1.3 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($GLOBALS['APP']['path']['core'] . 'auth/Bs_User.class.php');class Bs_UserManagement extends Bs_Object {var $objP;var $_formRecoverPasswordByUsername;var $_formSignup;var $appName;var $_users;var $_groups;var $_permissions;var $_items;var $_relations;var $formRecoverPasswordByUsername;function Bs_UserManagement() {}
function loadUsersAndGroups() {$sql = "";}
function loadAllUsers() {if ($t = &$this->_objP->loadAll('Bs_User')) {while (list($k) = each($t)) {$this->_users[$t[$k]->user] = &$t[$k];}
return TRUE;} else {Bs_Error::setError('See privious error.', 'WARNING');return FALSE;}
}
function loadUserByUsername($username) {$dbObject = &$this->_objP->getDbObject();$where = "WHERE user LIKE '" . $dbObject->escapeString($username) . "' LIMIT 1";if ($t = &$this->_objP->loadByWhere('Bs_User', $where)) {$k = key($t);$this->_users[$k] = &$t[$k];return TRUE;} else {Bs_Error::setError('See privious error.', 'WARNING');return FALSE;}
}
function &getUser($username) {if (!isSet($this->_users[$username])) {if (!$this->loadUserByUsername($username)) return FALSE;}
return $this->_users[$username];}
function &getAllUsers($load=TRUE) {if ($load) {if (!$this->loadAllUsers()) return FALSE;}
return $this->_users;}
function setUser(&$userObj) {if (empty($userObj->user)) return FALSE;$this->_users[$userObj->user] = &$userObj;}
function storeUser($user) {if (is_string($user)) {if (!isSet($this->_users[$user])) {Bs_Error::setError('no such user available in this class: ' . $user, 'WARNING', __LINE__, 'storeUser', __FILE__);return FALSE;}
$user = &$this->_users[$user];}
$status = $this->_objP->store($user);Bs_Error::setError('See privious error.', 'WARNING');return $status;}
function loadGroup($withUsers=TRUE) {}
function loadPermissions() {}
function loadItems() {}
function loadRelations() {}
function _loadRecoverPasswordByUsernameForm($doItAnyway=FALSE) {if (!$doItAnyway && isSet($this->formRecoverPasswordByUsername)) return;$form =& new Bs_Form();$form->internalName = "recoverPasswordByUsername";$form->name         = "recoverPasswordByUsername";$form->mode         = "add";$form->language     = $this->language;$FormField =& new Bs_FormFieldText();$FormField->name          = 'username';$FormField->caption       = array('en'=>'Username', 'de'=>'Benutzername', 'fr'=>"Nom d'utilisateur", 'it'=>'Nome utente');$FormField->editability   = 'always';$FormField->minLength     = 1;$FormField->maxLength     = 30;$FormField->orderId       = 1000;$FormField->bsDataType    = 'text';$FormField->bsDataInfo    = 1;$FormField->must          = TRUE;$FormField->trim          = 'none';$form->elementContainer->addElement($FormField);unset($FormField);$FormField =& new Bs_FormFieldSubmit();$FormField->name         = "submit";$FormField->editability  = 'always';$FormField->caption      = 'Request password';$form->elementContainer->addElement($FormField);unset($FormField);$this->formRecoverPasswordByUsername = &$form;}
function initObjPersister($objP) {$this->_objP = &$objP;$persistOptions = array (
'checkHintSyntax' => TRUE, 
'checkHintVars'   => FALSE, 
'tableName'       => '', 
'createTable'     => TRUE, 
'crypter'         => NULL, 
'cryptKey'        => 'users are losers',
);if (!$this->_objP->register('Bs_User', $persistOptions)) {Bs_Error::setError('See privious error.', 'WARNING');return FALSE;}
return TRUE;}
}
?>