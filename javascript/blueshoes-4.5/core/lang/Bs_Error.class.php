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
$GLOBALS['_BS_ERROR_BAG_'] = array();$GLOBALS['_BS_ERROR_BAG_POINTER_'] = 0;$GLOBALS['_BS_ERROR_ALERT_'] = '';class Bs_Error {function setError($msg, $msgType, $line=0, $func='', $file='') {if (PHPVERSION() >= 4.3) {$traceArr = debug_backtrace();$trace = array_shift($traceArr);if (empty($line)) $line = $trace['line'];if (empty($func)) {$func = '';if (isSet($trace['class'])) $func = $trace['class'].'::';$func .= $trace['function'];}
if (empty($file)) $file = $trace['file'];}
$GLOBALS['_BS_ERROR_BAG_'][] = 'In '.basename($file)." [{$func} near line {$line}] $msgType: $msg";}
function getLastError() {$s = $GLOBALS['_BS_ERROR_BAG_POINTER_'] = sizeOf($GLOBALS['_BS_ERROR_BAG_']) -1;return ($s >= 0) ? $GLOBALS['_BS_ERROR_BAG_'][$s] : '';}
function getLastErrors() {$ret = array();$s = sizeOf($GLOBALS['_BS_ERROR_BAG_']);$p = $GLOBALS['_BS_ERROR_BAG_POINTER_'];$diff = $s - $p;if ($diff>0) {for($p; $p<$s; $p++) $ret[] = $GLOBALS['_BS_ERROR_BAG_'][$p];$GLOBALS['_BS_ERROR_BAG_POINTER_'] = $s;} elseif ($diff==0 AND $s>0){$ret[] = $GLOBALS['_BS_ERROR_BAG_'][$s-1];}
return $ret;}
function getErrors() {return $GLOBALS['_BS_ERROR_BAG_'];}
function toTxt() {$out = '';foreach ($GLOBALS['_BS_ERROR_BAG_'] as $err) $out .= $err . "\n";return $out;}
function toHtml() {$out = '<pre>';$out .= Bs_Error::toTxt();$out .= '</pre>';return $out;}
function setAlert($msg) {$GLOBALS['_BS_ERROR_ALERT_'] = $msg;}
function appendAlert($msg) {if (empty($GLOBALS['_BS_ERROR_ALERT_'])) $GLOBALS['_BS_ERROR_ALERT_'] = $msg; else $GLOBALS['_BS_ERROR_ALERT_'] .= "\n" . $msg;}
function getAlert($readOnce=TRUE) {$ret = empty($GLOBALS['_BS_ERROR_ALERT_']) ? FALSE : $GLOBALS['_BS_ERROR_ALERT_'];if ($readOnce) $GLOBALS['_BS_ERROR_ALERT_'] = '';return $ret;}
}
if (!isSet($GLOBALS['Bs_Error'])) $GLOBALS['Bs_Error'] =& new Bs_Error();function bs_setError($msg, $msgType, $line=0, $func='', $file='') {if (PHPVERSION() >= 4.3) {$traceArr = debug_backtrace();$trace = array_shift($traceArr);$line  = $trace['line'];$func  = isSet($trace['class']) ? $trace['class'].'::' : '';$func .= $trace['function'];$file = $trace['file'];}
Bs_Error::setError($msg, $msgType, $line, $func, $file);}
function bs_getLastError() {return Bs_Error::getLastError();}
function bs_getLastErrors() {return Bs_Error::getLastErrors();}
function bs_getErrors() {return Bs_Error::getErrors();}
function bs_bt() {$s = '';if (PHPVERSION() >= 4.3) {$MAXSTRLEN = 30;$STYLE = 'font-size:14px';$traceArr = debug_backtrace();$s .= "<TABLE BORDER='1'>\n";$s .= "<TR><TD>#</TD><TD>File</TD><TD>Methode/Function</TD><TD>Param</TD></TR>\n";$ii = 0;for($stackPos=(sizeOf($traceArr)-1); $stackPos>=0; $stackPos--) {$trace = $traceArr[$stackPos];$s .= "<TR>\n";$ii++;$s .= "<TD STYLE='$STYLE'>$ii</TD>\n";$s .= "<TD STYLE='$STYLE'>" . basename($trace['file']) . "[{$trace['line']}]" . "</TD>\n";$s .= "<TD STYLE='$STYLE'>";$s .=  isSet($trace['class']) ? $trace['class'].'.'.$trace['function'] : $trace['function'];$s .= "()</TD>\n";$s .= "<TD STYLE='$STYLE'>";foreach($trace['args'] as $v) {if (is_null($v)) $s .= 'null';else if (is_array($v))  $s .= 'Array('.sizeOf($v).')';else if (is_object($v)) $s .= 'Object('.get_class($v).')';else if (is_bool($v)) $s .= $v ? 'TRUE' : 'FALSE';else {$v = (string) @$v;$strLen = strlen($v);if ($strLen > (2*$MAXSTRLEN)) {$v = substr($v,0,$MAXSTRLEN) . '...' . substr($v,-$MAXSTRLEN);}
$s .= "String($strLen) ". htmlspecialchars($v);}
$s .= "<BR />\n";}
$s .= "</TD>\n";$s .= "<TR>\n";}
$s .= "</TABLE>";}
return $s;}
if (basename($_SERVER['PHP_SELF']) == 'Bs_Error.class.php') {if (PHPVERSION() >= 4.3) {Bs_Error::setError('test_1', 'FATAL');Bs_Error::setError('test_2', 'FATAL');$tmp = Bs_Error::getLastErrors();echo "<pre>"; print_r($tmp);  echo "</pre>";bs_setError('test_3', 'FATAL');bs_setError('test_4', 'FATAL');$tmp = bs_getLastErrors();echo "<pre>"; print_r($tmp);  echo "</pre>";echo "bs_getLastError :" . bs_getLastError() . '<hr>';echo "BS_Error::toHtml :" . BS_Error::toHtml() . '<hr>';} else {Bs_Error::setError('test_1', 'FATAL', __LINE__, 'function name', __FILE__);Bs_Error::setError('test_2', 'FATAL', __LINE__, '', __FILE__);$tmp = Bs_Error::getLastErrors();echo "<pre>"; print_r($tmp);  echo "</pre>";bs_setError('test_3', 'FATAL', __LINE__, '', __FILE__);bs_setError('test_4', 'FATAL', __LINE__, '', __FILE__);$tmp = bs_getLastErrors();echo "<pre>"; print_r($tmp);  echo "</pre>";echo "bs_getLastError :" . bs_getLastError() . '<hr>';echo "BS_Error::toHtml :" . BS_Error::toHtml() . '<hr>';}
}
?>