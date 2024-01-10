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
class PhpSyntax_PhpUnit extends Bs_TestCase {var $empty       = '';var $null        = NULL;var $bool        = FALSE;var $notSet;var $array       = array();var $string      = 'hello';var $stringInt   = '15';var $stringZero  = '0';var $stringNeg   = '-1';var $stringTrue  = 'true';var $stringFalse = 'false';var $int         = 15;var $intTrue     = 1;var $intFalse    = 0;var $intNeg      = -1;var $true        = TRUE;var $false       = FALSE;function PhpSyntax_PhpUnit($name) {$this->Bs_TestCase($name);}
function __PhpSyntax_dataTypeEmpty() {$actual = $this->empty;$this->assertEqualsType('string', $actual, "this really shouldn't happen.");}
function __PhpSyntax_empty_empty() {$actual = empty($this->empty);$this->assertEquals(TRUE, $actual, "this really shouldn't happen.");}
function __PhpSyntax_is_null_empty() {$actual = is_null($this->empty);$this->assertEquals(FALSE, $actual, "crap. i liked the behavior as it was.");}
function __PhpSyntax_isSet_empty() {$actual = isSet($this->empty);$this->assertEquals(TRUE, $actual, "this really shouldn't happen.");}
function __PhpSyntax_toBool_empty() {$actual = (bool)$this->empty;$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_dataTypeNull() {$actual = $this->null;$this->assertEqualsType('NULL', $actual, "maybe you got 'unknown type'? then php changed it in a way i appreciate.");}
function __PhpSyntax_empty_null() {$actual = empty($this->null);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_is_null_null() {$actual = is_null($this->null);$this->assertEquals(TRUE, $actual, "this really shouldn't happen.");}
function __PhpSyntax_isSet_null() {$actual = isSet($this->null);$this->assertEquals(FALSE, $actual, "hrm...");}
function __PhpSyntax_toBool_null() {$actual = (bool)($this->null);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_dataTypeNotSet() {$actual = $this->notSet;$this->assertEqualsType('NULL', $actual, "maybe you got 'unknown type'? then php changed it in a way i appreciate.");}
function __PhpSyntax_dataTypeReallyNotSet() {$this->assertEqualsType('NULL', @$reallyNotSet, "maybe you got 'unknown type'? then php changed it in a way i appreciate.");}
function __PhpSyntax_empty_notSet() {$actual = empty($this->notSet);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_is_null_notSet() {$actual = is_null($this->notSet);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_isSet_notSet() {$actual = isSet($this->notSet);$this->assertEquals(FALSE, $actual, "this really shouldn't happen.");}
function __PhpSyntax_toBool_notSet() {$actual = (bool)($this->notSet);$this->assertEquals(FALSE, $actual, "this really shouldn't happen.");}
function __PhpSyntax_empty_array() {$actual = empty($this->array);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_is_null_array() {$actual = is_null($this->array);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_isSet_array() {$actual = isSet($this->array);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_compareOne_array() {$actual = (bool)$this->array;$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_compareTwo_array() {$actual = (array('foo') == array('foo'));$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_array_merge() {$arrOne   = array(''=>'');$arrTwo   = array('9'=>'apple', '15'=>'banana', '20'=>'grapefruit');$actual   = array_merge($arrOne, $arrTwo);$expected = array(''=>'', '0'=>'apple', '1'=>'banana', '2'=>'grapefruit');$this->assertEquals($expected, $actual, "failed? could be a good thing. check the code. i have written Bs_Array->merge() to work around this.");}
function __PhpSyntax_empty_bool() {$actual = empty($this->bool);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_is_null_bool() {$actual = is_null($this->bool);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_isSet_bool() {$actual = isSet($this->bool);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_empty_equals_null() {$actual = (bool)($this->empty == $this->null);$this->assertEquals(TRUE, $actual, "oh, i like this behavior change.");}
function __PhpSyntax_empty_equals_notSet() {$actual = (bool)($this->empty == $this->notSet);$this->assertEquals(TRUE, $actual, "oh, i like this behavior change.");}
function __PhpSyntax_null_equals_notSet() {$actual = (bool)($this->null == $this->notSet);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_empty_doubleequals_null() {$actual = (bool)($this->empty === $this->null);$this->assertEquals(FALSE, $actual, "crap. i liked the behavior as it was.");}
function __PhpSyntax_empty_doubleequals_notSet() {$actual = (bool)($this->empty === $this->notSet);$this->assertEquals(FALSE, $actual, "crap. i liked the behavior as it was.");}
function __PhpSyntax_null_doubleequals_notSet() {$actual = (bool)($this->null === $this->notSet);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_lousyBool_string() {$actual = (bool)($this->string);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_lousyBool_stringInt() {$actual = (bool)($this->stringInt);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_lousyBool_stringZero() {$actual = (bool)($this->stringZero);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_lousyBool_stringNeg() {$actual = (bool)($this->stringNeg);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_lousyBool_stringTrue() {$actual = (bool)($this->stringTrue);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_lousyBool_stringFalse() {$actual = (bool)($this->stringFalse);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_lousyBool_int() {$actual = (bool)($this->int);$this->assertEquals(TRUE, $actual, "crap. this really fucks up existing code.");}
function __PhpSyntax_lousyBool_intTrue() {$actual = (bool)($this->intTrue);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_lousyBool_intFalse() {$actual = (bool)($this->intFalse);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_lousyBool_intNeg() {$actual = (bool)($this->intNeg);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_lousyEquals_stringInt_int() {$actual = (bool)($this->stringInt == $this->int);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_strongEquals_stringInt_int() {$actual = (bool)($this->stringInt === $this->int);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_lousyEquals_string_int() {$actual = (bool)($this->string == $this->int);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_strongEquals_string_int() {$actual = (bool)($this->string === $this->int);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_lousyEquals_string_stringTrue() {$actual = (bool)($this->string == $this->stringTrue);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_strongEquals_string_stringTrue() {$actual = (bool)($this->string === $this->stringTrue);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_lousyEquals_string_intTrue() {$actual = (bool)($this->string == $this->intTrue);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_strongEquals_string_intTrue() {$actual = (bool)($this->string === $this->intTrue);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_lousyEquals_string_true() {$actual = (bool)($this->string == $this->true);$this->assertEquals(TRUE, $actual, "");}
function __PhpSyntax_strongEquals_string_true() {$actual = (bool)($this->string === $this->true);$this->assertEquals(FALSE, $actual, "");}
function __PhpSyntax_dataType_expression_true() {$actual = (TRUE);$this->assertEqualsType('boolean', $actual, "");}
function __PhpSyntax_dataType_expression_compare() {$actual = ($this->stringInt == $this->int);$this->assertEqualsType('boolean', $actual, "");}
function __PhpSyntax_dataType_expression_true_double_ampersand() {$actual = ((TRUE) && (TRUE));$this->assertEqualsType('boolean', $actual, "crap, bad again.");}
function __PhpSyntax_dataType_expression_true_double_and() {$actual = ((TRUE) AND (TRUE));$this->assertEqualsType('boolean', $actual, "crap, bad again.");}
function __PhpSyntax_dataType_expression_true_double2_stroke() {$actual = ((TRUE) || (TRUE));$this->assertEqualsType('boolean', $actual, "crap, bad again.");}
function __PhpSyntax_dataType_expression_true_double2_or() {$actual = ((TRUE) OR (TRUE));$this->assertEqualsType('boolean', $actual, "crap, bad again.");}
function __PhpSyntax_reference_1() {$a = 'hello';$b = &$a;$b = 'world';$actual = $a;$expected = 'world';$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_reference_2() {$a = 'hello';$b = &$a;unset($b);$b = 'world';$actual = $a;$expected = 'hello';$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_reference_3() {$a = 'hello';$b = &$a;unset($b);$actual = $a;$expected = 'hello';$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_reference_4() {$a = 'hello';$b = &$a;$b = 'world';unset($b);$actual = $a;$expected = 'world';$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_prePostDeIncrement_1() {$a = 1;$actual   = $a++;$expected = 1;$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_prePostDeIncrement_2() {$a = 1;$actual   = ++$a;$expected = 2;$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_prePostDeIncrement_11() {$a = 1;$b = &$a;$actual   = $a++;$expected = 1;$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_prePostDeIncrement_12() {$a = 1;$b = &$a;$actual   = ++$a;$expected = 2;$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_hashKeys_0() {$a = 'foo';$actual = $a['bar'];$expected = 'f';$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_hashKeys_1() {$a = 'foo';$actual = isSet($a['bar']);$expected = TRUE;$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_hashKeys_2() {$a = 'foo';$actual = empty($a['bar']);$expected = FALSE;$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_hashKeys_3() {$a = array('foo'=>'foo');$actual = isSet($a['bar']);$expected = FALSE;$this->assertEquals($expected, $actual, "");}
function __PhpSyntax_hashKeys_4() {$a = 1;$actual = isSet($a['bar']);$expected = FALSE;$this->assertEquals($expected, $actual, "");}
}
?>