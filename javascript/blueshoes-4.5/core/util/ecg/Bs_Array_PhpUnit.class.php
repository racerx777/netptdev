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
require_once($APP['path']['core'] . 'util/Bs_Array.class.php');class Bs_Array_PhpUnit extends Bs_TestCase {var $_Bs_Array;function Bs_Array_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_Array =& new Bs_Array;}
function __Bs_Array_explode() {$expected = array('abc');$string   = 'abc';$actual   = $this->_Bs_Array->explode(array(' '), $string);$this->assertEquals($expected, $actual, 'test 1');$expected = array('abc', 'def');$string   = 'abc def';$actual   = $this->_Bs_Array->explode(array(' '), $string);$this->assertEquals($expected, $actual, 'test 2');$expected = array('abc', 'def', 'ghi');$string   = 'abc def-ghi';$actual   = $this->_Bs_Array->explode(array(' ', '-'), $string);$this->assertEquals($expected, $actual, 'test 3');$expected = array('abc', 'def', 'ghi', 'jkl', 'mno');$string   = 'abc def-ghi jkl:mno';$actual   = $this->_Bs_Array->explode(array(' ', '-', ':'), $string);$this->assertEquals($expected, $actual, 'test 4');}
function __Bs_Array_inArray() {$expected = TRUE;$array = array('abc', 'def');$actual   = $this->_Bs_Array->inArray('abc', $array);$this->assertEquals($expected, $actual, 'test 1');$expected = FALSE;$array = array('abc', 'def');$actual   = $this->_Bs_Array->inArray('not-existent', $array);$this->assertEquals($expected, $actual, 'test 2');$expected = TRUE;$array = array('abc', ' deF');$actual   = $this->_Bs_Array->inArray('Def ', $array);$this->assertEquals($expected, $actual, 'test 3');$expected = FALSE;$array = array('abc', ' def');$actual   = $this->_Bs_Array->inArray('DEF ', $array, FALSE);$this->assertEquals($expected, $actual, 'test 4');$expected = FALSE;$array = array('abc', ' def');$actual   = $this->_Bs_Array->inArray('DEF ', $array, TRUE, FALSE);$this->assertEquals($expected, $actual, 'test 5');$expected = TRUE;$array = array('abc', '0');$actual   = $this->_Bs_Array->inArray('0 ', $array);$this->assertEquals($expected, $actual, 'test 6');$expected = TRUE;$array = array('abc', '   '); $actual   = $this->_Bs_Array->inArray(' ', $array); $this->assertEquals($expected, $actual, 'test 7');$expected = TRUE;$array = array('abc', '');$actual   = $this->_Bs_Array->inArray('', $array, FALSE, FALSE);$this->assertEquals($expected, $actual, 'test 8');}
function __Bs_Array_merge() {$expected = array(''=>'', '9'=>'apple', '15'=>'banana', '20'=>'grapefruit');$arrOne = array(''=>'');$arrTwo = array('9'=>'apple', '15'=>'banana', '20'=>'grapefruit');$actual = $this->_Bs_Array->merge($arrOne, $arrTwo);$this->assertEquals($expected, $actual, 'test 1');}
function __Bs_Array_sets() {$A = array('A'=>'a', 'B'=>'b', 'C'=>'c', 'D'=>'d', 'a');$B = array('A'=>'v', 'B'=>'b',           'D'=>'d', 'E'=>'c');$expected = array('B'=>'b', 'D'=>'d');$actual = $this->_Bs_Array->intersect($A, $B);$this->assertEquals($expected, $actual, 'Test Bs_Array::intersect');$expected = array('A'=>'a', 'C'=>'c', 'a');$actual = $this->_Bs_Array->diff($A, $B);$this->assertEquals($expected, $actual, 'Test Bs_Array::diff');$expected = array('A'=>'v', 'C'=>'c', 'a', 'E'=>'c');$actual = $this->_Bs_Array->complement($A, $B);$this->assertEquals($expected, $actual, 'Test Bs_Array::complement');}
}
?>