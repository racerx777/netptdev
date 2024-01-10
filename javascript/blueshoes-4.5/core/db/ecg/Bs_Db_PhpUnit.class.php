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
require_once($APP['path']['core'] . 'db/Bs_Db.class.php');$GLOBALS[strToLower('Bs_Db_PhpUnit')] = 'object';class Bs_Db_PhpUnit extends Bs_TestCase {var $_Bs_Db;function Bs_Db_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_Db = new Bs_Db();}
function setUp() {}
function runTest() {}
function __Bs_MySql_escapeString() {$expected = 'integer';$actual = $this->_Bs_Db->escapeString("insert into table set field = 'sam's pizzaland'");$this->assertEquals($expected, $actual, '__Bs_MySql_escapeString');$expected = 'this is a \\ backslash';$actual = $this->_Bs_Db->escapeString("this is a \ backslash");$this->assertEquals($expected, $actual, '__Bs_MySql_escapeString');}
}
?>