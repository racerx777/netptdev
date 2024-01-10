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
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');class Page extends Bs_Object {var $_dbAgent = NULL;var $_sessionData;var $_user;var $_userFooter = '';var $_metaNavigation = '';var $todo;function Page() {$this->todo = bs_requestToDo();}
function setDbAgent(&$dbAgent) {$this->_dbAgent =& $dbAgent;}
function _getMetaNavigation() {$metaNav = array('STEP1' => array('caption'=>'Collect Package'), 
'RUN'   => array('caption'=>'Create Package'), 
);$metaNavHtml = '';foreach ($metaNav as $screen => $hash) {$href = 'href="'.$_SERVER['PHP_SELF'].'?bs_todo[nextScreen]='.$screen.'" target="_top"';$metaNavHtml .=" <a {$href}>{$hash['caption']}</a>";} return $metaNavHtml;}
function setSessionData(&$sessionData) {$className = get_class($this);if (!isSet($sessionData[$className])) $sessionData[$className] = NULL;$this->_localSessionData =& $sessionData[$className];$this->_sessionData =& $sessionData;}
}
?>