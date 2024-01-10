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
require_once($APP['path']['core'] . 'util/Bs_CsvUtil.class.php');class Bs_CsvUtil_PhpUnit extends Bs_TestCase {var $_Bs_CsvUtil;function Bs_CsvUtil_PhpUnit($name) {$this->Bs_TestCase($name);$this->_Bs_CsvUtil =& new Bs_CsvUtil;}
function __Bs_CsvUtil_csvStringToArray() {$string = <<< EOD
  a;b;c
  1;2;3
EOD;
$expected = array(array('a', 'b', 'c'), array('1', '2', '3'));$actual   = $this->_Bs_CsvUtil->csvStringToArray($string, $separator=';', $trim='both', $removeHeader=FALSE);$this->assertEquals($expected, $actual, "test 1 with abc 123");}
function __Bs_CsvUtil_csvStringToArray2() {$string = <<< EOD
THIS,IS,TITLE
a,b,c
1,2,3
EOD;
$expected = array(array('a', 'b', 'c'), array('1', '2', '3'));$actual   = $this->_Bs_CsvUtil->csvStringToArray($string, $separator=',', $trim='none', $removeHeader=TRUE);$this->assertEquals($expected, $actual, "test 2 with abc 123 and a space as separator, header removed.");}
function __Bs_CsvUtil_csvStringToArray3() {$string = <<< EOD
  a; b; c
  ;  ;  
  1; 2; 3
EOD;
$expected = array(array('a', 'b', 'c'), array('1', '2', '3'));$actual   = $this->_Bs_CsvUtil->csvStringToArray($string, $separator='; ', $trim='both', $removeHeader=FALSE, $removeEmptyLines=TRUE);$this->assertEquals($expected, $actual, "test 3 with abc 123 and empty lines removed. separator is '; ' (with a space aswell).");}
function __Bs_CsvUtil_csvStringToArray4() {$string = <<< EOD
  a;"b bb";c
  1;2;"3 33"
EOD;
$expected = array(array('a', 'b bb', 'c'), array('1', '2', '3 33'));$actual   = $this->_Bs_CsvUtil->csvStringToArray($string, $separator=';', $trim='both', $removeHeader=FALSE);$this->assertEquals($expected, $actual, "test 4 with quotes.");}
function __Bs_CsvUtil_csvStringToArray5() {$string = <<< EOD
this;is;a;"multi
line";csv;string
this;one;is;"another 
multiline";csv;string :)   
EOD;
$expected = array(array('this', 'is', 'a', "multi\nline", 'csv', 'string'), array('this', 'one', 'is', "another \nmultiline", 'csv', 'string :)'));$actual   = $this->_Bs_CsvUtil->csvStringToArray($string, $separator=';', $trim='both', $removeHeader=FALSE, $removeEmptyLines=FALSE, $checkMultiline=TRUE);$this->assertEquals($expected, $actual, "test 5 with multilines.");}
function __Bs_CsvUtil_arrayToCsvString1() {$array = array(
array('madonna', 'pop', 'usa'), 
array('alanis morisette', 'rock', 'canada'), 
array('falco', 'pop', 'austria'), 
);$expected = 'madonna;pop;usa
alanis morisette;rock;canada
falco;pop;austria';$actual   = $this->_Bs_CsvUtil->arrayToCsvString($array, $separator=';', $trim='none', $removeEmptyLines=TRUE);$this->assertEquals($expected, $actual, "test arrayToCsvString() 1");}
function __Bs_CsvUtil_arrayToCsvString2() {$array = array('madonna', 'alanis morisette', 'falco');$expected = 'madonna;alanis morisette;falco';$actual   = $this->_Bs_CsvUtil->arrayToCsvString($array, $separator=';', $trim='none', $removeEmptyLines=TRUE);$this->assertEquals($expected, $actual, "test arrayToCsvString() 2");}
function __Bs_CsvUtil_arrayToCsvString3() {$array = array(
array('madonna', 'pop', 'usa'), 
array('alanis morisette', 'rock', 'canada'), 
array('falco aka "wolfgang hoelzl"', 'pop', 'austria'), 
);$expected = 'madonna;pop;usa
alanis morisette;rock;canada
"falco aka ""wolfgang hoelzl""";pop;austria';$actual   = $this->_Bs_CsvUtil->arrayToCsvString($array, $separator=';', $trim='none', $removeEmptyLines=TRUE);$this->assertEquals($expected, $actual, "test arrayToCsvString() 3");}
function __Bs_CsvUtil_arrayToCsvString4() {$array = array(
array('madonna', 'pop', 'usa'), 
array('alanis morisette', 'rock', 'canada'), 
array('falco; wolfgang hoelzl', 'pop', 'austria'), 
);$expected = 'madonna;pop;usa
alanis morisette;rock;canada
"falco; wolfgang hoelzl";pop;austria';$actual   = $this->_Bs_CsvUtil->arrayToCsvString($array, $separator=';', $trim='none', $removeEmptyLines=TRUE);$this->assertEquals($expected, $actual, "test arrayToCsvString() 4");}
function __Bs_CsvUtil_arrayToCsvString5() {$array = array(
array('madonna', 'pop', 'usa'), 
array('alanis morisette', 'rock', 'canada'), 
array('falco
wolfgang hoelzl', 'pop', 'austria'), 
);$expected = 'madonna;pop;usa
alanis morisette;rock;canada
"falco
wolfgang hoelzl";pop;austria';$actual   = $this->_Bs_CsvUtil->arrayToCsvString($array, $separator=';', $trim='none', $removeEmptyLines=TRUE);$this->assertEquals($expected, $actual, "test arrayToCsvString() 5");}
function __Bs_CsvUtil_guessSeparator() {$array = array('madonna;pop;usa', 'alanis morisette;rock;canada', 'falco; wolfgang hoelzl|pop|austria');$expected = ';';$actual   = $this->_Bs_CsvUtil->guessSeparator($array);$this->assertEquals($expected, $actual, "test guessSeparator 1");$array = array('madonna,pop,usa', 'alanis morisette,rock,canada', 'falco,pop,austria');$expected = ',';$actual   = $this->_Bs_CsvUtil->guessSeparator($array);$this->assertEquals($expected, $actual, "test guessSeparator 2");}
}
?>