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
require_once($APP['path']['core'] . 'util/Bs_String.class.php');require_once($APP['path']['core'] . 'date/Bs_Date.class.php');class Bs_Date_PhpUnit extends Bs_TestCase {var $_Bs_Date;function Bs_Date_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_Date = &$GLOBALS['Bs_Date'];}
function __Bs_Date_now() {$regexp = '20[0|1][0-9]/[0|1][0-9]/[0-3][0-9] [0-2][0-9]:[0-6][0-9]:[0-6][0-9]';$actual = $this->_Bs_Date->now();$this->assertRegexp($regexp, $actual, '');}
function __Bs_Date_formatUnixTimestamp() {$regexp = '[0-3][0-9]\.[0|1][0-9]\.20[0|1][0-9] [0-2][0-9]:[0-6][0-9]:[0-6][0-9]';$actual = $this->_Bs_Date->formatUnixTimestamp('eu-1');$this->assertRegexp($regexp, $actual, "eu-1 is d.m.Y H:i:s");$regexp = '[0-3][0-9]\.[0|1][0-9]\.20[0|1][0-9] [0-2][0-9]:[0-6][0-9]';$actual = $this->_Bs_Date->formatUnixTimestamp('eu-2');$this->assertRegexp($regexp, $actual, "eu-2 is d.m.Y H:i");$regexp = '[0-3][0-9]\.[0|1][0-9]\.20[0|1][0-9]';$actual = $this->_Bs_Date->formatUnixTimestamp('eu-3');$this->assertRegexp($regexp, $actual, "eu-2 is d.m.Y");$regexp = '[0-2][0-9]:[0-6][0-9]:[0-6][0-9]';$actual = $this->_Bs_Date->formatUnixTimestamp('eu-4');$this->assertRegexp($regexp, $actual, "H:i:s");$regexp = '[0-2][0-9]:[0-6][0-9]';$actual = $this->_Bs_Date->formatUnixTimestamp('eu-5');$this->assertRegexp($regexp, $actual, "eu-1 is H:i");}
function __Bs_Date_euDatetimeToUsDatetime() {$expected = '2001/02/28 20:54:23';$actual   = $this->_Bs_Date->euDatetimeToUsDatetime('28.02.2001 20:54:23');$this->assertEquals($expected, $actual, 'convert from eu to use datetime failed');}
function __Bs_Date_sqlTimestampToUnixTimestamp() {$expected = 982591823;$actual   = $this->_Bs_Date->sqlTimestampToUnixTimestamp('20010219151023');$this->assertEquals($expected, $actual, '');}
function __Bs_Date_timeToUnixTimestamp() {$regexp = '^[0-9]{9,10}$';$actual   = $this->_Bs_Date->timeToUnixTimestamp('15:10:23');$this->assertRegexp($regexp, $actual, '');}
function __Bs_Date_usDateToUnixTimestamp() {$regexp = '^[0-9]{9,10}$';$actual   = $this->_Bs_Date->usDateToUnixTimestamp('2002/10/19');$this->assertRegexp($regexp, $actual, '');}
function __Bs_Date_euDatetimeToUnixTimestamp() {$expected = 982591823;$actual   = $this->_Bs_Date->euDatetimeToUnixTimestamp('19.2.2001 15:10:23');$this->assertEquals($expected, $actual, '');}
function __Bs_Date_euDateToUnixTimestamp() {$regexp = '^[0-9]{9}$';$actual   = $this->_Bs_Date->euDateToUnixTimestamp('19.2.2001');$this->assertRegexp($regexp, $actual, '');}
function __Bs_Date_usDatetimeToEuDatetime() {$actual = $this->_Bs_Date->usDatetimeToEuDatetime();$this->assertInstanceOf('Bs_Exception', $actual, '');}
function __Bs_Date_sqlDatetimeToUsDatetime() {$expected = '2001/02/28 20:54:23';$actual   = $this->_Bs_Date->sqlDatetimeToUsDatetime('2001-02-28 20:54:23');$this->assertEquals($expected, $actual, '');}
}
?>