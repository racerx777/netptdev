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
require_once($GLOBALS['APP']['path']['core'] . 'Bs_MagicClass.class.php');class Bs_Ml_User extends Bs_MagicClass {var $email          = '';var $addDatetime    = '';function Bs_Ml_User($loadPersistHints=TRUE, $loadFormHints=FALSE) {parent::Bs_MagicClass($loadPersistHints, $loadFormHints);}
function loadPersistHints() {$this->persistDebug = array(
'checkHintSyntax' => TRUE, 
'checkClassVars'  => FALSE, 
);$this->persistFieldsUsed = array(
'email'       => TRUE, 
'addDatetime' => TRUE, 
);$this->persistPrimary = array(
'uniqueKey' => array('name'=>'ID', 'type'=>'auto_increment'), 
);$this->persistFields = array(
'addDatetime'   => array('name'=>'addDatetime',   'metaType'=>'string',  'size'=>'19'), 
'email'         => array('name'=>'email',         'metaType'=>'string',  'size'=>'60'), 
);}
function loadFormHints() {$this->formFieldsUsed = array('email'=>TRUE, 'addDatetime'=>TRUE);$accessRightsRestricted = array(
'user'  => 'omit', 
'admin'  => array(
'add'   => 'omit', 
'edit'  => 'show', 
), 
);$this->formFields = array(
'email' => array(
'fieldType'       => 'Bs_FormFieldText', 
'must'            => TRUE, 
'caption'         => array('en'=>'Email', 'de'=>'E-Mail'), 
'editability'     => 'always', 
'valueDefault'    => '', 
'bsDataType'      => 'email', 
'bsDataInfo'      => 1, 
), 
'addDatetime' => array(
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
'language'     => 'en', 
'buttons'      => 'default', 
'user'         => 'user', 
);}
}
?>