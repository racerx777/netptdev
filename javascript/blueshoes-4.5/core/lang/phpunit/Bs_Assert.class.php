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
class Bs_Assert extends Bs_Object {function Bs_Assert() {parent::Bs_Object(); }
function assert($boolean, $message='') {if (! $boolean) $this->fail($message);}
function assertEquals($expected, $actual, $message='') {$typeStatus = ($expected === $actual);$valueStatus  = ($expected == $actual);if (!$valueStatus) {$this->failNotEquals($expected, $actual, "expected", $message);return;}
if (!$typeStatus) {$this->failNotEquals($expected, $actual, "expected", $message . " Result OK *but* type mismatch!");return;}
}
function assertRegexp($regexp, $actual, $message='', $regFunction='ereg') {if (! $regFunction($regexp, $actual)) {$message .= "  [reg function used was: '$regFunction']  ";$this->failNotEquals($regexp, $actual, "pattern", $message);}
}
function assertEqualsType($expected, $actual, $message='') {if ($expected != getType($actual)) {$this->failNotEquals($expected, getType($actual) . " (value was: '$actual')", "type", $message);}
}
function assertInstanceOf($expected, $actual, $message='') {if ((! is_object($actual)) || (strToLower($expected) != strToLower(get_class($actual)))) {$this->failNotEquals($expected, $actual, "class", $message);}
}
function failNotEquals($expected, $actual, $expectedLabel, $message='') {$str = ($message != '') ? ($message . ' ') : '';$str .= "($expectedLabel/actual)<br>";$htmlExpected = $htmlActual = '';if (is_object($expected)) {$htmlExpected = 'Object['.get_class($expected).']';} else if (is_array($expected)) {$htmlExpected = 'Array['.sizeOf($expected).']';} else {$htmlExpected = htmlspecialchars($expected);}
$htmlExpected .= ' (' . getType($expected) . ')';if (is_object($actual)) {$htmlExpected = 'Object['.get_class($actual).']';} else if (is_array($actual)) {$htmlExpected = 'Array['.sizeOf($actual).']';} else {$htmlActual   = htmlspecialchars($actual);}
$htmlActual   .= ' (' . getType($actual)   . ')';$str .= sprintf("<pre>%s\n--------\n%s</pre>", $htmlExpected, $htmlActual);$this->fail($str);}
function fail($str) {echo "YOU HAVE TO OVERWRITE THIS FUNCTION!!!";}
}
?>