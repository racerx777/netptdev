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
require_once($APP['path']['core'] . 'text/Bs_TextUtil.class.php');class Bs_TextUtil_PhpUnit extends Bs_TestCase {var $_Bs_TextUtil;function Bs_TextUtil_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_TextUtil = &$GLOBALS['Bs_TextUtil'];}
function __Bs_TextUtil_parseSearchQuery() {$expected = array(
array('phrase'=>'foobar', 'words'=>array('foobar'), 'operator'=>'|', 'fuzzy'=>FALSE)
);$actual = $this->_Bs_TextUtil->parseSearchQuery($qry='foobar');$this->assertEquals($expected, $actual, 'test 1.1, query was: ' . $qry);$expected = array(
array('phrase'=>'foobar', 'words'=>array('foobar'), 'operator'=>'|', 'fuzzy'=>FALSE), 
array('phrase'=>'hello',  'words'=>array('hello'),  'operator'=>'&', 'fuzzy'=>FALSE), 
);$actual = $this->_Bs_TextUtil->parseSearchQuery($qry='foobar and hello');$this->assertEquals($expected, $actual, 'test 1.2, query was: ' . $qry);$expected = array(
array('phrase'=>'foobar', 'words'=>array('foobar'), 'operator'=>'&', 'fuzzy'=>FALSE), 
array('phrase'=>'hello',  'words'=>array('hello'),  'operator'=>'!', 'fuzzy'=>FALSE), 
);$actual = $this->_Bs_TextUtil->parseSearchQuery($qry='+foobar not hello');$this->assertEquals($expected, $actual, 'test 1.3, query was: ' . $qry);$expected = array(
array('phrase'=>'foobar', 'words'=>array('foobar'), 'operator'=>'&', 'fuzzy'=>FALSE), 
array('phrase'=>'hello',  'words'=>array('hello'),  'operator'=>'!', 'fuzzy'=>FALSE), 
array('phrase'=>'world',  'words'=>array('world'),  'operator'=>'|', 'fuzzy'=>FALSE), 
array('phrase'=>'blah',   'words'=>array('blah'),   'operator'=>'|', 'fuzzy'=>FALSE), 
);$actual = $this->_Bs_TextUtil->parseSearchQuery($qry='+foobar not hello world blah');$this->assertEquals($expected, $actual, 'test 1.4, query was: ' . $qry);$expected = array(
array('phrase'=>'foobar', 'words'=>array('foobar'), 'operator'=>'&', 'fuzzy'=>FALSE), 
array('phrase'=>'hello world', 'words'=>array('hello', 'world'), 'operator'=>'!', 'fuzzy'=>FALSE), 
array('phrase'=>'blah', 'words'=>array('blah'), 'operator'=>'|', 'fuzzy'=>FALSE), 
);$actual = $this->_Bs_TextUtil->parseSearchQuery($qry='+foobar not "hello world" blah');$this->assertEquals($expected, $actual, 'test 1.5, query was: ' . $qry);$expected = array(
array('phrase'=>'foobar', 'words'=>array('foobar'), 'operator'=>'&', 'fuzzy'=>TRUE), 
array('phrase'=>'hello world', 'words'=>array('hello', 'world'), 'operator'=>'!', 'fuzzy'=>TRUE), 
array('phrase'=>'blah', 'words'=>array('blah'), 'operator'=>'|', 'fuzzy'=>TRUE), 
);$actual = $this->_Bs_TextUtil->parseSearchQuery($qry='+~foobar not ~"hello world" ~blah');$this->assertEquals($expected, $actual, 'test 1.5, query was: ' . $qry);}
function __Bs_TextUtil_percentUppercase() {$expected = 50;$actual = $this->_Bs_TextUtil->percentUppercase($param='HELLOworld');$this->assertEquals($expected, $actual, 'test 1.1, param: ' . $param);$expected = 0;$actual = $this->_Bs_TextUtil->percentUppercase($param='hello world');$this->assertEquals($expected, $actual, 'test 1.1, param: ' . $param);$expected = 100;$actual = $this->_Bs_TextUtil->percentUppercase($param='HELLO');$this->assertEquals($expected, $actual, 'test 1.1, param: ' . $param);$expected = 20;$actual = $this->_Bs_TextUtil->percentUppercase($param='This Is Some Text...');$this->assertEquals($expected, $actual, 'test 1.1, param: ' . $param);}
}
?>