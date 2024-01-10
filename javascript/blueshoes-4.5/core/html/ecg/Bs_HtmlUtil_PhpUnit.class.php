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
require_once($APP['path']['core'] . 'html/Bs_HtmlUtil.class.php');class Bs_HtmlUtil_PhpUnit extends Bs_TestCase {var $_Bs_HtmlUtil;function Bs_HtmlUtil_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_HtmlUtil        = &$GLOBALS['Bs_HtmlUtil'];}
function __Bs_HtmlUtil_arrayToJsArray() {$data     = array('foo'=>'bar', 'hello'=>'world');$expected = "var x = new Array();x['foo'] = \"bar\";x['hello'] = \"world\";";$actual = $this->_Bs_HtmlUtil->arrayToJsArray($data, 'x');$this->assertEquals($expected, $actual, "the data was: " . dump($data, TRUE));$expected = "var x = new Array();x[0] = \"bar\";x[1] = \"world\";";$actual = $this->_Bs_HtmlUtil->arrayToJsArray($data, 'x', TRUE);$this->assertEquals($expected, $actual, "the data was: " . dump($data, TRUE));$data = array('foo'=>'bar', 'a'=>array('foo'=>'bar', 'hello'=>'world'), 'c'=>'d');$expected = "var x = new Array();x['foo'] = \"bar\";x['a'] = new Array();x['a']['foo'] = \"bar\";x['a']['hello'] = \"world\";x['c'] = \"d\";";$actual = $this->_Bs_HtmlUtil->arrayToJsArray($data, 'x');$this->assertEquals($expected, $actual, "the data was: " . dump($data, TRUE));$expected = "var x = new Array();x[0] = \"bar\";x[1] = new Array();x[1][0] = \"bar\";x[1][1] = \"world\";x[2] = \"d\";";$actual = $this->_Bs_HtmlUtil->arrayToJsArray($data, 'x', TRUE);$this->assertEquals($expected, $actual, "the data was: " . dump($data, TRUE));}
function __Bs_HtmlUtil_arrayToHiddenFormFields() {$data     = array('foo'=>'bar', 'hello'=>'world');$expected = '<input type="hidden" name="foo" value="bar">
<input type="hidden" name="hello" value="world">
';$actual = $this->_Bs_HtmlUtil->arrayToHiddenFormFields($data);$this->assertEquals($expected, $actual, "the data was: " . dump($data, TRUE));$data     = array('foo'=>array('foo'=>'bar', 'hello'=>'world'));$expected = '<input type="hidden" name="foo[foo]" value="bar">
<input type="hidden" name="foo[hello]" value="world">
';$actual = $this->_Bs_HtmlUtil->arrayToHiddenFormFields($data);$this->assertEquals($expected, $actual, "the data was: " . dump($data, TRUE));$data     = array('foo'=>"sam's pizzaland is \"cool\"", 'bar'=>'foo > bar');$expected = '<input type="hidden" name="foo" value="sam\'s pizzaland is &quot;cool&quot;">
<input type="hidden" name="bar" value="foo &gt; bar">
';$actual = $this->_Bs_HtmlUtil->arrayToHiddenFormFields($data);$this->assertEquals($expected, $actual, "the data was: " . dump($data, TRUE));$data     = array('foo'=>array());$expected = '';$actual = $this->_Bs_HtmlUtil->arrayToHiddenFormFields($data);$this->assertEquals($expected, $actual, "the data was: " . dump($data, TRUE));}
function __Bs_HtmlUtil_arrayToFormFieldNames() {$data     = array('foo'=>'bar', 'hello'=>'world');$expected = array('foo', 'hello');$actual   = $this->_Bs_HtmlUtil->arrayToFormFieldNames($data);$this->assertEquals($expected, $actual, "the data was: " . dump($data, TRUE));$data     = array('foo'=>array('foo'=>'bar', 'hello'=>'world'));$expected = array('foo[foo]', 'foo[hello]');$actual   = $this->_Bs_HtmlUtil->arrayToFormFieldNames($data);$this->assertEquals($expected, $actual, "the data was: " . dump($data, TRUE));}
}
?>