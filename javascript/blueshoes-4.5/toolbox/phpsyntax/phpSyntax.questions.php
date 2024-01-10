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
$dataTypeOptions = '
<option></option>
<option>boolean</option>
<option>integer</option>
<option>double</option>
<option>string</option>
<option>array</option>
<option>object</option>
<option>resource</option>
<option>user function</option>
<option>NULL</option>
<option>unknown type</option>
';$numberOptions = '
<option></option>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>invalid operation</option>
';$q = array(
array(
'group' => 'empty (var $empty = "";)', 
'qId'      => 'empty-1', 
'question' => 
'
datatype of <code>$empty</code>? 
<select name="empty-1">
' . $dataTypeOptions . '
</select>
', 
'actual'   => @$_results['empty-1'], 
'expected' => 'string', 
'comments' => '', 
), 
array(
'qId'      => 'empty-2', 
'question' => '
<code>$x = empty($empty);</code><br>
what is <code>$x</code>? <input type=radio name="empty-2" value=true>true <input type=radio name="empty-2" value=false>false 
', 
'actual'   => @$_results['empty-2'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'empty-3', 
'question' => '
<code>$x = is_null($empty);</code><br>
what is <code>$x</code>? <input type=radio name="empty-3" value=true>true <input type=radio name="empty-3" value=false>false 
', 
'actual'   => @$_results['empty-3'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'empty-4', 
'question' => '
<code>$x = isSet($empty);</code><br>
what is <code>$x</code>? <input type=radio name="empty-4" value=true>true <input type=radio name="empty-4" value=false>false 
',
'actual'   => @$_results['empty-4'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'empty-5', 
'question' => '
<code>$x = (bool)$empty;</code><br>
what is <code>$x</code>? <input type=radio name="empty-5" value=true>true <input type=radio name="empty-5" value=false>false 
', 
'actual'   => @$_results['empty-5'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'group'    => 'null (var $null = NULL;)', 
'qId'      => 'null-1', 
'question' => 
'
datatype of <code>$null</code>? 
<select name="null-1">
' . $dataTypeOptions . '
</select>
', 
'actual'   => @$_results['null-1'], 
'expected' => 'NULL', 
'comments' => 'i would have expected "unknown type". see http://www.php.net/manual/en/function.gettype.php. until some time ago "null" was not documented on that page. in c and java this results to null also.', 
), 
array(
'qId'      => 'null-2', 
'question' => '
<code>$x = empty($null);</code><br>
what is <code>$x</code>? <input type=radio name="null-2" value=true>true <input type=radio name="null-2" value=false>false 
', 
'actual'   => @$_results['null-2'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'null-3', 
'question' => '
<code>$x = is_null($null);</code><br>
what is <code>$x</code>? <input type=radio name="null-3" value=true>true <input type=radio name="null-3" value=false>false 
', 
'actual'   => @$_results['null-3'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'null-4', 
'question' => '
<code>$x = isSet($null);</code><br>
what is <code>$x</code>? <input type=radio name="null-4" value=true>true <input type=radio name="null-4" value=false>false 
', 
'actual'   => @$_results['null-4'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'null-5', 
'question' => '
<code>$x = (bool)$null;</code><br>
what is <code>$x</code>? <input type=radio name="null-5" value=true>true <input type=radio name="null-5" value=false>false 
', 
'actual'   => @$_results['null-5'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'group'    => 'notSet (var $notSet;)', 
'qId'      => 'notSet-1', 
'question' => 
'
datatype of <code>$notSet</code>? 
<select name="notSet-1">
' . $dataTypeOptions . '
</select>
', 
'actual'   => @$_results['notSet-1'], 
'expected' => 'NULL', 
'comments' => 'i would have expected an "unknown type". see http://www.php.net/manual/en/function.gettype.php. sam says c and java would be this way also.', 
), 
array(
'qId'      => 'notSet-2', 
'question' => '
<code>$x = empty($notSet);</code><br>
what is <code>$x</code>? <input type=radio name="notSet-2" value=true>true <input type=radio name="notSet-2" value=false>false 
', 
'actual'   => @$_results['notSet-2'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'notSet-3', 
'question' => '
<code>$x = is_null($notSet);</code><br>
what is <code>$x</code>? <input type=radio name="notSet-3" value=true>true <input type=radio name="notSet-3" value=false>false 
', 
'actual'   => @$_results['notSet-3'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'notSet-4', 
'question' => '
<code>$x = isSet($notSet);</code><br>
what is <code>$x</code>? <input type=radio name="notSet-4" value=true>true <input type=radio name="notSet-4" value=false>false 
', 
'actual'   => @$_results['notSet-4'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'notSet-5', 
'question' => '
<code>$x = (bool)$notSet;</code><br>
what is <code>$x</code>? <input type=radio name="notSet-5" value=true>true <input type=radio name="notSet-5" value=false>false 
', 
'actual'   => @$_results['notSet-5'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'group' => 'array (var $array = array();)', 
'qId'      => 'array-1', 
'question' => '
<code>$x = empty($array);</code><br>
what is <code>$x</code>? <input type=radio name="array-1" value=true>true <input type=radio name="array-1" value=false>false 
', 
'actual'   => @$_results['array-1'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'array-2', 
'question' => '
<code>$x = is_null($array);</code><br>
what is <code>$x</code>? <input type=radio name="array-2" value=true>true <input type=radio name="array-2" value=false>false 
', 
'actual'   => @$_results['array-2'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'array-3', 
'question' => '
<code>$x = isSet($array);</code><br>
what is <code>$x</code>? <input type=radio name="array-3" value=true>true <input type=radio name="array-3" value=false>false 
',
'actual'   => @$_results['array-3'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'array-4', 
'question' => '
<code>$x = (bool)$array;</code><br>
what is <code>$x</code>? <input type=radio name="array-4" value=true>true <input type=radio name="array-4" value=false>false 
', 
'actual'   => @$_results['array-4'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'array-5', 
'question' => '
<code>$x = (array(\'foo\') == array(\'foo\'));</code><br>
what is <code>$x</code>? <input type=radio name="array-5" value=true>true <input type=radio name="array-5" value=false>false 
', 
'actual'   => @$_results['array-5'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'array-6', 
'question' => '
<code>$x = (array(\'a\'=>\'foo\') == array(\'b\'=>\'foo\'));</code><br>
what is <code>$x</code>? <input type=radio name="array-6" value=true>true <input type=radio name="array-6" value=false>false 
', 
'actual'   => @$_results['array-6'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'array-7', 
'question' => '
<code>
$arrOne = array(\'\'=>\'\');<br>
$arrTwo = array(\'9\'=>\'apple\', \'15\'=>\'banana\', \'20\'=>\'grapefruit\');<br>
$x      = array_merge($arrOne, $arrTwo);</code><br>
what is <code>$x</code>? <br>
<input type=radio name="array-7" value=1> 1) array(\'\'=>\'\', 0=>\'apple\', 1=>\'banana\', 2=>\'grapefruit\');<br>
<input type=radio name="array-7" value=2> 2) array(\'\'=>\'\', 9=>\'apple\', 15=>\'banana\', 20=>\'grapefruit\');', 
'actual'   => @$_results['array-7'], 
'expected' => '1', 
'comments' => 'if you want 2) you can use Bs_Array->merge()', 
), 
array(
'group'    => 'bool (var $bool = FALSE;)', 
'qId'      => 'bool-1', 
'question' => 
'
<code>$x = empty($bool);</code><br>
what is <code>$x</code>? <input type=radio name="bool-1" value=true>true <input type=radio name="bool-1" value=false>false 
', 
'actual'   => @$_results['bool-1'], 
'expected' => TRUE, 
'comments' => 'empty($bool) is like empty(0) or empty("0") and all result to TRUE. Not everyone is happy about that.', 
), 
array(
'qId'      => 'bool-2', 
'question' => '
<code>$x = is_null($bool);</code><br>
what is <code>$x</code>? <input type=radio name="bool-2" value=true>true <input type=radio name="bool-2" value=false>false 
', 
'actual'   => @$_results['bool-2'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'bool-3', 
'question' => '
<code>$x = isSet($bool);</code><br>
what is <code>$x</code>? <input type=radio name="bool-3" value=true>true <input type=radio name="bool-3" value=false>false 
', 
'actual'   => @$_results['bool-3'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'group'    => 'compare', 
'qId'      => 'compare-1', 
'question' => '
<code>$x = (bool)($empty == $null);</code><br>
what is <code>$x</code>? <input type=radio name="compare-1" value=true>true <input type=radio name="compare-1" value=false>false 
', 
'actual'   => @$_results['compare-1'], 
'expected' => TRUE, 
'comments' => 'i would prefer to have FALSE returned. (andrej)', 
), 
array(
'qId'      => 'compare-2', 
'question' => '
<code>$x = (bool)($empty == $notSet);</code><br>
what is <code>$x</code>? <input type=radio name="compare-2" value=true>true <input type=radio name="compare-2" value=false>false 
', 
'actual'   => @$_results['compare-2'], 
'expected' => TRUE, 
'comments' => 'i would prefer to have FALSE returned. (andrej)', 
), 
array(
'qId'      => 'compare-3', 
'question' => '
<code>$x = (bool)($null == $notSet);</code><br>
what is <code>$x</code>? <input type=radio name="compare-3" value=true>true <input type=radio name="compare-3" value=false>false 
', 
'actual'   => @$_results['compare-3'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'compare-4', 
'question' => '
<code>$x = (bool)($empty === $null);</code><br>
what is <code>$x</code>? <input type=radio name="compare-4" value=true>true <input type=radio name="compare-4" value=false>false 
', 
'actual'   => @$_results['compare-4'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'compare-5', 
'question' => '
<code>$x = (bool)($empty === $notSet);</code><br>
what is <code>$x</code>? <input type=radio name="compare-5" value=true>true <input type=radio name="compare-5" value=false>false 
', 
'actual'   => @$_results['compare-5'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'compare-6', 
'question' => '
<code>$x = (bool)($null === $notSet);</code><br>
what is <code>$x</code>? <input type=radio name="compare-6" value=true>true <input type=radio name="compare-6" value=false>false 
', 
'actual'   => @$_results['compare-6'], 
'expected' => TRUE, 
'comments' => 'i would prefer to have FALSE returned. (andrej)<br>i think sam is satisfied with this.', 
), 
array(
'group'    => 'string', 
'qId'      => 'string-1', 
'question' => '
<code>$x = (bool)("hello");</code><br>
what is <code>$x</code>? <input type=radio name="string-1" value=true>true <input type=radio name="string-1" value=false>false 
', 
'actual'   => @$_results['string-1'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'string-2', 
'question' => '
<code>$x = (bool)("15");</code><br>
what is <code>$x</code>? <input type=radio name="string-2" value=true>true <input type=radio name="string-2" value=false>false 
', 
'actual'   => @$_results['string-2'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'string-3', 
'question' => '
<code>$x = (bool)("0");</code><br>
what is <code>$x</code>? <input type=radio name="string-3" value=true>true <input type=radio name="string-3" value=false>false 
', 
'actual'   => @$_results['string-3'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'string-4', 
'question' => '
<code>$x = (bool)("-1");</code><br>
what is <code>$x</code>? <input type=radio name="string-4" value=true>true <input type=radio name="string-4" value=false>false 
', 
'actual'   => @$_results['string-4'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'string-5', 
'question' => '
<code>$x = (bool)("true");</code><br>
what is <code>$x</code>? <input type=radio name="string-5" value=true>true <input type=radio name="string-5" value=false>false 
', 
'actual'   => @$_results['string-5'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'string-6', 
'question' => '
<code>$x = (bool)("false");</code><br>
what is <code>$x</code>? <input type=radio name="string-6" value=true>true <input type=radio name="string-6" value=false>false 
', 
'actual'   => @$_results['string-6'], 
'expected' => TRUE, 
'comments' => 'hah, got you? :)', 
), 
array(
'group'    => 'number', 
'qId'      => 'number-1', 
'question' => '
<code>$x = (bool)(15);</code><br>
what is <code>$x</code>? <input type=radio name="number-1" value=true>true <input type=radio name="number-1" value=false>false 
', 
'actual'   => @$_results['number-1'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'number-2', 
'question' => '
<code>$x = (bool)(1);</code><br>
what is <code>$x</code>? <input type=radio name="number-2" value=true>true <input type=radio name="number-2" value=false>false 
', 
'actual'   => @$_results['number-2'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'number-3', 
'question' => '
<code>$x = (bool)(0);</code><br>
what is <code>$x</code>? <input type=radio name="number-3" value=true>true <input type=radio name="number-3" value=false>false 
', 
'actual'   => @$_results['number-3'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'number-4', 
'question' => '
<code>$x = (bool)(-1);</code><br>
what is <code>$x</code>? <input type=radio name="number-4" value=true>true <input type=radio name="number-4" value=false>false 
', 
'actual'   => @$_results['number-4'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'group'    => 'compare 2', 
'qId'      => 'compare2-1', 
'question' => '
<code>$x = (bool)("15" == 15);</code><br>
what is <code>$x</code>? <input type=radio name="compare2-1" value=true>true <input type=radio name="compare2-1" value=false>false 
', 
'actual'   => @$_results['compare2-1'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'compare2-2', 
'question' => '
<code>$x = (bool)("15" === 15);</code><br>
what is <code>$x</code>? <input type=radio name="compare2-2" value=true>true <input type=radio name="compare2-2" value=false>false 
', 
'actual'   => @$_results['compare2-2'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'compare2-3', 
'question' => '
<code>$x = (bool)("hello" == 15);</code><br>
what is <code>$x</code>? <input type=radio name="compare2-3" value=true>true <input type=radio name="compare2-3" value=false>false 
', 
'actual'   => @$_results['compare2-3'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'compare2-4', 
'question' => '
<code>$x = (bool)("hello" === 15);</code><br>
what is <code>$x</code>? <input type=radio name="compare2-4" value=true>true <input type=radio name="compare2-4" value=false>false 
', 
'actual'   => @$_results['compare2-4'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'compare2-5', 
'question' => '
<code>$x = (bool)("hello" == "true");</code><br>
what is <code>$x</code>? <input type=radio name="compare2-5" value=true>true <input type=radio name="compare2-5" value=false>false 
', 
'actual'   => @$_results['compare2-5'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'compare2-6', 
'question' => '
<code>$x = (bool)("hello" === "true");</code><br>
what is <code>$x</code>? <input type=radio name="compare2-6" value=true>true <input type=radio name="compare2-6" value=false>false 
', 
'actual'   => @$_results['compare2-6'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'compare2-7', 
'question' => '
<code>$x = (bool)("hello" == 1);</code><br>
what is <code>$x</code>? <input type=radio name="compare2-7" value=true>true <input type=radio name="compare2-7" value=false>false 
', 
'actual'   => @$_results['compare2-7'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'compare2-8', 
'question' => '
<code>$x = (bool)("hello" === 1);</code><br>
what is <code>$x</code>? <input type=radio name="compare2-8" value=true>true <input type=radio name="compare2-8" value=false>false 
', 
'actual'   => @$_results['compare2-8'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'compare2-9', 
'question' => '
<code>$x = (bool)("hello" == TRUE);</code><br>
what is <code>$x</code>? <input type=radio name="compare2-9" value=true>true <input type=radio name="compare2-9" value=false>false 
', 
'actual'   => @$_results['compare2-9'], 
'expected' => TRUE, 
'comments' => '', 
), 
array(
'qId'      => 'compare2-10', 
'question' => '
<code>$x = (bool)("hello" === TRUE);</code><br>
what is <code>$x</code>? <input type=radio name="compare2-10" value=true>true <input type=radio name="compare2-10" value=false>false 
', 
'actual'   => @$_results['compare2-10'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'group'    => 'datatype', 
'qId'      => 'datatype-1', 
'question' => '
<code>$x = (TRUE);</code><br>
datatype of <code>$x</code>? 
<select name="datatype-1">
' . $dataTypeOptions . '
</select>
', 
'actual'   => @$_results['datatype-1'], 
'expected' => 'boolean', 
'comments' => '', 
), 
array(
'qId'      => 'datatype-2', 
'question' => '
<code>$x = ("15" == 15);</code><br>
datatype of <code>$x</code>? 
<select name="datatype-2">
' . $dataTypeOptions . '
</select>
', 
'actual'   => @$_results['datatype-2'], 
'expected' => 'boolean', 
'comments' => '', 
), 
array(
'qId'      => 'datatype-3', 
'question' => '
<code>$x = ((TRUE) && (TRUE));</code><br>
datatype of <code>$x</code>? 
<select name="datatype-3">
' . $dataTypeOptions . '
</select>
', 
'actual'   => @$_results['datatype-3'], 
'expected' => 'boolean', 
'comments' => 'used to return "integer". that problem is fixed somewhere between php 4.0.5 and 4.1.1.', 
), 
array(
'qId'      => 'datatype-4', 
'question' => '
<code>$x = ((TRUE) AND (TRUE));</code><br>
datatype of <code>$x</code>? 
<select name="datatype-4">
' . $dataTypeOptions . '
</select>
', 
'actual'   => @$_results['datatype-4'], 
'expected' => 'boolean', 
'comments' => 'used to return "integer". that problem is fixed somewhere between php 4.0.5 and 4.1.1.', 
), 
array(
'qId'      => 'datatype-5', 
'question' => '
<code>$x = ((TRUE) || (TRUE));</code><br>
datatype of <code>$x</code>? 
<select name="datatype-5">
' . $dataTypeOptions . '
</select>
', 
'actual'   => @$_results['datatype-5'], 
'expected' => 'boolean', 
'comments' => 'used to return "integer". that problem is fixed somewhere between php 4.0.5 and 4.1.1.', 
), 
array(
'qId'      => 'datatype-6', 
'question' => '
<code>$x = ((TRUE) OR (TRUE));</code><br>
datatype of <code>$x</code>? 
<select name="datatype-6">
' . $dataTypeOptions . '
</select>
', 
'actual'   => @$_results['datatype-6'], 
'expected' => 'boolean', 
'comments' => 'used to return "integer". that problem is fixed somewhere between php 4.0.5 and 4.1.1.', 
), 
array(
'group'    => 'reference', 
'qId'      => 'reference-1', 
'question' => '
<code>
$a = "hello";<br>
$b = &$a;<br>
$b = "world";</code><br>
what is <code>$a</code>? 
<select name="reference-1">
<option></option>
<option>hello</option>
<option>world</option>
<option>null</option>
<option>unset</option>
</select>
', 
'actual'   => @$_results['reference-1'], 
'expected' => 'world', 
'comments' => '', 
), 
array(
'qId'      => 'reference-2', 
'question' => '
<code>
$a = "hello";<br>
$b = &$a;<br>
unset($b);<br>
$b = "world";</code><br>
what is <code>$a</code>? 
<select name="reference-2">
<option></option>
<option>hello</option>
<option>world</option>
<option>null</option>
<option>unset</option>
</select>
', 
'actual'   => @$_results['reference-2'], 
'expected' => 'hello', 
'comments' => '', 
), 
array(
'qId'      => 'reference-3', 
'question' => '
<code>
$a = "hello";<br>
$b = &$a;<br>
unset($b);</code><br>
what is <code>$a</code>? 
<select name="reference-3">
<option></option>
<option>hello</option>
<option>world</option>
<option>null</option>
<option>unset</option>
</select>
', 
'actual'   => @$_results['reference-3'], 
'expected' => 'hello', 
'comments' => '', 
), 
array(
'qId'      => 'reference-4', 
'question' => '
<code>
$a = "hello";<br>
$b = &$a;<br>
$b = "world";<br>
unset($b);</code><br>
what is <code>$a</code>? 
<select name="reference-4">
<option></option>
<option>hello</option>
<option>world</option>
<option>null</option>
<option>unset</option>
</select>
', 
'actual'   => @$_results['reference-4'], 
'expected' => 'world', 
'comments' => '', 
), 
array(
'group'    => 'pre post de increment', 
'qId'      => 'ppdi-1', 
'question' => 
'
<code>
$a = 1;<br>
$b = $a++;</code><br>
what is <code>$b</code>? 
<select name="ppdi-1">
' . $numberOptions . '
</select>
', 
'actual'   => @$_results['ppdi-1'], 
'expected' => '1', 
'comments' => '', 
), 
array(
'qId'      => 'ppdi-2', 
'question' => 
'
<code>
$a = 1;<br>
$b = ++$a;</code><br>
what is <code>$b</code>? 
<select name="ppdi-2">
' . $numberOptions . '
</select>
', 
'actual'   => @$_results['ppdi-2'], 
'expected' => '2', 
'comments' => '', 
), 
array(
'group'    => 'pre post de increment (with reference)', 
'qId'      => 'ppdi-11', 
'question' => 
'
<code>
$a = 1;<br>
$x = &$a;<br>
$b = $a++;</code><br>
what is <code>$b</code>? 
<select name="ppdi-11">
' . $numberOptions . '
</select>
', 
'actual'   => @$_results['ppdi-11'], 
'expected' => '1', 
'comments' => '', 
), 
array(
'qId'      => 'ppdi-12', 
'question' => 
'
<code>
$a = 1;<br>
$x = &$a;<br>
$b = ++$a;</code><br>
what is <code>$b</code>? 
<select name="ppdi-12">
' . $numberOptions . '
</select>
', 
'actual'   => @$_results['ppdi-12'], 
'expected' => '2', 
'comments' => '', 
), 
array(
'group'    => 'hash keys', 
'qId'      => 'hashKeys-1', 
'question' => 
'
<code>
$a = \'foo\';<br>
$b = isSet($a[\'bar\']);</code><br>
what is <code>$b</code>? <input type=radio name="hashKeys-1" value=true>true <input type=radio name="hashKeys-1" value=false>false 
', 
'actual'   => @$_results['hashKeys-1'], 
'expected' => TRUE, 
'comments' => 'The \'bar\' in $a[\'bar\'] evaluates to int 0 (see the PHP Cheat Sheet at http://www.blueshoes.org/en/developer/php_cheat_sheet/). Then it is $a[0] and now each character in the string $a (foo) can be accessed with its char number, like an array. So that is an \'f\'. And that thing is set.', 
), 
array(
'qId'      => 'hashKeys-2', 
'question' => 
'
<code>
$a = \'foo\';<br>
$b = empty($a[\'bar\']);</code><br>
what is <code>$b</code>? <input type=radio name="hashKeys-2" value=true>true <input type=radio name="hashKeys-2" value=false>false 
', 
'actual'   => @$_results['hashKeys-2'], 
'expected' => FALSE, 
'comments' => 'Read the comment of the question before.', 
), 
array(
'qId'      => 'hashKeys-3', 
'question' => 
'
<code>
$a = array(\'foo\'=>\'foo\');<br>
$b = isSet($a[\'bar\']);</code><br>
what is <code>$b</code>? <input type=radio name="hashKeys-3" value=true>true <input type=radio name="hashKeys-3" value=false>false 
', 
'actual'   => @$_results['hashKeys-3'], 
'expected' => FALSE, 
'comments' => '', 
), 
array(
'qId'      => 'hashKeys-4', 
'question' => 
'
<code>
$a = 1;<br>
$b = isSet($a[\'bar\']);</code><br>
what is <code>$b</code>? <input type=radio name="hashKeys-4" value=true>true <input type=radio name="hashKeys-4" value=false>false 
', 
'actual'   => @$_results['hashKeys-4'], 
'expected' => FALSE, 
'comments' => '', 
), 
);?>