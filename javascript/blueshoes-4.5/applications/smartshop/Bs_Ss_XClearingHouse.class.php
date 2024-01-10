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
define('BS_SS_XCLEARINGHOUSE_VERSION',      '4.5.$Revision: 1.2 $');require_once($_SERVER['DOCUMENT_ROOT']    . '../global.conf.php');require_once($APP['path']['applications'] . 'smartshop/Bs_Ss_XCategory.class.php');require_once($APP['path']['applications'] . 'smartshop/Bs_Ss_XProduct.class.php');class Bs_Ss_XClearingHouse extends Bs_Object {var $_shop;var $_xCategories;var $_xProducts;function Bs_Ss_XClearingHouse(&$shop) {parent::Bs_Object(); $this->_shop = &$shop;}
function &getCategory($UID) {if (!isSet($this->_xCategories[$UID])) {$xCat =& new Bs_Ss_XCategory($this->_shop);$status = $xCat->init($UID);if (!$status) {return FALSE;}
$this->_xCategories[$UID] = &$xCat;}
return $this->_xCategories[$UID];}
function &getProduct($UID) {if (!isSet($this->_xProducts[$UID])) {$xProd =& new Bs_Ss_XProduct($this->_shop);$status = $xProd->init($UID);if (!$status) {return FALSE;}
$this->_xProducts[$UID] = &$xProd;}
return $this->_xProducts[$UID];}
}
?>