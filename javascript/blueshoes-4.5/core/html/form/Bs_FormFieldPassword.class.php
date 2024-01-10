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
define('BS_FORMFIELDPASSWORD_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldTxt.class.php');class Bs_FormFieldPassword extends Bs_FormFieldTxt {function Bs_FormFieldPassword() {$this->Bs_FormFieldTxt(); $this->fieldType  = 'password';$this->bsDataType = 'password';$this->minLength  = 6;$this->maxLength  = 20;$this->caption   = array(
'en'=>'Password', 
'de'=>'Passwort', 
'fr'=>'Mot de passe', 
'it'=>'Password'
);$this->persister->setVarSettings(&$this->persisterVarSettings);}
}
?>