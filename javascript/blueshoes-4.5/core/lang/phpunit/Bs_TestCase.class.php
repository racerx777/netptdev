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
define('BS_TESTCASE_VERSION',      '4.5.$Revision: 1.2 $');class Bs_TestCase extends Bs_Assert {var $_fName;var $_fResult;var $_fExceptions = array();function Bs_TestCase($name) {$this->Bs_Assert();$this->_fName = $name;}
function run($testResult=FALSE) {if (! $testResult)
$testResult = $this->_createResult();$this->_fResult = $testResult;$testResult->run(&$this);$this->_fResult = FALSE;return $testResult;}
function countTestCases() {return 1; }
function runTest() {$name = $this->_fName;$this->$name();}
function name() {return $this->_fName;}
function setUp() {}
function tearDown() {}
function _createResult() {return new Bs_TestResult;}
function fail($message=0) {$this->_fExceptions[] = new Bs_Exception(&$message);}
function error($message) {printf('<b>ERROR: ' . $message . '</b><br>');$this->_fResult->stop();}
function failed() {return count($this->_fExceptions);}
function getExceptions() {return $this->_fExceptions;}
function runBare() {$this->setup();$this->runTest();$this->tearDown();}
}
?>