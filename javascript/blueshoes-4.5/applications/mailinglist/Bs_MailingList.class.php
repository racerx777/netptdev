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
class Bs_MailingList extends Bs_Object {var $objP;var $_users;function Bs_MailingList() {parent::Bs_Object(); }
function loadAllUsers() {if ($t = &$this->_objP->loadAll('Bs_Ml_User')) {while (list($k) = each($t)) {$this->_users[$k] = &$t[$k];}
return TRUE;} else {Bs_Error::setError('See privious error.', 'WARNING');return FALSE;}
}
function loadUserByEmail($email) {$dbAgent = &$this->_objP->getDbAgent();$where = "WHERE email LIKE '" . $dbAgent->escapeString($email) . "' LIMIT 1";if ($t = &$this->_objP->loadByWhere('Bs_Ml_User', $where)) {$k = key($t);$this->_users[$k] = &$t[$k];return $k;} else {Bs_Error::setError('See privious error.', 'WARNING');return FALSE;}
}
function &getUser($email) {if (!$key = $this->loadUserByEmail($email)) return FALSE;return $this->_users[$key];}
function &getAllUsers($load=TRUE) {if ($load) {if (!$this->loadAllUsers()) return FALSE;}
return $this->_users;}
function setUser(&$userObj) {}
function storeUser(&$user) {$status = $this->_objP->store($user);if (!$status) Bs_Error::setError('See privious error.', 'WARNING');return $status;}
function initObjPersister($objP, $obj=NULL) {$this->_objP = &$objP;return TRUE;}
}
?>