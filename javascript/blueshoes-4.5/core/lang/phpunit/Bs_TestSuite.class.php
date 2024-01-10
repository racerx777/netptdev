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
define('BS_TESTSUITE_VERSION',      '4.5.$Revision: 1.2 $');class Bs_TestSuite extends Bs_Object {var $_fTests = array();function Bs_TestSuite($classname=false) {parent::Bs_Object(); if ($classname) {$names = get_class_methods($classname);while (list($key, $method) = each($names)) {if (preg_match('/^__/', $method)) {$this->addTest(new $classname($method));}
}
}
}
function addTest($test) {$this->_fTests[] = $test;}
function run(&$testResult) {reset($this->_fTests);while (list($dev0, $test) = each($this->_fTests)) {if ($testResult->shouldStop()) break;$test->run(&$testResult);}
}
function countTestCases() {$count = 0;reset($_fTests);while (list($na, $test_case) = each($this->_fTests)) {$count += $test_case->countTestCases();}
return $count;}
}
?>