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
define('BS_TESTRESULT_VERSION',      '4.5.$Revision: 1.2 $');class Bs_TestResult extends Bs_Object {var $_fFailures = array();var $_fRunTests = 0;var $_fStop = FALSE;function Bs_TestResult() {parent::Bs_Object(); }
function run($test) {$this->_startTest($test);$this->_fRunTests++;$test->runBare();$exceptions = $test->getExceptions();if ($exceptions) $this->_fFailures[] = new Bs_TestFailure(&$test, &$exceptions);$this->_endTest($test);}
function getFailures() {return $this->_fFailures;}
function countFailures() {if (is_array($this->_fFailures)) {return sizeOf($this->_fFailures);} else {return 0;}
}
function countTests() {return $this->_fRunTests;}
function shouldStop() {return $this->_fStop;}
function stop() {$this->_fStop = TRUE;}
function _startTest($test) {}
function _endTest($test) {}
}
?>