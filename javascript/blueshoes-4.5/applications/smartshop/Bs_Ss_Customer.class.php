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
define('BS_SS_CUSTOMER_VERSION',      '4.5.$Revision: 1.2 $');require_once($_SERVER['DOCUMENT_ROOT']     . '../global.conf.php');require_once($APP['path']['core']          . 'net/http/session/Bs_SessionFile.class.php');require_once($APP['path']['applications']  . 'smartshop/Bs_Ss_Basket.class.php');class Bs_Ss_Customer extends Bs_Object {var $_shop;var $_APP;var $basket;var $bsSession;function Bs_Ss_Customer(&$shop) {parent::Bs_Object(); $this->_APP  = &$GLOBALS['APP'];$this->_shop = &$shop;$this->basket =& new Bs_Ss_Basket($shop);$isOld = $this->checkSession();if ($this->bsSession->isRegistered('basket')) {$this->basket->_products = $this->bsSession->getVar('basket'); }
$this->bsSession->register('basket', &$this->basket->_products);}
function checkSession() {if (isSet($GLOBALS['bsSession'])) {$this->bsSession = &$GLOBALS['bsSession'];$isOld = TRUE;} else {$GLOBALS['bsSession'] =& new Bs_SessionFile($this->_shop->settings['sessionDir'], NULL, 1);$this->bsSession = &$GLOBALS['bsSession'];$isOld = $this->bsSession->init(2); if (!$isOld) {$this->bsSession->start('cookie');} else {}
bs_registerShutdownMethod(__LINE__, __FILE__, $this->bsSession, 'write');}
return $isOld;}
}
?>