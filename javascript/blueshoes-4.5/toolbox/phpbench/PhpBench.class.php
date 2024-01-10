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
$profileTest[] = array (
'title'     => 'READ LOOP: foreach() &nbsp;&nbsp; vs. &nbsp;&nbsp;  while(list()=each())',
'text'      => 'What is the best way to loop a hash array? <br>Given is a Hash array '
. 'with 100 elements, 24byte key and 10k data per entry<br> '
. 'I\'ve chosen the large data amount to try out what happens if I reference the '
. 'data with the &amp;-ref-operator (to avoid copying). But to my surprise '
. 'the loops are never faster! In tests 5 and 6 are even 10x - 30x slower !! '
. 'The larger the data entrys are the slower the tests 5 and 6 get! '
. 'Copying seams always faster then using the &amp;-ref-operator.'
. '<strong><br>Way ???<br>Let me know at &lt;sam at blueshoes dot org&gt; </strong>',
'function'  => 'forEach',
'param'     => 10,                       'conclusion'=> 'It must have something to do with PHP4 <A href="http://www.zend.com/zend/art/ref-count.php">variable ref-count</A> '
. 'So you can safely use foreach and only use the &amp;-ref-operator when realy needed OR (according to the link below) '
. 'when passing objects to functions. (Thanx to Wayne for his help)'
);$profileTest[] = array (
'title'     => 'MODIFY LOOP: foreach() &nbsp;&nbsp; vs. &nbsp;&nbsp;  while(list()=each())',
'text'      => 'While the below test only reads and copies the data the question arised what '
. 'would happen if I modify each value of the hash below. <br>'
. 'Again I an unexpected result. Even if I reduce the data size to 100 byte p. e. '
. 'it ends up that Nr.3 is 1.5 - 2x faster.',
'function'  => 'forEachMod',
'param'     => 10,                       'conclusion'=> 'Use foreach unless the hash is lage AND has lage data elements. In that case use variation Nr.3 .'
);$profileTest[] = array (
'title'     => 'For-loop test',
'text'      => 'Is it worth the effort to calculate the length of the loop in advance? '
.'<br>E.g. "for ($i=0; $i&lt;$size; $i++)" instead of  "for ($i=0; $i&lt;sizeOf($x); $i++)"',
'function'  => 'forLoop', 
'param'     => '',                      'conclusion'=> 'The test below speeks for it self. Always calculate the length of the loop in advance!'
);$profileTest[] = array (
'title'     => 'Using the &amp;-ref-operator as so called "alias"' ,
'text'      => 'Is a good idea to use the &amp;-ref-operator to substitute (or alias) a complex mutidim-array? . Call 1\'000x'
.'<br>E.g. $person = &$aHach["country"]["zip"]["streat"]["number"]["name"]',
'function'  => 'aliasing', 
'param'     => '1000',                    'conclusion'=> 'It seams to be ok to use aliases. It also makes the code more readabel. But I was expecting to get a lager '
.'performance gain; especially with very multdimetional arrays.'
);$profileTest[] = array (
'title'     => '$obj = new SomeClass() &nbsp;&nbsp; vs. &nbsp;&nbsp; $obj =& new SomeClass() using the =&-ref-operator',
'text'      => 'Is a good idea to use the =&amp;-ref-operator when creating a new object? Call 1\'000x',
'function'  => 'newClass', 
'param'     => '1000',                    'conclusion'=> 'There seams to be no difference in performance.'
);$profileTest[] = array (
'title'     => 'double (") &nbsp;&nbsp; vs. &nbsp;&nbsp; single (\') quotes',
'text'      => 'Is a there a difference in using double (") and single (\') quotes for strings. Call 1\'000x',
'function'  => 'doubleOrSingleQuote', 
'param'     => '1000',                       'conclusion'=> 'Single and double quoted strings behave almost the same with one exception: Don\'t use the a lonely ($) in double quoted string '
.'unless you want to reference a PHP-var; or use (\$). '
);$profileTest[] = array (
'title'     => 'isSet() &nbsp;&nbsp; vs. &nbsp;&nbsp; empty() &nbsp;&nbsp; vs. &nbsp;&nbsp is_array()',
'text'      => 'What is the performance of isSet() and empty(). Call 2\'000x',
'function'  => 'isSetVsEmpty',  
'param'     => '2000',                       'conclusion'=> 'isSet() and empty() are identical. Interesting that a is_array() on a unset val is 3x slower. So alway check if val is set at ' 
.'all befor using type-checking. E.g. if (isSet($foo) AND is_array($foo))'
);$profileTest[] = array (
'title'     => 'switch/case &nbsp;&nbsp; vs. &nbsp;&nbsp; if/elseif',
'text'      => 'Is a there a difference between switch and if elseif. Call 1\'000x',
'function'  => 'ifOrCase', 
'param'     => '1000',                       'conclusion'=> 'Using a switch/case or if/elseif is almost the same. Note that the test is unsing === and is slitly faster then using ==.'
);$profileTest[] = array (
'title'     => 'Function Call: if (isEx($foo)) &nbsp;&nbsp; vs. &nbsp;&nbsp;  if ($foo === FALSE)',
'text'      => 'How mutch CPU does a function call cost in comparance against a simple if([condition])<br>'
. 'This situation arised, when I wanted to simulate "Exception Handling" in PHP. '
. 'There mainly 2 ways to do it (rufly explained): <br> <ol> '
. '<li type="a">On Exception: Retrun FALSE. Then callback to fetch the Exception-Object. <br>'
. '&nbsp;&nbsp;e.g. if ($obj->methode() === FALSE) { if($ex = $obj->getEx()) { /* handle (possible) exeption*/} } </li>'
. '<li type="a">On Exception: Retrun the Exception-Object and call isEx() to check.  <br>'
. '&nbsp;&nbsp;e.g.  if ($ex = isEx($obj->methode())) {/* handle exeption*/}</li>'
. '</ol>a) has major advantages over b) but the drawback is the extra call to isEx() that is called 1000\'s '
. 'of times. Is a) it a CPU killer?',
'function'  => 'exHandling',
'param'     => 10000,                       'conclusion'=> '' );$aHash = array();class someClass {var $var1 = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";var $var2 =  100;function someClass() {}
function f3($a='bar',$b) {}
function f4($a=1,$b=2) {}
function f5($a,$b) { static $tmp = array('a'=>'b');}
function &retObj1() { $c =& new someClass(); return $c; }
function retObj2() { $c =& new someClass(); return $c; }
function &retObj3() { return new someClass(); }
function retObj4() { return new someClass(); }
}
$bigData = str_repeat('a', 10000);for ($i=0; $i<100; $i++) {$key='';for ($j=0; $j<24; $j++) {$key .= chr(mt_rand(32,88));}
$bigData .= chr(mt_rand(33,88));$aHash[$key] = $bigData;}
function forEach1($param, &$profTest) {$profTest['TestCaption'][] = 'foreach($aHash as $val) $val = $val . "a";';global $aHash;for ($i=0; $i<$param; $i++) {foreach($aHash as $val) $val = $val . "a";}
}
function forEach2($param, &$profTest) {$profTest['TestCaption'][] = 'while(list(,$val) = each($aHash)) $val = $val . "a";';global $aHash;for ($i=0; $i<$param; $i++) {reset($aHash);while(list(,$val) = each($aHash)) $val = $val . "a";}
}
function forEach3($param, &$profTest) {$profTest['TestCaption'][] = 'foreach($aHash as $key=>$val) $aHash[$key] .= "a";';global $aHash;for ($i=0; $i<$param; $i++) {foreach($aHash as $key=>$val) $aHash[$key] .= "a";}
}
function forEach4($param, &$profTest) {$profTest['TestCaption'][] = 'while(list($key) = each($aHash)) $aHash[$key] .= "a" ;';global $aHash;for ($i=0; $i<$param; $i++) {reset($aHash);while(list($key) = each($aHash)) $aHash[$key] .= "a" ;}
}
function forEach5($param, &$profTest) {$profTest['TestCaption'][] = 'while(list($key,$val) = each($aHash))  $aHash[$key] .= "a"';global $aHash;for ($i=0; $i<$param; $i++) {reset($aHash);while(list($key,$val) = each($aHash))  $aHash[$key] .= "a";}
}
function forEach6($param, &$profTest) {$profTest['TestCaption'][] = '<strong>Get key-/ value-array:</strong>  foreach($aHash as $key[]=>$val[]);';global $aHash;for ($i=0; $i<$param; $i++) {foreach($aHash as $key[]=>$val[]);}
}
function forEach7($param, &$profTest) {$profTest['TestCaption'][] = '<strong>Get key-/ value-array:</strong>  array_keys() /  array_values()';global $aHash;for ($i=0; $i<$param; $i++) {$key = array_keys($aHash); $val = array_values($aHash);}
}
function forEach8($param, &$profTest) {$profTest['TestCaption'][] = '<strong>STRANGE:</strong> This is the fasetest code when using the the &amp;-ref-operator (to avoid copying)<br>$key = array_keys($aHash);<br>$size = sizeOf($key);<br>for ($i=0; $i<$size; $i++) $tmp[] = &$aHash[$key[$i]];';global $aHash;for ($i=0; $i<$param; $i++) {$key = array_keys($aHash);$size = sizeOf($key);for ($j=0; $j<$size; $j++) $aHash[$key[$i]] .= "a";}
}
function forEachMod1($param, &$profTest) {$profTest['TestCaption'][] = 'foreach($aHash as $key=>$val) $aHash[$key] .= "a";';global $aHash;for ($i=0; $i<$param; $i++) {foreach($aHash as $key=>$val) $aHash[$key] .= "a";}
}
function forEachMod2($param, &$profTest) {$profTest['TestCaption'][] = 'while(list($key) = each($aHash)) $aHash[$key] .= "a";';global $aHash;for ($i=0; $i<$param; $i++) {reset($aHash);while(list($key) = each($aHash)) $aHash[$key] .= "a" ;}
}
function forEachMod3($param, &$profTest) {$profTest['TestCaption'][] = '<strong>STRANGE:</strong> This is the fasetest code :<br>$key = array_keys($aHash);<br>$size = sizeOf($key);<br>for ($i=0; $i<$size; $i++) $aHash[$key[$i]] .= "a";';global $aHash;for ($i=0; $i<$param; $i++) {$key = array_keys($aHash);$size = sizeOf($key);for ($j=0; $j<$size; $j++) $aHash[$key[$i]] .= "a";}
}
$forLoop = str_repeat('a', 10000);function forLoop1($param, &$profTest) {$profTest['TestCaption'][] = 'With pre calc';global $forLoop;$leng = strLen($forLoop);for ($i=0; $i<$leng; $i++) ;}
function forLoop2($param, &$profTest) {$profTest['TestCaption'][] = 'Without pre calc';global $forLoop;for ($i=0; $i<strLen($forLoop); $i++) ;}
function aliasing1($param, &$profTest) {$profTest['TestCaption'][] = 'NO Aliasing. Using: $aSingleDimArray[$i]';$aMultiDimArray[0] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);$aMultiDimArray[1] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);for ($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = $aMultiDimArray[$idx]["Name"];$tmp[$idx] = $aMultiDimArray[$idx]["streat"];$tmp[$idx] = $aMultiDimArray[$idx]["zip"];}
}
function aliasing2($param, &$profTest) {$profTest['TestCaption'][] = 'Aliasing. Using: $alias = &$aSingleDimArray[$i]';$aMultiDimArray[0] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);$aMultiDimArray[1] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);for ($i=0; $i<$param; $i++) {$idx = $i%2;$alias = &$aMultiDimArray[$idx];$tmp[$idx] = $alias["Name"];$tmp[$idx] = $alias["streat"];$tmp[$idx] = $alias["zip"];}
}
function aliasing3($param, &$profTest) {$profTest['TestCaption'][] = 'NO Aliasing. Using: $aMultiDimArray[$i]["aaaaa"]["aaaaaaaaaa"]';$aMultiDimArray[0]["aaaaa"]["aaaaaaaaaa"] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);$aMultiDimArray[1]["aaaaa"]["aaaaaaaaaa"] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = $aMultiDimArray[$idx]["aaaaa"]["aaaaaaaaaa"]["Name"];$tmp[$idx] = $aMultiDimArray[$idx]["aaaaa"]["aaaaaaaaaa"]["streat"];$tmp[$idx] = $aMultiDimArray[$idx]["aaaaa"]["aaaaaaaaaa"]["zip"];}
}
function aliasing4($param, &$profTest) {$profTest['TestCaption'][] = 'Aliasing. Using: $alias = &$aMultiDimArray[$i]["aaaaa"]["aaaaaaaaaa"]';$aMultiDimArray[0]["aaaaa"]["aaaaaaaaaa"] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);$aMultiDimArray[1]["aaaaa"]["aaaaaaaaaa"] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);for ($i=0; $i<$param; $i++) {$idx = $i%2;$alias = &$aMultiDimArray[$idx]["aaaaa"]["aaaaaaaaaa"];$tmp[$idx] = $alias["Name"];$tmp[$idx] = $alias["streat"];$tmp[$idx] = $alias["zip"];}
}
function aliasing5($param, &$profTest) {$profTest['TestCaption'][] = 'NO Aliasing. Using: veryMultiDimArray[$i]["a"]["aa"]["aaa"]["aaaa"]["aaaaa"]';$aMultiDimArray[0]["a"]["aa"]["aaa"]["aaaa"]["aaaaa"] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);$aMultiDimArray[1]["a"]["aa"]["aaa"]["aaaa"]["aaaaa"] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);for ($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = $aMultiDimArray[$idx]["a"]["aa"]["aaa"]["aaaa"]["aaaaa"]["Name"];$tmp[$idx] = $aMultiDimArray[$idx]["a"]["aa"]["aaa"]["aaaa"]["aaaaa"]["streat"];$tmp[$idx] = $aMultiDimArray[$idx]["a"]["aa"]["aaa"]["aaaa"]["aaaaa"]["zip"];}
}
function aliasing6($param, &$profTest) {$profTest['TestCaption'][] = 'Aliasing. Using: $alias = &$veryMultiDimArray[$i]["a"]["aa"]["aaa"]["aaaa"]["aaaaa"]';$aMultiDimArray[0]["a"]["aa"]["aaa"]["aaaa"]["aaaaa"] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);$aMultiDimArray[1]["a"]["aa"]["aaa"]["aaaa"]["aaaaa"] = array("Name"=>"Peter Pan", "streat"=>"Wonderlandstr 24", "zip"=>1234);for ($i=0; $i<$param; $i++) {$idx = $i%2;$alias = &$aMultiDimArray[$idx]["a"]["aa"]["aaa"]["aaaa"]["aaaaa"];$tmp[$idx] = $alias["Name"];$tmp[$idx] = $alias["streat"];$tmp[$idx] = $alias["zip"];}
}
function newClass1($param, &$profTest) {$profTest['TestCaption'][] = '$obj = new SomeClass()';for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = new SomeClass();}
}
function newClass2($param, &$profTest) {$profTest['TestCaption'][] = '$obj =& new SomeClass()';for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] =& new SomeClass();}
}
function newClass3($param, &$profTest) {$profTest['TestCaption'][] = '$obj =& $someClass->f();';$obj = new SomeClass();for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] =& $obj->retObj3();}
}
function newClass4($param, &$profTest) {$profTest['TestCaption'][] = '$obj = $someClass->f();';$obj = new SomeClass();for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = $obj->retObj4();}
}
function doubleOrSingleQuote1($param, &$profTest) {$profTest['TestCaption'][] = "single (') quotes. Just an empty string: \$tmp[] = '';";for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = '';}
}
function doubleOrSingleQuote2($param, &$profTest) {$profTest['TestCaption'][] = 'double (") quotes. Just an empty string: $tmp[] = "";';for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = "";}
}
function doubleOrSingleQuote3($param, &$profTest) {$profTest['TestCaption'][] = "single (') quotes. 20 bytes Text :  \$tmp[] = 'aaaaaaaaaaaaaaaaaaaa';";for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = 'aaaaaaaaaaaaaaaaaaaa';}
}
function doubleOrSingleQuote4($param, &$profTest) {$profTest['TestCaption'][] = 'double (") quotes. 20 bytes Text :  $tmp[] = "aaaaaaaaaaaaaaaaaaaa";';for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = "aaaaaaaaaaaaaaaaaaaa";}
}
function doubleOrSingleQuote5($param, &$profTest) {$profTest['TestCaption'][] = "single (') quotes. 20 bytes Text and 3x a $ :  \$tmp[] = 'aa $ aaaa $ aaaa $ a';";for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = 'aa $ aaaa $ aaaa $ a';}
}
function doubleOrSingleQuote6($param, &$profTest) {$profTest['TestCaption'][] = 'double (") quotes. 20 bytes Text and 3x a $ :  $tmp[] = "aa $ aaaa $ aaaa $ a";';for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = "aa $ aaaa $ aaaa $ a";}
}
function doubleOrSingleQuote7($param, &$profTest) {$profTest['TestCaption'][] = 'double (") quotes. 20 bytes Text and 3x a \$ :  $tmp[] = "aa \$ aaaa \$ aaaa \$ a";';for($i=0; $i<$param; $i++) {$idx = $i%2;$tmp[$idx] = "aa \$ aaaa \$ aaaa \$ a";}
}
function isSetVsEmpty1($param, &$profTest) {$profTest['TestCaption'][] = 'isSet() with var that was set';$var = 1;for ($i=0; $i<$param; $i++) isSet($var);}
function isSetVsEmpty2($param, &$profTest) {$profTest['TestCaption'][] = 'empty() with var that was set';$var = 1;for ($i=0; $i<$param; $i++) empty($var);}
function isSetVsEmpty3($param, &$profTest) {$profTest['TestCaption'][] = 'isSet() with var that was *not* set';for ($i=0; $i<$param; $i++) isSet($sdjf);}
function isSetVsEmpty4($param, &$profTest) {$profTest['TestCaption'][] = 'empty() with var that was *not* set';for ($i=0; $i<$param; $i++) empty($sdjf);}
function isSetVsEmpty5($param, &$profTest) {$profTest['TestCaption'][] = 'isSet() with array-var that was set';$var = array('22'=>TRUE);for ($i=0; $i<$param; $i++) isSet($var[22]);}
function isSetVsEmpty6($param, &$profTest) {$profTest['TestCaption'][] = 'empty() with array-var that was set';$var = array('22'=>TRUE);for ($i=0; $i<$param; $i++) empty($var[22]);}
function isSetVsEmpty7($param, &$profTest) {$profTest['TestCaption'][] = 'isSet() with array-var that was *not* set';$var = array('22'=>TRUE);for ($i=0; $i<$param; $i++) isSet($var[23]);}
function isSetVsEmpty8($param, &$profTest) {$profTest['TestCaption'][] = 'empty() with array-var that was *not* set';$var = array('22'=>TRUE);for ($i=0; $i<$param; $i++) empty($var[23]);}
function isSetVsEmpty9($param, &$profTest) {$profTest['TestCaption'][] = 'is_array() of an array';$var = array();for ($i=0; $i<$param; $i++) is_array($var);}
function isSetVsEmpty10($param, &$profTest) {$profTest['TestCaption'][] = 'is_array() of a string';$var = '';for ($i=0; $i<$param; $i++) is_array($var);}
function isSetVsEmpty11($param, &$profTest) {$profTest['TestCaption'][] = 'is_array() of a non set value';for ($i=0; $i<$param; $i++) @is_array($gaga);}
function isSetVsEmpty12($param, &$profTest) {$profTest['TestCaption'][] = 'isSet() AND is_array() of a non set value';for ($i=0; $i<$param; $i++) (isSet($gaga) AND is_array($gaga));}
function ifOrCase1($param, &$profTest) {$profTest['TestCaption'][] = 'if and elseif (using ==)';for($i=0; $i<$param; $i++) {$res = $i % 10;if ($res == 0) {} elseif ($res == 1) {} elseif ($res == 2) {} elseif ($res == 3) {} elseif ($res == 4) {} elseif ($res == 5) {} elseif ($res == 6) {} elseif ($res == 7) {} elseif ($res == 8) {} else {}
}
}
function ifOrCase2($param, &$profTest) {$profTest['TestCaption'][] = 'if and elseif (using ===)';for($i=0; $i<$param; $i++) {$res = $i % 10;if ($res === 0) {} elseif ($res === 1) {} elseif ($res === 2) {} elseif ($res === 3) {} elseif ($res === 4) {} elseif ($res === 5) {} elseif ($res === 6) {} elseif ($res === 7) {} elseif ($res === 8) {} else {}
}
}
function ifOrCase3($param, &$profTest) {$profTest['TestCaption'][] = 'case';for($i=0; $i<$param; $i++) {$res = $i % 10;switch($res) {case 0: break;case 1: break;case 2: break;case 3: break;case 4: break;case 5: break;case 6: break;case 7: break;case 8: break;default: break;}
}
}
class Exeption {var $x;function Exeption($x) {$this->x = $x;}
function isEx($val) {return (bool)(!empty($val) && is_a($val, 'Exeption'));}
}
function isEx($val) {return (bool)(!empty($val) && is_a($val, 'Exeption'));}
function exReturner($i) {$lastEx = new Exeption($i); return $lastEx;}
function falseReturner($i) {$lastEx = new Exeption($i); return FALSE;}
function exHandling1($param, &$profTest) {$profTest['TestCaption'][] = 'if (falseReturner() === FALSE)';for($i=0; $i<$param; $i++) {if(falseReturner($i)===FALSE) {}
}
}
function exHandling2($param, &$profTest) {$profTest['TestCaption'][] = 'if (isEx(exReturner())) Using a global isEx()-function';for($i=0; $i<$param; $i++) {if($ex = isEx(exReturner($i))) {}
}
}
function exHandling3($param, &$profTest) {$profTest['TestCaption'][] = 'if (Exeption::isEx(exReturner())) Using a static methode';for($i=0; $i<$param; $i++) {if($ex = Exeption::isEx(exReturner($i))) {}
}
}
$out = '';$out  .= '<h1>PHP Benchmark tests</h1>';$out  .= "<h2>Server SW: {$_SERVER['SERVER_SOFTWARE']}</h2>";$out  .= '<table> <tr valign="top"><td><strong> NOTE</strong>  </td>';$out  .= '<td>You must keep in mind to refresh this page a few times to "catch" the right result. <br>The reson is: <ul>'
. ' <li>PHP\'s memory garbage collector drops in randomly </li>'
. ' <li>other processes that run on this machine </li>'
. '</ul> influence the results heavily';$out  .= '<table>';$testSize = sizeOf($profileTest);for ($i=0; $i<$testSize; $i++) {$profTest = &$profileTest[$i];$funcionNr = 1;$function = $profTest['function'] . $funcionNr;while(function_exists($function)) {$sTime = explode(' ', microtime());$function($profTest['param'], $profTest);$eTime = explode(' ', microtime());$profTest['result'][] = (($eTime[1] - $sTime[1]) + ($eTime[0] - $sTime[0]))*1000;$funcionNr++;$function = $profTest['function'] . $funcionNr;}
$out  .= '<hr>'
.  '<table width="95%" border="0" cellspacing="0" cellpadding="0">' 
.  '<tr align="left" bgcolor="#bcd6f1"><th colspan=3>'
.     'Test:<br>' . $profTest['title']
.  '</th></tr>'
.  '<tr bgcolor="#bcd6f1"><td  colspan=3>'
.    $profTest['text']
.  '</td></tr>'
;$fastets = $profTest['result'][0];for ($res=1; $res<sizeOf($profTest['result']); $res++) {$fastets = min($fastets, $profTest['result'][$res]);}
$out  .= '<tr bgcolor="#e8e7e6">';$out  .= "<td  colspan=3 style='font-size:12; font-style:italic; font-weight:bold;'>";$out  .= 'Conclusion:<br>' . $profTest['conclusion'];$out  .= '</td></tr>';for ($res=0; $res<sizeOf($profTest['result']); $res++) {$color = ($res % 2 == 0) ? 'bgcolor="#e1fffe"' : 'bgcolor="#ecf5ff"';$procent  = round($profTest['result'][$res] *100 / $fastets);if ($procent>300)     $bgc = '#ff8080';elseif ($procent>200) $bgc = '#fccb96';elseif ($procent>150) $bgc = '#f9ff79';else                  $bgc = '#79ff79';$out  .= "<tr {$color}>";$out  .= "<td bgcolor='{$bgc}'>";$out  .=  '+'.str_replace(' ','&nbsp;',str_pad($procent,5,' ',STR_PAD_LEFT)).' %';$out  .= '</td>';$out  .= "<td  width=65% style='font-size: 11'>";$out  .= isSet($profTest['TestCaption'][$res]) ? ($res+1).': '. $profTest['TestCaption'][$res] : '&nbsp;';$out  .= '</td><td   width=25% style="font-family: courier">';$time = round($profTest['result'][$res]);$time = str_pad($time,5,' ',STR_PAD_LEFT);$out  .= 'Total time:' . str_replace(' ','&nbsp;',$time) . '[ms]';$out  .= '</td>';}
$out  .= '</table><br>';}
echo '<body  style="font-family: Verdana, Geneva, Arial">';echo $out;echo '</body>';?>