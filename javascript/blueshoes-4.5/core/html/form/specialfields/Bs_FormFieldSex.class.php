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
define('BS_FORMFIELDSEX_VERSION',      '4.5.$Revision: 1.5 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'html/form/Bs_FormFieldRadio.class.php');class Bs_FormFieldSex extends Bs_FormFieldRadio {var $firstnameField;var $useOptionUnknown = FALSE;function Bs_FormFieldSex() {$this->Bs_FormFieldRadio(); $this->fieldType = 'radio'; $this->caption = array(
'en'=>'Gender', 
'de'=>'Geschlecht', 
'fr'=>'Sexe', 
'it'=>'Sesso', 
);if ($this->useOptionUnknown) {$this->optionsHard = array('en'=>array('1'=>'Mr.',      '2'=>'Mrs.',    '3'=>'Unknown'), 
'de'=>array('1'=>'Herr',     '2'=>'Frau',    '3'=>'Unbekannt'), 
'fr'=>array('1'=>'Monsieur', '2'=>'Madame',  '3'=>'Inconnu'), 
'it'=>array('1'=>'Signore',  '2'=>'Signora', '3'=>'Sconosciuto'), 
);$this->bsDataInfo   = '1|2|3';} else {$this->optionsHard = array('en'=>array('1'=>'Mr.',      '2'=>'Mrs.'), 
'de'=>array('1'=>'Herr',     '2'=>'Frau'), 
'fr'=>array('1'=>'Monsieur', '2'=>'Madame'), 
'it'=>array('1'=>'Signore',  '2'=>'Signora'), 
);$this->bsDataInfo   = '1|2';}
$this->bsDataType   = 'number';$this->align        = 'h';$this->persisterVarSettings['firstnameField']     = array('mode'=>'stream');$this->persister->setVarSettings(&$this->persisterVarSettings);}
function getRadios($fieldName, $elementList=null) {$this->_form->seedClearingHouse(); if (isSet($this->firstnameField)) {if (method_exists($this->_form->clearingHouse[$this->firstnameField], 'getOnoClientName')) {$onoClientName = $this->_form->clearingHouse[$this->firstnameField]->getOnoClientName();if (TRUE) {$this->events = $this->_Bs_Array->hashKeysToLower($this->events);$t  = "try {" . $onoClientName . ".queryGender(this); } catch (e) {} \n";if (isSet($this->events['onchange'])) {$this->events['onchange'] .= ' ' . $t;} else {$this->events['onchange'] = $t;}
}
}
}
return parent::getRadios($fieldName, $elementList);}
}
?>