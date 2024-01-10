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
define('BS_TESTFAILURE_VERSION',      '4.5.$Revision: 1.2 $');class Bs_TestFailure extends Bs_Object {var $_fFailedTestName;var $_fExceptions;function Bs_TestFailure(&$test, &$exceptions) {parent::Bs_Object(); $this->_fFailedTestName = $test->name();$this->_fExceptions     = $exceptions;}
function getExceptions() {return $this->_fExceptions;}
function getTestName() {return $this->_fFailedTestName;}
}
?>