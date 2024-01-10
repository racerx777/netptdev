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
require_once($APP['path']['core'] . 'util/Bs_IniHandler.class.php');class Bs_IniHandler_PhpUnit extends Bs_TestCase {var $_Bs_IniHandler;function Bs_IniHandler_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_IniHandler =& new Bs_IniHandler;}
function __Bs_IniHandler_test1() {$string = <<< EOD
a=b
c=d
EOD;
$this->_Bs_IniHandler->reset();$this->_Bs_IniHandler->unQuote = BS_INIHANDLER_UNQUOTE_ALL;$this->_Bs_IniHandler->loadString($string);$actual   = $this->_Bs_IniHandler->get($section='', $key='a');$expected = 'b';$this->assertEquals($expected, $actual, "test 1");}
function __Bs_IniHandler_test2() {$string = <<< EOD
 #comment
 a = b 
  c   =  d  
EOD;
$this->_Bs_IniHandler->reset();$this->_Bs_IniHandler->unQuote = BS_INIHANDLER_UNQUOTE_ALL;$this->_Bs_IniHandler->loadString($string);$actual   = $this->_Bs_IniHandler->get($section='', $key='c');$expected = 'd';$this->assertEquals($expected, $actual, "test 2");}
function __Bs_IniHandler_test3() {$string = <<< EOD
a="b"
c='d'
EOD;
$this->_Bs_IniHandler->reset();$this->_Bs_IniHandler->unQuote = BS_INIHANDLER_UNQUOTE_NONE;$this->_Bs_IniHandler->loadString($string);$actual   = $this->_Bs_IniHandler->get($section='', $key='a');$expected = '"b"';$this->assertEquals($expected, $actual, "test 3");}
function __Bs_IniHandler_test4() {$string = <<< EOD
a="b"
c='d'
EOD;
$this->_Bs_IniHandler->reset();$this->_Bs_IniHandler->unQuote = BS_INIHANDLER_UNQUOTE_ALL;$this->_Bs_IniHandler->loadString($string);$actual   = $this->_Bs_IniHandler->get($section='', $key='a');$expected = 'b';$this->assertEquals($expected, $actual, "test 4");}
function __Bs_IniHandler_test5() {$string = <<< EOD
a="b"
c='d'
EOD;
$this->_Bs_IniHandler->reset();$this->_Bs_IniHandler->unQuote = BS_INIHANDLER_UNQUOTE_ALL;$this->_Bs_IniHandler->loadString($string);$actual   = $this->_Bs_IniHandler->get($section='', $key='c');$expected = 'd';$this->assertEquals($expected, $actual, "test 5");}
function __Bs_IniHandler_test6() {$string = <<< EOD
[blah]
a="b"
c='d'
EOD;
$this->_Bs_IniHandler->reset();$this->_Bs_IniHandler->unQuote = BS_INIHANDLER_UNQUOTE_NONE;$this->_Bs_IniHandler->loadString($string);$actual   = $this->_Bs_IniHandler->has($section='blah', $key='a');$expected = TRUE;$this->assertEquals($expected, $actual, "test 6");}
function __Bs_IniHandler_test7() {$header = BS_INIHANDLER_HEAD_COMMENT;$string = <<< EOD
   $header
   # Comment 1  
   # comment 2  
   []
   globalData = foo 
   
   # comment A
   [Some test data]
      # comment B
      one = hallo
      two = "hallo"
      # comment C
      food = "Tom's Pizza = 'good stuff'"
      more food = Sam's Pizza's = 'best stuff'
      empty = ""
      noVal =
   # comment D
   [more test data]
      one = hi
      two = 'hi'
      food = 'Pizza = "good"'
      empty = ''
      noVal
   # comment E
EOD;
$this->_Bs_IniHandler =& new Bs_IniHandler;$this->_Bs_IniHandler->loadString($string);$array = $this->_Bs_IniHandler->get();$actual   = $this->_Bs_IniHandler->get($section='', $key='globalData');$expected = 'foo';$this->assertEquals($expected, $actual, "test 7.1");$actual   = $this->_Bs_IniHandler->get($section='Some test data', $key='two');$expected = 'hallo';$this->assertEquals($expected, $actual, "test 7.2");$actual   = $this->_Bs_IniHandler->get($section='Some test data', $key='food');$expected = 'Tom\'s Pizza = \'good stuff\'';$this->assertEquals($expected, $actual, "test 7.3");$actual   = $this->_Bs_IniHandler->get($section='Some test data', $key='more food');$expected = 'Sam\'s Pizza\'s = \'best stuff\'';$this->assertEquals($expected, $actual, "test 7.4");$actual   = $this->_Bs_IniHandler->get($section='Some test data', $key='noVal');$expected = '';$this->assertEquals($expected, $actual, "test 7.5");$actual   = $this->_Bs_IniHandler->get($section='more test data', $key='food');$expected = 'Pizza = "good"';$this->assertEquals($expected, $actual, "test 7.6");$actual   = $this->_Bs_IniHandler->has($section='more test data', $key='noVal');$expected = FALSE;$this->assertEquals($expected, $actual, "test 7.7");}
}
?>