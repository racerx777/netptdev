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
require_once($APP['path']['core'] . 'net/email/Bs_EmailUtil.class.php');class Bs_EmailUtil_PhpUnit extends Bs_TestCase {var $_APP;var $_Bs_EmailUtil;function Bs_EmailUtil_PhpUnit($name) {$this->Bs_TestCase($name);$this->_APP               = &$GLOBALS['APP'];$this->_Bs_EmailUtil      =& new Bs_EmailUtil();}
function __Bs_EmailUtil_parse() {$expected = array('nospam', 'blueshoes.org');$actual = $this->_Bs_EmailUtil->parse('nospam@blueshoes.org');$this->assertEquals($expected, $actual, '');}
}
?>