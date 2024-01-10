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
define('BS_LANGUAGEHANDLER_VERSION',      '4.5.$Revision: 1.3 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'util/Bs_IniHandler.class.php');class Bs_LanguageHandler extends Bs_Object {var $_APP;var $_IniHandler;function Bs_LanguageHandler() {parent::Bs_Object(); $this->_APP = &$GLOBALS['APP'];$this->_IniHandler =& new Bs_IniHandler();}
function &readLanguage($path) {if (!$this->_IniHandler->loadFile($path)) return NULL;return $this->_IniHandler->get();}
function determineLanguage($path, $lang=NULL) {if (is_null($lang)) {if ((is_array($this->_APP['usedLanguages'])) && (sizeOf($this->_APP['usedLanguages']) > 0)) {reset($this->_APP['usedLanguages']);$lang = key($this->_APP['usedLanguages']);} else {$lang = 'en'; }
}
$base = '';switch ($path) {case 'Html/Form/validationErrors':
$base = $this->_APP['path']['core'] . 'Html/Form/lang/validationErrors.';break;case 'Storage/debedooGui':
$base = $this->_APP['path']['core'] . 'Storage/lang/debedooGui.';break;default:
$base = $path . '.';}
$isOk = TRUE;do { if (file_exists($base . $lang . '.txt')) break;$langTried[] = $lang;if (strlen($lang) > 2) {if (file_exists($base . substr($lang, 0, 2) . '.txt')) break;$langTried[] = substr($lang, 0, 2);}
if ((is_array($this->_APP['usedLanguages'])) && (sizeOf($this->_APP['usedLanguages']) > 0)) {reset($this->_APP['usedLanguages']);$lang = key($this->_APP['usedLanguages']);if (!in_array($lang, $langTried)) {if (file_exists($base . $lang . '.txt')) break;$langTried[] = $lang;}
}
$lang = 'en';if (!in_array($lang, $langTried)) {if (file_exists($base . $lang . '.txt')) break;}
$isOk = FALSE;} while (FALSE);if (!$isOk) return NULL;return array($lang, $base . $lang . '.txt');}
}
?>