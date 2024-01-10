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
require_once($APP['path']['core'] . 'util/Bs_Number.class.php');class Bs_Number_PhpUnit extends Bs_TestCase {var $_Bs_Number;function Bs_Number_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_Number =& new Bs_Number;}
function __Bs_Number_isNumericLarge() {$expected = TRUE;$actual   = $this->_Bs_Number->isNumericLarge('123');$this->assertEquals($expected, $actual, '123');$expected = TRUE;$actual   = $this->_Bs_Number->isNumericLarge(123);$this->assertEquals($expected, $actual, 123);$expected = FALSE;$actual   = $this->_Bs_Number->isNumericLarge('abc');$this->assertEquals($expected, $actual, 'abc');$expected = FALSE;$actual   = $this->_Bs_Number->isNumericLarge('1 23');$this->assertEquals($expected, $actual, '1 23');$expected = FALSE;$actual   = $this->_Bs_Number->isNumericLarge('abc');$this->assertEquals($expected, $actual, 'abc');$expected = TRUE;$actual   = $this->_Bs_Number->isNumericLarge('1234567890123456789212345678931234567894');$this->assertEquals($expected, $actual, '1234567890123456789212345678931234567894');$expected = TRUE;$actual   = $this->_Bs_Number->isNumericLarge('-123.45E293');$this->assertEquals($expected, $actual, '-123.45E293');$expected = TRUE;$actual   = $this->_Bs_Number->isNumericLarge(-123.45E293);$this->assertEquals($expected, $actual, -123.45E293);}
}
?>