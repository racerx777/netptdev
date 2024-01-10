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
define('BS_CUGARRAU_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'auth/Bs_Cug.class.php');class Bs_CugArray extends Bs_Cug {var $_userArray;function Bs_CugArray($cugName, $userArray) {parent::Bs_Cug($cugName); $this->_userArray = $userArray;}
function _validateLogin() {$t        = $this->form->getFieldValue('username');$username = $t[0];$t        = $this->form->getFieldValue('password');$password = $t[0];do {if (!is_array($this->_userArray) || empty($this->_userArray)) break;reset($this->_userArray);while (list(,$row) = each($this->_userArray)) {if (strToLower($username) == strToLower($row[$this->userFieldNames['user']])) {$validateData = array();$validateData['sentUser'] = $username;$validateData['sentPass'] = $password;$validateData['user'] = $row[$this->userFieldNames['user']];$validateData['pass'] = $row[$this->userFieldNames['pass']];if (isSet($row[$this->userFieldNames['isActive']]) && isSet($row[$this->userFieldNames['startDatetime']]) && isSet($row[$this->userFieldNames['endDatetime']])) {$validateData['isActive']      = $row[$this->userFieldNames['isActive']];$validateData['startDatetime'] = $row[$this->userFieldNames['startDatetime']];$validateData['endDatetime']   = $row[$this->userFieldNames['endDatetime']];}
$failedReason = $this->_validateLoginData($validateData);if ($failedReason === TRUE) {$this->errorMsg = '';return TRUE;} else {return FALSE;}
}
}
} while (FALSE);$this->errorMsg = 'Username or password wrong. Please try again.';return FALSE;}
}
?>