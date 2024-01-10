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
define("BS_USER_VERSION",      '4.5.$Revision: 1.3 $');if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_User extends Bs_MagicClass {var $formFieldsUsed    = array('user'=>TRUE, 'pass'=>TRUE, 'isActive'=>TRUE, 'startDatetime'=>TRUE, 'endDatetime'=>TRUE);var $storageFieldsUsed = array('user'=>TRUE, 'pass'=>TRUE, 'isActive'=>TRUE, 'startDatetime'=>TRUE, 'endDatetime'=>TRUE);var $user           = '';var $pass           = '';var $isActive       = FALSE;var $startDatetime  = '';var $endDatetime    = '';var $email          = '';function Bs_User($loadPersistHints=TRUE, $loadFormHints=FALSE) {parent::Bs_MagicClass($loadPersistHints, $loadFormHints);}
function loadPersistHints() {$this->persistDebug = array(
'checkHintSyntax' => TRUE, 
'checkClassVars'  => FALSE, 
);$this->persistFieldsUsed = array(
'email'       => TRUE, 
'addDatetime' => TRUE, 
);$this->persistPrimary = array(
'uniqueKey' => array('name'=>'ID', 'type'=>'auto_increment'), 
);$this->persistFields = array(
'user'          => array('name'=>'user',          'metaType'=>'string',  'size'=>'20', 'index'=>TRUE), 
'pass'          => array('name'=>'pass',          'metaType'=>'string',  'size'=>'20', 'index'=>TRUE), 
'isActive'      => array('name'=>'isActive',      'metaType'=>'boolean'), 
'startDatetime' => array('name'=>'startDatetime', 'metaType'=>'string',  'size'=>'10'), 
'endDatetime'   => array('name'=>'endDatetime',   'metaType'=>'string',  'size'=>'10'), 
'email'         => array('name'=>'email',         'metaType'=>'string',  'size'=>'60'), 
);}
function loadFormHints() {$this->formProps = array(
'internalName' => 'bsUserForm', 
'name'         => 'bsUserForm', 
'mode'         => 'add', 
'language'     => 'en', 
'buttons'      => 'default', 
'user'         => 'user', 
);$this->formGroups = array(
'grpAccount' => array(
'caption' => array('en'=>'Account', 'de'=>'Konto'), 
), 
'grpContact' => array(
'caption' => array('en'=>'Contact', 'de'=>'Kontakt'), 
), 
);$accessRightsRestricted = array(
'user'  => array(
'add'   => 'normal', 
'edit'  => 'show', 
), 
'admin' => 'normal', 
);$accessRightsHidden = array(
'user'  => 'omit', 
'admin' => 'normal', 
);$this->formFields = array(
'user' => array(
'group'           => 'grpAccount', 
'fieldType'       => 'Bs_FormFieldText', 
'must'            => TRUE, 
'caption'         => array('en'=>'Username', 'de'=>'Benutzername'), 
'editability'     => 'always', 
'accessRights'    => $accessRightsRestricted, 
'valueDefault'    => 'default', 
'bsDataType'      => 'username', 
'bsDataInfo'      => 2, 
'minLength'       => 6, 
'notEqualTo'      => 'pass', 
'additionalCheck' => '', 
), 
'pass' => array(
'group'           => 'grpAccount', 
'fieldType'       => 'Bs_FormFieldText', 'must'            => TRUE, 
'caption'         => array('en'=>'Password', 'de'=>'Passwort'), 
'editability'     => 'always', 
'valueDefault'    => 'default', 
'bsDataType'      => 'password', 
'bsDataInfo'      => 2, 
'minLength'       => 6, 
), 
'isActive' => array(
'group'           => 'grpAccount', 
'fieldType'       => 'Bs_FormFieldCheckbox', 
'must'            => TRUE, 
'caption'         => array('en'=>'Active', 'de'=>'Aktiv'), 
'editability'     => 'always', 
'accessRights'    => $accessRightsHidden, 
'valueDefault'    => '', 
'bsDataType'      => 'username', 
'bsDataInfo'      => '', 
'minLength'       => '', 
'maxLength'       => '', 
), 
'startDatetime' => array(
'group'           => 'grpAccount', 
'fieldType'       => 'Bs_FormFieldText', 
'must'            => TRUE, 
'caption'         => array('en'=>'Start Date Time', 'de'=>'Start Datum Uhrzeit'), 
'editability'     => 'always', 
'accessRights'    => $accessRightsHidden, 
'valueDefault'    => 'default', 
'bsDataType'      => 'datetime', 
'bsDataInfo'      => 3, 
'minLength'       => 10, 
'maxLength'       => 19, 
), 
'endDatetime' => array(
'group'           => 'grpAccount', 
'fieldType'       => 'Bs_FormFieldText', 
'must'            => TRUE, 
'caption'         => array('en'=>'End Date Time', 'de'=>'Ende Datum Uhrzeit'), 
'editability'     => 'always', 
'accessRights'    => $accessRightsHidden, 
'valueDefault'    => 'default', 
'bsDataType'      => 'datetime', 
'bsDataInfo'      => 3, 
'minLength'       => 10, 
'maxLength'       => 19, 
), 
'email' => array(
'group'           => 'grpContact', 
'fieldType'       => 'Bs_FormFieldText', 
'must'            => TRUE, 
'caption'         => array('en'=>'Email', 'de'=>'E-Mail'), 
'editability'     => 'always', 
'valueDefault'    => 'default', 
'bsDataType'      => 'email', 
'bsDataInfo'      => 1, 
), 
);}
function validateLogin($user, $pass) {}
}
?>