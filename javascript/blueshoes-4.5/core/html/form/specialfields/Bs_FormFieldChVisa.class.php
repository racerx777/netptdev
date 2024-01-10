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
define('BS_FORMFIELDCHVISA_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');class Bs_FormFieldChVisa extends Bs_FormFieldSelect {var $staticVisaList;function Bs_FormFieldChVisa() {$this->Bs_FormFieldSelect(); $this->fieldType = 'select'; $this->persister->setVarSettings(&$this->persisterVarSettings);}
function _prepareOptionsData() {if (!isSet($this->staticVisaList)) $this->loadVisaList();$t = array('en'=>array(''=>''), 
'de'=>array(''=>''), 
'fr'=>array(''=>''), 
'it'=>array(''=>'')
);$this->_options = array_merge_recursive($t, $this->staticVisaList);return parent::_prepareOptionsData();}
function loadVisaList($from='hard', $useCache=TRUE, $autoFallback=TRUE) {$this->_loadVisaListHard();return TRUE;}
function _loadVisaListHard() {if (!isSet($c)) {static $c;$c['en']['A'] = "A = Saisonnier";$c['en']['B'] = "B = Jahresaufenthalter";$c['en']['C'] = "C = Niederlassung / Diplomatenstatus";$c['en']['F'] = "F = vorläufig aufgenommener Ausländer";$c['en']['G'] = "G = Grenzgänger";$c['en']['L'] = "L = Kurzaufenthalter";$c['en']['N'] = "N = Asylbewerber";$c['en']['S'] = "S = Schutzbedürftige";$c['de']['A'] = "A = Saisonnier";$c['de']['B'] = "B = Jahresaufenthalter";$c['de']['C'] = "C = Niederlassung / Diplomatenstatus";$c['de']['F'] = "F = vorläufig aufgenommener Ausländer";$c['de']['G'] = "G = Grenzgänger";$c['de']['L'] = "L = Kurzaufenthalter";$c['de']['N'] = "N = Asylbewerber";$c['de']['S'] = "S = Schutzbedürftige";$c['fr']['A'] = "A = Saisonnier";$c['fr']['B'] = "B = Autorisation à année";$c['fr']['C'] = "C = autorisation d'établissement / statut diplomatique";$c['fr']['F'] = "F = Etrangers admis provisoirement";$c['fr']['G'] = "G = Frontaliers";$c['fr']['L'] = "L = séjours de courte durée";$c['fr']['N'] = "N = requérant d'asile";$c['fr']['S'] = "S = Personnes à protéger";$c['it']['A'] = "A = Stagionale";$c['it']['B'] = "B = Permesso annuale";$c['it']['C'] = "C = Permesso di domicilio/ stato diplomatico";$c['it']['F'] = "F = Straniero ammesso provisoriamente";$c['it']['G'] = "G = Frontaliere";$c['it']['L'] = "L = Residente per breve tempo";$c['it']['N'] = "N = richiedento asilo";$c['it']['S'] = "S = Persone bisognose di protezione";$this->staticVisaList = &$c;}
}
}
?>