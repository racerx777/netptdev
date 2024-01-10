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
define('BS_WSE_WEBSEARCHENGINE_VERSION',      '4.5.$Revision: 1.2 $');require_once($APP['path']['plugins']      . 'indexserver/Bs_Is_IndexServer.class.php');require_once($APP['path']['applications'] . 'websearchengine/Bs_Wse_Profile.class.php');require_once($APP['path']['applications'] . 'websearchengine/Bs_Wse_Walker.class.php');require_once($APP['path']['applications'] . 'websearchengine/Bs_Wse_Searcher.class.php');Class Bs_Wse_WebSearchEngine extends Bs_Object {var $Bs_Is_IndexServer;var $_clhProfile;var $_clhWalker;var $_clhSearcher;function Bs_Wse_WebSearchEngine() {if (isSet($GLOBALS['Bs_Wse_WebSearchEngine'])) return $GLOBALS['Bs_Wse_WebSearchEngine'];$GLOBALS['Bs_Wse_WebSearchEngine'] = &$this;parent::Bs_Object(); $this->Bs_Is_IndexServer =& new Bs_Is_IndexServer();}
function setProfile(&$profile) {$this->_clhProfile[$profile->profileName] = &$profile;}
function &getWalker($profileName) {if (isSet($this->_clhWalker[$profileName])) {return $this->_clhWalker[$profileName];}
if (!isSet($this->_clhProfile[$profileName])) return FALSE;$walker =& new Bs_Wse_Walker($this, $this->_clhProfile[$profileName], $this->_clhProfile[$profileName]->getIndexDbObj());$this->_clhWalker[$profileName] = &$walker;return $walker;}
function &getSearcher($profileName) {if (isSet($this->_clhSearcher[$profileName])) {return $this->_clhSearcher[$profileName];}
if (!isSet($this->_clhProfile[$profileName])) return FALSE;$searcher =& new Bs_Wse_Searcher($this, $this->_clhProfile[$profileName], $this->_clhProfile[$profileName]->getIndexDbObj());$this->_clhSearcher[$profileName] = &$searcher;return $searcher;}
function &getProfile($profileName) {if (isSet($this->_clhProfile[$profileName])) {return $this->_clhProfile[$profileName];}
return FALSE;}
}
?>